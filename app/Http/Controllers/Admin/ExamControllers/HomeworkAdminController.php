<?php

namespace App\Http\Controllers\Admin\ExamControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request};
use Illuminate\Support\Facades\{Auth, DB,Storage};
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\HomeworkModule;
use App\Models\HomeworkQuestion;
use App\Models\ExamManage;


class HomeworkAdminController extends Controller{

    public function __construct()
    {
        parent::__construct();

    }


    public function addHomework(Request $request)
    {

        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            $admin_id = Auth::user()->id;
            $award_id = isset($request->award_id) ? base64_decode($request->input('award_id')) : 0;
            $homework_title = isset($request->homework_title) ? htmlspecialchars_decode($request->input('homework_title')) : '';
            // $section_id = isset($request->section_id) ? base64_decode($request->input('section_id')) : 0;


            try {
                $request->validate([
                    'award_id' => ['required', 'string'],
                    'homework_title' => [
                        'required', 'min:3',
                        'max:255', 'string'
                    ],
                ]);
            } catch (ValidationException $e) {
                return json_encode(['code' => 202, 'title' => 'Required Fields are Missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $e->errors()]);
            }
            if (is_exist('course_master', ['id' => $award_id]) > 0) {
                try {

                    // commented because some courses have multiple assignment like (Award in International Organisational Management and Development)

                    $where = ['award_id' => $award_id, 'is_deleted' => 'No', 'deleted_at' => null];
                    $exists = is_exist('exam_homework', $where);
                    if (isset($exists) && $exists > 0) {
                        return json_encode([
                            'code' => 404, 'title' => "Already Added", 'message' => 'Course homework already added.', "icon" => generateIconPath("warning")
                        ]);
                    }else{
                        $select = [
                            'homework_title' => $homework_title,
                            'award_id' => $award_id,
                            'created_by' => $admin_id,
                            'is_deleted' => 'No',
                            'last_updated_by' => $admin_id,
                            'created_at' => $this->time,
                            // 'section_id'=> $section_id
                        ];
                        DB::beginTransaction();
                        $assignUpdate = processData(['exam_homework', 'id'], $select);
                        if (isset($assignUpdate) && $assignUpdate['status'] === true) {
                            $select = [
                                'exam_id' => $assignUpdate['id'],
                                'course_id' => $award_id,
                                'exam_type' => 10,
                                'created_by' => $admin_id,
                                'created_at' => $this->time,
                            ];
                            $homeworkManagUpdate = processData(['exam_management_master', 'id'], $select);
                            if (isset($homeworkManagUpdate) && $homeworkManagUpdate['status'] === true) {
                                DB::commit();
                                return json_encode(['code' => 200, 'title' => "Successfully Added", 'message' => 'Homework added successfully', "icon" => generateIconPath("success")]);
                            }
                        }
                        DB::rollBack();
                        return json_encode([
                            'code' => 404, 'title' => "Unable to Add Homework", 'message' => 'Please Try Again', "icon" => generateIconPath("error")
                        ]);
                    }
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return json_encode([
                        'code' => 404, 'title' => "Unable to Add Homework", 'message' => 'Something Went Wrong', "icon" => generateIconPath("error")
                    ]);
                }
            } else {
                return json_encode([
                    'code' => 404, 'title' => "Unable to Add Homework", 'message' => 'Please Try Again', "icon" => generateIconPath("error")
                ]);
            }
        }
    }
    public function editHomework(Request $request)
    {
      
        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            $admin_id = Auth::user()->id;
            $homework_id = isset($request->homework_id) ? base64_decode($request->input('homework_id')) : 0;
            $homework_title = isset($request->homework_title) ? htmlspecialchars_decode($request->input('homework_title')) : '';
            $homework_instruction = isset($request->homework_instruction) ? htmlentities($request->input('homework_instruction')) : '';
            $instruction = isset($request->instruction) ? htmlentities($request->input('instruction')) : '';
            $instruction_file = isset($request->instruction_file) && $request->hasFile('instruction_file') ? $request->file('instruction_file') : '';
            $old_instruction_file = isset($request->old_instruction_file) && !empty($request->old_instruction_file) ? htmlspecialchars($request->input('old_instruction_file')) : '';
            try {
                $validate = [
                    'homework_title' => ['required', 'min:3', 'max:255'],
                    'instruction' => ['nullable', 'min:3'],
                ];          
                $messages = [
                    // 'homework_title.required' => 'The homework title is required.',
                    // 'homework_title.min' => 'The homework title must be at least 3 characters.',
                    'instruction.min' => 'The instruction must be at least 3 characters.',
                    'instruction_file.max' => 'The instruction file should be less than 5MB.',
                    'instruction_file.mimes' => 'The instruction file must be a PDF, DOC, DOCX, XLS, XLSX, or CSV.',
                    'instruction_file.required' => 'The instruction file field is required.',
                ];
                
                if ($request->hasFile('instruction_file')) {
                    $validate['instruction_file'] = ['required', 'mimes:pdf,doc,docx,xls,xlsx,csv', 'max:5120'];
                } else {
                    $validate['instruction_file'] = ['nullable', 'mimes:pdf,doc,docx,xls,xlsx,csv', 'max:5120'];
                }
                
                $request->validate($validate,$messages);
            } catch (ValidationException $e) {
                return json_encode(['code' => 202, 'title' => 'Required Fields are Missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $e->errors()]);
            }

            $table = "exam_homework";
            $where = ['id' => $homework_id, 'is_deleted' => 'No'];


            $exists = is_exist($table, $where);
            if (isset($exists) && $exists > 0) {
                $select = [
                    'homework_title' => $homework_title,
                    'instructions' => $homework_instruction,
                    'last_updated_by' => $admin_id
                ];
                if ($request->hasFile('instruction_file')) {
                    $instruction_file_url =  UploadFiles($instruction_file, 'course/HomeworkDocs', $old_instruction_file);
                    if ($instruction_file_url === FALSE) {
                        return json_encode(['code' => 201, 'message' => 'File is corrupt', 'title' => "File is corrupt", "icon" => generateIconPath("error")]);
                    }
                    if (isset($instruction_file_url['url']) && Storage::disk('local')->exists($instruction_file_url['url'])) {
                        $thumbnail_filename = $instruction_file->getClientOriginalName();
                        $select = array_merge(
                            [
                                'instrcution_file_url' => !empty($instruction_file_url['url']) ? $instruction_file_url['url'] : 'No File',
                                'instrcution_file_name' => $thumbnail_filename
                            ],
                            $select
                        );
                    } else {
                        return json_encode(['code' => 201, 'title' => "Unable to Upload file", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                    }
                }
                try {
                    DB::beginTransaction();
                    $updateHomework = processData([$table, 'id'], $select, $where);
                    if (isset($updateHomework) && $updateHomework['status'] === true) {
                        DB::commit();
                        return json_encode(['code' => 200, 'title' => 'Successfully Updated', "message" => "Homework successfully updated", "icon" => generateIconPath("success")]);
                    }
                    DB::rollback();
                    return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                } catch (\Throwable $th) {
                    return $th;
                    DB::rollback();
                    return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                }
            }
        }
    }
    public function getHomeworkData($action = '', $id = '')
    {
        if (Auth::check()) {

            $homeworkData = [];

            $id = isset($id) && !empty($id) ? base64_decode($id) : 0;
            $action = isset($action) && !empty($action) ? base64_decode($action) : 'All';
            $where = [];
            if (isset($action) && !empty($action) && $action != '' && $action != 'edit' && $action != 'All') {
                $where = [
                    'is_active' => $action
                ];
            }
            if (
                isset($id) && !empty($id)
            ) {
                $whereId = [
                    'id' => $id
                ];
                $where = array_merge($where, $whereId);
            }

            if (isset($action) && !empty($action) && $action === 'edit') {
                $homeworkData = $this->homeworkModule->getHomeworkDetails($where);
                return view('admin/exam/edit-homework', compact('homeworkData'));
            } else {
                $homeworkData = $this->homeworkModule->getHomeworkDetails($where);
            }
            return response()->json($homeworkData);
        }
        return redirect('/login');
    }
    public function addHomeworkQuestion(Request $request)
    {

        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {

            $admin_id = Auth::user()->id;
            // $assignment_mark = isset($request->assignment_mark) ? htmlspecialchars($request->input('assignment_mark')) : '';
            $question = isset($request->questionData) ? $request->input('questionData') : '';
            
            // $answer_limit = isset($request->assignment_answer_limit) && is_numeric($request->assignment_answer_limit) ? htmlspecialchars($request->input('assignment_answer_limit')) : 100;
            $homework_id = isset($request->homework_id) ? base64_decode($request->input('homework_id')) : 0;
            $question_id = isset($request->question_id) ? base64_decode($request->input('question_id')) : 0;
            $section_id = isset($request->section_id) ? base64_decode($request->input('section_id')) : 0;

            $mimes = isset($request->mimes) ? $request->input('mimes') : '';
            $mimesString = implode(',', $mimes);
            $question_file = isset($request->question_file) && $request->hasFile('question_file') ? $request->file('question_file') : '';
            $old_question_file = isset($request->old_question_file) && !empty($request->old_question_file) ? htmlspecialchars($request->input('old_question_file')) : '';
            $timestamp = Carbon::now('Europe/Malta')->format('Y-m-d H:i:s');
            $exists = is_exist('exam_homework', ['id' => $homework_id, 'is_deleted' => 'No']);
            $Questionexists = is_exist('exam_homework_questions', ['id' => $question_id, 'is_deleted' => 'No']);
            try {
                // $request->validate([
                //     'questionData' => ['min:3'],
                //     // 'homeworkmark' => ['required', 'numeric'],
                // ]);
                $Messages = [];
                $validate = [];
                // $validate = [
                //     'questionData' => ['min:3'],
                // ];
                if ($request->hasFile('question_file')) {
                    $validate = array_merge($validate, ['question_file' => ['required', 'mimes:pdf,doc,docx,xls,xlsx,csv', 'max:5120']]);
                    $Messages = [
                        'question_file.max' => 'The file should be less than 5MB.',
                        'question_file.mimes' => 'The file must be a PDF.',
                        'instruction_file.required' => 'The instruction file field is required.',
                    ];
                }
                $request->validate($validate,$Messages);
            } catch (ValidationException $e) {
                return json_encode(['code' => 202, 'title' => 'Required Fields are Missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $e->errors()]);
            }
            $where = [];
            if (isset($exists) && is_numeric($exists) && $exists > 0) {
                $select = [
                    'homework_id' =>  $homework_id,
                    'question' => $question,
                    'section_id'=>$section_id,
                    // 'answer_limit' => $answer_limit,
                    'is_deleted' => 'No',
                ];
                if ($request->hasFile('question_file')) {
                    $question_file_url =  UploadFiles($question_file, 'course/HomeworkDocs/QuestionDocs', $old_question_file);
                    if ($question_file_url === FALSE) {
                        return json_encode(['code' => 201, 'message' => 'File is corrupt', 'title' => "File is corrupt", "icon" => generateIconPath("error")]);
                    }
                    if (isset($question_file_url['url']) && Storage::disk('local')->exists($question_file_url['url'])) {
                        $thumbnail_filename = $question_file->getClientOriginalName();
                        $select = array_merge(
                            [
                                'question_file_url' => !empty($question_file_url['url']) ? $question_file_url['url'] : 'No File',
                                'question_file_name' => $thumbnail_filename
                            ],
                            $select
                        );
                    } else {
                        return json_encode(['code' => 201, 'title' => "Unable to Upload file", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                    }
                }
                if(!empty($mimes)){
                    $select = array_merge(
                        [
                            'mimes' => $mimesString
                        ],
                        $select
                    );
                }
                if (isset($Questionexists) && is_numeric($Questionexists) && $Questionexists > 0) {
                    $where = ['id' => $question_id, 'is_deleted' => 'No'];
                    $select = array_merge($select, ['last_updated_by' => $admin_id]);
                    $title = "Successfully Updated";
                    $message = "Question updated successfully";
                } else {
                    $select = array_merge($select, ['created_by' => $admin_id, 'created_at' => $timestamp]);
                    $title = "Successfully Added";
                    $message = "Question added successfully";
                }
                $updateUser = processData(['exam_homework_questions', 'id'], $select, $where);
                if (isset($updateUser) && $updateUser['status'] === true) {
                    return json_encode(['code' => 200, 'title' => $title, 'message' => $message, "icon" => generateIconPath("success"), "data" => $updateUser]);
                }
                return json_encode(['code' => 404, 'title' => "Unable to Add Question", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
            }
            return json_encode(['code' => 404, 'title' => "Homework Not Available", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
        }
    }
    public function editViewHomeworkQuestion(Request $request)
    {
        if ($request->isMethod('POST') && Auth::check()) {
            $questionID = isset($request->question_id) ? base64_decode($request->input('question_id')) : 0;

            if (isset($questionID) && !empty($questionID)) {
                $exists = is_exist('exam_homework_questions', ['id' => $questionID, 'is_deleted' => 'No']);
                if (isset($exists) && is_numeric($exists) && $exists > 0) {
                    $QuestionHomework = getData('exam_homework_questions', ['id', 'question','question_file_url','question_file_name','mimes','section_id'], ['id' => $questionID]);
                    return json_encode(['code' => 200, 'data' => $QuestionHomework[0]]);
                }
                return json_encode(['code' => 404, 'title' => "Question not Found", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
            } else {
                return json_encode(['code' => 404, 'title' => "Quiz Not Available", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
            }
        }
        return json_encode(['code' => 404, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
    }
    public function deleteHomeworkQuestion(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $admin_id = Auth::user()->id;
            $id =  isset($req->id) ? base64_decode($req->input('id')) : 0;
            $table = "exam_homework_questions";
            $where = ['id' => $id, 'is_deleted' => 'No'];
            $exists = is_exist($table, $where);
            if (isset($exists) && $exists > 0) {
                $select = [
                    'is_deleted' => 'Yes',
                    'deleted_by' => $admin_id
                ];
                try {
                    DB::beginTransaction();
                    $updateHomework = processData([$table, 'id'], $select, $where);
                    if (isset($updateHomework) && $updateHomework['status'] === true) {
                        DB::commit();
                        return json_encode(['code' => 200, 'title' => 'Successfully Deleted', "message" => "", "icon" => generateIconPath("success")]);
                    }
                    DB::rollback();
                    return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                } catch (\Throwable $th) {
                    DB::rollback();
                    return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                }
            } else {
                return json_encode(['code' => 201, 'title' => "Already Deleted ", 'message' => 'Please Try Unique Name', "icon" => generateIconPath("error")]);
            }
        } else {
            return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
        }
    }
    public function deleteHomework(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $admin_id = Auth::user()->id;
            $id =  isset($req->id) ? base64_decode($req->input('id')) : 0;
            $course_id =  isset($req->course_id) ? base64_decode($req->input('course_id')) : 0;

            $table = "exam_homework";
            $where = ['id' => $id, 'is_deleted' => 'No'];
            $exists = is_exist($table, $where);
  
            if (isset($exists) && $exists > 0) {
                $select = [
                    'is_deleted' => 'Yes',
                    'deleted_by' => $admin_id
                ];
                try {
                    DB::beginTransaction();
                    $canDeleteExam = canDeleteExam($course_id);

                    if ($canDeleteExam['status'] === false) {
                        return json_encode(['code' => 403, 'title' => 'Deletion Failed', 'message' => $canDeleteExam['message'], "icon" => generateIconPath("error")]);
                    }
                 
                    $result = deleteRecord(HomeworkModule::class, $where, true);

                    if (isset($result) && $result['status'] === true) {
                        $questions = getData('exam_homework_questions', ['id'], ['homework_id' => $result['id']]);

                        if(isset($questions) && !empty($questions[0])){
                            foreach($questions as $question){
                                $where = ['id' => $question->id];
                                deleteRecord(HomeworkQuestion::class, $where, true);
                            }
                        }

                        $where = ['course_id' => $course_id, 'exam_type' => '10', 'exam_id' => $result['id']];
                        $examManageMaster = deleteRecord(ExamManage::class, $where, true);
                        if (isset($examManageMaster) && $examManageMaster['status'] === true) {
                            DB::commit();
                            return json_encode(['code' => 200, 'title' => 'Successfully Deleted', "message" => "Successfully deleted", "icon" => generateIconPath("success")]);
                        }
                    }
                    DB::rollback();
                    return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                } catch (\Throwable $th) {
                    DB::rollback();
                    return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                }
            } else {
                return json_encode(['code' => 201, 'title' => "Already Deleted ", 'message' => 'Please Try Unique Name', "icon" => generateIconPath("error")]);
            }
        } else {
            return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
        }
    }

    public function getCourseSectionList($courseId)
    {
        $courseId = base64_decode($courseId);
        $sectionMasters = DB::table('course_section_masters')
            ->select('course_section_masters.id','course_section_masters.section_name')
            ->leftJoin('course_managment_master','course_managment_master.section_id','course_section_masters.id')
            ->where('course_managment_master.course_master_id', $courseId)
            ->where('course_managment_master.is_deleted', 'No')
            ->get();
    
        $sections = [];
        foreach ($sectionMasters as $sectionMaster) {
            $sections[] = [
                'title' => $sectionMaster->section_name,
                'id' => $sectionMaster->id
            ];
        
        }
        return response()->json(['sections' => $sections]);
    }


}