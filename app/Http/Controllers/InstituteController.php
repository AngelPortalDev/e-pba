<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Redirect, Validator, Storage,Crypt,App,DB};
use Carbon\Carbon;
use App\Models\TeacherProfile;
class InstituteController extends Controller
{



    public function InstituteDashboard()
    {
        $instituteId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($instituteId) && $instituteId != 0) {
            $where = ['institute_id' => $instituteId];
            $instituteData = $this->instituteProfile->getInstituteProfile($where);

            // $where = ['university_code'=>$instituteData[0]->university_code,'role'=>'user'];

            $where = ['university_code' => $instituteData[0]->university_code, 'role' => 'user', 'users.is_deleted' => 'No', 'block_status' => '0' ];
            $users = getData('users',['id','name','photo','last_name'],$where);
            $purchasedCount = DB::table('student_course_master')->join('users','users.id','=','student_course_master.user_id')->where($where)->where('student_course_master.is_deleted', 'No')->count('student_course_master.user_id');
            $registeredStudentCount = count($users);

            $registeredTeacherCount = DB::table('lecturers_master')->where(['university_code' => $instituteData[0]->university_code, 'is_deleted' => 'No'])->count();

            $enrolledCount = is_enrolled('','',$where);

            $today = Carbon::today();
            $startOfWeek = Carbon::now()->startOfWeek();
            $startOfMonth = Carbon::now()->startOfMonth();


            $baseQuery = DB::table('payments')
                ->leftJoin('orders', 'payments.id', '=', 'orders.payment_id')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->where('users.university_code', $instituteData[0]->university_code)
                ->where('payments.is_deleted', 'No')
                ->where('payments.status', "0");

            $todayCourseSales = (clone $baseQuery)
                ->whereDate('payments.created_at', $today)
                ->count();

            $thisWeekCourseSales = (clone $baseQuery)
                ->where('payments.created_at', '>=', $startOfWeek)
                ->count();

            $thisMonthCourseSales = (clone $baseQuery)
                ->where('payments.created_at', '>=', $startOfMonth)
                ->count();

            $baseSalesQuery = DB::table('payments')
                ->leftJoin('orders', 'payments.id', '=', 'orders.payment_id')
                ->leftJoin('users', 'users.id', '=', 'orders.user_id')
                ->where('users.university_code', $instituteData[0]->university_code)
                ->where('payments.is_deleted', 'No')
                ->where('payments.status', "0");

            $todaySales = (clone $baseSalesQuery)
                ->whereDate('payments.created_at', $today)
                ->sum(DB::raw('course_price - IFNULL(promo_code_discount, 0)'));

            $thisWeekSales = (clone $baseSalesQuery)
                ->where('payments.created_at', '>=', $startOfWeek)
                ->sum(DB::raw('course_price - IFNULL(promo_code_discount, 0)'));

            $thisMonthSales = (clone $baseSalesQuery)
                ->where('payments.created_at', '>=', $startOfMonth)
                ->sum(DB::raw('course_price - IFNULL(promo_code_discount, 0)'));

            $coursesPassedCount = DB::table('student_course_master')
                ->leftJoin('users', 'users.id', '=', 'student_course_master.user_id')
                ->where('users.university_code', $instituteData[0]->university_code)
                ->where('student_course_master.is_deleted', 'No')
                ->where('student_course_master.exam_remark', '1')
                ->distinct()
                ->count('student_course_master.user_id');

            $coursesFailedCount = DB::table('student_course_master')
                ->leftJoin('users', 'users.id', '=', 'student_course_master.user_id')
                ->where('users.university_code', $instituteData[0]->university_code)
                ->where('student_course_master.is_deleted', 'No')
                ->where('student_course_master.exam_remark', '0')
                ->distinct('users.id')
                ->count('student_course_master.user_id');

            return view('frontend.institute.institute-dashboard', compact('instituteData','registeredStudentCount', 'registeredTeacherCount', 'enrolledCount','purchasedCount', 'todayCourseSales', 'thisWeekCourseSales', 'thisMonthCourseSales', 'todaySales', 'thisWeekSales', 'thisMonthSales', 'coursesPassedCount', 'coursesFailedCount'));

        }

        return redirect('/login');
    }

    public function InstituteProfile()
    {
        $instituteId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($instituteId) && $instituteId != 0) {
            $where = ['institute_id' => $instituteId];
            $instituteData = $this->instituteProfile->getInstituteProfile($where);
            // dd($instituteData);
            return view('frontend.institute.institute-profiles', compact('instituteData'));

        }

        return redirect('/login');
    }


    public function updateInstituteProfile(Request $request){
        if ($request->isMethod('POST') && $request->ajax() && Auth::check()) {
            $institute_id = Auth::user()->id;
            $university_name = isset($request->university_name) ? htmlspecialchars_decode($request->input('university_name')) : '';
            $email = isset($request->email) ? htmlspecialchars($request->input('email')) : '';
            $mob_code = isset($request->mob_code) ? htmlspecialchars($request->input('mob_code')) : '';
            $website = isset($request->website) ? htmlspecialchars_decode($request->input('website')) : '';
            $mobile = isset($request->mobile) ? htmlspecialchars($request->input('mobile')) : '';
            $address = isset($request->address) ? htmlspecialchars_decode($request->input('address')) : '';
            $billing_city = isset($request->billing_city) ? htmlspecialchars_decode($request->input('billing_city')) : '';
            $billing_state = isset($request->billing_state) ? htmlspecialchars_decode($request->input('billing_state')) : '';
            $billing_country = isset($request->billing_country) ? htmlspecialchars_decode($request->input('billing_country')) : '';
            $contact_person_name = isset($request->contact_person_name) ? htmlspecialchars_decode($request->input('contact_person_name')) : '';
            $contact_person_email = isset($request->contact_person_email) ? htmlspecialchars_decode($request->input('contact_person_email')) : '';
            $contact_person_mob_code = isset($request->contact_person_mob_code) ? htmlspecialchars_decode($request->input('contact_person_mob_code')) : '';
            $contact_person_mobile = isset($request->contact_person_mobile) ? htmlspecialchars_decode($request->input('contact_person_mobile')) : '';
            $contact_person_designation = isset($request->contact_person_designation) ? htmlspecialchars_decode($request->input('contact_person_designation')) : '';
            $institute_id = isset($request->institute_id) ? base64_decode($request->input('institute_id')) : '';
            $reupload = 0;

            $exists =   is_exist('users', ['id' => $institute_id,  'is_deleted' => 'No']);
            if (isset($exists) && is_numeric($exists) && $exists === 0) {
                return json_encode(['code' => 201, 'title' => 'Institute Not Available', 'message' => 'Institute not Exist', 'icon' => generateIconPath('warning')]);
            }
            $validate_rules = [
                'university_name' => 'required|string|max:225|min:4|regex:/^(?=(?:.*[a-zA-Z]){2,})[a-zA-Z\s&\-\(\)]+$/',
                'photo_id' => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
                'licence' => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
                'contact_person_name' => ['string', 'max:100','regex:/^[a-zA-Z\s]+$/'],
                'contact_person_designation' => ['max:100','regex:/^(?=(?:.*[a-zA-Z]){2,})[a-zA-Z\s&\-\(\)]+$/'],
                'billing_city' => [ 'string','max:100','regex:/^(?=(?:.*[a-zA-Z]){2,})[a-zA-Z\s&\-\(\)]+$/'],
                'billing_state' => ['string','max:100','regex:/^(?=(?:.*[a-zA-Z]){2,})[a-zA-Z\s&\-\(\)]+$/'],
            ];

            $custom_messages = [
                'university_name.required' => 'Please enter your institute name.',
                'university_name.min' => 'Institute name must be at least 4 characters.',
                'university_name.max' => 'Institute name must not exceed 225 characters.',

                'photo_id.mimes' => 'Photo ID must be JPG, JPEG, PNG or PDF (max 5MB).',
                'photo_id.max' => 'Photo ID file size must not exceed 5MB.',

                'licence.mimes' => 'License must be JPG, JPEG, PNG or PDF (max 5MB).',
                'licence.max' => 'License file size must not exceed 5MB.',
            ];
            $validate = Validator::make($request->all(), $validate_rules,$custom_messages);
            if (!$validate->fails()) {

                $instituteProfile = DB::table('institute_profile_master')->where('institute_id', $institute_id)->first();
                $storagePath = 'storage/instituteDocs/' . base64_encode($institute_id) . '/';

                // Handle Photo ID upload
                if ($request->hasFile('photo_id')) {
                    if ($instituteProfile->photo_id) {
                        Storage::delete($instituteProfile->photo_id); // Delete old file
                    }
                    $originalFileName = $request->file('photo_id')->getClientOriginalName();
                    $photoIdPath = $request->file('photo_id')->storeAs($storagePath, $originalFileName);
                    $reupload = 1;
                } else {
                    $photoIdPath = $instituteProfile->photo_id ?? null;
                }

                // Handle License upload
                if ($request->hasFile('licence')) {
                    if ($instituteProfile->licence) {
                        Storage::delete($instituteProfile->licence); // Delete old file
                    }
                    $originalFileName = $request->file('licence')->getClientOriginalName();
                    $licencePath = $request->file('licence')->storeAs($storagePath,$originalFileName);
                    $reupload = 1;
                } else {
                    $licencePath = $instituteProfile->licence ?? null;
                }


                $where = ['id'=> $institute_id];
                $data = [
                    'name' => $university_name,
                ];
                $updateUser = processData(['users', 'id'], $data, $where);
                if (isset($updateUser)) {
                    $where = ['institute_id'=>$institute_id];
                    $select = [
                        'website'=>$website,
                        'address'=>$address,
                        'billing_city'=>$billing_city,
                        'billing_state'=>$billing_state,
                        'billing_country'=>$billing_country,
                        'contact_person_name'=>$contact_person_name,
                        'contact_person_email'=>$contact_person_email,
                        'contact_person_mob_code'=>$contact_person_mob_code,
                        'contact_person_mobile'=>$contact_person_mobile,
                        'contact_person_designation'=>$contact_person_designation,
                        'photo_id' => $photoIdPath,
                        'licence' => $licencePath,
                        'updated_by'=>Auth::user()->id,
                        'updated_at' => $this->time
                    ];
                    $updateInstitute = processData(['institute_profile_master', 'id'], $select, $where);
                    if($reupload == 1){
                        if ((request()->getHost() === 'www.eascencia.mt')) {
                            $email = 'asyed@collegedeparis.fr';
                            $ccEmail = 'mrodrigues@ascenciamalta.mt';
                        }else{
                            $email = 'chetan@angel-portal.com';
                            $ccEmail = 'ankita@angel-portal.com';
                        }
                        $unsubscribeRoute = url('/unsubscribe/'.base64_encode($email));
                        mail_send(60, ['#instututeName#', '#contactPersonName#', '#unsubscribeRoute#'], [$university_name, $contact_person_name, $unsubscribeRoute], $email, $ccEmail);
                    };

                    return json_encode(['code' => 200, 'title' => 'Successfully updated.', "message" => "Institute profile successfully updated.", "icon" => generateIconPath("success")]);

                } else {
                    return json_encode(['code' => 201, 'title' => "Unable to Create University", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                }
                if (isset($updateInstitute) && $updateInstitute === FALSE) {
                    return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
                }
            } else {
                return json_encode(['code' => 202, 'title' => 'Required Fields are Missing', 'message' => 'Please Provide Required Info', "icon" => generateIconPath("error"), 'data' => $validate->errors()]);
            }
        }else{
            return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please Try Again', "icon" => generateIconPath("error")]);
        }
    }
    public function InstituteStudentData()
    {
        $instituteId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($instituteId) && $instituteId != 0) {
            $where = ['institute_id' => $instituteId];
            $instituteData = $this->instituteProfile->getInstituteProfile($where);
            return view('frontend.institute.institute-students', compact('instituteData'));

        }

        return redirect('/login');
    }

    public function InstituteStudentList($limit, Request $request)
    {
        $user = Auth::user();
        $instituteId = 0;


        if ($user->role === 'institute') {
            $instituteId = $user->id;
        } elseif (in_array($user->role, ['admin', 'superadmin'])) {
            $previousUrl = url()->previous();
            $segments = explode('/', parse_url($previousUrl, PHP_URL_PATH));
            $lastSegment = end($segments);
            $decodedId = base64_decode($lastSegment);

            // Validate that it's a valid institute ID
            $instituteId = DB::table('institute_profile_master')->where('id', $decodedId)->first()->institute_id ?? 0;
        }

        // $instituteId = isset(Auth::user()->id) ? Auth::user()->id : 0;

        if (Auth::check() && !empty($instituteId) && $instituteId != 0) {
            $instituteProfile = getData('institute_profile_master',['university_code'],['institute_id'=>$instituteId]);
            // $where = ['university_code'=>$instituteProfile[0]->university_code,'role'=>'user'];
            $where = ['university_code'=>$instituteProfile[0]->university_code,'role'=>'user'];
            if($limit == 1){
                $instituteStudentData = $this->instituteProfile->getInstituteStudentList($where,'',$limit);
            }else{
                $instituteStudentData = $this->instituteProfile->getInstituteStudentList($where);
            }
            $wheres = ['institute_id' => $instituteId];
            $instituteData = $this->instituteProfile->getInstituteProfile($wheres);
            return response()->json($instituteStudentData);


        }

        return redirect('/login');
    }

    public function pendingApproval()
    {
        if (Auth::check()) {
            $is_approved = DB::table('institute_profile_master')->where(['institute_id' => Auth::user()->id])->first()->is_approved;
            if (Auth::check() && Auth::user()->role == 'institute' && $is_approved == 0) {
                return view('frontend.institute.pending-approval');
            }elseif (Auth::check() && Auth::user()->role == 'institute' && $is_approved == 2) {
                return redirect()->route('institute-profiles');
            }elseif (Auth::check() && Auth::user()->role == 'institute' && $is_approved == 4) {
                return redirect()->route('institute-profiles');
            }else{
                return redirect()->route('institute-dashboard');
            }

        }

        return redirect('/login');
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $institutes = DB::table('institute_profile_master')
        ->leftJoin('users', 'users.id', '=', 'institute_profile_master.institute_id')
        ->where('institute_profile_master.status','0')
        ->where('institute_profile_master.is_approved','1')
        ->where('users.is_deleted', 'No')
        ->where('users.block_status', '0')
        ->where('users.is_active','Active')
        ->where(function ($query) use ($search) {
            $query->where('users.name', 'like', "%$search%")
                  ->orWhere('institute_profile_master.university_code', 'like', "%$search%");
        })
        ->select('users.id', 'users.name','institute_profile_master.university_code')
        ->get();

        return response()->json($institutes);
    }


    public function InstituteTeacherData()
    {
        $instituteId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($instituteId) && $instituteId != 0) {
            $wheres = ['institute_id' => $instituteId];
            $instituteData = $this->instituteProfile->getInstituteProfile($wheres);
            return view('frontend.institute.institute-teachers', compact('instituteData'));

        }

        return redirect('/login');
    }

    public function InstituteTeacherList($limit, Request $request)
    {
        $user = Auth::user();
        $instituteId = 0;


        if ($user->role === 'institute') {
            $instituteId = $user->id;
        } elseif (in_array($user->role, ['admin', 'superadmin'])) {
            $previousUrl = url()->previous();
            $segments = explode('/', parse_url($previousUrl, PHP_URL_PATH));
            $lastSegment = end($segments);
            $decodedId = base64_decode($lastSegment);

            // Validate that it's a valid institute ID
            $instituteId = DB::table('institute_profile_master')->where('id', $decodedId)->first()->institute_id ?? 0;
        }

        // $instituteId = isset(Auth::user()->id) ? Auth::user()->id : 0;

        if (Auth::check() && !empty($instituteId) && $instituteId != 0) {
            $instituteProfile = getData('institute_profile_master',['university_code'],['institute_id'=>$instituteId]);
            $where = ['university_code'=>$instituteProfile[0]->university_code,'is_deleted'=>'No'];
            $instituteTeacherData = $this->instituteProfile->getInstituteTeacherList($where);
            return response()->json($instituteTeacherData);
        }

        return redirect('/login');
    }
    public function InstituteTeacherView($id){

        $id = base64_decode($id);
        if (Auth::check() && !empty($id) && $id != 0) {
            $exists = is_exist('lecturers_master', ['id' => $id]);
            $query = TeacherProfile::where(['is_deleted' => 'No']);
            if ($exists > 0) {
                $where = ['id' => $id];
                $query->where($where);
                $teacherData = $query->orderByDesc('id')->first();

                $wheres = ['institute_id' => Auth()->user()->id];
                $instituteData = $this->instituteProfile->getInstituteProfile($wheres);
                return view('frontend.institute.institute-teacher-view', compact('teacherData','instituteData'));
            }
        }
        return redirect('/login');

    }
    public function updateInstituteStatus(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $table = "lecturers_master";
            $admin_id = Auth::user()->id;
            $status =  isset($req->status) ? base64_decode($req->input('status')) : '';

                $i=0;
                $rules = [
                    "status" => "required|string",
                    "id" => "required",
                ];
                $validate = validator::make($req->all(), $rules);
                if (!$validate->fails()) {
                try {

                    if($status == 'delete'){
                        $i=0;
                        $id =  isset($req->id) ? base64_decode($req->input('id')) : '';
                        $where = ['id' => $id, 'is_deleted' => 'No'];
                        $is_exits = is_exist($table, $where);
                        if (!empty($is_exits) && is_numeric($is_exits) && $is_exits > 0) {
                            // $updateTeacher = deleteRecord(TeacherProfile::class, $where, true);
                            $updateTeacher = processData([$table, 'id'], ['is_deleted' => 'Yes', 'deleted_by' => Auth::user()->id], $where);
                            if (isset($updateTeacher) && $updateTeacher !== FALSE) {
                                return response()->json(['code' => 200, 'title' => 'Records Successfully Deleted', 'message' => '', 'icon' => generateIconPath('success')]);
                            }else{
                                return response()->json(['code' => 201, 'title' => 'Unable to Delete', "icon" => generateIconPath("error")]);
                            }
                        }
                    }
                    if($status == 'teacher_status_active' || $status == 'teacher_status_inactive'){
                        $id =  isset($req->id) ? base64_decode($req->input('id')) : '';

                        $where = ['id' => $id, 'is_deleted' => 'No'];
                        $exists = is_exist($table, $where);
                        if (isset($exists) && $exists > 0) {


                            $where = ['id' => $id];

                            if($status == 'teacher_status_active'){

                                $selectData = [
                                    'status' => '0',
                                    'updated_by' => $admin_id,
                                    'updated_at'=>$this->time
                                ];

                            }else if($status == 'teacher_status_inactive'){

                                $selectData = [
                                    'status' => '1',
                                    'updated_by' => $admin_id,
                                    'updated_at'=> $this->time
                                ];
                            }

                            $Message = "Status Changed";
                            $Message_Text = "status changed";
                            $updateTeacher = processData([$table, 'id'], $selectData , $where);
                            if (isset($updateTeacher)) {

                                return json_encode(['code' => 200, 'title' => "Successfully $Message", "message" => "Teacher $Message_Text successfully", "icon" => generateIconPath("success")]);
                            }
                        }
                    }

                } catch (\Throwable $th) {
                    return $th;
                    return json_encode(['code' => 201, 'title' => "Something Went Wrong", 'message' => 'Please try again', "icon" => generateIconPath("error")]);
                }
            }else {
                return json_encode(['code' => 201, 'title' => "Something Went Wrong ", 'message' => 'Please try again', "icon" => generateIconPath("error")]);
            }

        } else {
            return json_encode(['code' => 201, 'title' => "Already Deleted ", 'message' => 'Please try unique name', "icon" => generateIconPath("error")]);
        }
    }

}
