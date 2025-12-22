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


class StudentsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    protected $user;
    protected $where;

    public function __construct($user = '', $where = [])
    {
        $this->user = $user;
        $this->where = $where;
    }

    public function collection()
    {
        $studentData = [];
        $user;
        $where = [];
        $studentData = $this->user->studentReportData($where);

        foreach ($studentData as $user) {
            $where['user_id'] = $user->id;
            $user->allPaidCourses = getAllPaidCourse($where);
            $examResults = [];
            $statusInfo = [];
            $docClass = [];
            $finalDocClass = [];
            $player = "admin";
            $doc_verified = getData('student_doc_verification',['english_score','identity_is_approved','edu_is_approved','identity_doc_file','edu_doc_file','resume_file','edu_trail_attempt','identity_trail_attempt','english_test_attempt','edu_athe_approved','edu_level','student_id','edu_master_approved'],['student_id'=> $user->id]);
            $player = "admin";
            $StatusDocument = '';
            if (empty($user->allPaidCourses)) {
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
            foreach ($user->allPaidCourses as $course) {
                $courseExamCount = getCourseExamCount(base64_encode($course->course_id));
                $examRemarkMasters = DB::table('exam_remark_master')->where([
                    'course_id' => $course->course_id,
                    'user_id' => $user->id,
                    'student_course_master_id' => $course->scmId,
                    'is_active' => 1,
                ])->get();

                $studentCourseMaster = getData('student_course_master', ['exam_attempt_remain'], [
                    'course_id' => $course->course_id,
                    'user_id' => $user->id,
                    'id' => $course->scmId
                ]);
                $doc_verified = getData('student_doc_verification', [
                    'english_score', 'identity_is_approved', 'edu_is_approved', 'identity_doc_file', 'edu_doc_file',
                    'resume_file', 'edu_trail_attempt', 'identity_trail_attempt', 'english_test_attempt',
                    'edu_athe_approved', 'edu_level', 'student_id', 'edu_master_approved'
                ], ['student_id' => $user->id]);

                $examResult = determineExamResult(
                    $studentCourseMaster[0]->exam_attempt_remain ?? 0,
                    count($examRemarkMasters),
                    $courseExamCount,
                    $course->course_id,
                    $user->id,
                    $course->scmId
                );
                if($course->category_id == 5){
                    $getExistMasterCourse = getDataArray('master_course_management', ['course_id','optional_course_id'], ['award_id' => $course->course_id, 'is_deleted' => 'No']);
                }
                $docClass[$course->scmId] = getDocumentStatusClass(
                    $course,
                    $doc_verified[0] ?? null,
                    $player,
                    $getExistMasterCourse ?? null
                );
                $examResults[$course->scmId] = $examResult;
                $statusInfo[$course->scmId] = getCourseStatus($course);

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
            $user->statusInfo = $statusInfo;
            $user->docClass = $docClass;
            $user->finalDocClass = $finalDocClass;
        }
        
        $formattedData = collect($studentData)->map(function ($data) {
            $paidCoursesList = collect($data->allPaidCourses)->map(function ($course, $index) {
                return ($index + 1) . '. ' . $course->course_title;
            })->implode("\n");
            
            $coursesExamResults = collect($data->allPaidCourses)->map(function ($course, $index) use ($data) {
                $examData = $data->examResults && $data->examResults[$course->scmId] ? $data->examResults[$course->scmId] : null;
            
                if ($examData) {
                    return ($index + 1) . '. ' . $examData['result'];
                } else {
                    return ($index + 1) . '. ' . 'Pending';
                }
            })->implode("\n");
            
            $coursesStatus = collect($data->allPaidCourses)->map(function ($course, $index) use ($data) {
                $statusData = $data->statusInfo && $data->statusInfo[$course->scmId] ? $data->statusInfo[$course->scmId] : null;

                if ($statusData) {
                    return ($index + 1) . '. ' . $statusData['status'];
                }
                
            })->implode("\n");

            if(!empty($data->allPaidCourses)){
                $courseStudentStatus = collect($data->allPaidCourses)->map(function ($course,$index) use ($data) {
                    // get statusKey from docClass
                    $statusKey = $data->docClass && $data->docClass[$course->scmId] ? $data->docClass[$course->scmId] : null;
                    return ($index + 1) . '. ' . $this->mapStatusLabel($statusKey);
                })->implode("\n");
            }else{
                $courseStudentStatus = '1. ' . $this->mapStatusLabel($data->docClass['default'] ?? null);
            }


            return [
                $data['roll_no'],
                $data['name'] . ' ' . $data['last_name'],
                $data['email'],
                $data['mob_code'] . ' ' . $data['phone'],
                $paidCoursesList ?: '',
                $coursesExamResults ?: '',
                $coursesStatus ?: '',
                $courseStudentStatus ? : '',
                $data['is_active'],
            ];
        });
        return $formattedData;
    }

    public function headings(): array
    {
        return [
            'Roll No.',
            'Name',
            'Email',
            'Phone No.',
            'Course Name',
            'Exam',
            'Course Status',
            'Verification Status',
            'Is Active',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $sheet->getStyle('E2:E' . $sheet->getHighestRow())
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle('F2:F' . $sheet->getHighestRow())
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle('G2:G' . $sheet->getHighestRow())
            ->getAlignment()->setWrapText(true);
        $sheet->getStyle('H2:H' . $sheet->getHighestRow())
            ->getAlignment()->setWrapText(true);

        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function mapStatusLabel($statusKey) {
        switch ($statusKey) {
            case 'examTab':
                return 'Verified';
    
            case 'documentUploadGreaterSix':
                return 'Highest Edu Missing';
    
            case 'documentVerified':
                return 'Pending';
    
            case 'documentNotUploadedDoc':
            case 'documentNotUploaded':
            case 'noMasterCourse':
            case 'documentNotUploadedATHE':
                return 'Not Uploaded';
    
            case 'englishAttempt':
                return 'English One Attempt Pending';
    
            case 'documentRejected':
                return 'Rejected';
    
            case 'documentEnglishTestPending':
                return 'English Test Pending';
    
            case 'unverified':
                return 'Unverified';
    
            case 'englishVerified':
                return '1st English Test Failed.';
    
            default:
                return 'Pending';
        }
    }
    
}
