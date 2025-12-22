<?php

namespace App\Exports\Reports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
class StudentsReportExport implements WithMultipleSheets
{
    protected $user;
    protected $where;

    public function __construct(User $user, $where = [])
    {
        $this->user = $user;
        $this->where = $where;
    }

    public function sheets(): array
    {
        return [
            new StudentReportSheetData($this->user, $this->where),
            new EnrolledStudentSheet($this->where),
        ];
    }
}

class StudentReportSheetData implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    protected $user;
    protected $where;
    protected $totalSalesAmount = 0;
    protected $totalCourseSales = 0;

    public function __construct(User $user, $where = [])
    {
        $this->user = $user;
        $this->where = $where;
    }

    public function title(): string
    {
        return 'Student Report';
    }

    public function collection()
    {
        $studentData = [];
        $user;
        $where = [];

        if (isset($this->where['start_date']) && isset($this->where['end_date'])) {
            $where['start_date'] = ['course_start_date', '>=', $this->where['start_date']];
            $where['end_date'] = ['course_start_date', '<=', $this->where['end_date']];
        }

        $studentData = $this->user->studentReportData($where);

        foreach ($studentData as $user) {
            $where['user_id'] = $user->id;
            $user->allPaidCourses = getAllPaidCourse($where);
            $doc_verified = getData('student_doc_verification',['english_score','identity_is_approved','edu_is_approved','identity_doc_file','edu_doc_file','resume_file','edu_trail_attempt','identity_trail_attempt','english_test_attempt','edu_athe_approved','edu_level','student_id','edu_master_approved'],['student_id'=> $user->id]);

            $this->totalCourseSales += count($user->allPaidCourses);
            $examResults = [];
            $docClass = [];
            $finalDocClass = [];
            if (empty($user->paidCourses)) {
                $player = "admin";
                $StatusDocument = $user->is_verified;
                $doc = $doc_verified[0];
                if (
                    $doc->identity_is_approved === "Approved" &&
                    $doc->edu_is_approved === "Approved" &&
                    !empty($doc->resume_file) &&
                    $doc->english_score >= 10
                ) {
                    $StatusDocument = 'Verified';
                }
                if (empty($doc->edu_doc_file) && empty($doc->identity_doc_file) && empty($doc->resume_file)) {
                    $StatusDocument =  'documentNotUploaded'; // When Any Documents not uploaded
                }
                if (!empty($doc->edu_doc_file) || !empty($doc->identity_doc_file) || !empty($doc->resume_file)){      
                    if (!empty($doc->edu_doc_file) && !empty($doc->identity_doc_file) && !empty($doc->resume_file)) {
                        if( $doc->english_test_attempt == "2") {
                            $StatusDocument = 'documentEnglishTestPending';
                        }elseif($doc->identity_is_approved === "Approved" && $doc->edu_is_approved === "Approved" && !empty($doc->resume_file) &&
                            $doc->english_score >= 10) {
                            $StatusDocument = 'Verified';
                        }elseif($doc_verified[0]->edu_is_approved == 'Pending' || $doc_verified[0]->identity_is_approved == 'Pending'){
                            $StatusDocument = 'unverified';  
                        }else{
                            $StatusDocument = 'unverified';
                        }
                    } else {
                        $StatusDocument = 'pending'; // fallback if nothing else matched
                    }
                }   
                if (
                    ($doc->identity_is_approved === "Rejected" && $doc->identity_trail_attempt == 0) ||
                    ($doc->edu_is_approved === "Rejected" && $doc->edu_trail_attempt == 0)
                ) {
                    $StatusDocument = 'documentRejected';
                }
                $docClass['default'] = $StatusDocument;
                $finalDocClass[$user->id] = $docClass['default'];
            }

            $instituteAffiliation = 'N';
            if (!empty($user->university_code)) {
                $institute = DB::table('institute_profile_master')
                    ->where('university_code', $user->university_code)
                    ->first();

                if ($institute) {
                    $instituteUser = DB::table('users')
                        ->where('id', $institute->institute_id)
                        ->select('name')
                        ->first();

                    if ($instituteUser) {
                        $instituteAffiliation = 'Y - ' . $instituteUser->name;
                    }
                }
            }
            
            
            foreach ($user->allPaidCourses as $course) {
                $courseExamCount = getCourseExamCount(base64_encode($course->course_id));
                $examRemarkMasters = DB::table('exam_remark_master')->where([
                    'student_course_master_id' => $course->scmId,
                    'is_active' => 1,
                ])->get();

                $studentCourseMaster = getData('student_course_master', ['exam_attempt_remain'], [
                    'id' => $course->scmId
                ]);

                $examResult = determineExamResult(
                    $studentCourseMaster[0]->exam_attempt_remain ?? 0,
                    count($examRemarkMasters),
                    $courseExamCount,
                    $course->course_id,
                    $user->id,
                    $course->scmId
                );

                $examResults[$course->scmId] = $examResult;
                if($course->category_id == 5){
                    $getExistMasterCourse = getDataArray('master_course_management', ['course_id','optional_course_id'], ['award_id' => $course->course_id, 'is_deleted' => 'No']);
                }
                $docClass[$course->scmId] = getDocumentStatusClass($course, $doc_verified[0], $player, $getExistMasterCourse ?? null);

                $priority = ['examTab', 'unverified', 'pending', 'documentNotUploaded', 'documentVerified', 'documentUploadGreaterSix','documentEnglishTestPending','englishVerified','englishVerified','englishAttempt','documentNotUploadedATHE'];
                $finalStatus = null;

                foreach ($priority as $p) {
                    if (in_array($p, $docClass)) {
                        $finalStatus = $p;
                        break;
                    }
                }

                if (!$finalStatus && !empty($docClass)) {
                    $finalStatus = reset($docClass);
                }

                $finalDocClass[$user->id] = $finalStatus;
            }
            

            $user->examResults = $examResults;
            $user->docClass = $docClass;
            $user->finalDocClass = $finalDocClass;
            if (in_array($finalDocClass[$user->id], ['examTab', 'Verified'])) {
                $finalStatus = 'verified';
            } elseif (in_array($finalDocClass[$user->id],['documentRejected','rejected'])) {
                $finalStatus = 'rejected';
            }else{
                $finalStatus = $finalDocClass[$user->id];
            }
            $user->finalDocClass = $finalStatus;
            if (in_array($finalDocClass[$user->id], ['examTab', 'Verified'])) {
                $finalStatus = 'verified';
            } elseif (in_array($finalDocClass[$user->id],['documentRejected','rejected'])) {
                $finalStatus = 'rejected';
            }else{
                $finalStatus = $finalDocClass[$user->id];
            }
            $user->finalDocClass = $finalStatus;
            $user->instituteAffiliation = $instituteAffiliation;
        }
        
        $formattedData = collect($studentData)->map(function ($student) {
            $coursesTitles = collect($student->allPaidCourses)->map(function ($course, $index) {
                return ($index + 1) . '. ' . $course->course_title;
            })->implode("\n");
        
            $coursesStartDates = collect($student->allPaidCourses)->map(function ($course) {
                return $course->course_start_date;
            })->implode("\n");

            $coursesExpireDates = collect($student->allPaidCourses)->map(function ($course) {
                return $course->course_expired_on;
            })->implode("\n");

            // $coursesPurchasePrices = collect($student->allPaidCourses)->map(function ($course) {
            //     return $course->purchase_price ? '€ ' . $course->purchase_price : '';
            // })->implode("\n");

            $coursesPurchasePrices = collect($student->allPaidCourses)->map(function ($course) {
                $totalPurchaseAm = purchaseTotalAmount($course->scmId,$course->course_id);
                $this->totalSalesAmount += $totalPurchaseAm; // Correct total sum for report
                return '€ ' . ($totalPurchaseAm);
            })->implode("\n");
            
            $courseStudentStatus = collect($student->allPaidCourses)->map(function ($course) use ($student) {
                // get statusKey from docClass
                $statusKey = $student->docClass[$course->scmId] ?? null;
            
                if (!$statusKey) {
                    return 'Pending';
                }
            
                // Map JS logic to PHP equivalent
                switch ($statusKey) {
                    case 'examTab':
                        $label = 'Verified';
                        break;
            
                    case 'documentUploadGreaterSix':
                        $label = 'Highest Edu Missing';
                        break;
            
                    case 'documentVerified':
                        $label = 'Pending';
                        break;
            
                    case 'documentNotUploadedDoc':
                    case 'documentNotUploaded':
                    case 'noMasterCourse':
                    case 'documentNotUploadedATHE':
                        $label = 'Not Uploaded';
                        break;
            
                    case 'englishAttempt':
                        $label = 'English One Attempt Pending';
                        break;
            
                    case 'documentRejected':
                        $label = 'Rejected';
                        break;
            
                    case 'documentEnglishTestPending':
                        $label = 'English Test Pending';
                        break;
            
                    case 'unverified':
                        $label = 'Unverified';
                        break;
            
                    case 'englishVerified':
                        $label = '1st English Test Failed.';
                        break;
            
                    default:
                        $label = 'Pending';
                        break;
                }
            
                return $label;
            })->implode("\n");
            

            $coursesExamResults = collect($student->allPaidCourses)->map(function ($course) use ($student) {
                $examData = $student->examResults && $student->examResults[$course->scmId] ? $student->examResults[$course->scmId] : null;
            
                if ($examData) {
                    return $examData['result'];
                } else {
                    return 'Pending';
                }
            })->implode("\n");
            

            $coursesExpiries = collect($student->allPaidCourses)->map(function ($course) {
                $today = now();
                $adjustedExpiryDate = \Carbon\Carbon::parse($course->adjusted_expiry);
                $isExpired = ($adjustedExpiryDate < $today) ||
                            ($course->exam_attempt_remain === 0) ||
                            ($course->exam_remark === '1');

                return $isExpired ? 'Yes' : 'No';
            })->implode("\n");
            
            $examAttemptRemain = collect($student->allPaidCourses)->map(function ($course) {
                $today = now();
                $adjustedExpiryDate = \Carbon\Carbon::parse($course->adjusted_expiry);
                $isExpired = ($adjustedExpiryDate < $today) ||
                            ($course->exam_attempt_remain === 0) ||
                            ($course->exam_remark === '1');

                return $isExpired ? '' : $course->exam_attempt_remain;
            })->implode("\n");


            // return [
            //     $student['roll_no'],
            //     $student['name'] . ' ' . $student['last_name'],
            //     $student['identity_doc_type'],
            //     $student['identity_doc_number'],
            //     $student['is_verified'],
            //     $coursesTitles ?: '',
            //     $coursesStartDates ?: '',
            //     $coursesExpireDates ?: '',
            //     $coursesPurchasePrices ?: '',
            //     $coursesExamResults ?: '',
            //     $coursesExpiries ?: '',
            //     $coursesExpiries ?: '',
            //     $examAttemptRemain ?: '',
            // ];

            $baseData = [
                $student['roll_no'],
                $student['name'] . ' ' . $student['last_name'],
                $student['identity_doc_type'],
                $student['identity_doc_number'],
                $courseStudentStatus,
                $coursesTitles ?: '',
                $coursesStartDates ?: '',
                $coursesExpireDates ?: '',
                $coursesPurchasePrices ?: '',
                $coursesExamResults ?: '',
            ];
            
            if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
                $baseData[] = $coursesExpiries ?: '';
                $baseData[] = $coursesExpiries ?: '';
            }
            
            $baseData[] = $examAttemptRemain ?: '';
            $baseData[] = $student->instituteAffiliation ?? 'N';


            
            return $baseData;
            
        }); 
        return $formattedData;
    }
    
    public function headings(): array
    {
        return [
            'Roll No.',
            'Student Name',
            'Identity Type',
            'Identity Number',
            'Verification Status',
            'Course Name',
            'Purchase Date',
            'Expire Date',
            'Purchase Price',
            'Exam Remark',
            // 'Is Expired',
            // 'Is Completed',
            'Exam Attempt Remain',
            'Institute Affiliation',
        ];
        
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            array_splice($headings, 10, 0, ['Is Expired', 'Is Completed']);
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        for ($col = 'B'; $col <= 'P'; $col++) {
            $sheet->getStyle($col . '2:' . $col . $sheet->getHighestRow())
                ->getAlignment()->setWrapText(true);
        }

        // $rows = $sheet->getHighestRow();
        // for ($row = 2; $row <= $rows; $row++) {
        //     $cellValue = $sheet->getCell('E' . $row)->getValue();
        //     if ($cellValue === 'Verified') {
        //         $sheet->getStyle('E' . $row)->getFont()->getColor()->setARGB('33A294');
        //     } elseif ($cellValue === 'Unverified') {
        //         $sheet->getStyle('E' . $row)->getFont()->getColor()->setARGB('DB2D7A');
        //     } elseif ($cellValue === 'Pending') {
        //         $sheet->getStyle('E' . $row)->getFont()->getColor()->setARGB('FF771D');
        //     }
        // }

        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
            2 => [
                'font' => [
                    'bold' => true,
                ],
            ],
            3 => [
                'font' => [
                    'bold' => true,
                ],
            ],
            4 => [
                'font' => [
                    'bold' => true,
                ],
            ],
            6 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->setCellValue('A1', 'Students Report');
                $durationText = 'Duration - All records till date';
                if (!empty($this->where['start_date']) && !empty($this->where['end_date'])) {
                    $durationText = 'Duration - ' . $this->where['start_date'] . ' to ' . $this->where['end_date'];
                }
                $sheet->setCellValue('A2', $durationText);
                $sheet->setCellValue('A3', 'Total courses sales - ' . $this->totalCourseSales);
                $sheet->setCellValue('A4', 'Total sales amount - € ' . $this->totalSalesAmount);
            },
        ];
    }
    
    public function startCell(): string
    {
        return 'A6';
    }
}
class EnrolledStudentSheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    protected $where;
    protected $totalStudent = 0;
    protected $totalStudentsEnrolled = 0;
    
    public function __construct($where = [])
    {
        $this->where = $where;
    }

    public function title(): string
    {
        return 'Enrolled Student Report';
    }

    public function collection()
    {
        
        $studentData = DB::table('users')
        ->select('users.id', 'name', 'last_name')
        ->where([
            'role' => 'user',
            'block_status' => '0',
            'is_deleted' => 'No',
            'is_active' => 'Active'
        ])
        ->orderBy('id','desc')
        ->get();
        
        $results = collect();

        if (isset($this->where['end_date'])) {
            $where['end_date'] = ['course_start_date', '<=', $this->where['end_date']];
        }
        
        foreach ($studentData as $student) {
            $studentName = $student->name . ' ' . $student->last_name;
        
            $where['user_id'] = $student->id;
            
            $paidCourses = getAllPaidCourse($where);
        
            if (!empty($paidCourses)) {
                $this->studentsWithCourses[] = $student->id;
            } else {
                continue;
            }
            $this->totalStudentsEnrolled = count(array_unique($this->studentsWithCourses));

            $student->allPaidCourses = $paidCourses;        
            $student->examResults = [];
        
            foreach ($paidCourses as $course) {
                $courseExamCount = getCourseExamCount(base64_encode($course->course_id));
                $examRemarkMasters = DB::table('exam_remark_master')->where([
                    'student_course_master_id' => $course->scmId,
                    'is_active' => 1,
                ])->get();
        
                $studentCourseMaster = getData('student_course_master', ['exam_attempt_remain'], [
                    'id' => $course->scmId
                ]);
        
                $examResult = determineExamResult(
                    $studentCourseMaster[0]->exam_attempt_remain ?? 0,
                    count($examRemarkMasters),
                    $courseExamCount,
                    $course->course_id,
                    $student->id,
                    $course->scmId
                );
        
                $student->examResults[$course->scmId] = $examResult;
            }
        
            $results->push($student);
        }
        
        // Now format the data
        $formattedData = $results->map(function ($student) {


            $coursesTitles = collect($student->allPaidCourses)->map(function ($course, $index) {
                return ($index + 1) . '. ' . trim($course->course_title);
            })->implode("\n");
        

            $coursesStartDates = collect($student->allPaidCourses)->map(function ($course) {
                return $course->course_start_date;
            })->implode("\n");

            $coursesProgress = collect($student->allPaidCourses)
            ->map(function ($course) {
                return $course->course_progress !== null && $course->course_progress !== '' ? $course->course_progress : '0';
            })
            ->implode("\n");

            $coursesExpireDates = collect($student->allPaidCourses)->map(function ($course) {
                return $course->course_expired_on;
            })->implode("\n");

            $coursesExamResults = collect($student->allPaidCourses)->map(function ($course) use ($student) {
                $examData = $student->examResults && $student->examResults[$course->scmId] ? $student->examResults[$course->scmId] : null;
            
                if ($examData) {
                    return $examData['result'];
                } else {
                    return 'Pending';
                }
            })->implode("\n");


            $baseData = [
                $student->name . ' ' . $student->last_name,
                $coursesTitles ?: '',
                $coursesStartDates ?: '',
                $coursesProgress ?: '0',
                $coursesExamResults ?: '',
                $coursesExpireDates ?: '',
            ];
        
        
            return $baseData;
        });
        

        return $formattedData;

    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Course Title',
            'Purchased Date',
            'Course Progress (%)',
            'Exam Status',
            'Course Expiry Date',

        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);
        $sheet->getStyle('A4:C4')->getFont()->setBold(true);
        

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $sheet->getStyle('D2:D' . $sheet->getHighestRow())
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        for ($col = 'B'; $col <= 'P'; $col++) {
            $sheet->getStyle($col . '2:' . $col . $sheet->getHighestRow())
                ->getAlignment()->setWrapText(true);
        }

        
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
            3 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->setCellValue('A1', 'Student Enrolled Report');
                $durationText = 'Duration - All records till date';
                $sheet->setCellValue('A2', $durationText);
                $sheet->setCellValue('A3', 'Total Enrolled Students - ' . $this->totalStudentsEnrolled);

            },
        ];
    }
    
    public function startCell(): string
    {
        return 'A5';
    }
}