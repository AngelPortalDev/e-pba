<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator,  DB,Storage};
use File;
use App\Models\ExamRemarkMaster;
use App\Models\Notification;
use Carbon\Carbon;

class EmentorController extends Controller
{
   
    public function EmentorDashboard()
    {
        $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
            $where = ['ementor_id' => $ementorId];
            $ementorData = $this->CourseModule->getEmentorDashboardData($where);
            return view('frontend.teacher.e-mentor-dashboard', compact('ementorData'));
        }

        return redirect('/login');
    }
 
    public function EmentorProfile()
    {
        $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
            $where = ['ementor_profile_master.ementor_id' => $ementorId];
            if(Auth::user()->role === 'sub-instructor'){
                $ementorData = $this->EmentorProfile->getSubEmentorProfile($where);
            }else if(Auth::user()->role === 'turnitin-instructor'){
                $ementorData = $this->EmentorProfile->getTurnitinEmentorProfile($where);
            }else{
                $ementorData = $this->EmentorProfile->getEmentorProfile($where);
            }
            return view('frontend.teacher.e-mentor-profile', compact('ementorData'));
        }

        return redirect('/login');
    }

    public function updateEmentorProfile(Request $req){
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {

            $ementor_id = Auth::user()->id;
            $first_name = isset($req->first_name) ? htmlspecialchars_decode($req->input('first_name')) : '';
            $last_name = isset($req->last_name) ? htmlspecialchars_decode($req->input('last_name')) : '';
            $dob = isset($req->dob) ? htmlspecialchars($req->input('dob')) : '';
            $gender = isset($req->gender) ? htmlspecialchars($req->input('gender')) : null;
            $country_id = isset($req->country_id) ? htmlspecialchars($req->input('country_id')) : 0;
            $nationality = isset($req->nationality) ? htmlspecialchars_decode($req->input('nationality')) : '';
            $address = isset($req->address) ? htmlspecialchars_decode($req->input('address')) : '';
            $highest_education = isset($req->highest_education) ? htmlspecialchars($req->input('highest_education')) : '';
            $specialization = isset($req->specialization) ? htmlspecialchars_decode($req->input('specialization')) : '';
            $institution_name = isset($req->institution_name) ? htmlspecialchars_decode($req->input('institution_name')) : '';
            $ementor_resume = $req->hasFile('ementor_resume') ? $req->file('ementor_resume') : '';
            $old_resume_file = isset($req->old_resume_file) ? htmlspecialchars($req->input('old_resume_file')) : '';

            $exists =   is_exist('users', ['id' => $ementor_id,  'is_deleted' => 'No']);
            if (isset($exists) && is_numeric($exists) && $exists === 0) {
                return json_encode(['code' => 201, 'title' => 'E-mentor Not Available', 'message' => 'E-mentor not Exist', 'remark' => 'warning']);
            }
            $validate_rules = [
                'first_name' => 'required|string|max:225|min:3',
                'last_name' => 'required|string|max:225|min:2',
                // 'dob' => 'required|date|before:today',
                // 'country_id' => 'required|numeric|min:1',
            ];
            $validate = Validator::make($req->all(), $validate_rules);
            if (!$validate->fails()) {
                $select = [
                    'name' => $first_name,
                    'last_name' => $last_name
                ];

                $where = ['id' => $ementor_id,  'is_deleted' => 'No'];
                $updateUser = processData(['users', 'id'], $select, $where, 'update');

                $resume_file ='';
                $filename ='';
                $getEmentorData = getData('ementor_profile_master', ['resume_file_name', 'folder_name', 'upload_resume'],['ementor_id' => $ementor_id]);
                if (isset($getEmentorData) && !empty($getEmentorData)) {
                    $resume_file = $getEmentorData[0]->upload_resume;
                    $filename= $getEmentorData[0]->resume_file_name;
                    $folder = $getEmentorData[0]->folder_name;

                }
                if($req->hasFile('ementor_resume')){
                    if (isset($getEmentorData[0]->folder_name) && !empty($getEmentorData[0]->folder_name)) {
                        $folder = $getEmentorData[0]->folder_name;
                    }else{
                        $folder = "Ementor_" . time() . "_" . $first_name;
                        $makeFolder = File::makeDirectory(public_path("storage/ementorDocs/" . $folder), $mode = 0777, true, true);
                        if (!isset($makeFolder) && $makeFolder === 0 && is_numeric($makeFolder)) {
                            return false;
                        }
                    }
                    
                    $filename = $ementor_resume->getClientOriginalName();
              
                    $docUpload = UploadFiles($ementor_resume, 'ementorDocs/'.$folder, $old_resume_file);

                    if ($docUpload === FALSE) {
                        return json_encode(['code' => 201, 'title' => "File is corrupt", 'message' => 'File is corrupt', "icon" => generateIconPath("error")]);
                    }
               
                    $resume_file = $docUpload['url'];
              
                   
                }
                if (isset($updateUser) && $updateUser['status'] === TRUE && $updateUser['id']  > 0) {
                    $select = [
                        'ementor_id' => $updateUser['id'],
                        'address' => $address,
                        'country_id' => $country_id,
                        'gender' => $gender,
                        'dob' => $dob,
                        'nationality' => $nationality,
                        'highest_education'=> $highest_education,
                        'specialization' => $specialization,
                        'institution_name'=>$institution_name,
                        'upload_resume'=> $resume_file,
                        'resume_file_name'=>$filename,
                        'folder_name'=>$folder,
                        // 'occupation' => $occupation,
                        'last_profile_update_on' =>  $this->time,
                    ];
                    $where = ['ementor_id' => $updateUser['id']];
                    $exists = is_exist('ementor_profile_master', $where);

                    $updateEmentorProfile = processData(['ementor_profile_master','ementor_profile_id'], $select, $where);

                    if (isset($updateEmentorProfile) && $updateEmentorProfile === FALSE) {
                        return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                    }
                    return json_encode(['code' => 200, 'title' => 'Successfully Updated', "message" => "Your profile has been updated successfully.", "icon" => generateIconPath("success")]);
                }
            } else {


                return json_encode(['code' => 202, 'title' => 'Required Fields are Missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $validate->errors()]);
            }
        }
    }

    public function EmentorSocialProfile()
    {
        $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
            $select = ['facebook', 'instagram', 'twitter', 'linkedIn','youtube'];
            $where = ['ementor_id' => $ementorId];
            $ementorData = $this->EmentorProfile->getEmentorProfile($where,$select);

            return view('frontend.teacher.e-mentor-social-profile', compact('ementorData'));
        }

        return redirect('/login ');
    }
    

    public function updateEmentorSocialProfile(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $ementor_id = Auth::user()->id;
            // $whatsapp = isset($req->whatsapp) ? htmlspecialchars($req->input('whatsapp')) : '';
            // $facebook = isset($req->facebook) ? htmlspecialchars($req->input('facebook')) : '';
            // $instagram = isset($req->instagram) ? htmlspecialchars($req->input('instagram')) : '';
            $linkedin = isset($req->linkedin) ? htmlspecialchars($req->input('linkedin')) : '';
            // $twitter = isset($req->twitter) ? htmlspecialchars($req->input('twitter')) : '';
            // $youtube = isset($req->youtube) ? htmlspecialchars($req->input('youtube')) : '';

            $exists =   is_exist('users', ['id' => $ementor_id,  'is_deleted' => 'No']);


            if (isset($exists) && is_numeric($exists) && $exists > 0) {

                $where = ['ementor_id' => $ementor_id];
                $exists = is_exist('ementor_profile_master', $where);
                $select = [
                    // 'whatsapp' => $whatsapp,
                    // 'facebook' => $facebook,
                    // 'instagram' => $instagram,
                    'linkedIn' => $linkedin,
                    // 'twitter' => $twitter,
                    // 'youtube' => $youtube,
                    'last_profile_update_on' =>  $this->time,
                ];
                if(Auth::user()->role === 'sub-instructor'){
                    $role = 'Sub E-mentor';
                }else{
                    $role = 'E-mentor';
                }

                $updateEmentorProfile = processData(['ementor_profile_master', 'ementor_profile_id'], $select, $where);
                if (isset($updateEmentorProfile) && $updateEmentorProfile === FALSE) {
                    return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                }
                return json_encode(['code' => 200, 'title' => 'Successfully Updated', "message" => $role." social profile updated successfully", "icon" => generateIconPath("success")]);
            } else {
                return json_encode(['code' => 201, 'title' => 'User Not Available', 'message' => 'User not Exist', 'remark' => 'warning', "icon" => generateIconPath("error")]);
            }
        }else {
            return json_encode(['code' => 404, 'title' => 'Something Went Wrong', 'message' => 'Please Login Again & Try', 'records' => '', 'remark' => 'warning', "icon" => generateIconPath("error")]);
        }
    }

    public function EmentorAboutMe()
    {
        $ementor_id = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($ementor_id) && $ementor_id != 0) {
            $AboutmeData = DB::table('ementor_about_me')->where('ementor_id',$ementor_id)->get();
            return view('frontend.teacher.e-mentor-about-me', compact('AboutmeData'));
        }

        return redirect('/login ');
    }

    public function updateAboutme(Request $req){
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {

            if (Auth::user()->role === 'admin') {
                $ementor_id = isset($req->ementor_id) ? base64_decode($req->input('ementor_id')) : '';
            } else {
                $ementor_id = Auth::user()->id;
            }

            $exists =   is_exist('users', ['id' => $ementor_id,  'is_deleted' => 'No']);
            if (isset($exists) && is_numeric($exists) && $exists > 0) {
                
                $selectData=[];
                $i = 0;

                foreach($req->question_id as $key => $question){
                   

              
                    $question =  base64_decode($question);
                    $where = ['ementor_id' => $ementor_id,  'question_id' => $question];
                    $exists = is_exist('ementor_about_me', $where);


                    $selectCols = [
                        'ementor_id' => $ementor_id,
                        'question_id' => $question,
                        'answer' => $req->answer[$key]
                    ];

                    if (isset($exists) && $exists > 0) {

                        $where = ['ementor_id' => $ementor_id,  'question_id' => $question];

                        $addSelect = [
                            'updated_by' => $ementor_id,
                            'updated_at' =>  $this->time,
                        ];
                        $action = 'update';
                    
                    }else{
                        $addSelect = [
                            'created_by' => $ementor_id,
                            'created_at' =>  $this->time,
                        ];
                        $action = 'insert';
                    }

                    $selectData = array_merge(
                        $selectCols,
                        $addSelect
                    );       

                    if(!empty($req->answer[$key])){
                        $i++;   
                    }
                    $updateEmentorAboutme = processData(['ementor_about_me', 'id'], $selectData, $where,$action);
                  
                }

                if (isset($updateEmentorAboutme) && $updateEmentorAboutme['status'] == TRUE) {

                    return json_encode(['code' => 200, 'title' => 'Successfully Updated', "message" => "E-mentor about me updated successfully.", "icon" => "success"]);
                }


                return json_encode(['code' => 201, 'title' => "Please enter at least one field before submitting the form.", 'message' => 'Please Try Again', "icon" => "error"]);

               
            }else {
                return json_encode(['code' => 201, 'title' => 'User Not Available', 'message' => 'User not Exist', 'remark' => 'warning']);
            }
        }else {
            return json_encode(['code' => 404, 'title' => 'Something Went Wrong', 'message' => 'Please Login Again & Try', 'records' => '', 'remark' => 'warning']);
        }
    }


    public function EmentorSecurityProfile()
    {
        $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
            $where = ['ementor_id' => $ementorId];
            $ementorData = $this->EmentorProfile->getEmentorProfile($where);
            return view('frontend.teacher.e-mentor-security', compact('ementorData'));
        }
        return redirect('/login ');
    }
    public function assignCoursesList($cat){
        $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
            $where = ['ementor_id' => ($ementorId)];
            // if($cat == 'athe'){
            //     $where['category_id'] = 5;
            // } 
            if($cat == 'dba'){
                $where['category_id'] = 5;
            }
            if($cat == 'award'){
                $where['category_id'] = 1;
            }
            if($cat == 'masters'){
                $where['category_id'] = 4;
            }
            if($cat == 'diploma'){
                $where['category_id'] = 3;
            }
            if($cat == 'certificate'){
                $where['category_id'] = 2;
            }
            if($cat == 'athe3'){
                $where['category_id'] = 6;
            }
            if($cat == 'athe4'){
                $where['category_id'] = 7;
            }
            if($cat == 'athe5'){
                $where['category_id'] = 8;
            }
            $ementorData = $this->CourseModule->getEmentorCourseData($where);
            return response()->json($ementorData);        
        }
        return redirect('/login');
    }
    public function getEmentorCourseDetails($id){
        // $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        // if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
        //     $ementorData = $this->CourseModule->getEmentorStudents($where, $ementor);

        if (Auth::check() && !empty($id) && $id != 0) {
            $data = $this->ExamRemark->courseDetail($id);
            return view('frontend.teacher.e-mentor-course-details', compact('data'));
        }
        return redirect('/login');

    }
    
    
    // Exam Methods Start 
    public function ementorExamList($status = '')
    {
        $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        $subEmentorId = null;
        $role = Auth::user()->role;
        if ($role == 'sub-instructor') {
            $subEmentorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
            $ementorId = DB::table('ementor_submentor_relations')
            ->where('sub_ementor_id', $subEmentorId)
            ->pluck('ementor_id')
            ->first();

            $studentCourseMasterIds = DB::table('subementor_student_relations')
            ->where('sub_ementor_id', $subEmentorId)
            ->pluck('student_course_master_id');
        }else if($role == "turnitin-instructor"){
            $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        }
        $status = isset($status) && !empty($status) ? base64_decode($status) : 4;
        if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
            $where = [];
            $ementorData = [];
            $students = [];
            $courseIds = [];
            $studentCourseMasters = [];
            if($role == "turnitin-instructor"){
                $ementor = ['turnitin_ementor_id' => $ementorId];
            }else{
                $ementor = ['ementor_id' => $ementorId];  
            }
            if ($status === '0') {
                // pending
                $where = ['is_cheking_completed' => "0"];

                $ementorData = DB::table('exam_remark_master')
                    ->join('course_master', 'course_master.id', '=', 'exam_remark_master.course_id')
                    ->join('users', 'users.id', '=', 'exam_remark_master.user_id')
                    ->when(Auth::user()->role === 'sub-instructor' && isset($subEmentorId), function ($query) use ($subEmentorId) {
                        return $query->join('subementor_student_relations', 'subementor_student_relations.student_id', '=', 'exam_remark_master.user_id')
                                        ->where('subementor_student_relations.sub_ementor_id', $subEmentorId);
                    })
                    ->when(Auth::user()->role === 'turnitin-instructor',function ($query)  use ($ementorId){
                        $query->join('exam_assignment_turnitin', function ($join) {
                            $join->on(
                                    'exam_assignment_turnitin.student_course_master_id', '=', 'exam_remark_master.student_course_master_id'
                                )
                                 ->on(
                                    'exam_assignment_turnitin.exam_id', '=', 'exam_remark_master.exam_id');
                                })        
                        ->where('exam_assignment_turnitin.ementor_id', $ementorId)
                        ->where('exam_assignment_turnitin.is_cheking_completed', '0');
                    })
                    ->when(Auth::user()->role !== 'turnitin-instructor', function ($query) use ($ementorId) {
                        $query->where('course_master.ementor_id', $ementorId);
                    })
                    ->where('users.is_active', 'Active')
                    ->where('exam_remark_master.is_active', '1')
                    ->where('exam_remark_master.is_cheking_completed', '0');
                    if(Auth::user()->role === 'sub-instructor'){
                        $ementorData = $ementorData->whereIn('exam_remark_master.student_course_master_id', $studentCourseMasterIds);
                    }
                    if(Auth::user()->role === 'turnitin-instructor'){
                        $ementorData = $ementorData->where('exam_remark_master.exam_type', '1');
                    }
                    $ementorData = $ementorData->select(
                        'course_master.course_title', 
                        'users.name', 
                        'users.last_name', 
                        'exam_remark_master.exam_type', 
                        'exam_remark_master.user_id as user_id', 
                        'course_master.id', 
                        DB::raw("DATE_FORMAT(exam_remark_master.created_at, '%Y-%m-%d') as created_at"), 
                        'exam_remark_master.id as exam_id', 
                        'exam_remark_master.exam_id as exam_table_id', 
                        'exam_remark_master.student_course_master_id'
                    )
                    ->orderBy('exam_remark_master.created_at', 'desc')
                    ->get()
                    ->map(function ($record) {
                        $table = getExamTable($record->exam_type);
                        
                        if ($table === 'exam_discord') {
                            $record->exam_title = 'Forum Leadership';
                        } elseif ($table) {
                            $titleColumn = $table === 'exam_assignments' ? 'assignment_tittle' : ($table === 'exam_homework' ? 'homework_title' : 'title');
                            $titleRecord = DB::table($table)
                                ->where('id', $record->exam_table_id)
                                ->select($titleColumn)
                                ->first();
                            
                            $record->exam_title = $titleRecord ? html_entity_decode($titleRecord->$titleColumn) : 'No Title Found';
                        } else {
                            $record->exam_title = 'No Title Found';
                        }
                        return $record;
                    });

                    if($role === 'sub-instructor'){
                        $ementorData = collect($ementorData)->unique('exam_id')->values()->all();
                    }
                    if($role === 'turnitin-instructor'){
                        $ementorData = collect($ementorData)->unique('exam_id')->values()->all();
                    }

                    // dd($ementorData);
                return response()->json($ementorData);

            } elseif ($status === '1') {
                // checking
                $where = ['is_cheking_completed' => "1"];
                $ementorData = $this->CourseModule->getEmentorCheckingStudents($ementorId);
                // return  $ementorData;
                return response()->json($ementorData);
            } elseif ($status === '2') {
                // fail
                $ementorData = DB::table('student_course_master')->select( DB::raw("TO_BASE64(users.id) as userId"), 'users.photo', 'users.name', 'users.last_name', 'course_master.course_title', DB::raw("DATE_FORMAT(student_course_master.created_at, '%Y-%m-%d') as created_at"), 'student_course_master.exam_remark', DB::raw("TO_BASE64(course_master.id) as courseId"), 'student_course_master.exam_perc', 'student_course_master.course_start_date', 'student_course_master.id as student_course_master_id' )->join('users', 'users.id', 'student_course_master.user_id')->join('course_master', 'course_master.id', 'student_course_master.course_id')->where('exam_remark', '0')->where(['course_master.ementor_id' => $ementorId, 'student_course_master.is_deleted' => 'No', 'users.is_active' => 'Active'])->orderBy('student_course_master.remark_update_on', 'desc')->get();
                return response()->json($ementorData);
            } elseif ($status === '3') {
                // pass
                $ementorData = DB::table('student_course_master')->select(DB::raw("TO_BASE64(users.id) as userId"), 'users.photo', 'users.name', 'users.last_name', 'course_master.course_title', DB::raw("DATE_FORMAT(student_course_master.created_at, '%Y-%m-%d') as created_at"), 'student_course_master.exam_remark', DB::raw("TO_BASE64(course_master.id) as courseId"), 'student_course_master.exam_perc', 'student_course_master.course_start_date', 'student_course_master.id as student_course_master_id', 'student_course_master.cert_file')->join('users', 'users.id', 'student_course_master.user_id')->join('course_master', 'course_master.id', 'student_course_master.course_id')->where('exam_remark', '1')->where(['course_master.ementor_id' => $ementorId, 'student_course_master.is_deleted' => 'No', 'users.is_active' => 'Active'])->orderBy('student_course_master.remark_update_on', 'desc')->get();
                return response()->json($ementorData);
            }

            $ementorData = $this->CourseModule->getEmentorStudents($where, $ementor);
            if (isset($status) && empty($status)) {
                return view('frontend.teacher.e-mentor-students-exam', compact('ementorData'));
            }

            return response()->json($ementorData);
        }

        return redirect('/login');
    }

    public function courseStudentList($status = '', $courseId)
    {
        $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        $status = isset($status) && !empty($status) ? base64_decode($status) : 4;
        if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
            $where = [];
            $studentData = [];
            $ementor = ['ementor_id' => $ementorId, 'is_deleted' => 'No'];
            
            $studentData = $this->ExamRemark->studentList($ementorId, $courseId, $status);

            return response()->json($studentData);
        }

        return redirect('/login');
    }

    public function ementorStudentInfo($studentId, $course_id, $student_course_master_id)
    {
        if (Auth::check() && isset($studentId) && !empty($studentId) && isset($course_id) && !empty($course_id) && $course_id) {
            $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
            $studentId = isset($studentId) && !empty($studentId) ? base64_decode($studentId) : 0;
            $course_id = isset($course_id) && !empty($course_id) ? base64_decode($course_id) : 0;
            $student_course_master_id = isset($student_course_master_id) && !empty($student_course_master_id) ? base64_decode($student_course_master_id) : 0;


            $where = ['user_id' => $studentId, 'course_id' => $course_id, 'student_course_master_id' => $student_course_master_id];
            $ementorStudentData = $this->ExamRemark->getEmentorStudentsDetails($where);
            $ementorStudentDataFirstAttempt = $this->ExamRemark->getEmentorStudentsDetailsFirstAttempt($where);
            $ementorStudentDataHomework = $this->ExamRemark->getEmentorStudentsDetailsHomework($where);
            return view('frontend/teacher/e-mentor-students-exam-details', compact('ementorStudentData','ementorStudentDataFirstAttempt','ementorStudentDataHomework'));
        }
        return redirect('/login');
    }
    public function studentAnswerSheet($examId, $examType,$user_id, $student_course_master_id)
    {
        if (Auth::check() && isset($examId) && !empty($examId) && isset($examType) && !empty($examType)) {
            $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
            $examId = isset($examId) && !empty($examId) ? (int) base64_decode($examId) : 0;
            $examType = isset($examType) && !empty($examType) ? base64_decode($examType) : 0;
            $user_id = isset($user_id) && !empty($user_id) ? base64_decode($user_id) : 0;
            $student_course_master_id = isset($student_course_master_id) && !empty($student_course_master_id) ? base64_decode($student_course_master_id) : 0;

            $examData = [];
            $where = ['id' => $examId];
            if ($examType === '1') {
                // Assignment Exam
                $where = ['id' => $examId,'exam_type'=>'1','is_cheking_completed'=>'0'];
                $examData = $this->ExamRemark->getQuestionAnswer($where, 1,$user_id, $student_course_master_id);            
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/assignment-answersheet', compact('examData', 'courseId'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '2') {
                // Mock Inteview Exam
                $where = ['id' => $examId,'exam_type'=>'2','is_cheking_completed'=>'0'];
                // if (is_exist('exam_mock_interview', $where) === 0) {
                //     return redirect()->back()->with('exam', 'Exam not Exist');
                // }
                $examData = $this->ExamRemark->getQuestionAnswer($where, 2,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/mock-interview-answersheet', compact('examData', 'courseId'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '3') {
                // Vlog Exam
                $where = ['id' => $examId,'exam_type'=>'3','is_cheking_completed'=>'0'];
                $examData = $this->ExamRemark->getQuestionAnswer($where, 3,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/vlog-answersheet', compact('examData', 'courseId'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '4') {
                // Peer Review Exam
                $where = ['id' => $examId,'exam_type'=>'4','is_cheking_completed'=>'0'];
                $examData = $this->ExamRemark->getQuestionAnswer($where, 4,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/peer-review-answersheet', compact('examData', 'courseId'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '5') {
                // Forum Leadership
                $where = ['id' => $examId,'exam_type'=>'5','is_cheking_completed'=>'0'];
                $examData = $this->ExamRemark->getQuestionAnswer($where, 5,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/forum-leadership-answersheet', compact('examData', 'courseId'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '6') {
                // Reflective Journal
                $where = ['id' => $examId,'exam_type'=>'6','is_cheking_completed'=>'0'];
                $examData = $this->ExamRemark->getQuestionAnswer($where, 6,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/reflective-journal-answersheet', compact('examData', 'courseId'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '8') {
                // Survey
                $where = ['id' => $examId,'exam_type'=>'8','is_cheking_completed'=>'0'];
                $examData = $this->ExamRemark->getQuestionAnswer($where, 8,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    // dd($examData);
                    return view('frontend/exam/survey-answersheet', compact('examData', 'courseId'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '9') {
                // Artificial Intelligence
                $where = ['id' => $examId,'exam_type'=>'9','is_cheking_completed'=>'0'];
                $examData = $this->ExamRemark->getQuestionAnswer($where, 9,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    // dd($examData);
                    return view('frontend/exam/artificial-intelligence-answersheet', compact('examData', 'courseId'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '10') {
                // Homework
                $where = ['id' => $examId,'exam_type'=>'10','is_cheking_completed'=>'2'];
                $examData = $this->ExamRemark->getQuestionAnswer($where, 10,$user_id, $student_course_master_id);             
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    // dd($examData);
                    return view('frontend/exam/homework-answersheet', compact('examData', 'courseId'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } else {
                return redirect()->back()->with('exam', 'Checking Already Done');
            }
        }
        return redirect('/login');
    }
    public function studentAnswerSheetAttempt($examId, $examType,$user_id, $student_course_master_id,$attempt_exam)
    {

        if (Auth::check() && isset($examId) && !empty($examId) && isset($examType) && !empty($examType)) {
            $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
            $examId = isset($examId) && !empty($examId) ? (int) base64_decode($examId) : 0;
            $examType = isset($examType) && !empty($examType) ? base64_decode($examType) : 0;
            $user_id = isset($user_id) && !empty($user_id) ? base64_decode($user_id) : 0;
            $student_course_master_id = isset($student_course_master_id) && !empty($student_course_master_id) ? base64_decode($student_course_master_id) : 0;
            // echo $student_course_master_id;
            $examData = [];
            $where = ['id' => $examId];

            if ($examType === '1') {
                // Assignment Exam
                $where = ['id' => $examId,'exam_type'=>'1'];
                $examData = $this->ExamRemark->getQuestionAnswerFirstAttempt($where, 1,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/assignment-attempt', compact('examData', 'courseId','attempt_exam'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '2') {


                // Mock Inteview Exam
                $where = ['id' => $examId,'exam_type'=>'2'];
                // if (is_exist('exam_mock_interview', $where) === 0) {
                //     return redirect()->back()->with('exam', 'Exam not Exist');
                // }
                $examData = $this->ExamRemark->getQuestionAnswerFirstAttempt($where, 2,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/mock-interview-attempt', compact('examData', 'courseId','attempt_exam'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '3') {
                // Vlog Exam
                $where = ['id' => $examId,'exam_type'=>'3'];
                $examData = $this->ExamRemark->getQuestionAnswerFirstAttempt($where, 3,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/vlog-attempt', compact('examData', 'courseId','attempt_exam'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '4') {
                // Peer Review Exam
                $where = ['id' => $examId,'exam_type'=>'4'];
                $examData = $this->ExamRemark->getQuestionAnswerFirstAttempt($where, 4,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/peer-review-attempt', compact('examData', 'courseId','attempt_exam'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '5') {
                // Forum Leadership
                $where = ['id' => $examId,'exam_type'=>'5'];
                $examData = $this->ExamRemark->getQuestionAnswerFirstAttempt($where, 5,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/forum-leadership-attempt', compact('examData', 'courseId','attempt_exam'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '6') {
                // Reflective Journal
                $where = ['id' => $examId,'exam_type'=>'6'];
                $examData = $this->ExamRemark->getQuestionAnswerFirstAttempt($where, 6,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/reflective-journal-attempt', compact('examData', 'courseId','attempt_exam'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '8') {
                // Survey
                $where = ['id' => $examId,'exam_type'=>'8'];
                $examData = $this->ExamRemark->getQuestionAnswerFirstAttempt($where, 8,$user_id, $student_course_master_id);

                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    return view('frontend/exam/survey-attempt', compact('examData', 'courseId','attempt_exam'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '9') {
                // Artificial Intelligence
                $where = ['id' => $examId,'exam_type'=>'9'];
                $examData = $this->ExamRemark->getQuestionAnswerFirstAttempt($where, 9,$user_id, $student_course_master_id);
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    // dd($examData);
                    return view('frontend/exam/artificial-intelligence-attempt', compact('examData', 'courseId','attempt_exam'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } elseif ($examType === '10') {
                // Homework
                $where = ['id' => $examId,'exam_type'=>'10'];
                $examData = $this->ExamRemark->getQuestionAnswerFirstAttempt($where, 10,$user_id, $student_course_master_id);             
                if (!empty($examData)) {
                    $course = getData('student_course_master', ['course_id'], ['id' => $examData[0]['student_course_master_id']]);
                    $courseId = 0;
                    if (isset($course) && !empty($course)) {
                        $courseId = $course[0]->course_id;
                    }
                    // dd($examData);
                    return view('frontend/exam/homework-answersheet', compact('examData', 'courseId'));
                } else {
                    return redirect()->back()->with('exam', 'Checking Already Done');
                }
            } else {
                return redirect()->back()->with('exam', 'Checking Already Done');
            }
        }
        return redirect('/login');
    }
    public function ementorCheckSubmit(Request $req)
    {
        $actionType = $req->input('actionType');
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $user_id = Auth::user()->id;
            $student_id  = isset($req->student_id) ? base64_decode($req->input('student_id')) : 0;
            $exam_id  = isset($req->exam_id) ? base64_decode($req->input('exam_id')) : 0;
            $course_id  = isset($req->course_id) ? base64_decode($req->input('course_id')) : 0;
            $exam_type  = isset($req->exam_type) ? base64_decode($req->input('exam_type')) : 0;
            $student_course_master_id  = isset($req->student_course_master_id) ? base64_decode($req->input('student_course_master_id')) : 0;
            $marks  = isset($req->marks) && is_array($req->marks) ? $req->input('marks') : [];
            $type = isset($req->type) ? $req->type : '';
            $suggestion = isset($req->suggestion) ? $req->suggestion : '';

            if(isset($req->question_id) && is_array($req->question_id) && count($req->input('question_id')) > 0){

                $question  = isset($req->question_id) && is_array($req->question_id) ? $req->input('question_id') : [];

                $validate_rules = [
                    'question_id' => 'required|array',
                ];
    
                $validate = Validator::make($req->all(), $validate_rules);
                if (!$validate->fails()) {
                    $courseExpired = is_expired(['user_id' => $student_id, 'course_id' => $course_id]);
                    if (isset($courseExpired) && is_numeric($courseExpired) && $courseExpired >= 0) {
                        $where = ['id' => $exam_id, 'is_deleted' => 'No'];
                        $table = '';
                        if ($exam_type === '1') {
                            $table = 'exam_assignments';
                            $answerTable = 'exam_assignment_answers';
                        } elseif ($exam_type === '2') {
                            $table = 'exam_mock_interview';
                            $answerTable = 'exam_mock_answers';
                        }elseif ($exam_type === '6') {
                            $table = 'exam_reflective_journals';
                            $answerTable = 'exam_reflective_journal_answers';
                        }elseif ($exam_type === '8') {
                            $table = 'exam_survey';
                            $answerTable = 'exam_survey_answers';
                        }elseif ($exam_type === '9') {
                            $table = 'exam_artificial_intelligence';
                            $answerTable = 'exam_artificial_intelligence_answers';
                        }
                        $exam_exist = is_exist($table, $where);
                        $whereRemark =  ['student_course_master_id' => $student_course_master_id, 'user_id' => $student_id, 'course_id' => $course_id, 'exam_id' => $exam_id, 'exam_type' => (int) $exam_type, 'is_active' => '1'];
                        $is_submitted = is_exist('exam_remark_master', $whereRemark);
                        if (is_numeric($exam_exist) && $exam_exist > 0 && is_numeric($is_submitted) && $is_submitted > 0) {

                            $where = [
                                'student_course_master_id' => $student_course_master_id,
                                'user_id' => $student_id,
                                'course_id' => $course_id,
                                'exam_type' => '1',
                                'exam_id' => $exam_id,
                                'is_active' => '1',
                            ];
                                
                            $existingRecord = DB::table('draft_exam')
                            ->where($where)
                            ->whereIn('draft', [1, 2]) 
                            ->first();

                            if($suggestion != '' && $marks[0] == '0'){
                                $i = 0;
                                if($existingRecord){
                                    DB::commit();
                                    $updateCourseDraft = DB::table('draft_exam')->where('id', $existingRecord->id)->update(['suggestion' => $suggestion, 'last_updated_by' => $user_id, 'updated_at' => $this->time]);
                                    
                                    if (is_numeric($updateCourseDraft) && $updateCourseDraft > 0) {
                                        
                                        if($actionType != 'draft'){
                                            $result = deleteRecord(ExamRemarkMaster::class, $whereRemark);
                                        }
                                        DB::commit();
                                        $i++;
                                        $message_title = "Suggestion Submitted";
                                        $message_text = "Suggestion submitted successfully";
                                        return json_encode(['code' => 200, 'title' => $message_title, "message" => $message_text, "icon" => generateIconPath("success")]);
                                    }
                                }
                            }else{
                                $draft_exam = getData('draft_exam', ['suggestion'], ['student_course_master_id' => $student_course_master_id, 'user_id' => $student_id, 'exam_id' => $exam_id, 'exam_type' => '1', 'draft' => '1', 'is_active' => '1']);
                                
                                if(isset($draft_exam[0])){
                                    if($actionType != 'draft'){
                                        $updateCourseDraft = DB::table('draft_exam')->where('id', $existingRecord->id)->update(['is_active' => '0', 'updated_at' => $this->time]);
                                    }else{
                                        $updateCourseDraft = DB::table('draft_exam')->where('id', $existingRecord->id)->update(['suggestion' => $suggestion, 'last_updated_by' => $user_id, 'updated_at' => $this->time]);
                                    }
                                }
                            }
                            try {
                                if (count($question) > 0) {
                                    $i = 0;
                                    $marksTotal = 0;
                                        DB::beginTransaction();
                                    foreach ($question as $questions) {
                                        $marksGiven = $marks[$i];
                                        $questionId = base64_decode($questions);
                                        $where = [
                                            'student_course_master_id' => $student_course_master_id,
                                            'user_id' => $student_id,
                                            'course_id' => $course_id,
                                            'question_id' => $questionId,
                                            'is_active' => '1',
                                            ];
                                        $select = [
                                            'marks_given' => $marksGiven,
                                            'marks_updated_by' => $user_id,
                                            'last_updated_by' => $user_id,
                                        ];
                                            $is_answered =  is_exist($answerTable, $where);
                                        if ($is_answered > 0) {
                                            $updateCourse = processData([$answerTable, 'id'], $select, $where);
                                        } else {
                                            $select = [
                                                'student_course_master_id' => $student_course_master_id,
                                                'type' => $type,
                                                'user_id' => $student_id,
                                                'course_id' => $course_id,
                                                'question_id' => $questionId,
                                                'marks_given' => $marksGiven,
                                                'is_attempt' => '1',
                                                'marks_updated_by' => $user_id,
                                                'last_updated_by' => $user_id,
                                            ];

                                            if ($answerTable == 'exam_reflective_journal_answers' || $answerTable == 'exam_assignment_answers' || $answerTable == 'exam_artificial_intelligence_answers' ) {
                                                unset($select['is_attempt']);
                                            }
                                            $updateCourse = processData([$answerTable, 'id'], $select);
                                        }
                                        if (isset($updateCourse) && is_array($updateCourse) && $updateCourse['status'] === TRUE) {
                                            $i++;
                                        }
                                        $marksTotal += $marksGiven;
                                    }
                                    if ($i > 0) {
                                        if($actionType == 'draft'){
                                            $select = ['final_score_obtain' => $marksTotal, 'remark_updated_by' => $user_id, 'is_cheking_completed' => '0'];
                                            $message_title = "Marks Saved";
                                            $message_text = "Marks saved successfully";
                                        }else{
                                            $select = ['final_score_obtain' => $marksTotal, 'remark_updated_by' => $user_id, 'is_cheking_completed' => '2'];
                                            $message_title = "Marks Submitted";
                                            $message_text = "Marks submitted successfully";
        
                                        }
                                        $updateCourse = processData(['exam_remark_master', 'id'], $select, $whereRemark);
                                        if (isset($updateCourse) && is_array($updateCourse) && $updateCourse['status'] === TRUE) {
        
                                                $this->ExamRemark->finalResult($student_id, $course_id, $student_course_master_id, $exam_id);
        
                                                DB::commit();
                                            return json_encode(['code' => 200, 'title' => $message_title, "message" => $message_text, "icon" => generateIconPath("success")]);
                                        }
                                    }
                                }
                                DB::rollback();
                                return json_encode(['code' => 201, 'title' => "Unable to submit mock interview", 'message' => 'Something Went Wrong. Please Try Again...', "icon" => generateIconPath("error")]);
                            } catch (\Throwable $th) {
                                return $th;
                                DB::rollback();
                                return json_encode(['code' => 201, 'title' => "Unable to submit mock interview", 'message' => 'Something Went Wrong. Please Try Again...', "icon" => generateIconPath("error")]);
                            }
                        } else {
                            return json_encode(['code' => 202, 'title' => 'Exam not exist or deleted', 'message' => 'Contact to Admin Support', "icon" => generateIconPath("error")]);
                        }
                    } else {
                        return json_encode(['code' => 202, 'title' => 'Course not exist or expired', 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                    }
                } else {
                    return json_encode(['code' => 202, 'title' => 'Required fields are missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $validate->errors()]);
                }
            }elseif(isset($req->question_id) && $req->question_id === '0'){
                
                $validate_rules = [
                    'question_id' => 'required',
                ];
    
                $validate = Validator::make($req->all(), $validate_rules);
                if (!$validate->fails()) {
                    $courseExpired = is_expired(['user_id' => $student_id, 'course_id' => $course_id]);
                    if (isset($courseExpired) && is_numeric($courseExpired) && $courseExpired >= 0) {
                        $where = ['id' => $exam_id, 'is_deleted' => 'No'];
                        $table = '';
                        if ($exam_type === '3') {
                            $table = 'exam_vlog';
                            $answerTable = 'exam_vlog_answers';
                        }
                        $exam_exist = is_exist($table, $where);
                        $whereRemark =  ['student_course_master_id' => $student_course_master_id, 'user_id' => $student_id, 'course_id' => $course_id, 'exam_id' => $exam_id, 'exam_type' => (int) $exam_type, 'is_active' => '1'];
                        $is_submitted = is_exist('exam_remark_master', $whereRemark);
                        if (is_numeric($exam_exist) && $exam_exist > 0 && is_numeric($is_submitted) && $is_submitted > 0) {
                        try {
                            $i = 0;
                            $marksTotal = 0;
                                DB::beginTransaction();
                                $marksGiven = $marks[0];
                                $where = [
                                    'student_course_master_id' => $student_course_master_id,
                                    'user_id' => $student_id,
                                    'course_id' => $course_id,
                                    'is_active' => '1',
                                    ];
                                $select = [
                                    'marks_given' => $marksGiven,
                                    'marks_updated_by' => $user_id,
                                    'last_updated_by' => $user_id,
                                ];
                                    $is_answered =  is_exist($answerTable, $where);
                                if ($is_answered > 0) {
                                        $updateCourse = processData([$answerTable, 'id'], $select, $where);
                                } else {
                                    $select = [
                                        'student_course_master_id' => $student_course_master_id,
                                        'user_id' => $student_id,
                                        'course_id' => $course_id,
                                        'marks_given' => $marksGiven,
                                        'is_attempt' => '1',
                                        'marks_updated_by' => $user_id,
                                        'last_updated_by' => $user_id,
                                    ];
                                        $updateCourse = processData([$answerTable, 'id'], $select);
                                    }
                                if (isset($updateCourse) && is_array($updateCourse) && $updateCourse['status'] === TRUE) {
                                    $i++;
                                }
                                $marksTotal += $marksGiven;
                                if ($i > 0) {
                                    if($actionType == 'draft'){
                                        $select = ['final_score_obtain' => $marksTotal, 'remark_updated_by' => $user_id, 'is_cheking_completed' => '0'];
                                        $message_title = "Marks Saved";
                                        $message_text = "Marks saved successfully";
                                    }else{
                                        $select = ['final_score_obtain' => $marksTotal, 'remark_updated_by' => $user_id, 'is_cheking_completed' => '2'];
                                        $message_title = "Marks Submitted";
                                        $message_text = "Marks submitted successfully";
    
                                    }
                                    $updateCourse = processData(['exam_remark_master', 'id'], $select, $whereRemark);
                                    if (isset($updateCourse) && is_array($updateCourse) && $updateCourse['status'] === TRUE) {
    
                                            $this->ExamRemark->finalResult($student_id, $course_id, $student_course_master_id, $exam_id);
    
                                            DB::commit();
                                        return json_encode(['code' => 200, 'title' => $message_title, "message" => $message_text, "icon" => generateIconPath("success")]);
                                    }
                                }
                                return json_encode(['code' => 201, 'title' => "Unable to submit vlog", 'message' => 'Something Went Wrong. Please Try Again...', "icon" => generateIconPath("error")]);
                            } catch (\Throwable $th) {
                                return $th;
                            DB::rollback();
                            return json_encode(['code' => 201, 'title' => "Unable to submit mock interview", 'message' => 'Something Went Wrong. Please Try Again...', "icon" => generateIconPath("error")]);
                        }
                        } else {
                            return json_encode(['code' => 202, 'title' => 'Exam not exist or deleted', 'message' => 'Contact to Admin Support', "icon" => generateIconPath("error")]);
                        }
                    } else {
                        return json_encode(['code' => 202, 'title' => 'Course not exist or expired', 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                    }
                } else {
                    return json_encode(['code' => 202, 'title' => 'Required fields are missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $validate->errors()]);
                }
            }

        } else {
            return json_encode(['code' => 201, 'title' => "Something went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
        }
    }
    public function ementorCheckSubmitWithoutQuestion(Request $req)
    {
        $actionType = $req->input('actionType');
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {


            $user_id = Auth::user()->id;
            $student_id  = isset($req->student_id) ? base64_decode($req->input('student_id')) : 0;
            $exam_id  = isset($req->exam_id) ? base64_decode($req->input('exam_id')) : 0;
            $course_id  = isset($req->course_id) ? base64_decode($req->input('course_id')) : 0;
            $exam_type  = isset($req->exam_type) ? base64_decode($req->input('exam_type')) : 0;
            $mark  = isset($req->mark) ? $req->input('mark') : 0;
            $student_course_master_id = isset($req->student_course_master_id) ? base64_decode($req->input('student_course_master_id')) : 0;

            $courseExpired = is_expired(['user_id' => $student_id, 'course_id' => $course_id]);
            if (isset($courseExpired) && is_numeric($courseExpired) && $courseExpired >= 0) {
                $where = ['id' => $exam_id, 'is_deleted' => 'No'];
                $table = '';
                $exam = '';
                if ($exam_type === '4') {
                    $table = 'exam_peer_review';
                    $answerTable = 'exam_peer_review_answers';
                    $exam = 'Peer Review';
                }elseif ($exam_type === '5') {
                        $table = 'exam_discord';
                        $answerTable = 'exam_discord_answers';
                        $exam = 'Discord';
                }
                $exam_exist = is_exist($table, $where);
                $whereRemark =  ['student_course_master_id' => $student_course_master_id, 'user_id' => $student_id, 'course_id' => $course_id, 'exam_id' => $exam_id, 'exam_type' => (int) $exam_type, 'is_active' => '1'];
                $is_submitted = is_exist('exam_remark_master', $whereRemark);
                if (is_numeric($exam_exist) && $exam_exist > 0 && is_numeric($is_submitted) && $is_submitted > 0) {
                    try {
                            $i = 0;
                            $marksTotal = 0;
                            DB::beginTransaction();
                            $marksGiven = $mark;
                            $where = [
                                'student_course_master_id' => $student_course_master_id,
                                'user_id' => $student_id,
                                'course_id' => $course_id,
                                'is_active' => '1',
                                ];
                            $select = [
                                'marks_given' => $marksGiven,
                                'marks_updated_by' => $user_id,
                                'last_updated_by' => $user_id,
                            ];
                                $is_answered =  is_exist($answerTable, $where);
                            if ($is_answered > 0) {
                                    $updateCourse = processData([$answerTable, 'id'], $select, $where);
                            } else {
                                $select = [
                                    'student_course_master_id' => $student_course_master_id,
                                    'user_id' => $student_id,
                                    'course_id' => $course_id,
                                    'marks_given' => $marksGiven,
                                    'is_attempt' => '1',
                                    'marks_updated_by' => $user_id,
                                    'last_updated_by' => $user_id,
                                ];
                                    $updateCourse = processData([$answerTable, 'id'], $select);
                                }
                            if (isset($updateCourse) && is_array($updateCourse) && $updateCourse['status'] === TRUE) {
                                $i++;
                            }
                            $marksTotal += $marksGiven;
                            if ($i > 0) {
                                if($actionType == 'draft'){
                                    $select = ['final_score_obtain' => $marksTotal, 'remark_updated_by' => $user_id, 'is_cheking_completed' => '0'];
                                    $message_title = "Marks Saved";
                                    $message_text = "Marks saved successfully";
                                }else{
                                    $select = ['final_score_obtain' => $marksTotal, 'remark_updated_by' => $user_id, 'is_cheking_completed' => '2'];
                                    $message_title = "Marks Submitted";
                                    $message_text = "Marks submitted successfully";

                                }
                                $updateCourse = processData(['exam_remark_master', 'id'], $select, $whereRemark);
                                if (isset($updateCourse) && is_array($updateCourse) && $updateCourse['status'] === TRUE) {

                                        $this->ExamRemark->finalResult($student_id, $course_id, $student_course_master_id, $exam_id);

                                        DB::commit();
                                    return json_encode(['code' => 200, 'title' => $message_title, "message" => $message_text, "icon" => generateIconPath("success")]);
                                }
                            }
                    } catch (\Throwable $th) {
                        return $th;
                        DB::rollback();
                        return json_encode(['code' => 201, 'title' => "Unable to submit $exam marks", 'message' => 'Something Went Wrong. Please Try Again...', "icon" => generateIconPath("error")]);
                    }
                } else {
                    return json_encode(['code' => 202, 'title' => 'Exam not exist or deleted', 'message' => 'Contact to Admin Support', "icon" => generateIconPath("error")]);
                }
            } else {
                return json_encode(['code' => 202, 'title' => 'Course not exist or expired', 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
            }

        } else {
            return json_encode(['code' => 201, 'title' => "Something went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
        }
    }
    
    public function ementorEportfolioCheckSubmit(Request $req)
    {
        $actionType = $req->input('actionType');
        if ($req->isMethod('POST') && $req->ajax() && Auth::check() && count($req->input('eportfolio_id')) > 0) {

            $user_id = Auth::user()->id;
            $student_id  = isset($req->student_id) ? base64_decode($req->input('student_id')) : 0;
            $course_id  = isset($req->course_id) ? base64_decode($req->input('course_id')) : 0;
            $student_course_master_id  = isset($req->student_course_master_id) ? base64_decode($req->input('student_course_master_id')) : 0;
            $remark  = isset($req->remark) ? $req->input('remark') : '';
            $comment  = isset($req->comment) ? $req->input('comment') : '';
            $eportfolios  = isset($req->eportfolio_id) && is_array($req->eportfolio_id) ? $req->input('eportfolio_id') : [];

            $validate_rules = [
                'eportfolio_id' => 'required|array',
                'remark' => 'required',
            ];

            $validate = Validator::make($req->all(), $validate_rules);
            if (!$validate->fails()) {
                $courseExpired = is_expired(['user_id' => $student_id, 'course_id' => $course_id]);
                if (isset($courseExpired) && is_numeric($courseExpired) && $courseExpired >= 0) {
                    $table = 'exam_eportfolio';
                    $where = [];
                    $exam_exist = is_exist($table, $where);
                    $whereRemark =  ['user_id' => $student_id, 'course_id' => $course_id];
                    $is_submitted = is_exist('exam_eportfolio', $whereRemark);
                    if (is_numeric($exam_exist) && $exam_exist > 0 && is_numeric($is_submitted) && $is_submitted > 0) {
                        try {
                            if (count($eportfolios) > 0) {
                                DB::beginTransaction();
                                foreach ($eportfolios as $eportfolio) {
                                    $i = 0;
                                    $where = [
                                        'student_course_master_id' => $student_course_master_id,
                                        'user_id' => $student_id,
                                        'course_id' => $course_id,
                                        'id' => $eportfolio,
                                    ];
                                    $select = [
                                        'remark' => $remark,
                                        'comment' => $comment,
                                        'checked_by' => $user_id,
                                    ];
                                    $is_answered =  is_exist('exam_eportfolio', $where);
                                    if ($is_answered > 0) {
                                        $updateCourse = processData(['exam_eportfolio', 'id'], $select, $where);
                                    }
                                    if (isset($updateCourse) && is_array($updateCourse) && $updateCourse['status'] === TRUE) {
                                        $i++;
                                    }

                                }
                                if ($i > 0) {
                                    $message_title = "Saved";
                                    $message_text = "Saved successfully";
                                    $this->ExamRemark->finalResult($student_id, $course_id, $student_course_master_id, $exam_id = 0);
                                    DB::commit();
                                    return json_encode(['code' => 200, 'title' => $message_title, "message" => $message_text, "icon" => generateIconPath("success")]);
                                }
                            }
                        } catch (\Throwable $th) {
                            return $th;
                        DB::rollback();
                        return json_encode(['code' => 201, 'title' => "Unable to submit eportfolio", 'message' => 'Something Went Wrong. Please Try Again...', "icon" => generateIconPath("error")]);
                        }
                    } else {
                        return json_encode(['code' => 202, 'title' => 'Exam not exist or deleted', 'message' => 'Contact to Admin Support', "icon" => generateIconPath("error")]);
                    }
                } else {
                    return json_encode(['code' => 202, 'title' => 'Course not exist or expired', 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                }
            } else {
                return json_encode(['code' => 202, 'title' => 'Required fields are missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $validate->errors()]);
            }
        } else {
            return json_encode(['code' => 201, 'title' => "Something went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
        }
    }
    
    public function ementorStudentPortfolioAnswersheet($studentId, $courseId, $studentCourseMasterId)
    {
        if (Auth::check() && isset($studentId) && !empty($studentId) && isset($courseId) && !empty($courseId)) {
            
            $studentId = isset($studentId) && !empty($studentId) ? $studentId : 0;
            $courseId = isset($courseId) && !empty($courseId) ? $courseId : 0;
            $studentCourseMasterId = isset($studentCourseMasterId) && !empty($studentCourseMasterId) ? $studentCourseMasterId : 0;

            $portfolioData = $this->ExamRemark->getportfolioQuestionAnswer($studentId, $courseId, $studentCourseMasterId);
            return view('frontend/exam/e-portfolio-answersheet', compact('portfolioData'));
        }
        return redirect('/login');
    }
    public function ementorStudentPortfolioAttempt($studentId, $courseId, $studentCourseMasterId,$attempt_exam)
    {
        if (Auth::check() && isset($studentId) && !empty($studentId) && isset($courseId) && !empty($courseId)) {
            
            $studentId = isset($studentId) && !empty($studentId) ? $studentId : 0;
            $courseId = isset($courseId) && !empty($courseId) ? $courseId : 0;
            $studentCourseMasterId = isset($studentCourseMasterId) && !empty($studentCourseMasterId) ? $studentCourseMasterId : 0;

            $portfolioData = $this->ExamRemark->getportfolioQuestionAnswer($studentId, $courseId, $studentCourseMasterId);
            return view('frontend/exam/e-portfolio-attempt', compact('portfolioData','attempt_exam'));
        }
        return redirect('/login');
    }
    
    public function ementorAllStudentList()
    {
        $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        $status = isset($status) && !empty($status) ? base64_decode($status) : 4;
        if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
            $users = $this->user->studentReportData([]);
            
            $subEmentors = $this->ementorSubementorRelation->getSubEmentorList([ 'ementor_id' => $ementorId ]);
            
            $studentData = $users->filter(function($user) {
                $user->allPaidCourses = getAllPaidCourse(['user_id' => $user->id, 'ementor_id' => Auth::user()->id]);
                return 
                    !empty($user->allPaidCourses) && 
                    $user->is_active === 'Active' && 
                    $user->is_verified === 'Verified';
            })->values();
            
            foreach ($studentData as $user) {

                $user->allPaidCourses = getAllPaidCourse(['user_id' => $user->id, 'ementor_id' => $ementorId]);
                $examResults = [];

                foreach ($user->allPaidCourses as $course) {
                    $course->assigned_sub_mentor_id = getAssignedSubMentor($course->scmId);
                    $courseExamCount = getCourseExamCount(base64_encode($course->course_id));
                    $examRemarkMasters = DB::table('exam_remark_master')->where([
                        // 'course_id' => $course->course_id,
                        // 'user_id' => $user->id,
                        'student_course_master_id' => $course->scmId,
                        'is_active' => 1,
                    ])->get();

                    $studentCourseMaster = getData('student_course_master', ['exam_attempt_remain'], [
                        // 'course_id' => $course->course_id,
                        // 'user_id' => $user->id,
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
                }

                $user->examResults = $examResults;
            }

            return response()->json([
                'studentData' => $studentData,
                'subEmentors' => $subEmentors,
            ]);
            
        }

        return redirect('/login');
    }

    public function ementorPurchasedStudentList($action='')
    {
        $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        $status = isset($status) && !empty($status) ? base64_decode($status) : 4;
        $today = Carbon::now()->format('Y-m-d');
        if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
            $where = [];
            $users = $this->user->studentReportData([]);
            $subEmentors = $this->ementorSubementorRelation->getSubEmentorList([
                'ementor_id' => $ementorId
            ]);

            $studentData = $users->filter(function($user) use ($action, $today) {
                $conditions = [
                    'user_id'    => $user->id,
                    'ementor_id' => Auth::user()->id,
                ];
                if ($action === 'expired') {
                    $conditions['before_expired_on'] = $today;
                }
                if ($action === 'withdraw') {
                    $conditions['withdraw_status_on'] = "Withdraw";
                }
                $user->allPaidCourses = getAllPaidCourse($conditions);
                return 
                    !empty($user->allPaidCourses) && 
                       $user->is_active === 'Active';
            })->values();

            foreach ($studentData as $user) {
                $conditions = [
                    'user_id'    => $user->id,
                    'ementor_id' => Auth::user()->id,
                ];
                
                if ($action === 'expired') {
                    $conditions['before_expired_on'] = $today;   
                }
                if ($action === 'withdraw') {
                    $conditions['withdraw_status'] = "Withdraw";
                }
                $user->allPaidCourses = getAllPaidCourse($conditions);
                $examResults = [];
                $docClass = [];
                $statusMap = [
                    'examTab' => ['label' => 'Verified', 'color' => '#4caf50'],
                    'documentUploadGreaterSix' => ['label' => 'Highest Edu Missing', 'color' => '#ffc107'],
                    'documentVerified' => ['label' => 'Pending', 'color' => '#2196f3'],
                    'documentNotUploadedDoc' => ['label' => 'Not Uploaded', 'color' => '#ff9800'],
                    'documentNotUploaded' => ['label' => 'Not Uploaded', 'color' => '#ff9800'],
                    'englishAttempt' => ['label' => 'English One Attempt Pending', 'color' => '#fd7e14'],
                    'noMasterCourse' => ['label' => 'Not Uploaded', 'color' => '#f44336'],
                    'documentRejected' => ['label' => 'Rejected', 'color' => '#dc3545'],
                    'documentEnglishTestPending' => ['label' => 'English Test Pending', 'color' => '#673ab7'],
                    'documentNotUploadedATHE' => ['label' => 'Not Uploaded', 'color' => '#6c757d'],
                    'unverified' => ['label' => 'Unverified', 'color' => '#f44336'],
                    'englishVerified' => ['label' => '1st English Test Failed', 'color' => '#03a9f4'],
                    'Verified'=> ['label' => 'Verified', 'color' => '#4caf50'],
                ];
                foreach ($user->allPaidCourses as $course) {
                    $course->assigned_sub_mentor_id = getAssignedSubMentor($course->scmId);
                    $courseExamCount = getCourseExamCount(base64_encode($course->course_id));
                    
                    $examRemarkMasters = DB::table('exam_remark_master')->where([
                        'student_course_master_id' => $course->scmId,
                        'is_active' => 1,
                    ])->get();
        
                    $studentCourseMaster = getData('student_course_master', ['exam_attempt_remain'], [
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
        
                    $examResults[$course->scmId] = $examResult;
        
                    $player = 'admin';
                    $getExistMasterCourse = [];
                    if ($course->category_id == 5) {
                        $getExistMasterCourse = getDataArray(
                            'master_course_management',
                            ['course_id', 'optional_course_id'],
                            ['award_id' => $course->course_id, 'is_deleted' => 'No']
                        );
                    }
                    
                    $statusKey  = getDocumentStatusClass(
                        $course,
                        $doc_verified[0] ?? null,
                        $player,
                        $getExistMasterCourse ?? null
                    );
                    if ($statusKey && isset($statusMap[$statusKey])) {
                        $docClass[$course->scmId] = [
                            'key' => $statusKey,
                            'label' => $statusMap[$statusKey]['label'],
                            'color' => $statusMap[$statusKey]['color']
                        ];
                    } else {
                        $docClass[$course->scmId] = [
                            'key' => $statusKey ?? 'unknown',
                            'label' => 'Unknown',
                            'color' => '#000'
                        ];
                    }
                }
                if ($action != 'expired') {
                    $user->examResults = $examResults;
                    $user->docClass = $docClass;
                }
               
            }
            return response()->json([
                'studentData' => $studentData,
                'subEmentors' => $subEmentors,
            ]);
        }
        

        return redirect('/login');
    } 

    public function ementorAllStudentLists($cat)
    {
        $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        $status = isset($status) && !empty($status) ? base64_decode($status) : 4;
        if (Auth::check() && !empty($ementorId) && $ementorId != 0) {
            $where = ['cat'=>$cat];
            $users = $this->user->studentReportData($where);
            
            $subEmentors = $this->ementorSubementorRelation->getSubEmentorList([ 'ementor_id' => $ementorId ]);
            $today = Carbon::now()->format('Y-m-d');

            $studentData = $users->filter(function($user) use ($cat,$today) {
                $user->allPaidCourses = getAllPaidCourse(['user_id' => $user->id, 'ementor_id' => Auth::user()->id,'cat'=>$cat,'course_expired_not_on' => $today]);
                return 
                    !empty($user->allPaidCourses) && 
                    $user->is_active === 'Active' && 
                    $user->is_verified === 'Verified';
            })->values();
            foreach ($studentData as $user) {

                $user->allPaidCourses = getAllPaidCourse(['user_id' => $user->id, 'ementor_id' => $ementorId,'cat'=>$cat,'course_expired_not_on' => $today]);
                $examResults = [];

                foreach ($user->allPaidCourses as $course) {
                    $course->assigned_sub_mentor_id = getAssignedSubMentor($course->scmId);
                    $courseExamCount = getCourseExamCount(base64_encode($course->course_id));
                    $examRemarkMasters = DB::table('exam_remark_master')->where([
                        // 'course_id' => $course->course_id,
                        // 'user_id' => $user->id,
                        'student_course_master_id' => $course->scmId,
                        'is_active' => 1,
                    ])->get();

                    $studentCourseMaster = getData('student_course_master', ['exam_attempt_remain'], [
                        // 'course_id' => $course->course_id,
                        // 'user_id' => $user->id,
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
                }

                $user->examResults = $examResults;
            }

            return response()->json([
                'studentData' => $studentData,
                'subEmentors' => $subEmentors,
            ]);
            
        }

        return redirect('/login');
    }

    public function ementorExamSubmit(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            try{ 
                DB::beginTransaction();
                $studentCourseMaster = getData('student_course_master', ['exam_attempt_remain', 'exam_remark', 'id', 'course_id'], [
                    'user_id' => base64_decode($req->user_id),
                    // 'course_id' => base64_decode($req->course_id),
                    'id' => base64_decode($req->student_course_master_id)
                ], 1, 'created_at', 'desc');

                $student_course_master_id = base64_decode($req->student_course_master_id);
            
                if (isset($studentCourseMaster) && !empty($studentCourseMaster)) {
                    $updateData = [
                        'exam_attempt_remain' => $studentCourseMaster[0]->exam_attempt_remain - 1
                    ];
            
                    if (isset($req->remark) && $req->remark == '1') {
                        $updateData['exam_remark'] = '1';
                    } elseif (isset($req->remark) && $req->remark == '0') {
                        $updateData['exam_remark'] = '0';
                    }
            
                    $updateCourse = DB::table('student_course_master')
                        ->where('id', $studentCourseMaster[0]->id)
                        ->update($updateData);
            
                    if ($updateCourse) {
                        $examRemarkMasters = ExamRemarkMaster::where([
                            'student_course_master_id' => $student_course_master_id,
                            'user_id' => base64_decode($req->user_id),
                            'is_cheking_completed' => '2',
                            'is_active' => '1',
                        ])->latest()->get();
                        $exam_perc = 0;
                        $exam_score = 0;
            
                        foreach ($examRemarkMasters as $examRemarkMaster) {
                            $exam_perc += $examRemarkMaster->final_obtain_percentage;
                            $exam_score += $examRemarkMaster->final_score_obtain;
                            $examRemarkMaster->is_active = 0;
                            $examRemarkMaster->save();

                            $assignments = DB::table('exam_assignments')
                                ->where('exam_assignments.id', $examRemarkMaster->exam_id)
                                ->where('exam_assignments.is_deleted', "No")
                                ->leftJoin('exam_assignment_questions', 'exam_assignment_questions.assignments_id', '=', 'exam_assignments.id')
                                ->select('exam_assignments.id', 'exam_assignments.assignment_percentage', DB::raw('SUM(CASE WHEN exam_assignment_questions.is_deleted = "No" THEN exam_assignment_questions.assignment_mark ELSE 0 END) as total_marks'))
                                ->groupBy('exam_assignments.id', 'exam_assignments.assignment_percentage')
                                ->get();
                            

                            if (count($assignments)>0) {
                                foreach ($assignments as $assignment) {
                                    $examAssignmentAnswers = DB::table('exam_assignment_answers')
                                        ->where(['student_course_master_id' => $student_course_master_id, 'user_id' => base64_decode($req->user_id), 'is_active' => '1'])
                                        ->whereIn('question_id', function($query) use ($assignment) {
                                            $query->select('id')
                                                ->from('exam_assignment_questions')
                                                ->where('assignments_id', $assignment->id);
                                        })
                                        ->update(['is_active' => 0]);

                                    $examAssignmentTurnitin = DB::table('exam_assignment_turnitin')
                                        ->where(['student_course_master_id' => $student_course_master_id, 'user_id' => base64_decode($req->user_id), 'is_active' => '1'])
                                        ->update(['is_active' => 0]);
                                }
                            }

                            $mockInterviews = DB::table('exam_mock_interview')
                                ->where('exam_mock_interview.id', $examRemarkMaster->exam_id)
                                ->leftJoin('exam_mock_questions', 'exam_mock_questions.mock_intr_id', '=', 'exam_mock_interview.id')
                                ->select('exam_mock_interview.id','exam_mock_interview.percentage',  DB::raw('SUM(CASE WHEN exam_mock_questions.is_deleted = "No" THEN exam_mock_questions.marks ELSE 0 END) as total_marks'))
                                ->groupBy('exam_mock_interview.id', 'exam_mock_interview.percentage')
                                ->get();
                
                            if (count($mockInterviews)>0) {
                                foreach ($mockInterviews as $mockInterview) {
                                    $examMockAnswers = DB::table('exam_mock_answers')
                                    ->where(['student_course_master_id' => $student_course_master_id, 'user_id' => base64_decode($req->user_id), 'is_active' => '1'])
                                    ->whereIn('question_id', function($query) use ($mockInterview) {
                                        $query->select('id')
                                            ->from('exam_mock_questions')
                                            ->where('mock_intr_id', $mockInterview->id);
                                    })
                                    ->update(['is_active' => 0]);
                                }
                            }
                            
                            $vlogs = DB::table('exam_vlog')
                                ->where('exam_vlog.id', $examRemarkMaster->exam_id)
                                ->where('exam_vlog.is_deleted', "No")
                                ->leftJoin('exam_vlog_questions', 'exam_vlog_questions.vlog_id', '=', 'exam_vlog.id')
                                ->select('exam_vlog.id', 'exam_vlog.percentage', DB::raw('SUM(CASE WHEN exam_vlog_questions.is_deleted = "No" THEN exam_vlog_questions.marks ELSE 0 END) as total_marks'))
                                ->groupBy('exam_vlog.id', 'exam_vlog.percentage')
                                ->get();
                            

                            if (count($vlogs)>0) {
                                foreach ($vlogs as $vlog) {
                                    $examVlogAnswers = DB::table('exam_vlog_answers')
                                        ->where(['student_course_master_id' => $student_course_master_id, 'user_id' => base64_decode($req->user_id), 'is_active' => '1'])
                                        ->whereIn('question_id', function($query) use ($vlog) {
                                            $query->select('id')
                                                ->from('exam_vlog_questions')
                                                ->where('vlog_id', $vlog->id);
                                        })
                                        ->update(['is_active' => 0]);
                                }
                            }
                            
                            // $peerReview = DB::table('exam_peer_review')
                            //     ->where('exam_peer_review.id', $examRemarkMaster->exam_id)
                            //     ->where('exam_peer_review.is_deleted', "No")
                            //     ->select('id', 'percentage')
                            //     ->first();
                            
                            $examPeerReviewAnswer = DB::table('exam_peer_review_answers')
                                ->where(['student_course_master_id' => $student_course_master_id, 'user_id' => base64_decode($req->user_id), 'is_active' => '1'])
                                ->update(['is_active' => 0]);
                                
                            
                            // $discord = DB::table('exam_discord')
                            //     ->where('exam_discord.id', $examRemarkMaster->exam_id)
                            //     ->where('exam_discord.is_deleted', "No")
                            //     ->select('id', 'percentage', 'marks')
                            //     ->first();
                            
                            $examDiscordAnswer = DB::table('exam_discord_answers')
                                ->where(['student_course_master_id' => $student_course_master_id, 'user_id' => base64_decode($req->user_id), 'is_active' => '1'])
                                ->update(['is_active' => 0]);
                                

                            $reflectiveJournals = DB::table('exam_reflective_journals')
                                ->where('exam_reflective_journals.id', $examRemarkMaster->exam_id)
                                ->where('exam_reflective_journals.is_deleted', "No")
                                ->leftJoin('exam_reflective_journal_questions', 'exam_reflective_journal_questions.reflective_journal_id', '=', 'exam_reflective_journals.id')
                                ->select('exam_reflective_journals.id', 'exam_reflective_journals.percentage', DB::raw('SUM(CASE WHEN exam_reflective_journal_questions.is_deleted = "No" THEN exam_reflective_journal_questions.marks ELSE 0 END) as total_marks'))
                                ->groupBy('exam_reflective_journals.id', 'exam_reflective_journals.percentage')
                                ->get();

                            if (count($reflectiveJournals)>0) {
                                foreach ($reflectiveJournals as $reflectiveJournal) {
                                    $examAssignmentAnswers = DB::table('exam_reflective_journal_answers')
                                        ->where(['student_course_master_id' => $student_course_master_id, 'user_id' => base64_decode($req->user_id), 'is_active' => '1'])
                                        ->whereIn('question_id', function($query) use ($reflectiveJournal) {
                                            $query->select('id')
                                                ->from('exam_reflective_journal_questions')
                                                ->where('reflective_journal_id', $reflectiveJournal->id);
                                        })
                                        ->update(['is_active' => 0]);
                                }
                            }
                            
                            $mcqs = DB::table('exam_mcq')
                                ->where('exam_mcq.id', $examRemarkMaster->exam_id)
                                ->where('exam_mcq.is_deleted', "No")
                                ->leftJoin('exam_mcq_questions', 'exam_mcq_questions.mcq_id', '=', 'exam_mcq.id')
                                ->select('exam_mcq.id', 'exam_mcq.percentage', DB::raw('SUM(CASE WHEN exam_mcq_questions.is_deleted = "No" THEN exam_mcq_questions.mark ELSE 0 END) as total_marks'))
                                ->groupBy('exam_mcq.id', 'exam_mcq.percentage')
                                ->get();

                            if (count($mcqs)>0) {
                                foreach ($mcqs as $mcq) {
                                    $examMcqAnswers = DB::table('exam_mcq_answers')
                                        ->where(['user_id' => base64_decode($req->user_id), 'is_active' => '1'])
                                        ->whereIn('question_id', function($query) use ($mcq) {
                                            $query->select('id')
                                                ->from('exam_mcq_questions')
                                                ->where('mcq_id', $mcq->id);
                                        })
                                        ->update(['is_active' => 0]);
                                }
                            }
                            
                            $surveys = DB::table('exam_survey')
                                ->where('exam_survey.id', $examRemarkMaster->exam_id)
                                ->where('exam_survey.is_deleted', "No")
                                ->leftJoin('exam_survey_questions', 'exam_survey_questions.survey_id', '=', 'exam_survey.id')
                                ->select('exam_survey.id', 'exam_survey.percentage', DB::raw('SUM(CASE WHEN exam_survey_questions.is_deleted = "No" THEN exam_survey_questions.marks ELSE 0 END) as total_marks'))
                                ->groupBy('exam_survey.id', 'exam_survey.percentage')
                                ->get();

                            if (count($surveys)>0) {
                                foreach ($surveys as $survey) {
                                    $examSurveyAnswers = DB::table('exam_survey_answers')
                                        ->where(['user_id' => base64_decode($req->user_id), 'is_active' => '1'])
                                        ->whereIn('question_id', function($query) use ($survey) {
                                            $query->select('id')
                                                ->from('exam_survey_questions')
                                                ->where('survey_id', $survey->id);
                                        })
                                        ->update(['is_active' => 0]);

                                    $inputFiles = DB::table('exam_input_files')->where(['exam_id' => $examRemarkMaster->exam_id, 'user_id' => base64_decode($req->user_id), 'student_course_master_id' => $student_course_master_id, 'is_active' => '1'])
                                    ->update(['is_active' => 0]);
                                        
                                }
                            }
                            
                            $artificialIntelligences = DB::table('exam_artificial_intelligence')
                                ->where('exam_artificial_intelligence.id', $examRemarkMaster->exam_id)
                                ->where('exam_artificial_intelligence.is_deleted', "No")
                                ->leftJoin('exam_artificial_intelligence_questions', 'exam_artificial_intelligence_questions.artificial_intelligence_id', '=', 'exam_artificial_intelligence.id')
                                ->select('exam_artificial_intelligence.id', 'exam_artificial_intelligence.percentage', DB::raw('SUM(CASE WHEN exam_artificial_intelligence_questions.is_deleted = "No" THEN exam_artificial_intelligence_questions.marks ELSE 0 END) as total_marks'))
                                ->groupBy('exam_artificial_intelligence.id', 'exam_artificial_intelligence.percentage')
                                ->get();

                            if (count($artificialIntelligences)>0) {
                                foreach ($artificialIntelligences as $artificialIntelligence) {
                                    $examArtificialIntelligences = DB::table('exam_artificial_intelligence_answers')
                                        ->where(['student_course_master_id' => $student_course_master_id, 'user_id' => base64_decode($req->user_id), 'is_active' => '1'])
                                        ->whereIn('question_id', function($query) use ($artificialIntelligence) {
                                            $query->select('id')
                                                ->from('exam_artificial_intelligence_questions')
                                                ->where('artificial_intelligence_id', $artificialIntelligence->id);
                                        })
                                        ->update(['is_active' => 0]);
                                }
                            }
                        }

                        $courseMaster = getData('course_master', ['category_id'], ['id' => $studentCourseMaster[0]->course_id]);

                        $awardCourseCount = awardCoursesCountByMasterCourseId($studentCourseMaster[0]->course_id, $student_course_master_id);
                        
                        if($courseMaster[0]->category_id != 1){
                            $updateData['exam_perc'] = round($exam_perc/$awardCourseCount);
                        }else{
                            $updateData['exam_perc'] = round($exam_perc);
                        }
                        $updateData['exam_score'] = $exam_score;
                        $updateCourse = DB::table('student_course_master')
                            ->where('id', $student_course_master_id)
                            ->update($updateData);

                        $student = DB::table('users')->where('id', base64_decode($req->user_id))->first();
                        $course = DB::table('course_master')->where('id', $studentCourseMaster[0]->course_id)->first();
                        
                        $studentCourseMaster = getData('student_course_master',['exam_attempt_remain','exam_remark', 'exam_perc'], ['id' => $student_course_master_id]);

                        $unsubscribeRoute = url('/unsubscribe/'.base64_encode($student->email));
                        if(isset($studentCourseMaster) && $studentCourseMaster[0]->exam_remark === '1'){
                            mail_send(
                                36,
                                [
                                    '#Name#',
                                    '#[Course Name]#',
                                    '#[Score/Grade]#',
                                    '#unsubscribeRoute#',
                                ],
                                [
                                    $student->name." ".$student->last_name,
                                    $course->course_title,
                                    !empty($studentCourseMaster[0]->exam_perc) ? $studentCourseMaster[0]->exam_perc : 0,
                                    $unsubscribeRoute
                                ],
                                $student->email
                            );
                        }elseif(isset($studentCourseMaster) && $studentCourseMaster[0]->exam_attempt_remain === 1 && $studentCourseMaster[0]->exam_remark === '0'){

                            $eportfolioFailReason = '';

                            $eportfolioFailReason = DB::table('exam_eportfolio')->where([
                                'student_course_master_id' => $student_course_master_id,
                                'user_id' => base64_decode($req->user_id),
                            ])->first()->comment ?? '';

                            $eportfolioFailReasonBlock = '';

                            if (!empty($eportfolioFailReason)) {
                                $eportfolioFailReasonBlock = "
                                    <p class='text-color'>
                                        <strong>E-Portfolio Fail Reason:</strong> <span style='color: #dc3545; font-weight: bold;'>$eportfolioFailReason</span>
                                        
                                    </p>
                                ";
                            }


                            mail_send(
                                41,
                                [
                                    '#Name#',
                                    '#Course Name#',
                                    '#EportfolioFailReasonBlock#',
                                    '#unsubscribeRoute#',
                                ],
                                [
                                    $student->name." ".$student->last_name,
                                    $course->course_title,
                                    $eportfolioFailReasonBlock,
                                    $unsubscribeRoute
                                ],
                                $student->email
                            );
                        }else{
                            
                            $eportfolioFailReason = '';

                            $eportfolioFailReason = DB::table('exam_eportfolio')->where([
                                'student_course_master_id' => $student_course_master_id,
                                'user_id' => base64_decode($req->user_id),
                            ])->first()->comment ?? '';

                            $eportfolioFailReasonBlock = '';

                            if (!empty($eportfolioFailReason)) {
                                $eportfolioFailReasonBlock = "
                                    <p class='text-color'>
                                        <strong>E-Portfolio Fail Reason:</strong> <span style='color: #dc3545; font-weight: bold;'>$eportfolioFailReason</span>
                                        
                                    </p>
                                ";
                            }
                            
                            mail_send(
                                35,
                                [
                                    '#Name#',
                                    '#[Course Name]#',
                                    '#EportfolioFailReasonBlock#',
                                    '#unsubscribeRoute#',
                                ],
                                [
                                    $student->name." ".$student->last_name,
                                    $course->course_title,
                                    $eportfolioFailReasonBlock,
                                    $unsubscribeRoute
                                ],
                                $student->email
                            );
                        }
                        
                        DB::commit();
                        return json_encode(['code' => 200, 'title' => "Success", 'message' => 'Update successful', "icon" => generateIconPath("success")]);
                    } else {
                        return json_encode(['code' => 201, 'title' => "Error", 'message' => 'Update failed, please try again', "icon" => generateIconPath("error")]);
                    }
                } else {
                    return json_encode(['code' => 201, 'title' => "Error", 'message' => 'Student course data not found', "icon" => generateIconPath("error")]);
                }
            } catch (\Throwable $th) {
                return $th;
                DB::rollback();
                return json_encode(['code' => 201, 'title' => "Unable to submit eportfolio", 'message' => 'Something Went Wrong. Please Try Again...', "icon" => generateIconPath("error")]);
            }
        }
        
        return redirect('/login');
        
    }
    public function getNotification()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        $notifications = auth()->user()->unreadNotifications()->orderBy('created_at', 'desc')->get();

        $notificationHtml = '';

        foreach ($notifications as $notification) {
            $examId = $notification->data['exam_id'];
            $examType = isset($notification->data['exam_type']) ? base64_encode($notification->data['exam_type']) : 0 ;
            $userId = $notification->data['student_id'];
            $scmId = $notification->data['student_course_master_id'];
            $avatar = asset('frontend/images/avatar/avatar-1.jpg');
            
            if(isset($notification->data['exam_name']) ?  $notification->data['exam_name'] == 'E-Portfolio' : ''){
                $url = url("ementor/e-portfolio-answersheet/{$userId}/{$courseId}/{$scmId}");
            }else{
                $url = url("ementor/answersheet/{$examId}/{$examType}/{$userId}/{$scmId}");
            }

            // $notificationHtml .= '<li class="list-group-item">
            //     <a href="'.$url.'" class="text-decoration-none mark-as-read" data-notification-id="' . htmlspecialchars($notification->id, ENT_QUOTES) . '">
            //         <strong>' . htmlspecialchars($notification->data['student_name'], ENT_QUOTES) . '</strong> submitted an <strong>' . htmlspecialchars(getExamType(isset($notification->data['exam_name']) ? $notification->data['exam_name'] : $notification->data['exam_type']), ENT_QUOTES) . '</strong> for the course <strong>' . htmlspecialchars($notification->data['course_name'], ENT_QUOTES) . '</strong>.
            //         <small class="text-muted float-end">' . $notification->created_at->diffForHumans() . '</small>
            //     </a>
            // </li>';

            $notificationHtml .= '<li class="list-group-item bg-light">
                <div class="row">
                    <div class="col">
                        <a href="' . $url . '" class="text-body text-decoration-none mark-as-read" data-notification-id="' . htmlspecialchars($notification->id, ENT_QUOTES) . '">
                            <div class="d-flex align-items-center">
                                <img src="' . $avatar . '" alt="Student Avatar" class="avatar-md rounded-circle" />
                                <div class="ms-3">
                                    <strong>' . htmlspecialchars($notification->data['student_name'], ENT_QUOTES) . '</strong> has submitted an <strong>' . htmlspecialchars(isset($notification->data['exam_name']) ? $notification->data['exam_name'] : getExamType($notification->data['exam_type']), ENT_QUOTES) . '</strong> for the course <strong>' . htmlspecialchars($notification->data['course_name'], ENT_QUOTES) . '</strong>.
                                    <div class="fs-6 text-muted">
                                        <span>
                                            <span class="bi bi-clock text-success me-1"></span>
                                            ' . $notification->created_at->diffForHumans() . ', ' . \Carbon\Carbon::parse($notification->created_at)->format('h:i A') . '
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </li>';
        
        }
        
        if ($notifications->isEmpty()) {
            $notificationHtml .= '<li class="list-group-item">No new notifications.</li>';
        }    

        return response()->json(['data' => $notificationHtml, 'count' => $notifications->count()]);
    }
    
    public function markAsRead(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $notificationId = $req->input('id');

            $notification = Notification::find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false], 404);
        }
        return redirect('/login');
    }
    
    public function assignSubEmentor(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $studentId = isset($req->studentId) ? base64_decode($req->input('studentId')) : 0;
            $courseId = isset($req->courseId) ? base64_decode($req->input('courseId')) : 0;
            $scmId = isset($req->scmId) ? base64_decode($req->input('scmId')) : 0;
            $subEmentorId = isset($req->subEmentorId) ? base64_decode($req->input('subEmentorId')) : 0;
            try {

                $select = [
                    'sub_ementor_id' => $subEmentorId,
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                    'student_course_master_id' => $scmId,
                    'created_by' => Auth::user()->id,
                    'is_deleted' => 'No',
                ];

                $where = [
                    'student_id' => $studentId,
                    'course_id' => $courseId,
                    'student_course_master_id' => $scmId,
                    'is_deleted' => 'No',
                ];

                $action = is_exist('subementor_student_relations', $where) ? 'update' : 'insert';

                processData(['subementor_student_relations', 'id'], $select, $where, $action);

                return response()->json([
                    'message' => $action == 'insert' ? 'Sub-mentor assigned successfully!' : 'Sub-mentor assignment updated!',
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Something went wrong! Please try again later.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } 
        return redirect('/login ');
    }

    public function getSubementorList()
    {
        if (Auth::check()) {
            $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
            $subEmentors = $this->ementorSubementorRelation->getSubEmentorList([ 'ementor_id' => $ementorId ]);
            return response()->json([
                'subEmentors' => $subEmentors,
            ]);
        }
        return redirect('/login');
    }

    public function subEmentorsExams($subEmentorId)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $subEmentorIdDecoded = base64_decode($subEmentorId);

        $subEmentorName = DB::table('users')->where('id', $subEmentorIdDecoded)->first(['name', 'last_name']);
        $subEmentorName = $subEmentorName->name . ' ' . $subEmentorName->last_name;

        $approvedAmounts = DB::table('exam_remark_master')
            ->where('remark_updated_by', $subEmentorIdDecoded)
            ->where('is_cheking_completed', '2')
            ->where('approved_status', '1')
            ->sum('amount') ?? 0;

        $checkedExams = DB::table('exam_remark_master')
            ->where('remark_updated_by', $subEmentorIdDecoded)
            ->where('is_cheking_completed', '2')
            ->whereNull('approved_by')
            ->select('exam_type', 'exam_id')
            ->get();

        $pendingAmounts = $checkedExams->sum(function ($checkedExam) {
            return DB::table('exam_amounts')
                ->where('exam_type', $checkedExam->exam_type)
                ->where('exam_id', $checkedExam->exam_id)
                ->sum('amount');
        });

        $totalAmounts = $approvedAmounts + $pendingAmounts;

        $data = [
            'subEmentorName' => $subEmentorName,
            'totalAmounts' => $totalAmounts,
            'approvedAmounts' => $approvedAmounts,
            'pendingAmounts' => $pendingAmounts,
        ];

        return view('frontend/teacher/sub-ementors-exam-list-details', compact('subEmentorId', 'data'));
    }
    
    public function getCheckedExamsPendingForApproval($subEmentorId)
    {
        if (Auth::check()) {
            $subEmentorId = isset($subEmentorId) ? base64_decode($subEmentorId) : 0;
            $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
            $checkedExams = getData('exam_remark_master', ['id', 'exam_type', 'exam_id', 'course_id', 'user_id', 'approved_status'], [
                'remark_updated_by' => $subEmentorId,
                'is_cheking_completed' => '2',
                'approved_by' => null,
            ], '', 'created_at', 'desc');

            foreach ($checkedExams as $checkedExam) {
                $examTitle = getExamTitle($checkedExam->exam_type, $checkedExam->exam_id);
                $examAmount = getExamAmount(base64_encode($checkedExam->course_id), base64_encode($checkedExam->exam_type), base64_encode($checkedExam->exam_id));
                $student = DB::table('users')->where('id', $checkedExam->user_id)->first(['name', 'last_name']);
                $studentName = $student->name . ' ' . $student->last_name; 

                $checkedExam->exam_title = $examTitle;
                $checkedExam->exam_amount = $examAmount;
                $checkedExam->student_name = $studentName;
            }
            return response()->json([
                'checkedExams' => $checkedExams,
            ]);
        }
        return redirect('/login');
    }
    
    public function getApprovedExams($subEmentorId)
    {
        if (Auth::check()) {
            $subEmentorId = isset($subEmentorId) ? base64_decode($subEmentorId) : 0;
            $ementorId = isset(Auth::user()->id) ? Auth::user()->id : 0;
            $approvedExams = getData('exam_remark_master', ['id', 'exam_type', 'exam_id', 'course_id', 'amount', 'user_id'], [
                'remark_updated_by' => $subEmentorId,
                'is_cheking_completed' => '2',
                ['approved_by', '!=', null],
            ], '', 'created_at', 'desc');

            foreach ($approvedExams as $approvedExam) {
                $examTitle = getExamTitle($approvedExam->exam_type, $approvedExam->exam_id);
                $student = DB::table('users')->where('id', $approvedExam->user_id)->first(['name', 'last_name']);
                $studentName = $student->name . ' ' . $student->last_name; 


                $approvedExam->exam_title = $examTitle;
                $approvedExam->approved_amount = $approvedExam->amount;
                $approvedExam->student_name = $studentName;
            }
            return response()->json([
                'approvedExams' => $approvedExams,
            ]);
        }
        return redirect('/login');
    }
    
    public function approveExam(Request $request)
    {
        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            \DB::beginTransaction();

            try {
                $scmId = $request->input('scmId');
                $amount = $request->input('amount');
                
                $data = [
                    'approved_by' => Auth::user()->id,
                    'amount' => $amount,
                    'exam_approved_on' => now(),
                    'exam_reject_reason' => null,
                    'approved_status' => 1,
                ];

                $is_updated = saveData($this->ExamRemark, $data, ['id' => $scmId]);

                if (isset($is_updated) && $is_updated !== false) {
                    \DB::commit();
                    return response()->json(['success' => true]);
                } else {
                    \DB::rollBack();
                    return response()->json(['success' => false]);
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                return response()->json(['success' => false, 'error' => 'Something went wrong']);
            }
        }
        return redirect('/login');
    }

    public function rejectExam(Request $request)
    {
        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            \DB::beginTransaction();

            try {
                $scmId = base64_decode($request->input('scmId'));
                $rejectReason = $request->reason;
                
                $data = [
                    'approved_status' => 2,
                    'exam_reject_reason' => $rejectReason,
                ];
                
                $is_updated = saveData($this->ExamRemark, $data, ['id' => $scmId]);

                if (isset($is_updated) && $is_updated !== false) {
                    \DB::commit();
                    return response()->json(['success' => true, 'scmId' => $scmId]);
                } else {
                    \DB::rollBack();
                    return response()->json(['success' => false]);
                }
            } catch (\Exception $e) {
                \DB::rollBack();
                return response()->json(['success' => false, 'error' => 'Something went wrong']);
            }
        }
        return redirect('/login');
    }



    public function checkCertificate(Request $request)
    {
        $id = $request->student_course_master_id;

        $exists = DB::table('certitficate_issue')
            ->where('student_course_master_id', $id)
            ->whereNull('transferred_on')
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function ementorTurnitinCheckSubmit(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $user_id = Auth::user()->id;
            $student_id  = isset($req->student_id) ? base64_decode($req->input('student_id')) : 0;
            $exam_id  = isset($req->exam_id) ? base64_decode($req->input('exam_id')) : 0;
            $course_id  = isset($req->course_id) ? base64_decode($req->input('course_id')) : 0;
            $exam_type  = isset($req->exam_type) ? base64_decode($req->input('exam_type')) : 0;
            $student_course_master_id  = isset($req->student_course_master_id) ? base64_decode($req->input('student_course_master_id')) : 0;
            $turnitin_marks  = isset($req->turnitin_marks)  ? $req->input('turnitin_marks') : '';
            $turnitin_file = $req->hasFile('turnitin_file') ? $req->file('turnitin_file') : 0;

            $courseExpired = is_expired(['user_id' => $student_id, 'course_id' => $course_id]);
            $validate_rules = [
                'turnitin_file' => 'required|mimes:pdf',
            ];
            $custom_messages = [
                'turnitin_file.required' => 'Please upload a file. PDF only is required.',
                'turnitin_file.mimes' => 'please upload only pdf.',
            ];
            
            $validate = Validator::make($req->all(), $validate_rules, $custom_messages);
            if (!$validate->fails()) {
                if (isset($courseExpired) && is_numeric($courseExpired) && $courseExpired >= 0) {
                    $where = ['id' => $exam_id, 'is_deleted' => 'No'];
                    
                    $whereRemark =  ['student_course_master_id' => $student_course_master_id, 'user_id' => $student_id, 'course_id' => $course_id, 'exam_id' => $exam_id, 'exam_type' => 1, 'is_active' => '1'];

                    $is_submitted = is_exist('exam_assignment_turnitin', $whereRemark);
                    if (is_numeric($is_submitted) && $is_submitted > 0) {
                        
                        $where = [
                            'student_course_master_id' => $student_course_master_id,
                            'user_id' => $student_id,
                            'course_id' => $course_id,
                            'exam_type' => '1',
                            'exam_id' => $exam_id,
                            'is_active' => '1',
                        ];
                            
                        $existingRecord = DB::table('exam_assignment_turnitin')
                        ->where($where)
                        ->where('answer_file_url','!=','') 
                        ->first();

                            // $i = 0;
                            if($existingRecord){
                                // DB::commit();

                                if ($req->hasFile('turnitin_file')) { 
                                    $filename = $turnitin_file->getClientOriginalName();
                                    $turnitin_file_url =  UploadFiles($turnitin_file, 'course/AssigmentDocs/TurnitinDocs','');
                                    if ($turnitin_file_url === FALSE) {
                                        return json_encode(['code' => 201, 'message' => 'File is corrupt', 'title' => "File is corrupt", "icon" => generateIconPath("error")]);
                                    }
                                    if (isset($turnitin_file_url['url']) && Storage::disk('local')->exists($turnitin_file_url['url'])) {
                                        $fileUrl = !empty($turnitin_file_url['url']) ? $turnitin_file_url['url'] : 'No File';
                                    } else {
                                        return json_encode(['code' => 201, 'title' => "Unable to Upload Pdf", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                                    }
                                }
                                $updateCourseDraft = DB::table('exam_assignment_turnitin')->where('id', $existingRecord->id)->update(['turnitin_marks' => $turnitin_marks, 
                                'turnitin_file_url'=> $fileUrl,'turnitin_file_name'=>$filename,'is_cheking_completed'=>'1','turnitin_updated_at'=>$this->time,'updated_at' => $this->time]);
                                
                                
                                if (is_numeric($updateCourseDraft) && $updateCourseDraft > 0) {
                                    
                                    $subEmentorId = getAssignedSubMentor($student_course_master_id);
                                    $courseData = getData('course_master', ['ementor_id', 'course_title'], ['id' => $course_id]);
                                    $ementorId = isset($courseData[0]->ementor_id) ? $courseData[0]->ementor_id : 0;
                                    $ementorData = getData('users', ['name', 'last_name', 'email'], ['id' => $ementorId]);
                                    $unsubscribeRoute = url('/unsubscribe/'.base64_encode($ementorData[0]->email));
                                    $StudentData = getData('users',['name','email','last_name'],['id'=> $student_id]);

                                    $recipientEmail = $ementorData[0]->email;
                                    $ccEmail = null;

                                    if ($subEmentorId) {
                                        $subEmentorData = getData('users', ['name', 'last_name', 'email'], ['id' => $subEmentorId]);
                                        $recipientEmail = $subEmentorData[0]->email;
                                        $ccEmail = $ementorData[0]->email;
                                    }

                                    $mailData = [
                                        '#EmentorName#' => $subEmentorId ? $subEmentorData[0]->name . " " . $subEmentorData[0]->last_name : $ementorData[0]->name . " " . $ementorData[0]->last_name,
                                        '#StudentName#' => $StudentData[0]->name . " " . $StudentData[0]->last_name,
                                        '#Exam#' => 'Assignment',
                                        '#Course Name#' => $courseData[0]->course_title,
                                        '#Submission Date#' => now()->format('Y-m-d'),
                                        '#unsubscribeRoute#' => $ementorData[0]->email
                                    ];

                                    mail_send(43, array_keys($mailData), array_values($mailData), $recipientEmail, $ccEmail);

                                    $message_title = "Similarity Percentage Submitted";
                                    $message_text = "Similarity percentage submitted successfully";
                                    return json_encode(['code' => 200, 'title' => $message_title, "message" => $message_text, "icon" => generateIconPath("success")]);
                                }
                            }
                        
                        
                    } else {
                        return json_encode(['code' => 202, 'title' => 'Exam not exist or deleted', 'message' => 'Contact to Admin Support', "icon" => generateIconPath("error")]);
                        
                    }
                } else {
                    return json_encode(['code' => 202, 'title' => 'Course not exist or expired', 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                }
            }else{
                $allMessages = implode("\n", $validate->errors()->all());
                return json_encode(['code' => 202, 'title' => 'Required Fields are Missing', 'message' => $allMessages, "icon" => generateIconPath("error"), 'data' => $validate->errors()]);
            }
        } else {
            return json_encode(['code' => 201, 'title' => "Something went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
        }
    }
    public function getStudent($enc_id)
    {
        if (Auth::check()) {
            $id = base64_decode($enc_id);
            $aboutmeData = '';
            $exists = is_exist('student_profile_master', ['student_id' => $id]);
            if ($exists > 0) {
                $where = ['student_id' => $id];
                $studentData = $this->userProfile->getUserProfile($where);
                $studentDoc = $this->studentDocument->getUserDocInfo($where);
                $aboutmeData = DB::table('student_about_me')->where('student_id',$id)->get();
                $wishlistData = $this->userProfile->getStudentLearning($where);
                return view('frontend.teacher.student-edit', compact('studentData', 'studentDoc','aboutmeData','wishlistData'));
            } 
        }
        return redirect('/login');
    }
    public function DocVerify(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $admin_id = Auth::user()->id;
            $name_person = isset($req->name_person) ? htmlspecialchars_decode($req->input('name_person')) : '';
            $user_id = isset($req->student_id) ? base64_decode($req->input('student_id')) : '';
            $birth_dob = isset($req->birth_dob) ? htmlspecialchars($req->input('birth_dob')) : '';
            $proof_name = isset($req->proof_name) ? htmlspecialchars($req->input('proof_name')) : '';
            $doc_id_no = isset($req->doc_id_no) ? htmlspecialchars($req->input('doc_id_no')) : '';
            $doc_auth = isset($req->doc_auth) ? htmlspecialchars($req->input('doc_auth')) : '';
            $issue_date = isset($req->issue_date) ? htmlspecialchars($req->input('issue_date')) : '0000-00-00';
            $expiry_date = isset($req->expiry_date) ? htmlspecialchars($req->input('expiry_date')) : '';
            $issue_country = isset($req->issue_country) ? htmlspecialchars($req->input('issue_country')) : '';
            $id_doc_status = $req->id_doc_status === "1" ? "Approved" : 'Rejected';
            $id_doc_status_text = $req->id_doc_status === "1" ? "approved" : 'rejected';

            $identity_doc_comments = isset($req->identity_doc_comments) ? htmlspecialchars($req->input('identity_doc_comments')) : '';
            $exists =   is_exist('users', ['id' => $user_id,  'is_deleted' => 'No']);


            if (isset($exists) && is_numeric($exists) && $exists === 0) {
                return json_encode(['code' => 201, 'title' => 'User Not Available', 'message' => 'User not Exist', 'icon' => generateIconPath('warning')]);
            }
            $validate_rules = [
                'name_person' => 'required|string|max:225|min:3',
                'proof_name' => 'required|string|max:225|min:3',
                'doc_id_no' => 'required|max:225|min:3',
                'doc_auth' => 'string|max:225|min:3',
                'birth_dob' => 'before:today',
                // 'issue_date' => 'before:today',
                'issue_country' => 'required|string|min:3',
                'id_doc_status' => 'required|string|min:1',
            ];
 

            if (isset($id_doc_status) && $id_doc_status == 'Approved') {
                if(isset($proof_name) && strtolower($proof_name) == 'passport' || strtolower($proof_name) == 'passeport'){
                    $validate_rules = array_merge($validate_rules, ['expiry_date' => 'required|date|after:today']);
                }
            }

            $validate = Validator::make($req->all(), $validate_rules);
            if (!$validate->fails()) {

                $where = ['student_id' => $user_id];
                $exists = is_exist('student_doc_verification', $where);
                if (isset($exists) && is_numeric($exists) && $exists > 0) {
                    $action = 'update';
                } else {
                    $action = 'insert';
                }
                if ($id_doc_status === 'Approved') {
                    $select = [
                        'identity_doc_number' => $doc_id_no,
                        'identity_doc_country' => $issue_country,
                        'identity_doc_authority' => $doc_auth,
                        'identity_doc_issue_date' => $issue_date,
                        'identity_doc_expiry' => $expiry_date,
                        'identity_doc_type' => $proof_name,
                        'name_on_identity_card' => $name_person,
                        'dob_on_identity_card' => $birth_dob,
                        'identity_approved_by' => $admin_id,
                        'identity_is_approved' => $id_doc_status,
                        'identity_doc_comments' => $identity_doc_comments
                    ];
                    $select = array_merge($select, ['identity_approved_on' => $this->time]);
                } else {
                    $select = [
                        'identity_doc_number' => '',
                        'identity_doc_country' => '',
                        'identity_doc_authority' => '0',
                        'identity_doc_issue_date' => '',
                        'identity_doc_expiry' => '',
                        'identity_doc_type' => '',
                        'name_on_identity_card' => '',
                        'dob_on_identity_card' => '',
                        'identity_approved_by' => 0,
                        'identity_is_approved' => 'Reject',
                        'identity_doc_comments' => $identity_doc_comments,
                        'identity_doc_file'=> '',
                        'doc_file_name'=>''

                    ];
                    $select = array_merge($select, ['identity_approved_on' => $this->time]);


                }
                $updateUserProfile = processData(['student_doc_verification', 'student_doc_id'], $select, $where, $action);

                $user = DB::table('users')
                    ->leftjoin('student_doc_verification','student_doc_verification.student_id','users.id','english_score')
                    ->where('id', $user_id)
                    ->select('name', 'last_name','email','identity_trail_attempt','edu_is_approved','resume_file_name')
                    ->first();




                $unsubscribeRoute = url('/unsubscribe/'.base64_encode($user->email));
                if (isset($updateUserProfile) && !empty($updateUserProfile['id'])) {

                    if ($id_doc_status === 'Approved') {

                        // $studentCourseMaster = DB::table('student_course_master')
                        // ->where('user_id', $user_id)
                        // ->orderBy('created_at', 'desc')
                        // ->first(['course_id']);
                        $studentCourseMaster = DB::table('student_course_master')
                        ->select('course_master.category_id','student_course_master.course_id')
                        ->leftJoin('course_master', 'course_master.id', '=', 'student_course_master.course_id')
                        ->where('user_id', $user_id)
                        ->where('student_course_master.is_deleted', 'No')
                        ->orderBy('student_course_master.created_at', 'desc')
                        ->first();

                        $CourseLink = '';
                        if ($studentCourseMaster) {
                            $base64EncodedCourseId = base64_encode($studentCourseMaster->course_id);
                            if($studentCourseMaster->category_id == 1){
                                $CourseLink = env('APP_URL')."/"."student/student-award-course-panel/".$base64EncodedCourseId;
                            }else{
                                $CourseLink = env('APP_URL')."/"."student/student-master-course-panel/".$base64EncodedCourseId;
                            }
                        } else {
                            $base64EncodedCourseId = null;
                        }

                        mail_send(
                            27,
                            [
                                '#Name#',
                                '#documents#',
                                '#[Study material link].#',
                                '#unsubscribeRoute#'
                            ],
                            [
                                $user->name . ' ' . $user->last_name,
                                'Identity Document',
                                $CourseLink,
                                $unsubscribeRoute
                            ],
                            $user->email
                        );
                        // if($id_doc_status === 'Approved' && $user->edu_is_approved == "Approved" && $user->resume_file_name != ''){

                        //     mail_send(
                        //         44,
                        //         [
                        //             '#Name#',
                        //             '#unsubscribeRoute#'
                        //         ],
                        //         [
                        //             $user->name . ' ' . $user->last_name,
                        //             $unsubscribeRoute
                        //         ],
                        //         $user->email
                        //     );
                        // }
                        // $this->service->verificationStatusUpdate($user_id);
                        
                    }else{

                        $unsubscribeRoute = url('/unsubscribe/'.base64_encode($user->email));
                        mail_send(
                            25,
                            [
                                '#Name#',
                                '#rejected_reason#',
                                '#2attempt#',
                                '#unsubscribeRoute#'
                            ],
                            [
                                $user->name . ' ' . $user->last_name,
                                !empty($identity_doc_comments) ? $identity_doc_comments : 'Your submitted documents need to meet the specific criteria required for enrollment.',
                                $user->identity_trail_attempt,
                                $unsubscribeRoute
                            ],
                            $user->email
                        );
                    }
                    $this->user->verificationStatutsUpdate($user_id);

                    return json_encode(['code' => 200, 'title' => 'Successfully ' . $id_doc_status, "message" => "Student details $id_doc_status_text successfully", "icon" => generateIconPath("success")]);
                }
            }else{
                return json_encode(['code' => 202, 'title' => 'Required Fields are Missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $validate->errors()]);
            }
        } else {
            return json_encode(['code' => 202, 'title' => 'Something Went Wrong', 'message' => 'Something Went Wrong.', "icon" => generateIconPath("error")]);
        }
    }
    public function EduDocVerify(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $admin_id = Auth::user()->id;
            $eduStudentName = isset($req->eduStudentName) ? htmlspecialchars_decode($req->input('eduStudentName')) : '';
            $user_id = isset($req->student_id) ? base64_decode($req->input('student_id')) : '';
            $institue_name = isset($req->institue_name) ? htmlspecialchars($req->input('institue_name')) : '';
            $is_id_verified = isset($req->institue_name) ? htmlspecialchars($req->input('institue_name')) : '';
            $passsingYear = isset($req->passsingYear) ? htmlspecialchars($req->input('passsingYear')) : '';
            $edu_level = isset($req->edu_level) ? htmlspecialchars($req->input('edu_level')) : '';
            $specilization = isset($req->specilization) ? htmlspecialchars($req->input('specilization')) : '';
            $eduDocName = isset($req->eduDocName) ? htmlspecialchars($req->input('eduDocName')) : '';
            $eduDocId = isset($req->eduDocId) ? htmlspecialchars($req->input('eduDocId')) : '';
            // $eduGrade = isset($req->eduGrade) ? htmlspecialchars($req->input('eduGrade')) : '';
            $eduRemark = isset($req->eduRemark) ? htmlspecialchars($req->input('eduRemark')) : '';
            $eduComments = isset($req->eduComments) ? htmlspecialchars($req->input('eduComments')) : '';
            $edu_doc_status = $req->edu_doc_status === "1" ? "Approved" : 'Rejected';
            $edu_doc_status_text = $req->edu_doc_status === "1" ? "approved" : 'rejected';
            $exists =   is_exist('users', ['id' => $user_id,  'is_deleted' => 'No']);


            if (isset($exists) && is_numeric($exists) && $exists === 0) {
                return json_encode(['code' => 201, 'title' => 'User Not Available', 'message' => 'User not Exist', 'icon' => generateIconPath('warning')]);
            }
            $validate_rules = [
                'edu_level' => 'required|string',
                // 'specilization' => 'required|string',
                'eduStudentName' => 'required|string|max:225|min:3',
                // 'institue_name' => 'required|string|max:225|min:3',
                'eduDocName' => 'required|string|max:225|min:3',
                'passsingYear' => 'required|before:today',
                // 'eduDocId' => 'required|string|max:225|min:3',
                // 'eduGrade' => 'required|string|max:225',
                'eduRemark' => 'required|string|max:225|min:3',
                'edu_doc_status' => 'required|string|min:1',
            ];

            $validate = Validator::make($req->all(), $validate_rules);
            $selectAthe = [];
            if (!$validate->fails()) {

                $where = ['student_id' => $user_id];
                $exists = is_exist('student_doc_verification', $where);
                if (isset($exists) && is_numeric($exists) && $exists > 0) {
                    $action = 'update';
                } else {
                    $action = 'insert';
                }   
                if ($edu_doc_status === 'Approved') {
                    $select = [
                        'education_doc_number' => $eduDocId,
                        'edu_level' => $edu_level,
                        'edu_specialization' => $specilization,
                        'degree_course_name' => $eduDocName,
                        'name_on_education_doc' => $eduStudentName,
                        'remark_on_edu_doc' => $eduRemark,
                        // 'grade_on_edu_doc' => $eduGrade,
                        'university_name_on_edu_doc' => $institue_name,
                        'passing_year' => $passsingYear,
                        'edu_approved_by' => $admin_id,
                        'edu_is_approved' => $edu_doc_status,
                        'comments_on_edu_doc' => $eduComments
                    ];
                    if($edu_level <= 5){
                        $selectAthe = [
                            'edu_athe_approved' => $edu_doc_status
                        ];
                    }
                    if($edu_level == 6){
                        $selectAthe = [
                            'edu_master_approved' => $edu_doc_status
                        ];
                    }
                    $select = array_merge($select, ['edu_approved_on' => $this->time],$selectAthe);
                } else {
                    $select = [
                        'education_doc_number' => '',
                        'edu_level' => '0',
                        'education_level_num'=>'',
                        'edu_specialization' => '',
                        'degree_course_name' => '',
                        'name_on_education_doc' => '',
                        'remark_on_edu_doc' => '',
                        'grade_on_edu_doc' => '',
                        'university_name_on_edu_doc' => '',
                        'passing_year' => null,
                        'edu_approved_by' => $admin_id,
                        'edu_is_approved' => 'Reject',
                        'comments_on_edu_doc' => $eduComments,
                        'edu_doc_file'=> '',
                        'edu_doc_file_name'=>'',

                    ];
                    if($edu_level <= 5){
                        $selectAthe = [
                            'edu_athe_approved' => ''
                        ];
                    }
                    if($edu_level == 6){
                        $selectAthe = [
                            'edu_master_approved' => ''
                        ];
                    }
                    $select = array_merge($select, ['edu_approved_on' => $this->time],$selectAthe);
                }

                $user = DB::table('users')
                ->leftjoin('student_doc_verification','student_doc_verification.student_id','users.id')
                ->where('id', $user_id)
                ->select('name', 'last_name','email','edu_trail_attempt','identity_is_approved','resume_file_name','edu_athe_approved','edu_master_approved','edu_level','english_score')
                ->first();

                $users = DB::table('users')
                ->leftjoin('document_verification','document_verification.student_id','users.id')
                ->where('users.id', $user_id)
                ->select('name', 'last_name','email','edu_trail_attempt','edu_level')
                ->first();

                if($edu_level <= 5 && !empty($user->edu_athe_approved)){
                    $updateUserProfile = processData(['student_doc_verification', 'student_doc_id'], $select, $where, $action);
                    $edu_trail_attempt = $user->edu_trail_attempt;

                }
                if($edu_level <= 6 && !empty($user->edu_master_approved)){
                    $updateUserProfile = processData(['student_doc_verification', 'student_doc_id'], $select, $where, $action);
                    $edu_trail_attempt = $user->edu_trail_attempt;

                }

                if($edu_level > 5 && $edu_level <= 6  && empty($user->edu_athe_approved)){
                    $updateUserProfile = processData(['student_doc_verification', 'student_doc_id'], $select, $where, $action);
                    $edu_trail_attempt = $user->edu_trail_attempt;

                }
                if($edu_level > 5 && $edu_level <= 6 && !empty($user->edu_athe_approved)){
                    $updateUserProfile = processData(['document_verification','id'],$select, $where, $action);
                    $edu_trail_attempt = $users->edu_trail_attempt;

                }
                if($edu_level >= 7 ){

                    if (empty($user->edu_master_approved)) {
                        if (empty($user->edu_athe_approved)) {

                            $updateUserProfile = processData(['student_doc_verification', 'student_doc_id'], $select, $where, $action);
                            $edu_trail_attempt = $user->edu_trail_attempt;

                        }
                    } elseif (empty($user->edu_athe_approved)) {
                        if (empty($user->edu_master_approved)) {

                            $updateUserProfile = processData(['student_doc_verification', 'student_doc_id'], $select, $where, $action);
                            $edu_trail_attempt = $user->edu_trail_attempt;

                        }
                    }
                }
                if($edu_level >= 7){ 

                    if (!empty($user->edu_master_approved)) {
                        if (empty($user->edu_athe_approved)) {

                            $updateUserProfile = processData(['document_verification','id'],$select, $where, $action);
                            $updateUserProfile = processData(['student_doc_verification', 'student_doc_id'], $select, $where, $action);

                            $edu_trail_attempt = $users->edu_trail_attempt;

                        }
                    } elseif (!empty($user->edu_athe_approved)) {
                        if (empty($user->edu_master_approved)) {

                            $updateUserProfile = processData(['document_verification','id'],$select, $where, $action);
                            $updateUserProfile = processData(['student_doc_verification', 'student_doc_id'], $select, $where, $action);
                            $edu_trail_attempt = $users->edu_trail_attempt;

                        }
                    }

                }

                $unsubscribeRoute = url('/unsubscribe/'.base64_encode($user->email));

                if (isset($updateUserProfile) && !empty($updateUserProfile['id'])) {

                    if ($edu_doc_status === 'Approved') {

                        $studentCourseMaster = DB::table('student_course_master')
                        ->select('course_master.category_id','student_course_master.course_id')
                        ->leftJoin('course_master', 'course_master.id', '=', 'student_course_master.course_id')
                        ->where('user_id', $user_id)
                        ->where('student_course_master.is_deleted', 'No')
                        ->orderBy('student_course_master.created_at', 'desc')
                        ->first();

                        $CourseLink = '';
                        if ($studentCourseMaster) {
                            $base64EncodedCourseId = base64_encode($studentCourseMaster->course_id);
                            if($studentCourseMaster->category_id == 1){
                                $CourseLink = env('APP_URL')."/"."student/student-award-course-panel/".$base64EncodedCourseId;
                            }else{
                                $CourseLink = env('APP_URL')."/"."student/student-master-course-panel/".$base64EncodedCourseId;
                            }
                        } else {
                            $base64EncodedCourseId = null;
                        }

                        mail_send(
                            27,
                            [
                                '#Name#',
                                '#documents#',
                                '#[Study material link].#',
                                '#unsubscribeRoute#'
                            ],
                            [
                                $user->name . ' ' . $user->last_name,
                                'Educational Document',
                                $CourseLink,
                                $unsubscribeRoute
                            ],
                            $user->email
                        );
                        // if($user->identity_is_approved === 'Approved' && $edu_doc_status == "Approved" && $user->resume_file_name != ''){

                        //     mail_send(
                        //         44,
                        //         [
                        //             '#Name#',
                        //             '#unsubscribeRoute#'
                        //         ],
                        //         [
                        //             $user->name . ' ' . $user->last_name,
                        //             $unsubscribeRoute
                        //         ],
                        //         $user->email
                        //     );
                        // }
                        // $this->service->verificationStatusUpdate($user_id);
                    }else{

                        mail_send(
                            26,
                            [
                                '#Name#',
                                '#rejected_reason#',
                                '#2attempt#',
                                '#unsubscribeRoute#'
                            ],
                            [
                                $user->name . ' ' . $user->last_name,
                                !empty($eduComments) ? $eduComments : 'Your submitted documents need to meet the specific criteria required for enrollment.',
                                $edu_trail_attempt,
                                $unsubscribeRoute
                            ],
                            $user->email
                        );
                    }
                }
                $this->user->verificationStatutsUpdate($user_id);

                
                return json_encode(['code' => 200, 'title' => 'Successfully ' . $edu_doc_status, "message" => "Educational doc $edu_doc_status_text successfully", "icon" => generateIconPath("success")]);
            } else {


                return json_encode(['code' => 202, 'title' => 'Required Fields are Missing', 'message' => 'Please provide required info', "icon" => generateIconPath("error"), 'data' => $validate->errors()]);
            }
        }
    }
    public function ResearchDocVerify(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $admin_id = Auth::user()->id;
            $user_id = isset($req->student_id) ? base64_decode($req->input('student_id')) : '';
            // $research_doc_status = $req->research_doc_status === "1" ? "Approved" : 'Rejected';
            // $research_doc_status_text = $req->research_doc_status === "1" ? "approved" : 'rejected';
            if ($req->research_doc_status === "1") {
                $research_doc_status = "Approved";
                $research_doc_status_text = "approved";
            } elseif ($req->research_doc_status === "0") {
                $research_doc_status = "Rejected";
                $research_doc_status_text = "rejected";
                $research_doc_update = "Reject";

            } elseif ($req->research_doc_status === "2") {
                $research_doc_status = "Reupload";
                $research_doc_status_text = "reupload";
                $research_doc_update = "Reupload";
            }
            $exists =   is_exist('users', ['id' => $user_id,  'is_deleted' => 'No']);

            if (isset($exists) && is_numeric($exists) && $exists === 0) {
                return json_encode(['code' => 201, 'title' => 'User Not Available', 'message' => 'User not Exist', 'icon' => generateIconPath('warning')]);
            }
            $validate_rules = [
                'research_doc_status' => 'required|string|min:1',
            ];

            $validate = Validator::make($req->all(), $validate_rules);
            if (!$validate->fails()) {

                $where = ['student_id' => $user_id];
                $exists = is_exist('student_doc_verification', $where);
                if (isset($exists) && is_numeric($exists) && $exists > 0) {
                    $action = 'update';
                } else {
                    $action = 'insert';
                }
                if ($research_doc_status === 'Approved') {
                    $select = [
                        'proposal_approved_by' => $admin_id,
                        'proposal_is_approved' => $research_doc_status,
                    ];
                    $select = array_merge($select, ['proposal_approved_on' => $this->time]);
                } else {
                    $select = [
                        'proposal_approved_by' => 0,
                        'proposal_is_approved' => $research_doc_update,
                        'research_proposal_file'=> '',
                        'research_proposal_file_name'=>'',
                        'proposal_uploaded_at'=>''

                    ];
                    $select = array_merge($select, ['proposal_approved_on' => $this->time]);

                }
                $updateUserProfile = processData(['student_doc_verification', 'student_doc_id'], $select, $where, $action);
                if (isset($updateUserProfile) && !empty($updateUserProfile['id'])) {
                    return json_encode(['code' => 200, 'title' => 'Successfully ' . $research_doc_status, "message" => "Student details $research_doc_status_text successfully", "icon" => generateIconPath("success")]);
                }
            }else{
                return json_encode(['code' => 202, 'title' => 'Required Fields are Missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $validate->errors()]);
            }
        } else {
            return json_encode(['code' => 202, 'title' => 'Something Went Wrong', 'message' => 'Something Went Wrong.', "icon" => generateIconPath("error")]);
        }
    }
    public function showlastwatchtime($courseId, $videoId,$user_id)
    {

        $userId = base64_decode($user_id);

        $decodedCourseId = base64_decode($courseId, true);

        if ($decodedCourseId === false) {
            return response()->json(['error' => 'Invalid course ID'], 400);
        }

        $progress = DB::table('video_progress')->where(['user_id' => $userId,'course_id' => $decodedCourseId,'video_id' => $videoId])->first();

        $lastWatchTime = $progress->last_record_time ?? 0;
        $totalWatchTime = $progress->last_watch_time ?? 0;
        $duration = $progress->duration ?? 0;

        if ($duration > 0 && $lastWatchTime >= ($duration - 5)) {

            $ranges = json_decode($progress->watchedRanges ?? '[]', true);
            $missingStart = null;

            if (is_array($ranges) && !empty($ranges)) {
                usort($ranges, function ($a, $b) {
                    return $a['start'] <=> $b['start'];
                });

                $current = 0;
                foreach ($ranges as $range) {
                    if ($range['start'] > $current + 1) {
                        $missingStart = $current;
                        break;
                    }
                    $current = max($current, $range['end']);
                }

                if ($current < $duration - 1 && $missingStart === null) {
                    $missingStart = $current;
                }
            }
            return response()->json([
                'last_watch_time' => $missingStart !== null ? $missingStart : $lastWatchTime
            ]);
        }

        return response()->json(['last_watch_time' => floor($lastWatchTime)]);
    }
    public function QuizViews(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $user_id = isset($req->user_id) ? base64_decode($req->input('user_id')) : 0;;
            $quiz_id  = isset($req->id) ? base64_decode($req->input('id')) : 0;
            $course_id  = isset($req->course_id) ? base64_decode($req->input('course_id')) : 0;
            $validate_rules = [
                'id' => 'required|string',
            ];
            $validate = Validator::make($req->all(), $validate_rules);
            if (is_exist('exam_quiz', ['id' => $quiz_id, 'is_deleted' => 'No']) > 0) {
                $is_done = is_exist('score_quiz', ['user_id' => $user_id, 'course_id' => $course_id, 'quiz_id' => $quiz_id]);
                if ($is_done === 0) {
                    if (!$validate->fails()) {
                        $where = ['id' => $quiz_id, 'is_deleted' => 'No'];
                        $examDetails = $this->Quiz->getQuizDetails($where);
                        return json_encode(['code' => 200, 'data' => $examDetails]);
                    } else {
                        return json_encode(['code' => 202, 'title' => 'Something Went Wrong', 'message' => 'Please Try Again', "icon" => "error"]);
                    }
                } else {
                    $getScore = getData('score_quiz', ['quiz_score'], ['user_id' => $user_id, 'course_id' => $course_id, 'quiz_id' => $quiz_id]);
                    $score = $getScore[0]->quiz_score;
                    return json_encode(['code' => 203, 'title' => "Quiz Already Submit", 'message' => 'Something Went Wrong. Please Try Again...', "icon" => "error", "score" => $score]);
                }
            } else {
                return json_encode(['code' => 201, 'title' => "Quiz not Exist", 'message' => 'Something Went Wrong. Please Try Again...', "icon" => "error"]);
            }
        } else {
            return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Something Went Wrong. Please Try Again...', "icon" => "error"]);
        }
    }
}