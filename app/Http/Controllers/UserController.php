<?php

namespace App\Http\Controllers;

use Mindee\{
    Client,
    Product\Passport\PassportV1,
    Product\Generated\GeneratedV1,
    Input\PredictMethodOptions
};
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\StudentDocument;
use App\Models\StudentProfile;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, Redirect, Validator, Storage, Crypt, App,Log};
use Illuminate\View\View;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Carbon\Carbon;
use File;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Socialite\Facades\Socialite;
use App\Providers\RouteServiceProvider;
use Google\Client as GoogleClient;
use Google\Service\PeopleService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\RateLimiter;
use DB;
use PDF;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
class UserController extends Controller
{

    protected $mindeeClient;
    protected $eduCategory;
    protected $nationIDCategory;
    protected $mindeeUser;
    public function __construct()
    {
        parent::__construct();
        $this->eduCategory = env('MIND_EDU_CAT');
        $this->nationIDCategory = env('MIND_NATIONALID_CAT');
        $this->mindeeUser = env('MIND_USERNAME');
    }
    public function loginView()
    {
        return view('frontend.login');
    }
    public  function profilImageUpload(Request $req)
    {
        if (
            $req->isMethod('POST') && $req->ajax() && Auth::check()
        ) {

            $image_file = $req->hasFile('image_file') ? $req->file('image_file') : '';
            $old_img_name = !empty($req->input('old_img_name')) ? $req->input('old_img_name') : '';
            $cat = !empty($req->input('cat')) ? base64_decode($req->input('cat')) : '';
            if ($req->hasFile('image_file')) {
                if (auth()->user()->role == 'institute') {
                    $rules = [
                        'image_file' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:width=302,height=272'],
                    ];
                    $messages = [
                        'image_file.required'   => 'The image file is required.',
                        'image_file.mimes'      => 'Invalid file type! Only JPG, PNG, and SVG files are allowed.',
                        'image_file.max'        => 'File size must be less than 1MB.',
                        'image_file.dimensions' => 'Image must be exactly 302x272 pixels (Width: 302px, Height: 272px).',
                    ];
                } else {
                    $rules = [
                        'image_file' => 'required|mimes:jpeg,png,jpg,svg|max:1024',
                    ];
                    $messages = [
                        'image_file.required' => 'Please upload an image.',
                        'image_file.mimes' => 'Allowed file types are JPEG, PNG, JPG, SVG.',
                        'image_file.max' => 'Image size should not exceed 1MB.',
                    ];
                }
                $validate = Validator::make($req->only(['image_file']), $rules, $messages);
                if (!$validate->fails() && !empty($cat)) {

                    if (auth()->user()->role == 'instructor') {

                        $logo_uploaded = $this->EmentorfilesUpload($image_file, $cat, []);
                    } else if (auth()->user()->role == 'institute') {

                        $logo_uploaded = $this->InstitutefilesUpload($image_file, $cat, []);
                    } else if (auth()->user()->role === 'turnitin-instructor') {

                        $logo_uploaded = $this->EmentorfilesUpload($image_file, $cat, []);
                    } else if (auth()->user()->role === 'sub-instructor') {

                        $logo_uploaded = $this->EmentorfilesUpload($image_file, $cat, []);
                    } else {

                        $logo_uploaded = $this->StudentfilesUpload($image_file, $cat, []);
                    }
                    if (isset($logo_uploaded['code']) && $logo_uploaded['code'] === 200) {
                        return json_encode(['code' => 200, 'message' => __('response.successfully_updated'), 'text' => "", "icon" => "error", 'new' => $logo_uploaded['url']]);
                    } else {
                        return json_encode(['code' => 202, 'message' => 'Unable to Upload2', 'text' => "Try Again", "icon" => "error", 'old' => $old_img_name]);
                    }
                } else {

                    echo json_encode(['code' => 203, 'message' => $validate->errors()->first(), 'text' => "File Should be JPG, PNG & Less then 1MB", "icon" => "error", 'old' => $old_img_name]);
                }
            } else {

                echo json_encode(['code' => 204, 'message' => 'No Image', 'text' => "", "icon" => "error", 'old' => $old_img_name]);
            }
        } else {

            echo json_encode(['code' => 205, 'message' => __('response.cart.something_went_wrong'), 'text' => "", "icon" => "error"]);
        }
    }
    public function UserProfile()
    {
        $studentId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($studentId) && $studentId != 0) {
            $where = ['student_id' => $studentId];
            $studentData = $this->userProfile->getUserProfile($where);
            return view('frontend.student.student-profile', compact('studentData'));
        }

        return redirect('/login');
    }
    public function UserSocialProfile()
    {
        $studentId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($studentId) && $studentId != 0) {
            $select = ['facebook', 'instagram', 'twitter', 'linkedIn'];

            $studentData = $this->userProfile->getCurrentUserProfile($select);

            return view('frontend.student.student-social-profile', compact('studentData'));
        }

        return redirect('/login ');
    }
    public function UserAboutMe()
    {
        $studentId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($studentId) && $studentId != 0) {
            $studentData = $this->userProfile->getCurrentUserProfile();

            $AboutmeData = DB::table('student_about_me')->where('student_id', $studentId)->get();
            return view('frontend.student.student-about-me', compact('AboutmeData', 'studentData'));
        }

        return redirect('/login ');
    }
    public function UserSecurityProfile()
    {
        $studentId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($studentId) && $studentId != 0) {
            $select = ['facebook', 'instagram', 'twitter', 'linkedIn'];

            $studentData = $this->userProfile->getCurrentUserProfile($select);
            return view('frontend.student.student-account-security', compact('studentData'));
        }

        return redirect('/login ');
    }
    public function UserVerificationView()
    {
        $studentId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($studentId) && $studentId != 0) {
            $select = ['identity_doc_number', 'identity_doc_country', 'identity_doc_authority', 'identity_doc_issue_date', 'identity_doc_expiry', 'name_on_identity_card', 'identity_is_approved', 'identity_is_approved', 'identity_trail_attempt', 'identity_doc_uploaded_on', 'education_doc_number', 'degree_course_name', 'name_on_education_doc', 'remark_on_edu_doc', 'grade_on_edu_doc', 'university_name_on_edu_doc', 'passing_year', 'edu_trail_attempt', 'edu_is_approved', 'edu_doc_uploaded_on'];
            $where = ['student_id' => $studentId];

            $studentData = $this->studentDocument->getUserDocInfo($where);

            return view('frontend.student.student-document-verification', compact('studentData'));
        }

        return redirect('/login');
    }
    public function UserInvoice()
    {
        $studentId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($studentId) && $studentId != 0) {
            $subQuery = DB::table('payments as p')
                ->selectRaw("
                    MAX(COALESCE(pi.created_at, p.created_at)) as latest_created_at,
                    p.user_id,
                    o.course_id,
                    CASE WHEN p.installment_status = 'InstallmentPayment' THEN pi.paid_install_no ELSE NULL END as installment_no
                ")
                ->leftJoin('payment_installment as pi', 'pi.payment_id', '=', 'p.id')
                ->leftJoin('orders as o', 'o.payment_id', '=', 'p.id')
                ->where('p.is_deleted', 'No')
                ->groupBy(
                    'p.user_id',
                    'o.course_id',
                    DB::raw("CASE WHEN p.installment_status = 'InstallmentPayment' THEN pi.paid_install_no ELSE NULL END")
                );

                $InvoiceData = DB::table('payments')
                ->leftJoin('payment_installment', 'payment_installment.payment_id', '=', 'payments.id')
                ->leftJoin('orders', 'orders.payment_id', '=', 'payments.id')
                ->joinSub($subQuery, 'latest', function($join) {
                    $join->on(DB::raw("COALESCE(payment_installment.created_at, payments.created_at)"), '=', 'latest.latest_created_at')
                    ->on('payments.user_id', '=', 'latest.user_id')
                    ->on('orders.course_id', '=', 'latest.course_id');
                })
                ->selectRaw("
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  payments.uni_order_id, payment_installment.uni_order_id) as uni_order_id,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  payments.first_name, payment_installment.first_name) as first_name,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  payments.last_name, payment_installment.last_name) as last_name,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  payments.email, payment_installment.email) as email,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  payments.address, payment_installment.address) as address,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  payments.session_id, payment_installment.session_id) as session_id,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  payments.created_at, payment_installment.created_at) as created_at,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  payments.status, payment_installment.paid_install_status) as status,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  payments.id, payment_installment.id) as id,
                    paid_install_amount,
                    paid_install_date,
                    paid_install_status,
                    installment_status,
                    paid_install_no,
                    payment_type,
                    scholarship,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  orders.course_title, payment_installment.course_title) as course_title,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  orders.promo_code_name, '') as promo_code_name,
                    IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL,  orders.course_price, payment_installment.total_amount) as course_price,
                    orders.final_price
                ")
                ->where('payments.user_id', $studentId)
                ->orwhere('payment_installment.user_id', $studentId)
                ->orderByRaw("IF(payments.installment_status = 'FullPayment' OR payments.installment_status IS NULL, payments.created_at, payment_installment.created_at) DESC")
                ->get();  
                
            $studentData = $this->userProfile->getCurrentUserProfile();

            return view('frontend.student.student-invoice', compact('InvoiceData', 'studentData'));
        }

        return redirect('/login');
    }

    public function downloadInvoice($id, $action)
    {
        // Retrieve the invoice data from the database
        set_time_limit(300);
        ini_set('memory_limit', '512M');
        $id = base64_decode($id);
        $action = base64_decode($action);
        $InvoiceData = $this->PaymentModule->getPaymentDetails($id, '');

        $formattedDate = Carbon::parse($InvoiceData[0]['created_at'])->format('F j, Y');
        $InvoiceDate = Carbon::parse($InvoiceData[0]['created_at'])->format('j M Y');

        $subtotal = isset($InvoiceData[0]['final_price']) ? $InvoiceData[0]['final_price'] : 0;
        $discount = isset($InvoiceData[0]['discount']) && !empty($InvoiceData[0]['discount']) ? $InvoiceData[0]['discount'] : 0;
        $scholarship = isset($InvoiceData[0]['scholarship']) ? $InvoiceData[0]['scholarship'] : 0;
        if($subtotal > 0){
            $grandTotal = $subtotal - $scholarship - $discount;
        }else{
            $grandTotal = $InvoiceData[0]['course_price'] - $discount;
        }
        $OrderDate = Carbon::parse($InvoiceData[0]['created_at'])->format('j M Y');
        $data = [
            'title' => 'frontend/images/brand/logo/logo.svg',
            'date' => $formattedDate,
            'invoiceNumber' => $InvoiceData[0]['uni_order_id'],
            'invoiceFrom' => [
                'name' => 'Ascencia Malta / E-Ascencia',
                'address' => '23, Vincenzo Dimech Street, Floriana, Valletta, Malta'
            ],
            'invoiceTo' => [
                'name' => $InvoiceData[0]['first_name'] . ' ' . $InvoiceData[0]['last_name'],
                'address' => $InvoiceData[0]['address']
            ],
            'invoiceDate' => $InvoiceDate,
            'paymentType' => $InvoiceData[0]['payment_type'],
            'items' => [],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'grandTotal' => $grandTotal,
            'scholarship' => $InvoiceData[0]['scholarship'],
            'courseName' => htmlspecialchars_decode($InvoiceData[0]['course_title']),
            'orderDate' => $OrderDate,
            'couponCode' => $InvoiceData[0]['promo_code_name'],
            'amount' => $InvoiceData[0]['course_price'],
            'installment_status'=> $InvoiceData[0]['installment_status'],
            'paid_install_amount'=>  $InvoiceData[0]['paid_install_amount'],
            'id'=> $id
        ];
        // foreach ($InvoiceData[0]['order_data'] as $key => $value) {
            // Add each item to the 'items' array
            // $OrderDate = Carbon::parse($value['created_at'])->format('j M Y');

            // $data['items'][] = [
                // 'name' => htmlspecialchars_decode($value['course_title']),
                // 'orderDate' => $OrderDate,
                // 'couponCode' => $value['promo_code_name'],
                // 'amount' => number_format($value['course_price'], 2, '.', ',')
            // ];
        // }

        if ($action == 'invoice') {
            // Example data for the invoice
            // Load the view and pass data
            $pdf = PDF::loadView('frontend.payment.invoice', $data);

            // Download the PDF file
            return $pdf->download('Eascencia-' . $InvoiceData[0]['uni_order_id'] . '.pdf');
        } else if ($action == 'pdfopen') {

            $pdf = PDF::loadView('frontend.payment.invoice', $data);

            return $pdf->download('Eascencia-' . $InvoiceData[0]['uni_order_id'] . '.pdf');
        } else {

            return view('frontend.payment.receipt', $data);
        }
    }

    public function UserCloseAccount()
    {
        $studentId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($studentId) && $studentId != 0) {
            $studentData = $this->userProfile->getCurrentUserProfile();
            return view('frontend.student.student-deactivate-account', compact('studentData'));
        }
    }
    public function UserStudentLearing()
    {

        $studentId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($studentId) && $studentId != 0) {
            $where = ['student_id' => $studentId];
            $studentData = $this->userProfile->getStudentLearning($where);
            $certData = $this->StudentMaster->studentCerficateGenerateData();
            $attendance = $this->StudentMaster->getcertificate();
            //dd( $studentData[0]['orderlist'][0]['course_id']);
             //$attendancegenrate = generateAttendanceCertificate($studentData[0]['student_id'], $studentData[0]['orderlist'][0]['course_id']);
             //dd($attendancegenrate);
            return view('frontend.student-my-learning', compact('studentData', 'certData', 'attendance'));
        }
        return redirect('/login');
    }

    public function UserPaymentDetails()
    {

        $studentId = isset(Auth::user()->id) ? Auth::user()->id : 0;
        if (Auth::check() && !empty($studentId) && $studentId != 0) {
            $where = ['student_id' => $studentId];
            $studentData = $this->userProfile->getCurrentUserProfile();

            $studentId = $studentId; // parameter
            // Step 1: Get all courses where the student has some installment records
            $studentCourses = DB::table('payment_installment as pi')
                ->join('course_master as c', 'c.id', '=', 'pi.course_id')
                ->where('pi.user_id', $studentId)
                ->select('c.id as course_id', 'c.course_title as course_name', 'c.no_of_installment','c.installment_duration','c.installment_amount','c.course_old_price','c.course_final_price')
                ->distinct()
                ->get();
            $installmentData = [];
            $firstUnpaid = [];

            foreach ($studentCourses as $course) {

                // Paid installments for this course
                $paidInstallments = DB::table('payment_installment')
                    ->where('user_id', $studentId)
                    ->where('course_id', $course->course_id)
                    ->orderBy('paid_install_no', 'asc')
                    ->where('paid_install_status','0')
                    ->get();

                $paidInstallmentsByNo = [];

                foreach ($paidInstallments as $paid) {
                    $installNos = !empty($paid->multiple_install_no) && $paid->multiple_install_no !== '0' 
                        ? explode(',', $paid->multiple_install_no)
                        : [$paid->paid_install_no];

                    foreach ($installNos as $no) {
                        $no = trim($no);
                        $paidInstallmentsByNo[$no] = [
                            'paid_install_status' => $paid->paid_install_status,
                            'paid_install_date' => $paid->paid_install_date,
                            'paid_install_amount' => $paid->paid_install_amount,
                            'student_course_master_id' => $no == 1 ? ($paid->student_course_master_id ?? null) : null,
                            'next_install_date' => $paid->next_install_date ?? null,
                            'created_at'=> $paid->created_at ?? null,
                        ];
                    }
                }

                // Loop through all installments per course
                $installmentData[$course->course_id] = [];
                $paid_date = $paidInstallmentsByNo[1]['paid_install_date'] ?? now();

                for ($i = 1; $i <= $course->no_of_installment; $i++) {
                    $paidData = $paidInstallmentsByNo[$i] ?? null;

                    $installmentData[$course->course_id][] = [
                        'course_id' => $course->course_id,
                        'course_name' => $course->course_name,
                        'install_no' => $i,
                        'due_date' =>  $i == 1
                                ? '-'
                                : Carbon::parse($paid_date)
                                    ->addMonths($course->installment_duration * ($i - 1))
                                    ->format('Y-m-d'),
                        'amount' => $paidData['paid_install_amount'] ?? $course->installment_amount,
                        'status' => $paidData['paid_install_status'] ?? 1, // 0=Paid, 1=Unpaid
                        'payment_date' => $paidData['created_at'] ?? null,
                        'student_course_master_id' => $paidData['student_course_master_id'] ?? null,
                    ];

                    // Track first unpaid installment per course
                    if (($paidData['paid_install_status'] ?? 1) == 1 && !isset($firstUnpaid[$course->course_id])) {
                        $firstUnpaid[$course->course_id] = $i;
                    }
                }
            }
            return view('frontend.student.student-payment-details', compact('studentData', 'installmentData','firstUnpaid','studentCourses'));
        }
        return redirect('/login');
    }

    public function UserDocCheck(Request $req)
    {


        if (Auth::check()) {
            $document_received = $req->hasFile('document') ? $req->file('document') : '';
            $proofType = !empty($req->input('doc_type')) ? base64_decode($req->input('doc_type')) : '';
            if ($req->hasFile('document') && $proofType != '' && !is_array($document_received) && !is_array($proofType)) {
                $rules = [
                    'document' => 'required|mimes:jpeg,png,jpg,pdf|max:3072',
                    'doc_type' => 'required|string',
                ];
                $existTable = 'student_doc_verification';
                $validate = Validator::make($req->all(), $rules);
                if (!$validate->fails()) {
                    $result = FALSE;
                    $strtext = '';
                    if (
                        $proofType === 'ID'
                    ) {
                        $attemp =  $this->studentDocument->docAttemptRemain(Auth::user()->id, 'ID');
                        if (is_exist($existTable, ['student_id' => Auth::user()->id, 'identity_is_approved' => 'Approved', 'identity_is_deleted' => 'No']) > 0) {
                            return json_encode(['code' => 201, 'title' => 'Already Approved', 'message' => '', 'icon' => generateIconPath('warning')]);
                        } elseif ($attemp <= 0) {
                            return json_encode(['code' => 201, 'title' => 'Only 3 Attempts Are Allowed', 'message' => 'Please Contact to Admin', 'icon' => generateIconPath('warning')]);
                        } else {
                            if (strtolower($document_received->getClientOriginalExtension()) === 'pdf' || strtolower($document_received->getClientOriginalExtension()) === 'jpg' || strtolower($document_received->getClientOriginalExtension()) === 'png' || strtolower($document_received->getClientOriginalExtension()) === 'jpeg') {
                                return $this->passportVerify($document_received);
                            }
                            //  else {
                            // try {
                            //     $tesser = new TesseractOCR($document_received);
                            //     $extractTxt =  $tesser->run();
                            //     $strtext = strval($extractTxt);
                            //     preg_match_all('/passport/i', $strtext, $result);
                            //         if (!empty($strtext)) {
                            // return $this->passportVerify($document_received, 'passport');
                            // } else {
                            return json_encode(['code' => 201, 'title' => 'Unable to Verify', 'message' => 'File Should be Clear', 'icon' => generateIconPath('error')]);
                            // }
                            // } catch (\Throwable $th) {
                            //         return json_encode(['code' => 201, 'title' => 'Unable to Verify', 'message' => 'File Should be Clear or Pdf allowed max 5 pages', 'icon' => generateIconPath('error')]);
                            //     }
                            // }
                        }
                    } elseif ($proofType === 'EDU') {
                        $edu_level = isset($req->edu_level) ? htmlspecialchars($req->input('edu_level')) : '';
                        $doc_level = isset($req->doc_level) ? base64_decode($req->input('doc_level')) : '';
                        $specilization = isset($req->specilization) ? htmlspecialchars($req->input('specilization')) : '';
                        $document_received = $req->hasFile('document') ? $req->file('document') : '';
                        $attemp =  $this->studentDocument->docAttemptRemain(Auth::user()->id, 'EDU');
                        $attemptlevel6 = DB::table('document_verification')->where('student_id',Auth::user()->id)->first();
                        // $StudentDoc = getData('student_doc_verification',['edu_athe_approved',''],['student_id',Auth::user()->id]);
                        $StudentDoc = getData('student_doc_verification', ['student_id','edu_is_approved','edu_athe_approved','edu_level','edu_master_approved'], ['student_id' => auth()->user()->id]);
                        if (is_exist($existTable, ['student_id' => Auth::user()->id, 'edu_is_approved' => 'Approved', 'identity_is_deleted' => 'No']) > 0 && $doc_level == '') {
                            return json_encode(['code' => 201, 'title' => 'Already Approved', 'message' => '', 'icon' => generateIconPath('warning')]);

                        }elseif(is_exist($existTable, ['student_id' => Auth::user()->id, 'edu_is_approved' => 'Approved', 'identity_is_deleted' => 'No']) > 0 && $doc_level == '6' && (($StudentDoc[0]->edu_athe_approved == "" && $StudentDoc[0]->edu_level <= 5) ||  ($StudentDoc[0]->edu_master_approved == "" && $StudentDoc[0]->edu_level == 6))){
                            return json_encode(['code' => 201, 'title' => 'Already Approved', 'message' => '', 'icon' => generateIconPath('warning')]);

                        } elseif ($attemp <= 0 && $doc_level == '') {
                            return json_encode(['code' => 201, 'title' => 'Only 3 Attempts Are Allowed', 'message' => '', 'icon' => generateIconPath('warning')]);

                        } elseif (isset($attemptlevel6->edu_trail_attempt) && $attemptlevel6->edu_trail_attempt <= 0 && $doc_level == '6'){
                            return json_encode(['code' => 201, 'title' => 'Only 3 Attempts Are Allowed', 'message' => '', 'icon' => generateIconPath('warning')]);

                        } else {
                            $data = $this->EduCertVerify($document_received, ['edu_level' => $edu_level, 'specilization' => $specilization,'doc_level'=> $doc_level]);
                            // if ($data['first_name'] != '' && $data['institute_name'] != '' && $data['name_of_certification'] != '' && $data['completion_remark'] == TRUE) {
                            $datas = json_decode($data);
                            if (isset($datas->code) && $datas->code === 201) {
                                return json_encode(['code' => 202, 'title' => 'File is corrupt', 'message' => 'Invalid File Please Try Again', 'records' => $data, 'icon' => generateIconPath('warning')]);
                            }
                            if (isset($datas->code) && $datas->code === 204) {
                                return  json_encode(['code' => 202, 'title' => 'Invalid Document.', 'message' => "Please Try Again", 'records' => $data, 'icon' => generateIconPath('warning')]);
                            }
                            if (isset($datas->code) && $datas->code === 206) {
                                return  json_encode(['code' => 202, 'title' => 'Please Upload Your Highest Education Document', 'message' => "Please Try Again",'records' => $data, 'icon' => generateIconPath('warning')]);
                            }

                            if ($datas->records->first_name != '' && $datas->records->institute_name != '' && $datas->records->name_of_certification != '') {
                                // $searchTerms = ['passed','class','Class','First'];
                                // foreach ($searchTerms as $term) {
                                //     if (stripos($data['completion_remark'], $term) !== false) {
                                // return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => 'It will be approved within 1-2 working days.', 'records' => $datas->records, 'icon' => generateIconPath('warning'), ]);

                                $studentDocVerification = getData('student_doc_verification', ['student_id', 'identity_is_approved', 'edu_is_approved', 'english_score', 'english_test_attempt'], ['student_id' => auth()->user()->id]);
                                if($studentDocVerification[0]->edu_is_approved == 'Approved'){
                                    $studentCourseMaster = DB::table('student_course_master')
                                        ->select('course_master.category_id','student_course_master.course_id')
                                        ->leftJoin('course_master', 'course_master.id', '=', 'student_course_master.course_id')
                                        ->where('user_id', Auth::user()->id)
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
                                        $unsubscribeRoute = url('/unsubscribe/' . base64_encode(Auth::user()->email));
                                    if($doc_level == '6'){
                                        $attemptlevel6data = getData('document_verification', ['student_id','edu_is_approved'], ['student_id' => auth()->user()->id]);
                                        Log::info("docUpload check p", [
                                            'status' => $attemptlevel6data,
                                        ]);
                                        if($attemptlevel6data[0]->edu_is_approved == 'Approved'){
                                            Log::info("Sdadadafdsffds  SDFSD");

                                            

                                            mail_send(
                                                27,
                                                [
                                                    '#Name#',
                                                    '#documents#',
                                                    '#[Study material link].#',
                                                    '#unsubscribeRoute#'
                                                ],
                                                [
                                                    Auth::user()->name . ' ' . Auth::user()->last_name,
                                                    'Educational Document',
                                                    $CourseLink,
                                                    $unsubscribeRoute
                                                ],
                                                Auth::user()->email
                                            );

                                            $this->user->verificationStatutsUpdate(auth()->user()->id);
                    
                                            return  json_encode(['code' => 200, 'title' => 'Verified', 'message' => 'Successfully Verified', 'records' => $datas->records, 'icon' => generateIconPath('success')]);
                                        }else{
                                            Log::info("Sdadadafdsffds  dsfsf dfsdf asdada SDFSD");

                                            return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $datas->records, 'redirect' => 'english-test']);
                                        }
                                    }else{
                                        Log::info("Sdadadafdsffds dfsfdsfds dsfsf dfsdf asdada SDFSD");

                                        mail_send(
                                            27,
                                            [
                                                '#Name#',
                                                '#documents#',
                                                '#[Study material link].#',
                                                '#unsubscribeRoute#'
                                            ],
                                            [
                                                Auth::user()->name . ' ' . Auth::user()->last_name,
                                                'Educational Document',
                                                $CourseLink,
                                                $unsubscribeRoute
                                            ],
                                            Auth::user()->email
                                        );

                                        $this->user->verificationStatutsUpdate(auth()->user()->id);
                                        
                                        return  json_encode(['code' => 200, 'title' => 'Verified', 'message' => 'Successfully Verified', 'records' => $datas->records, 'icon' => generateIconPath('success')]);
                                    }
                                }elseif ($studentDocVerification[0]->identity_is_approved == 'Pending' && $studentDocVerification[0]->edu_is_approved == 'Pending' && ($studentDocVerification[0]->english_test_attempt == 2 || ($studentDocVerification[0]->english_test_attempt == 1 && $studentDocVerification[0]->english_score < 10))) {
                                    return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $datas->records, 'redirect' => 'english-test']);
                                } elseif ($studentDocVerification[0]->identity_is_approved != 'Approved' || $studentDocVerification[0]->edu_is_approved != 'Approved') {
                                    return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $datas->records, 'redirect' => 'student-document-verification']);
                                } elseif (($studentDocVerification[0]->english_score == null) || ($studentDocVerification[0]->english_score < 10 && $studentDocVerification[0]->english_test_attempt == 1)) {
                                    return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $datas->records, 'redirect' => 'english-test']);
                                } else {
                                    return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $datas->records, 'redirect' => 'english-test']);
                                }
                            } else {

                                return json_encode(['code' => 202, 'title' => 'Not Verified', 'message' => 'Send for Approval', 'records' => $data, 'icon' => generateIconPath('warning')]);
                            }
                        }
                    } elseif ($proofType === 'RESUME') {
                        $data = $this->StudentfilesUpload($document_received, 'RESUME', []);
                        if (isset($data['code']) && $data['code'] === 200) {
                            echo json_encode(['code' => 200, 'title' => 'Resume Uploaded', 'message' => 'Successfully Uploaded', 'records' => ['doc_type' => 'RESUME'], 'icon' => generateIconPath('success')]);
                        } else {
                            return json_encode(['code' => 201, 'title' => 'Resume', 'message' => 'Unable to Upload Resume', 'icon' => generateIconPath('warning')]);
                        }
                    } elseif ($proofType === 'RESEARCH_PROPOSAL') {
                        $data = $this->StudentfilesUpload($document_received, 'RESEARCH_PROPOSAL', []);
                        if (isset($data['code']) && $data['code'] === 200) {
                            return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'doc_type' => 'RESEARCH_PROPOSAL']);
                        } else {
                            return json_encode(['code' => 201, 'title' => 'Research Proposal', 'message' => 'Unable to Upload Research Proposal', 'icon' => generateIconPath('warning')]);
                        }
                    } else {
                        return json_encode(['code' => 404, 'title' => __('response.cart.something_went_wrong'), 'message' => '', 'icon' => generateIconPath('error')]);
                    }
                } else {
                    return json_encode(['code' => 404, 'title' => 'Incorrect File', 'message' => 'File should be jpg, png or pdf & file size < 3MB', 'records' => '', 'icon' => generateIconPath('warning')]);
                }
            } else {
                return json_encode(['code' => 404, 'title' => 'File not Uploaded', 'message' => 'Invalid File Please Try Again', 'records' => '', 'icon' => generateIconPath('warning')]);
            }
        } else {
            return json_encode(['code' => 404, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_login_again_try'), 'records' => '', 'icon' => generateIconPath('warning')]);
        }
    }
    protected function passportVerify($document_received, $docType = ' ')
    {
        if (isset($document_received) && !empty($document_received) && !is_array($document_received)) {

            try {

                $mindeeClient = new Client(env('MINDEE_API_KEY'));
                $inputSource = $mindeeClient->sourceFromPath($document_received);
                $apiResponse = $mindeeClient->parse(PassportV1::class, $inputSource);
                $data = $apiResponse->document->inference->prediction;
                $records['fist_name'] = isset($data->givenNames[0]->value) ? $data->givenNames[0]->value : '';
                // $records['last_name'] = isset($data->givenNames[1]->value) ? $data->givenNames[1]->value : '';
                $records['last_name'] = isset($data->surname->value) ? $data->surname->value : '';
                $records['country'] = isset($data->country->value) ? $data->country->value : '';
                $records['passport_no'] = isset($data->idNumber->value) ? $data->idNumber->value : '';
                $records['id_confidence'] = isset($data->idNumber->confidence) ? $data->idNumber->confidence : '';
                $records['issuance_date'] = isset($data->issuanceDate->value) ? $data->issuanceDate->value : '';
                $records['expiryDate'] = isset($data->expiryDate->value) ? $data->expiryDate->value : '';
                $records['birth_date'] = isset($data->birthDate->value) ? $data->birthDate->value : '';
                $records['mrz1'] = isset($data->mrz1->value) ? $data->mrz1->value : '';
                $records['doc_type'] = 'passport';

                $selectCols = [
                    'identity_doc_number' => htmlspecialchars_decode($records['passport_no']),
                    'identity_doc_country' => $records['country'],
                    'identity_doc_authority' => 'Passport',
                    'identity_doc_issue_date' => $records['issuance_date'],
                    'identity_doc_expiry' => $records['expiryDate'],
                    'identity_doc_type' => 'Passport',
                    'name_on_identity_card' => htmlspecialchars_decode($records['fist_name'] . " " . $records['last_name']),
                    'dob_on_identity_card' => $records['birth_date'],
                ];


                $birthDate = Carbon::parse($records['birth_date']);
                $age = $birthDate->age;

                $passport_no_pattrn = "/^(?!^0+$)[a-zA-Z0-9]{6,20}$/";
                $name = isset(Auth::user()->name) ? strtolower(Auth::user()->name) : '';
                $last_name = isset(Auth::user()->last_name) ? strtolower(Auth::user()->last_name) : '';
                if ($records['expiryDate'] != '') {
                    $expiryDate =  Carbon::parse($records['expiryDate'])->format('Y-m-d');
                    if ($expiryDate <= $this->date) {
                        return  json_encode(['code' => 201, 'title' => 'Expired', 'message' => "Your Passport has been Expired ", 'records' => $records, 'icon' => generateIconPath('warning')]);
                    }
                }
                if (isset($records['passport_no']) && !empty($records['passport_no']) && preg_match($passport_no_pattrn, $records['passport_no']) && $records['id_confidence'] === 1) {

                    if ($records['fist_name'] != '' && strtolower($records['fist_name']) === $name && strtolower($records['last_name']) === $last_name && $records['passport_no'] != '' && $records['expiryDate'] != '' && $records['country'] != '' && $records['birth_date'] != '') {
                        if($age < 18){
                            return  json_encode(['code' => 200, 'title' => 'Not eligible', 'message' => 'Your age is not eligible for this courses', 'records' => $records, 'icon' => generateIconPath('success')]);
                        }
                        $addSelect = [
                            'identity_approved_on' => $this->time,
                            'identity_approved_by' => 1,
                            'identity_is_approved' => 'Approved',
                        ];
                        $selectData = array_merge(
                            $selectCols,
                            $addSelect
                        );
                        $data = $this->StudentfilesUpload($document_received, 'ID', $selectData);


                        // $studentCourseMaster = DB::table('student_course_master')
                        //     ->where('user_id', Auth::user()->id)
                        //     ->orderBy('created_at', 'desc')
                        //     ->first(['course_id']);
                        $studentCourseMaster = DB::table('student_course_master')
                            ->select('course_master.category_id','student_course_master.course_id')
                            ->leftJoin('course_master', 'course_master.id', '=', 'student_course_master.course_id')
                            ->where('user_id', Auth::user()->id)
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
                        $unsubscribeRoute = url('/unsubscribe/' . base64_encode(Auth::user()->email));

                        mail_send(
                            27,
                            [
                                '#Name#',
                                '#documents#',
                                '#[Study material link].#',
                                '#unsubscribeRoute#'
                            ],
                            [
                                Auth::user()->name . ' ' . Auth::user()->last_name,
                                'Identity Document',
                                $CourseLink,
                                $unsubscribeRoute
                            ],
                            Auth::user()->email
                        );


                        $studentDocVerification = getData('student_doc_verification', ['student_id', 'identity_is_approved', 'edu_is_approved', 'english_score', 'resume_file'], ['student_id' => auth()->user()->id]);

                        $this->user->verificationStatutsUpdate(auth()->user()->id);

                        // if ($studentDocVerification[0]->identity_is_approved === 'Approved' && $studentDocVerification[0]->edu_is_approved == "Approved" && $studentDocVerification[0]->resume_file != '' && $studentDocVerification[0]->english_score >= 10) {

                        //     mail_send(
                        //         44,
                        //         [
                        //             '#Name#',
                        //             '#unsubscribeRoute#'
                        //         ],
                        //         [
                        //             Auth::user()->name . ' ' . Auth::user()->last_name,
                        //             $unsubscribeRoute
                        //         ],
                        //         Auth::user()->email
                        //     );
                        // }


                        return  json_encode(['code' => 200, 'title' => 'Verified', 'message' => 'Successfully Verified', 'records' => $records, 'icon' => generateIconPath('success')]);
                    } else {
                            $addSelect = [
                                'identity_is_approved' => 'Pending',
                            ];
                            $selectData = array_merge(
                                $selectCols,
                                $addSelect
                            );
                            $data = $this->StudentfilesUpload($document_received, 'ID', $selectData);
                            $this->studentDocument->verificationStatutsPending(Auth::user()->id);

                            $user = User::where('id', Auth::user()->id)->first();

                            $unsubscribeRoute = url('/unsubscribe/' . base64_encode(Auth::user()->email));

                            mail_send(
                                33,
                                [
                                    '#Name#',
                                    '#[Identity/Educational]#',
                                    '#unsubscribeRoute#',
                                ],
                                [
                                    $user->name . ' ' . $user->last_name,
                                    'Identity',
                                    $unsubscribeRoute
                                ],
                                Auth::user()->email
                            );
                            $studentDocVerification = getData('student_doc_verification', ['student_id', 'identity_is_approved', 'edu_is_approved', 'english_score', 'english_test_attempt'], ['student_id' => auth()->user()->id]);
                            if ($studentDocVerification[0]->identity_is_approved == 'Pending' && $studentDocVerification[0]->edu_is_approved == 'Pending' && ($studentDocVerification[0]->english_test_attempt == 2 || ($studentDocVerification[0]->english_test_attempt == 1 && $studentDocVerification[0]->english_score < 10))) {
                                return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'english-test']);
                            } elseif ($studentDocVerification[0]->identity_is_approved != 'Approved' || $studentDocVerification[0]->edu_is_approved != 'Approved') {
                                return  json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'student-document-verification']);
                            } elseif (($studentDocVerification[0]->english_score == null) || ($studentDocVerification[0]->english_score < 10 && $studentDocVerification[0]->english_test_attempt == 1)) {
                                return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'english-test']);
                            } else {
                                return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records]);
                            }
                    }
                } elseif (isset($docType) && !empty($docType)) {
                    return $this->OtherNationalIDVerify($document_received);
                }
            } catch (\Exception $th) {
                // return json_encode(['code' => 201, 'title' => 'Unable to Verify', 'message' => "Max PDF Pages or Files allowed are 5. please Try Again", 'icon' => generateIconPath('warning')]);
                return json_encode(['code' => 201, 'title' => 'Incorrect File', 'message' => 'Incorrect File', 'records' => '', 'icon' => generateIconPath('warning')]);
            }
        }
    }
    protected function OtherNationalIDVerify($document_received)
    {
        $selectCols = [];
        if (isset($document_received) && !empty($document_received) && !is_array($document_received)) {
            try {

                $mindeeClient = new Client(env('MINDEE_API_KEY'));
                // $mindeeUser = $this->mindeeUser;
                // $nationIDCategory = $this->nationIDCategory;
                $predictOptions = new PredictMethodOptions();


                $inputSource = $mindeeClient->sourceFromPath($document_received);
                $customEndpoint = $mindeeClient->createEndpoint(
                    env('MIND_NATIONALID_CAT'),
                    env('MIND_USERNAME'),
                    "1"
                );
                $predictOptions->setEndpoint($customEndpoint);
                $apiResponse = $mindeeClient->enqueueAndParse(GeneratedV1::class, $inputSource, $predictOptions);
                $jsonrecord =  json_encode($apiResponse);
                $jsonRes = json_decode($jsonrecord, true);


                $data =  $jsonRes['document']['inference']['prediction']['fields'];
                $records['first_name_of_person'] = isset($data['first_name_of_person']['value']) ? $data['first_name_of_person']['value'] : '';
                $records['last_name_of_person'] = isset($data['surname_last_name_of_person']['value']) ? $data['surname_last_name_of_person']['value'] : '';
                $records['id_card_number'] = isset($data['id_card_number']['value']) ? $data['id_card_number']['value'] : '';
                $records['doc_type'] = isset($data['name_of_id_proof']['value']) ? $data['name_of_id_proof']['value'] : '';
                $records['expiry_date'] = isset($data['expiry_date']['value']) ? $data['expiry_date']['value'] : '';
                $records['issuing_authority'] = isset($data['issuing_authority']['value']) ? $data['issuing_authority']['value'] : '';
                $records['issuing_country'] = isset($data['issuing_country']['value']) ? $data['issuing_country']['value'] : '';
                $records['date_of_birth'] = isset($data['date_of_birth']['value']) ? $data['date_of_birth']['value'] : '';
                $records['middle_name'] = isset($data['middle_name']['value']) ? $data['middle_name']['value'] : '';
                $issuing_authority_title = isset($records['issuing_authority']) && !empty($records['issuing_authority']) ? htmlspecialchars_decode($records['issuing_authority']) : (isset($records['issuing_country']) ? 'Republic of '.$records['issuing_country']: '');

                $selectCols = [
                    'identity_doc_number' => isset($records['id_card_number']) ? htmlspecialchars_decode($records['id_card_number']) : '',
                    'identity_doc_country' => isset($records['issuing_country']) ? $records['issuing_country'] : '',
                    'identity_doc_authority' => isset($records['issuing_authority']) ? $records['issuing_authority'] : '',
                    'identity_doc_issue_date' => isset($records['issuance_date']) ? $records['issuance_date'] : '',
                    'identity_doc_expiry' => isset($records['expiry_date']) ? $records['expiry_date'] : '',
                    'identity_doc_type' => isset($records['doc_type']) ? $records['doc_type'] : '',
                    'name_on_identity_card' => isset($records['first_name_of_person']) ? htmlspecialchars_decode($records['first_name_of_person'] . " " . $records['last_name_of_person']) : '',
                    'dob_on_identity_card' => isset($records['date_of_birth']) ? $records['date_of_birth'] : '',
                ];
                $birthDate = Carbon::parse($records['date_of_birth']);
                $age = $birthDate->age;
                $name = isset(Auth::user()->name) ? strtolower(Auth::user()->name) : '';
                $last_name = isset(Auth::user()->last_name) ? strtolower(Auth::user()->last_name) : '';
                $firstName = explode(" ", $records['first_name_of_person']);
                if(empty($records['first_name_of_person'])){
                    return json_encode(['code' => 201, 'title' => 'Invalid Document.', 'message' => 'Please try again.', 'icon' => generateIconPath('error')]);
                }
                if (isset($records['expiry_date'], $records['doc_type']) && in_array(Str::lower(trim($records['doc_type'])), ['passport', 'passeport'])) {
                    $expiryDate = Carbon::parse($records['expiry_date'])->format('Y-m-d');
                    if ($expiryDate <= $this->date) {
                        return  json_encode(['code' => 201, 'title' => 'Expired', 'message' => "Your id proof has been expired ", 'records' => $records, 'icon' => generateIconPath('warning')]);
                    }
                }
                Log::info("records check", [
                    'status' => $records
                ]);
                // if ($expiryDate >= $this->date) {
                if (!empty($records['first_name_of_person']) &&
                (
                    strtolower($firstName[0]) === strtolower($name) ||
                    strtolower($firstName[0]) === strtolower($last_name)
                ) &&
                (
                    stripos($records['last_name_of_person'], $name) !== false ||
                    stripos($records['last_name_of_person'], $last_name) !== false ||
                    stripos($records['middle_name'],$name) !== false ||
                    stripos($records['middle_name'], $last_name) !== false
                )
                && $records['id_card_number'] != '' && $records['doc_type'] != ''  && $records['issuing_authority'] != '') {
                    if($age < 18){
                        return  json_encode(['code' => 200, 'title' => 'Not eligible', 'message' => 'Your age is not eligible for this courses', 'records' => $records, 'icon' => generateIconPath('success')]);
                    }
                    Log::info("records check enter", [
                        'status' => $records
                    ]);
                    $addSelect = [
                        'identity_approved_on' => $this->time,
                        'identity_approved_by' => 1,
                        'identity_is_approved' => 'Approved',
                    ];
                    $selectData = array_merge(
                        $selectCols,
                        $addSelect
                    );
                    Log::info("records check enter addselect", [
                        'status' => $records
                    ]);
                    $data = $this->StudentfilesUpload($document_received, 'ID', $selectData);
                    if (isset($data['code']) && $data['code'] === 200) {

                        // $studentCourseMaster = DB::table('student_course_master')
                        //     ->where('user_id', Auth::user()->id)
                        //     ->where('is_deleted', 'No')
                        //     ->orderBy('created_at', 'desc')
                        //     ->first(['course_id']);
                        $studentCourseMaster = DB::table('student_course_master')
                        ->select('course_master.category_id','student_course_master.course_id')
                        ->leftJoin('course_master', 'course_master.id', '=', 'student_course_master.course_id')
                        ->where('user_id', Auth::user()->id)
                        ->where('student_course_master.is_deleted', 'No')
                        ->orderBy('student_course_master.created_at', 'desc')
                        ->first();
                        Log::info("sadsd", [
                            'status' => $records['id_card_number'],
                        ]);
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
                        $unsubscribeRoute = url('/unsubscribe/' . base64_encode(Auth::user()->email));

                        mail_send(
                            27,
                            [
                                '#Name#',
                                '#documents#',
                                '#[Study material link].#',
                                '#unsubscribeRoute#'
                            ],
                            [
                                Auth::user()->name . ' ' . Auth::user()->last_name,
                                'Identity Document',
                                $CourseLink,
                                $unsubscribeRoute
                            ],
                            Auth::user()->email
                        );


                        $studentDocVerification = getData('student_doc_verification', ['student_id', 'identity_is_approved', 'edu_is_approved', 'english_score', 'resume_file'], ['student_id' => auth()->user()->id]);

                        Log::info("sadsd", [
                            'status' => $records['id_card_number']
                        ]);
                        $this->user->verificationStatutsUpdate(auth()->user()->id);
                        // if ($studentDocVerification[0]->identity_is_approved === 'Approved' && $studentDocVerification[0]->edu_is_approved == "Approved" && $studentDocVerification[0]->resume_file != '' && $studentDocVerification[0]->english_score >= 10) {

                        //     mail_send(
                        //         44,
                        //         [
                        //             '#Name#',
                        //             '#unsubscribeRoute#'
                        //         ],
                        //         [
                        //             Auth::user()->name . ' ' . Auth::user()->last_name,
                        //             $unsubscribeRoute
                        //         ],
                        //         Auth::user()->email
                        //     );
                        // }
                        //                         $studentDocVerification = getData('student_doc_verification', ['student_id', 'identity_is_approved', 'edu_is_approved', 'english_score', 'english_test_attempt'], ['student_id' => auth()->user()->id]);
                        //                         if ($studentDocVerification[0]->identity_is_approved == 'Pending' && $studentDocVerification[0]->edu_is_approved == 'Pending' && (
                        //     $studentDocVerification[0]->english_test_attempt == 2 ||
                        //     ($studentDocVerification[0]->english_test_attempt == 1 && $studentDocVerification[0]->english_score < 10)
                        // )) {
                        //                             return  json_encode(['code' => 200, 'title' => 'Verified', 'message' => 'Successfully Verified', 'icon' => generateIconPath('success'), 'records' => $records, 'redirect' => 'english-test']);
                        //                         }elseif($studentDocVerification[0]->identity_is_approved != 'Approved' || $studentDocVerification[0]->edu_is_approved != 'Approved'){
                        //                             return  json_encode(['code' => 200, 'title' => 'Verified', 'message' => 'Successfully Verified', 'icon' => generateIconPath('success'), 'records' => $records, 'redirect' => 'student-document-verification']);
                        //                         }elseif($studentDocVerification[0]->identity_is_approved == 'Approved' && $studentDocVerification[0]->edu_is_approved == 'Approved' && $studentDocVerification[0]->english_score >= 10){
                        //                             return  json_encode(['code' => 200, 'title' => 'Verified', 'message' => 'Successfully Verified', 'icon' => generateIconPath('success'), 'records' => $records, 'redirect' => 'student-my-learning']);
                        //                         }elseif(($studentDocVerification[0]->identity_is_approved == 'Approved' && $studentDocVerification[0]->edu_is_approved == 'Approved' && $studentDocVerification[0]->english_score == null) || ($studentDocVerification[0]->identity_is_approved == 'Approved' && $studentDocVerification[0]->edu_is_approved == 'Approved' && $studentDocVerification[0]->english_score < 10 && $studentDocVerification[0]->english_test_attempt == 1)){
                        //                             return  json_encode(['code' => 200, 'title' => 'Verified', 'message' => 'Successfully Verified', 'icon' => generateIconPath('success'), 'records' => $records, 'redirect' => 'english-test']);
                        //       
                        Log::info("sadsd test last", [
                            'status' => $records['id_card_number']
                        ]);                  
                        return  json_encode(['code' => 200, 'title' => 'Verified', 'message' => 'Successfully Verified', 'records' => $records, 'icon' => generateIconPath('success')]);
                    } else {
                        return  json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => "Please Try Again", 'icon' => generateIconPath('warning')]);
                    }
                } else {
                    Log::info("records check test", [
                        'status' => $records
                    ]);
                    $addSelect = [
                        'identity_is_approved' => 'Pending',
                    ];
                    $selectData = array_merge(
                        $selectCols,
                        $addSelect
                    );
                    $data = $this->StudentfilesUpload($document_received, 'ID', $selectData);
                    $this->studentDocument->verificationStatutsPending(Auth::user()->id);

                    if (isset($data['code']) && $data['code'] === 200) {

                        $user = User::where('id', Auth::user()->id)->first();

                        $unsubscribeRoute = url('/unsubscribe/' . base64_encode(Auth::user()->email));
                        mail_send(
                            33,
                            [
                                '#Name#',
                                '#[Identity/Educational]#',
                                '#unsubscribeRoute#',
                            ],
                            [
                                $user->name . ' ' . $user->last_name,
                                'Identity',
                                $unsubscribeRoute
                            ],
                            Auth::user()->email
                        );
                        $studentDocVerification = getData('student_doc_verification', ['student_id', 'identity_is_approved', 'edu_is_approved', 'english_score', 'english_test_attempt'], ['student_id' => auth()->user()->id]);
                        if ($studentDocVerification[0]->identity_is_approved == 'Pending' && $studentDocVerification[0]->edu_is_approved == 'Pending' && (
                            $studentDocVerification[0]->english_test_attempt == 2 ||
                            ($studentDocVerification[0]->english_test_attempt == 1 && $studentDocVerification[0]->english_score < 10)
                        )) {
                            return  json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'english-test']);
                        } elseif ($studentDocVerification[0]->identity_is_approved != 'Approved' || $studentDocVerification[0]->edu_is_approved != 'Approved') {
                            return  json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'student-document-verification']);
                        } elseif (($studentDocVerification[0]->english_score == null) || ($studentDocVerification[0]->english_score < 10 && $studentDocVerification[0]->english_test_attempt == 1)) {
                            return  json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'english-test']);
                        } else {
                            return  json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records]);
                        }
                    } else {
                        return  json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => "Please Try Again", 'icon' => generateIconPath('warning')]);
                    }
                }
                // } else {
                //     return  json_encode(['code' => 201, 'title' => 'Expired', 'message' => "Your id proof has been expired ", 'records' => $records, 'icon' => generateIconPath('warning')]);
                // }
            } catch (\Exception $th) {
                $addSelect = [
                    'identity_is_approved' => 'Pending',
                ];
                $selectData = array_merge(
                    $selectCols,
                    $addSelect
                );
                $data = $this->StudentfilesUpload($document_received, 'ID', $selectData);
                $this->studentDocument->verificationStatutsPending(Auth::user()->id);
                if (isset($data['code']) && $data['code'] === 200) {

                    $user = User::where('id', Auth::user()->id)->first();

                    $unsubscribeRoute = url('/unsubscribe/' . base64_encode(Auth::user()->email));

                    mail_send(
                        33,
                        [
                            '#Name#',
                            '#[Identity/Educational]#',
                            '#unsubscribeRoute#',
                        ],
                        [
                            $user->name . ' ' . $user->last_name,
                            'Identity',
                            $unsubscribeRoute
                        ],
                        Auth::user()->email
                    );

                    $studentDocVerification = getData('student_doc_verification', ['student_id', 'identity_is_approved', 'edu_is_approved', 'english_score', 'english_test_attempt'], ['student_id' => auth()->user()->id]);
                    if ($studentDocVerification[0]->identity_is_approved == 'Pending' && $studentDocVerification[0]->edu_is_approved == 'Pending' && (
                        $studentDocVerification[0]->english_test_attempt == 2 ||
                        ($studentDocVerification[0]->english_test_attempt == 1 && $studentDocVerification[0]->english_score < 10)
                    )) {
                        return  json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => [], 'redirect' => 'english-test']);
                    } elseif ($studentDocVerification[0]->identity_is_approved != 'Approved' || $studentDocVerification[0]->edu_is_approved != 'Approved') {
                        return  json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => [], 'redirect' => 'student-document-verification']);
                    } elseif (($studentDocVerification[0]->english_score == null) || ($studentDocVerification[0]->english_score < 10 && $studentDocVerification[0]->english_test_attempt == 1)) {
                        return  json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => [], 'redirect' => 'english-test']);
                    } else {
                        return  json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => []]);
                    }
                } else {
                    return  json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => "Please Try Again", 'icon' => generateIconPath('warning')]);
                }
                // return json_encode(['code' => 201, 'title' => 'Unable to Verify', 'message' => '', 'icon' => generateIconPath('error')]);
            }
        }
    }
    protected function EduCertVerify($document_received, $dataSelect)
    {
        $selectCols = [];
        $records = [];
        if (isset($document_received) && !empty($document_received) && !is_array($document_received)) {
            try {

                $mindeeClient = new Client(env('MINDEE_API_KEY'));
                // $mindeeUser = $this->mindeeUser;
                // $eduCategory = $this->eduCategory;
                $inputSource = $mindeeClient->sourceFromPath($document_received);
                $predictOptions = new PredictMethodOptions();
                $customEndpoint = $mindeeClient->createEndpoint(
                    env('MIND_EDU_CAT'),
                    env('MIND_USERNAME'),
                    "1"
                );
                $predictOptions->setEndpoint($customEndpoint);
                $apiResponse = $mindeeClient->enqueueAndParse(GeneratedV1::class, $inputSource, $predictOptions);

                $jsonrecord =  json_encode($apiResponse);
                $jsonRes = json_decode($jsonrecord, true);
                $data = $jsonRes['document']['inference']['prediction']['fields'];
                $records['certificate_id'] = isset($data['certificate_id']['value']) ? $data['certificate_id']['value'] : '';
                $records['name_of_certification'] = isset($data['name_of_certification']['value']) ? $data['name_of_certification']['value'] : '';
                $records['completion_date'] = isset($data['completion_date']['value']) ? $data['completion_date']['value'] : '';
                // $records['grade'] = isset($data['grade']['value']) ? $data['grade']['value'] : '';
                $records['institute_name'] = isset($data['institute_name']['value']) ? $data['institute_name']['value'] : '';
                $records['completion_remark'] = isset($data['completion_remark']['value']) ? $data['completion_remark']['value'] : '';
                $records['signature'] = isset($data['signature']['value']) ? $data['signature']['value'] : '';
                $records['student_name'] = isset($data['student_name']['value']) ? $data['student_name']['value'] : '';
                $records['first_name'] = isset($data['first_name']['value']) ? $data['first_name']['value'] : '';
                $records['last_name'] = isset($data['last_name']['value']) ? $data['last_name']['value'] : '';
                $records['middle_name'] = isset($data['middle_name']['value']) ? $data['middle_name']['value'] : '';
                $records['level_of_education'] = isset($data['level_of_education']['value']) ? $data['level_of_education']['value'] : '';
                $records['is_intermediate_certificate'] = isset($data['is_intermediate_certificate']['value']) ? $data['is_intermediate_certificate']['value'] : '';
                $records['is_bachelor_certificate'] = isset($data['is_bachelor_certificate']['value']) ? $data['is_bachelor_certificate']['value'] : '';
                $records['is_post_graduation_certificate'] = isset($data['is_post_graduation_certificate']['value']) ? $data['is_post_graduation_certificate']['value'] : '';
                $records['is_diploma_certificate'] = isset($data['is_diploma_certificate']['value']) ? $data['is_diploma_certificate']['value'] : '';
                $records['is_senior_school_certificate'] = isset($data['is_senior_school_certificate']['value']) ? $data['is_senior_school_certificate']['value'] : '';
                $records['specialization'] = isset($data['specialization']['value']) ? $data['specialization']['value'] : '';

                $level_of_education =  $records['level_of_education'];
                $level_education = '0';
                if ($records['is_intermediate_certificate'] == '1') {
                    $level_education = '5';
                }
                if ($records['is_bachelor_certificate'] == '1') {
                    $level_education = '6';
                }
                if ($records['is_post_graduation_certificate'] == '1') {
                    $level_education = '7';
                }
                if ($records['is_diploma_certificate'] == '1') {
                    $level_education = '5';
                }
                if ($records['is_senior_school_certificate'] == '1') {
                    $level_education = '5';
                }
                if($level_of_education == "Bachelor's"){
                    $level_education = '6';
                }
                $records['doc_type'] = 'Edu';
                $edu_leve = !empty($dataSelect['edu_leve']) ? $dataSelect['edu_leve'] : '';
                $specilization = !empty($dataSelect['specilization']) ? $dataSelect['specilization'] : '';
                $doc_level = !empty($dataSelect['doc_level']) ? $dataSelect['doc_level'] : '';
                $selectCols = [
                    'education_doc_number' => htmlspecialchars($records['certificate_id']),
                    'edu_level' => $level_education,
                    'edu_specialization' => htmlspecialchars($records['specialization']),
                    'degree_course_name' => htmlspecialchars($records['name_of_certification']),
                    'name_on_education_doc' => htmlspecialchars_decode($records['first_name']) . " " . htmlspecialchars($records['middle_name']) . " " . htmlspecialchars($records['last_name']),
                    'remark_on_edu_doc' => htmlspecialchars($records['completion_remark']),
                    // 'grade_on_edu_doc' => $records['grade'],
                    'university_name_on_edu_doc' => htmlspecialchars($records['institute_name']),
                    'passing_year' => htmlspecialchars($records['completion_date']),
                    'education_level_num' => htmlspecialchars($level_of_education)
                ];
                $name = isset(Auth::user()->name) ? Auth::user()->name : '';
                $last_name = isset(Auth::user()->last_name) ? Auth::user()->last_name : '';
                $full_name = $name . " " . $last_name;
                $atheSelect = [];

                // if (htmlspecialchars($records['name_of_certification']) != '' && htmlspecialchars($records['first_name']) != '' &&  strtolower(htmlspecialchars($records['first_name'])) === strtolower(htmlspecialchars($name)) && htmlspecialchars($records['last_name']) != '' &&  strtolower(htmlspecialchars($records['last_name'])) === strtolower(htmlspecialchars($last_name)) && 
                // // htmlspecialchars($records['completion_remark']) == true && 
                // $level_education >= 6) {
                if($doc_level == '6'){
                    if(!empty($records['level_of_education']) && $level_education < 6){
                        return  json_encode(['code' => 206, 'title' => 'Please Upload Your Highest Education Document', 'message' => "Please Try Again", 'icon' => generateIconPath('warning')]);
                    }
                }
                if (
                    !empty($records['name_of_certification']) &&
                    !empty($records['first_name']) &&
                    !empty($records['last_name']) &&
                    // (((strtolower(trim($records['first_name'])) === strtolower(trim($name)) || strtolower(trim($records['first_name'])) == strtolower(trim($last_name)))  && (strtolower(trim($records['last_name'])) === strtolower(trim($name)) || strtolower(trim($records['last_name'])) === strtolower(trim($last_name))))
                    // || (!empty($records['middle_name']) && (strtolower(trim($records['middle_name'])) === strtolower(trim($name)) || strtolower(trim($records['middle_name'])) === strtolower(trim($last_name))))) &&
                    (
                        strtolower($records['first_name']) === strtolower($name) ||
                        strtolower($records['first_name']) === strtolower($last_name)
                    ) &&
                    (
                        stripos($records['last_name'], $name) !== false ||
                        stripos($records['last_name'], $last_name) !== false ||
                        stripos($records['middle_name'], $name) !== false ||
                        stripos($records['middle_name'], $last_name) !== false
                    )  &&
                    in_array(strtolower(trim($records['completion_remark'])), ['true'], true) &&
                    $level_education >= 6
                ){
                    Log::info("docUpload check level_education", [
                        'status' => $level_education
                    ]);
                    $addSelect = [
                        'edu_approved_on' => $this->time,
                        'edu_approved_by' => 1,
                        'edu_is_approved' => 'Approved',
                    ];
                    if($level_education <= 5){
                        $atheSelect = [
                            'edu_athe_approved' => 'Approved',
                        ];
                    }
                    if($level_education == 6){
                        $atheSelect = [
                            'edu_master_approved' => 'Approved',
                        ];
                    }
                    $selectData = array_merge(
                        $selectCols,
                        $addSelect,
                        $atheSelect
                    );
                    $data = $this->StudentfilesUpload($document_received, 'EDU', $selectData);
                    if (isset($data['code']) && $data['code'] === 200) {
                        $studentDocVerification = getData('student_doc_verification', ['student_id', 'identity_is_approved', 'edu_is_approved', 'english_score', 'english_test_attempt'], ['student_id' => auth()->user()->id]);
                        if ($studentDocVerification[0]->identity_is_approved == 'Pending' && $studentDocVerification[0]->edu_is_approved == 'Pending' && (
                            $studentDocVerification[0]->english_test_attempt == 2 ||
                            ($studentDocVerification[0]->english_test_attempt == 1 && $studentDocVerification[0]->english_score < 10)
                        )) {
                            return  json_encode(['code' => 200, 'title' => 'Send for Approval', 'message' => 'It will be approved within 1-2 working days.', 'records' => $records, 'icon' => generateIconPath('warning'), 'redirect' => 'english-test']);
                        } elseif ($studentDocVerification[0]->identity_is_approved != 'Approved' || $studentDocVerification[0]->edu_is_approved != 'Approved') {
                            return  json_encode(['code' => 200, 'title' => 'Send for Approval', 'message' => 'It will be approved within 1-2 working days.', 'records' => $records, 'icon' => generateIconPath('warning'), 'redirect' => 'student-document-verification']);
                        } elseif (($studentDocVerification[0]->english_score == null) || ($studentDocVerification[0]->english_score < 10 && $studentDocVerification[0]->english_test_attempt == 1)) {
                            return  json_encode(['code' => 200, 'title' => 'Send for Approval', 'message' => 'It will be approved within 1-2 working days.', 'records' => $records, 'icon' => generateIconPath('warning'), 'redirect' => 'english-test']);
                        } else {
                            return  json_encode(['code' => 200, 'title' => 'Send for Approval', 'message' => 'It will be approved within 1-2 working days.', 'records' => $records, 'icon' => generateIconPath('warning'), 'redirect' => 'english-test']);
                        }
                    } else {
                        return  json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => "Please Try Again", 'icon' => generateIconPath('warning')]);
                    }
                } else {
                    if(empty($records['first_name'])){
                        return  json_encode(['code' => 204, 'title' => 'Invalid Document.', 'message' => "Please Try Again", 'icon' => generateIconPath('warning')]);
                    }
                    $addSelect = [
                        'edu_is_approved' => 'Pending',
                    ];
                    if($level_education <= 5){
                        $atheSelect = [
                            'edu_athe_approved' => 'Pending',
                        ];
                    }
                    if($level_education == 6){
                        $atheSelect = [
                            'edu_master_approved' => 'Pending',
                        ];
                    }
                    $selectData = array_merge(
                        $selectCols,
                        $addSelect,
 						$atheSelect
                    );
                    $data = $this->StudentfilesUpload($document_received, 'EDU', $selectData);
                    // $this->studentDocument->verificationStatutsPending(Auth::user()->id);
                    if (isset($data['code']) && $data['code'] === 200) {
                        $user = User::where('id', Auth::user()->id)->first();

                        $unsubscribeRoute = url('/unsubscribe/' . base64_encode(Auth::user()->email));

                        mail_send(
                            33,
                            [
                                '#Name#',
                                '#[Identity/Educational]#',
                                '#unsubscribeRoute#',
                            ],
                            [
                                $user->name . ' ' . $user->last_name,
                                'Educational',
                                $unsubscribeRoute
                            ],
                            Auth::user()->email
                        );
                        $studentDocVerification = getData('student_doc_verification', ['student_id', 'identity_is_approved', 'edu_is_approved', 'english_score', 'english_test_attempt'], ['student_id' => auth()->user()->id]);
                        if ($studentDocVerification[0]->identity_is_approved == 'Pending' && $studentDocVerification[0]->edu_is_approved == 'Pending' && (
                            $studentDocVerification[0]->english_test_attempt == 2 ||
                            ($studentDocVerification[0]->english_test_attempt == 1 && $studentDocVerification[0]->english_score < 10)
                        )) {
                            return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'english-test']);
                        } elseif ($studentDocVerification[0]->identity_is_approved != 'Approved' || $studentDocVerification[0]->edu_is_approved != 'Approved') {
                            return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'student-document-verification']);
                        } elseif (($studentDocVerification[0]->english_score == null) || ($studentDocVerification[0]->english_score < 10 && $studentDocVerification[0]->english_test_attempt == 1)) {
                            return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'english-test']);
                        } else {
                            return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'english-test']);
                        }
                    } else {
                        return  json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => "Please Try Again", 'icon' => generateIconPath('warning')]);
                    }
                }
            } catch (\Exception $th) {
                if(empty($records['first_name'])){
                    return  json_encode(['code' => 204, 'title' => 'Invalid Document.', 'message' => "Please Try Again", 'icon' => generateIconPath('warning')]);
                }
                $addSelect = [
                    'edu_is_approved' => 'Pending',
                ];
                $selectData = array_merge(
                    $selectCols,
                    $addSelect
                );
                $data = $this->StudentfilesUpload($document_received, 'EDU', $selectData);
                $this->studentDocument->verificationStatutsPending(Auth::user()->id);
                if (isset($data['code']) && $data['code'] === 200) {
                    $studentDocVerification = getData('student_doc_verification', ['student_id', 'identity_is_approved', 'edu_is_approved', 'english_score', 'english_test_attempt'], ['student_id' => auth()->user()->id]);
                    if ($studentDocVerification[0]->identity_is_approved == 'Pending' && $studentDocVerification[0]->edu_is_approved == 'Pending' && (
                        $studentDocVerification[0]->english_test_attempt == 2 ||
                        ($studentDocVerification[0]->english_test_attempt == 1 && $studentDocVerification[0]->english_score < 10)
                    )) {
                        return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'english-test']);
                    } elseif ($studentDocVerification[0]->identity_is_approved != 'Approved' || $studentDocVerification[0]->edu_is_approved != 'Approved') {
                        return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'student-document-verification']);
                    } elseif (($studentDocVerification[0]->english_score == null) || ($studentDocVerification[0]->english_score < 10 && $studentDocVerification[0]->english_test_attempt == 1)) {
                        return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records, 'redirect' => 'english-test']);
                    } else {
                        return json_encode(['code' => 202, 'title' => 'Send for Approval', 'message' => "It will be approved within 1-2 working days.", 'icon' => generateIconPath('warning'), 'records' => $records,  'redirect' => 'english-test']);
                    }
                } else {
                    return  json_encode(['code' => 201, 'title' => 'Unable to Verify', 'message' => "Please Try Again", 'icon' => generateIconPath('warning')]);
                }

                // return json_encode(['code' => 201, 'title' => 'Unable to Verify', 'message' => 'Not Clear', 'icon' => generateIconPath('error')]);
            }
        }
    }
    protected function DocSendtoAdmin($doc, $type)
    {
        if (auth::check()) {
        }
        return redirect()->route('login');
    }
    public function userSignup()
    {
        return view('frontend.sign-up-student');
    }
    public function userDbaSignup()
    {
        return view('frontend.sign-up-student-dba');
    }
    protected function StudentfilesUpload($file, $DocCategory, $records)
    {

        if (isset($DocCategory) && !empty($DocCategory) && isset($file) && !empty($file)) {
            $userDocs =  $this->studentDocument->getCurrentUserDocInfo();
            $user_id = $userDocs['user']['id'];
            if (isset($DocCategory) && $DocCategory === 'ID') {
                $old_file = $userDocs['identity_doc_file'];
            } elseif ($DocCategory === 'EDU') {
                $old_file = $userDocs['edu_doc_file'];
            } elseif ($DocCategory === 'RESUME') {
                $old_file = $userDocs['resume_file'];
            } elseif ($DocCategory === 'PROFILE') {
                $old_file = $userDocs['user']['photo'];
            } elseif ($DocCategory === 'PROFILE_BACKGORUND') {
                $old_file = $userDocs['user']['profile_background'];
            } elseif ($DocCategory === 'RESEARCH_PROPOSAL') {
                $old_file = $userDocs['research_proposal_file'];
            }



            if (isset($userDocs['folder_name']) && !empty($userDocs['folder_name'])) {
                $folder = $userDocs['folder_name'];
            } else {

                $folder = "Student_" . time() . "_" . $userDocs['user']['name'];

                $makeFolder = File::makeDirectory(public_path("storage/studentDocs/" . $folder), $mode = 0777, true, true);
                if (!isset($makeFolder) && $makeFolder === 0 && is_numeric($makeFolder)) {
                    return false;
                }
            }

            try {

                $studentDocAthe = getData('document_verification', ['student_id','edu_trail_attempt'], ['student_id' => $user_id]);
                $studentDocVerification = getData('student_doc_verification', ['student_id', 'edu_athe_approved','edu_master_approved'], ['student_id' => $user_id]);
                if (isset($studentDocVerification) && !empty($studentDocVerification) && ($studentDocVerification[0]->edu_athe_approved != '' || $studentDocVerification[0]->edu_master_approved != '')) {
                    $old_file = $studentDocAthe[0]->edu_doc_file ?? '';
                    $docUpload = UploadFiles($file, 'studentDocs/' . $folder, $old_file);
                } else {
                    $docUpload = UploadFiles($file, 'studentDocs/' . $folder, '');
                }
                Log::info("docUpload check", [
                    'status' => $docUpload['status'],
                    'type' => gettype($docUpload['status']),
                ]);
                $where = ['student_id' => $user_id];
                $updateUser  = FALSE;
                if ($docUpload['status'] === TRUE) {

                    if (isset($DocCategory) && $DocCategory === 'ID') {
                        $doc_file_name = $file->getClientOriginalName();
                        $attemptRemain  =  $userDocs['identity_trail_attempt'] - 1;
                        Log::info("docUpload check test addSelect", [
                            'code' => $records,
                        ]);
                        $addSelect = [
                            'folder_name' => $folder,
                            'identity_doc_file' => $docUpload['url'],
                            'doc_file_name' => $doc_file_name,
                            'identity_trail_attempt' => $attemptRemain,
                            'identity_doc_uploaded_on' => $this->time,
                        ];
                        $select = array_merge($records, $addSelect);
                        $updateUser = processData(['student_doc_verification', 'student_doc_id'], $select, $where);
                    } elseif ($DocCategory === 'EDU') {
                        $doc_file_name = $file->getClientOriginalName();
                        $attemptRemain  =  $userDocs['edu_trail_attempt'] - 1;
                        $studentDocVerification = getData('student_doc_verification', ['student_id', 'edu_athe_approved','edu_master_approved','edu_is_approved'], ['student_id' => $user_id]);
                        $studentDocAthe = getData('document_verification', ['student_id','edu_trail_attempt'], ['student_id' => $user_id]);

                        Log::info("docUpload check test", [
                            'code' => $studentDocVerification,
                        ]);
                        if (isset($studentDocVerification) && !empty($studentDocVerification) && ($studentDocVerification[0]->edu_athe_approved != '' || $studentDocVerification[0]->edu_master_approved !='')){

                        
                            if($records['edu_level'] > 5){
                                    Log::info("docUpload check test", [
                                        'code_records' => $records,
                                    ]);
                                    $attemptRemains = isset($studentDocAthe) && isset($studentDocAthe[0]->edu_trail_attempt)? $studentDocAthe[0]->edu_trail_attempt - 1 : 2;
                                    $studentCourseMaster = DB::table('student_course_master')
                                    ->select('course_master.category_id','student_course_master.course_id')
                                    ->leftJoin('course_master', 'course_master.id', '=', 'student_course_master.course_id')
                                    ->where('user_id',$user_id)
                                    ->where('student_course_master.is_deleted', 'No')
                                    ->orderBy('student_course_master.created_at', 'desc')
                                    ->get();
                                    if(!empty($studentCourseMaster) && isset($studentCourseMaster)){
                                        foreach($studentCourseMaster as $studentCourseMasters){
                                            if($studentCourseMasters->category_id == 5 && $records['edu_level'] < 7){
                                                $records['edu_is_approved'] = "Pending";
                                            }
                                            if($studentCourseMasters->category_id < 5 && $records['edu_level'] < 6){
                                                $records['edu_is_approved'] = "Pending";
                                            }
                                        }
                                    }
                                    if($records['edu_is_approved'] == 'Pending'){
                                        $addAtheSelect = [
                                            'student_id'=> Auth::user()->id,
                                            'edu_doc_file' => $docUpload['url'],
                                            'edu_doc_file_name' => $doc_file_name,
                                            'edu_trail_attempt' => $attemptRemains,
                                            'edu_doc_uploaded_on' => $this->time,
                                            'edu_is_approved'=> 'Pending'
                                            // 'edu_trail_attempt'=>
                                        ];

                                        $select = array_merge($records, $addAtheSelect);
                                        Log::info("docUpload check test", [
                                            'code_records' => $records,
                                        ]);
                                        $updateUser = processData(['document_verification', 'id'], $select, $where);

                                    }elseif($records['edu_is_approved'] == 'Approved'){
                                        $addAtheSelect = [
                                            'student_id'=> Auth::user()->id,
                                            'edu_doc_file' => $docUpload['url'],
                                            'edu_doc_file_name' => $doc_file_name,
                                            'edu_trail_attempt' => $attemptRemains,
                                            'edu_doc_uploaded_on' => $this->time,
                                            'edu_is_approved'=> 'Approved'

                                        ];
                                        $select = array_merge($records,$addAtheSelect);

                                        $updateUser = processData(['document_verification', 'id'], $select, $where);

                                        // $select = [
                                        //     'folder_name' => $folder,
                                        //     'edu_doc_file' => $docUpload['url'],
                                        //     'edu_doc_file_name' => $doc_file_name,
                                        //     'edu_trail_attempt' => $attemptRemains,
                                        //     'edu_doc_uploaded_on' => $this->time,
                                        //     'edu_is_approved'=> 'Approved'

                                        // ];
                                        // $select = array_merge($records, $select);

                                        // $updateUser = processData(['student_doc_verification', 'student_doc_id'], $select, $where);


                                    }

                            }else{
                                if (isset($studentDocVerification) && !empty($studentDocVerification) && $studentDocVerification['edu_is_approved'] == "Approved"){
                                    $attemptRemains = isset($studentDocAthe) && isset($studentDocAthe[0]->edu_trail_attempt)? $studentDocAthe[0]->edu_trail_attempt - 1 : 2;

                                    $addAtheSelect = [
                                        'student_id'=> Auth::user()->id,
                                        'edu_doc_file' => $docUpload['url'],
                                        'edu_doc_file_name' => $doc_file_name,
                                        'edu_trail_attempt' => $attemptRemains,
                                        'edu_doc_uploaded_on' => $this->time,
                                        'edu_is_approved'=> 'Pending'
                                        // 'edu_trail_attempt'=>
                                    ];

                                    $select = array_merge($records, $addAtheSelect);
                                    $updateUser = processData(['document_verification', 'id'], $select, $where);

                                }else{

                                    $select = [
                                        'folder_name' => $folder,
                                        'edu_doc_file' => $docUpload['url'],
                                        'edu_doc_file_name' => $doc_file_name,
                                        'edu_trail_attempt' => $attemptRemain,
                                        'edu_doc_uploaded_on' => $this->time,

                                    ];

                                    $select = array_merge($records, $select);
                                    $updateUser = processData(['student_doc_verification', 'student_doc_id'], $select, $where);
                                }
                            }

                        }else{
                            Log::info("✅ Entered IF block test check"  );
                            $select = [
                                'folder_name' => $folder,
                                'edu_doc_file' => $docUpload['url'],
                                'edu_doc_file_name' => $doc_file_name,
                                'edu_trail_attempt' => $attemptRemain,
                                'edu_doc_uploaded_on' => $this->time,

                            ];
                            $select = array_merge($records, $select);
                            Log::info("docUpload check test dgfdgfgd" , [
                                'code' => $records,
                            ]);
                            $updateUser = processData(['student_doc_verification', 'student_doc_id'], $select, $where);
                        }
                    } elseif ($DocCategory === 'RESUME') {
                        $doc_file_name = $file->getClientOriginalName();

                        $select = [
                            'folder_name' => $folder,
                            'resume_file' => $docUpload['url'],
                            'resume_file_name' => $doc_file_name,
                            'last_resume_upload_at' => $this->time,

                        ];
                        $updateUser = processData(['student_doc_verification', 'student_doc_id'], $select, $where);
                        $this->user->verificationStatutsUpdate($user_id);
                    } elseif ($DocCategory === 'PROFILE') {

                        $where = ['student_id' => $user_id];
                        $select = [
                            'folder_name' => $folder,
                        ];
                        $updateStudent = processData(['student_doc_verification', 'student_doc_id'], $select, $where);

                        $where = ['id' => $user_id];
                        $select = [
                            'photo' => $docUpload['url'],
                        ];


                        $updateUser = processData(['users', 'id'], $select, $where);
                        return ['code' => 200, 'url' => $docUpload['url'], 'old_url' => $old_file];
                    } elseif ($DocCategory === 'PROFILE_BACKGORUND') {
                        $where = ['id' => $user_id];
                        $select = [
                            'profile_background' => $docUpload['url'],
                        ];
                        $updateUser = processData(['users', 'id'], $select, $where);
                        return ['code' => 200, 'url' => $docUpload['url'], 'old_url' => $old_file];
                    } elseif ($DocCategory === 'RESEARCH_PROPOSAL') {
                        $doc_file_name = $file->getClientOriginalName();
                        $select = [
                            'folder_name' => $folder,
                            'research_proposal_file' => $docUpload['url'],
                            'research_proposal_file_name' => $doc_file_name,
                            'proposal_uploaded_at' => $this->time,
                            'proposal_is_approved' => 'Pending'

                        ];
                        $updateUser = processData(['student_doc_verification', 'student_doc_id'], $select, $where);
                        // $this->user->verificationStatutsUpdate($user_id);

                    }
                    if ($updateUser['status']) {
                        return ['code' => 200];
                    }
                    return ['code' => 202];
                }
                return ['code' => 203];
            } catch (\Exception $th) {

                return ['code' => 404];
            }
        }
        return ['code' => 404];
    }
    public function instructorSignup()
    {
        return view('frontend.sign-up-teacher');
    }
    public function instituteSignup()
    {
        return view('frontend.sign-up-institute');
    }
    public function updateProfile(Request $req)
    {

        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {

            if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin' || Auth::user()->role === 'sales') {
                $user_id = isset($req->student_id) ? base64_decode($req->input('student_id')) : '';
            } else {
                $user_id = Auth::user()->id;
            }
            $first_name = isset($req->first_name) ? htmlspecialchars($req->input('first_name')) : '';
            $last_name = isset($req->last_name) ? htmlspecialchars($req->input('last_name')) : '';
            $dob = isset($req->dob) ? htmlspecialchars($req->input('dob')) : '';
            $gender = isset($req->gender) ? htmlspecialchars($req->input('gender')) : null;
            $country = isset($req->country) ? htmlspecialchars($req->input('country')) : 0;
            $city = isset($req->city) ? htmlspecialchars($req->input('city')) : '';
            $nationality = isset($req->nationality) ? htmlspecialchars($req->input('nationality')) : '';
            $address = isset($req->address) ? htmlspecialchars($req->input('address')) : '';
            $zip = isset($req->zip) ? htmlspecialchars($req->input('zip')) : '';
            $occupation = isset($req->occupation) ? htmlspecialchars($req->input('occupation')) : '';
            $exists =   is_exist('users', ['id' => $user_id,  'is_deleted' => 'No']);
            if (isset($exists) && is_numeric($exists) && $exists === 0) {
                return json_encode(['code' => 201, 'title' => __('response.user_not_available'), 'message' => __('response.user_not_exit'), 'remark' => 'warning']);
            }
            $validate_rules = [
                'first_name' => 'required|string|max:225|min:3|regex:/^[a-zA-Z\s]+$/',
                'last_name' => 'required|string|max:225|min:2|regex:/^[a-zA-Z\s]+$/',
                'dob' => 'required|date|before:today',
                // 'gender' => 'required|string|in:Male,Female,Not Disclose',
                // 'occupation' => 'required|string|in:Student,Employed,Unemployed',
                'country' => 'required|numeric|min:1',
                'city' => 'required|string|min:1|regex:/^[a-zA-Z\s]+$/',
                // 'zip' => 'string|min:1',
                // 'nationality' => 'required|string|max:225',
                'address' => 'max:100',
            ];
            $validate = Validator::make($req->all(), $validate_rules);
            if (!$validate->fails()) {
                $select = [
                    'name' => htmlspecialchars_decode($first_name),
                    'last_name' => htmlspecialchars_decode($last_name)
                ];

                $where = ['id' => $user_id,  'is_deleted' => 'No'];
                $updateUser = processData(['users', 'id'], $select, $where, 'update');

                if (isset($updateUser) && $updateUser['status'] === TRUE && $updateUser['id']  > 0) {
                    $select = [
                        'student_id' => $updateUser['id'],
                        'address' => htmlspecialchars_decode($address),
                        'city_id' => htmlspecialchars_decode($city),
                        'country_id' => $country,
                        'gender' => $gender,
                        'dob' => $dob,
                        'zip' => htmlspecialchars_decode($zip),
                        'nationality' => htmlspecialchars_decode($nationality),
                        'occupation' => $occupation,
                        'last_profile_update_on' =>  $this->time,
                    ];
                    $where = ['student_id' => $updateUser['id']];
                    $exists = is_exist('student_profile_master', $where);
                    if (isset($exists) && $exists > 0) {
                        $action = 'update';
                    } else {
                        $action = 'insert';
                    }
                    $updateUserProfile = processData(['student_profile_master', 'student_profile_id'], $select, $where, $action);

                    if (isset($updateUserProfile) && $updateUserProfile === FALSE) {
                        return json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_try_again'), "icon" => "error"]);
                    }
                    return json_encode(['code' => 200, 'title' => __('response.successfully_updated'), "message" => __('response.student_details_updated'), "icon" => "success"]);
                }
            } else {


                return json_encode(['code' => 202, 'title' => __('response.required_fields_are_missing'), 'message' => __('response.please_provide_required_info'), "icon" => "error", 'data' => $validate->errors()]);
            }
        }
    }
    public function updateSocialProfile(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {
            $user_id = Auth::user()->id;
            $facebook = isset($req->facebook) ? htmlspecialchars($req->input('facebook')) : '';
            $insta = isset($req->insta) ? htmlspecialchars($req->input('insta')) : '';
            $linkedin = isset($req->linkedin) ? htmlspecialchars($req->input('linkedin')) : '';
            $twitter = isset($req->twitter) ? htmlspecialchars($req->input('twitter')) : '';
            $whatsapp = isset($req->whatsapp) ? htmlspecialchars($req->input('whatsapp')) : '';
            $whatsapp_country_code = isset($req->whatsapp_country_code) ? htmlspecialchars($req->input('whatsapp_country_code')) : '';
            $exists =   is_exist('users', ['id' => $user_id,  'is_deleted' => 'No']);


            if (isset($exists) && is_numeric($exists) && $exists > 0) {

                $where = ['student_id' => $user_id];
                $exists = is_exist('student_profile_master', $where);
                if (isset($exists) && is_numeric($exists) && $exists > 0) {
                    $action = 'update';
                } else {
                    $action = 'insert';
                }
                $select = [
                    'facebook' => htmlspecialchars_decode($facebook),
                    'instagram' => htmlspecialchars_decode($insta),
                    'linkedIn' => $linkedin,
                    'twitter' => $twitter,
                    'whatsapp' => $whatsapp,
                    'whatsapp_country_code' => $whatsapp_country_code,
                    'last_profile_update_on' =>  $this->time,
                ];
                $updateUserProfile = processData(['student_profile_master', 'student_profile_id'], $select, $where, $action);
                if (isset($updateUserProfile) && $updateUserProfile === FALSE) {
                    return json_encode(['code' => 201, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_try_again'), "icon" => "error"]);
                }
                return json_encode(['code' => 200, 'title' => __('response.successfully_updated'), "message" => __('response.student_social_profile_updated'), "icon" => "success"]);
            } else {
                return json_encode(['code' => 201, 'title' => __('response.user_not_available'), 'message' => __('response.user_not_exit'), 'remark' => 'warning']);
            }
        } else {
            return json_encode(['code' => 404, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_login_again_try'), 'records' => '', 'remark' => 'warning']);
        }
    }

    public function aboutMe(Request $req)
    {
        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {

            if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin') {
                $user_id = isset($req->student_id) ? base64_decode($req->input('student_id')) : '';
            } else {
                $user_id = Auth::user()->id;
            }

            $exists =   is_exist('users', ['id' => $user_id,  'is_deleted' => 'No']);
            if (isset($exists) && is_numeric($exists) && $exists > 0) {

                $selectData = [];
                $i = 0;
                foreach ($req->question_id as $key => $question) {

                    $question =  base64_decode($question);
                    $where = ['student_id' => $user_id,  'question_id' => $question];
                    $exists = is_exist('student_about_me', $where);


                    $selectCols = [
                        'student_id' => $user_id,
                        'question_id' => $question,
                        'answer' => $req->answer[$key]
                    ];

                    if (isset($exists) && $exists > 0) {

                        $where = ['student_id' => $user_id,  'question_id' => $question];

                        $addSelect = [
                            'updated_by' => $user_id,
                            'updated_at' =>  $this->time,
                        ];
                        $action = 'update';
                    } else {
                        $addSelect = [
                            'created_by' => $user_id,
                            'created_at' =>  $this->time,
                        ];
                        $action = 'insert';
                    }

                    $selectData = array_merge(
                        $selectCols,
                        $addSelect
                    );

                    if (!empty($req->answer[$key])) {
                        $i++;
                    }

                    $updateUserAboutme = processData(['student_about_me', 'id'], $selectData, $where, $action);
                }
                if (isset($updateUserAboutme) && $updateUserAboutme['status'] == TRUE) {
                    return json_encode(['code' => 200, 'title' => __('response.successfully_updated'), "message" => __('response.about_me_updated_successfully'), "icon" => "success"]);
                }
                // if($i == 0){
                return json_encode(['code' => 201, 'title' => __('response.at_least_one_field'), 'message' => __('response.please_try_again'), "icon" => "error"]);
                // }else{
                // return json_encode(['code' => 200, 'title' => __('response.successfully_updated'), "message" => __('response.about_me_updated_successfully'), "icon" => "success"]);

                // }

            } else {
                return json_encode(['code' => 201, 'title' => __('response.user_not_available'), 'message' => __('response.user_not_exit'), 'remark' => 'warning']);
            }
        } else {
            return json_encode(['code' => 404, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_login_again_try'), 'records' => '', 'remark' => 'warning']);
        }
    }

    public function closeAccount(Request $req)
    {

        $user_id = isset($req->user_id) ? base64_decode($req->user_id) : '';

        if (isset($user_id)) {
            $i = 0;
            try {

                $exists = is_exist('users', ['id' => $user_id, 'is_deleted' => 'No']);
                if (!empty($exists) && $exists > 0) {

                    $where = ['id' => $user_id];


                    $select = [

                        'is_active' => "Inactive",

                    ];
                    $updateUser = processData(['users', 'id'], $select, $where);


                    Auth::logout();
                    $req->session()->invalidate();
                    $req->session()->regenerateToken();

                    return response()->json(['code' => 200, 'title' => __('response.account_deactivated'), 'icon' => 'success']);
                } else {
                    return json_encode(['code' => 404, 'title' => __('response.user_does_not_exist'), 'message' => __('response.please_try_again'), "icon" => "error"]);
                }
            } catch (\Exception $e) {
                return response()->json(['code' => 201, 'title' => __('response.cart.something_went_wrong'), "icon" => "error"]);
            }
        } else {

            return response()->json(['code' => 201, 'title' => __('response.cart.something_went_wrong'), "icon" => "error"]);
        }
    }
    // public function edit(Request $request): View
    // {
    //     return view('profile.edit', [
    //         'user' => $request->user(),
    //     ]);
    // }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        // return $request->all();
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function forgetPassword()
    {
        return view('frontend.forget-password');
    }
    public function watchProgressCheck(Request $req)
    {

        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {

            if (Auth::user()->role === 'admin') {
                $user_id = isset($req->student_id) ? base64_decode($req->input('student_id')) : '';
            } else {
                $user_id = Auth::user()->id;
            }

            $watch_content = isset($req->watch_content) ? ($req->input('watch_content')) : '';

            $course_id = isset($req->course_id) ? base64_decode($req->input('course_id')) : '';

            $exists =   is_exist('student_course_master', ['user_id' => $user_id, 'course_id' => $course_id]);
            $total_progress_display_count = '';
            if (isset($exists) && is_numeric($exists) && $exists > 0) {
                $where = ['user_id' => $user_id, 'course_id' => $course_id];
                $select = ['watch_content_id', 'total_progress_display_count'];
                $table = 'student_course_master';
                $watch_content_id_arrays = [];
                $getData = getData($table, $select, $where, '', 'id', 'DESC');
                $total_progress_display_count = $getData[0]->total_progress_display_count;
                $watch_content_id_arrays = explode(",", $getData[0]->watch_content_id);
                if (in_array($watch_content, $watch_content_id_arrays)) {
                    $Data = "TRUE";
                } else {
                    $Data = "FALSE";
                }
                return json_encode(['code' => 200, 'title' => __('response.successfully_updated'), "message" => __('response.about_me_updated_successfully'), "icon" => "success", "data" => $Data, 'count' => $total_progress_display_count]);
            } else {
                return json_encode(['code' => 201, 'title' => __('response.user_not_available'), 'message' => __('response.user_not_exit'), 'remark' => 'warning']);
            }
        } else {
            return json_encode(['code' => 404, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_login_again_try'), 'records' => '', 'remark' => 'warning']);
        }
    }

    public function watchProgress(Request $req)
    {

        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {

            if (Auth::user()->role === 'admin') {
                $user_id = isset($req->student_id) ? base64_decode($req->input('student_id')) : '';
            } else {
                $user_id = Auth::user()->id;
            }

            $progress_bar = isset($req->progress_bar) ? ($req->input('progress_bar')) : '';
            $total_progress_display_value = isset($req->total_progress_display_value) ? ($req->input('total_progress_display_value')) : '';
            $total_progress_display_count = isset($req->total_progress_display_count) ? ($req->input('total_progress_display_count')) : '';


            $course_id = isset($req->course_id) ? base64_decode($req->input('course_id')) : '';
            $watch_content_id = '';
            $exists =   is_exist('student_course_master', ['user_id' => $user_id, 'course_id' => $course_id, 'is_deleted' => 'No']);
            if (isset($exists) && is_numeric($exists) && $exists > 0) {
                $where = ['user_id' => $user_id, 'course_id' => $course_id];
                $select = ['watch_content_id', 'id'];
                $table = 'student_course_master';
                $getData = getData($table, $select, $where, '', 'id', 'desc');

                if (!empty($getData[0]->watch_content_id)) {
                    $watch_content_id = $getData[0]->watch_content_id;
                    $watch_content_id = $watch_content_id . ',' . $total_progress_display_value;
                } else {
                    $watch_content_id = $total_progress_display_value;
                }

                $where = ['user_id' => $user_id, 'course_id' => $course_id, 'id' => $getData[0]->id];

                $selectData = [
                    'course_progress' => $progress_bar,
                    'watch_content_id' => $watch_content_id,
                    'total_progress_display_count' => $total_progress_display_count,
                    'progress_updated_at' => now(),
                ];

                $VideoID = explode('_', $total_progress_display_value);
                $whereProgress = ['user_id' => $user_id, 'video_id' => $VideoID[1], 'student_course_master_id' => $getData[0]->id];
                $existsProgress =   is_exist('video_progress', $whereProgress);
                if (isset($existsProgress) && is_numeric($existsProgress) && $existsProgress > 0) {
                    $selectDatas = [
                        'full_check' => 'Yes'
                    ];
                    $updateProgress = processData(['video_progress', 'id'], $selectDatas, $whereProgress);
                }
                $updateUserAboutme = processData(['student_course_master', 'id'], $selectData, $where);

                if (isset($updateUserAboutme) && $updateUserAboutme['status'] == TRUE) {
                    //Student Mail Attendance
                    // if ($progress_bar >= 95) {
                    //     $attendance = generateAttendanceCertificate($user_id, $course_id);
                    //     if (isset($attendance)  && $attendance['status'] == 'true'){
                    //         $courseName = $attendance['course_name'];
                    //         $studentName = Auth::user()->name . ' ' . Auth::user()->last_name;
                    //         mail_send(66,['#Name#', '#CourseName#'],[$studentName, $courseName],Auth::user()->email);
                    //     }
                    // }
                    return json_encode(['code' => 200, 'title' => __('response.successfully_updated'), "message" => __('response.about_me_updated_successfully'), "icon" => "success", "data" => $watch_content_id]);
                }
                // if($i == 0){
                return json_encode(['code' => 201, 'title' => __('response.at_least_one_field'), 'message' => __('response.please_try_again'), "icon" => "error"]);
                // }else{
                // return json_encode(['code' => 200, 'title' => __('response.successfully_updated'), "message" => __('response.about_me_updated_successfully'), "icon" => "success"]);

                // }

            } else {
                return json_encode(['code' => 201, 'title' => __('response.user_not_available'), 'message' => __('response.user_not_exit'), 'remark' => 'warning']);
            }
        } else {
            return json_encode(['code' => 404, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_login_again_try'), 'records' => '', 'remark' => 'warning']);
        }
    }


    public function saveProgress(Request $req)
    {

        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {

            $user_id = Auth::user()->id;
            $duration = isset($req->duration) ? ($req->input('duration')) : '';
            $course_id = isset($req->course_id) ? base64_decode($req->input('course_id')) : '';
            $watch_content = isset($req->watch_content) ? ($req->input('watch_content')) : '';


            $watch_content_id = '';


            $whereCourse = ['user_id' => $user_id, 'course_id' => $course_id];
            $selectCourse = ['id'];
            $tableCourse = 'student_course_master';
            $getCourse = getData($tableCourse, $selectCourse, $whereCourse, '', 'id', 'desc');
            // if (isset($exists) && is_numeric($exists) && $exists > 0) {
            $where = ['user_id' => $user_id, 'course_id' => $course_id, 'video_id' => $watch_content, 'student_course_master_id' => $getCourse[0]->id];

            $selectData = [
                'user_id' => $user_id,
                'course_id' => $course_id,
                'video_id' => $watch_content,
                'duration' => $duration,
                'student_course_master_id' => $getCourse[0]->id,
                'created_at'=> $this->time 
            ];

            $updateUserAboutme = processData(['video_progress', 'id'], $selectData, $where);

            if (isset($updateUserAboutme) && $updateUserAboutme['status'] == TRUE) {
                return json_encode(['code' => 200, 'title' => __('response.successfully_updated'), "message" => __('response.about_me_updated_successfully'), "icon" => "success", "data" => $duration]);
            }
            return json_encode(['code' => 404, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_login_again_try'), 'records' => '', 'remark' => 'warning']);
        } else {
            return json_encode(['code' => 201, 'title' => __('response.user_not_available'), 'message' => __('response.user_not_exit'), 'remark' => 'warning']);
        }
    }

    public function getProgress(Request $req)
    {

        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {

            $user_id = Auth::user()->id;
            $duration = isset($req->duration) ? ($req->input('duration')) : '';
            $course_id = isset($req->course_id) ? base64_decode($req->input('course_id')) : '';
            $watch_content = isset($req->watch_content) ? ($req->input('watch_content')) : '';


            $watch_content_id = '';
            $exists =   is_exist('video_progress', ['user_id' => $user_id, 'course_id' => $course_id]);

            if (isset($exists) && is_numeric($exists) && $exists > 0) {

                $whereCourse = ['user_id' => $user_id, 'course_id' => $course_id];
                $selectCourse = ['id'];
                $tableCourse = 'student_course_master';
                $getCourse = getData($tableCourse, $selectCourse, $whereCourse, '', 'id', 'desc');


                $where = ['user_id' => $user_id, 'course_id' => $course_id, 'student_course_master_id' => $getCourse[0]->id];
                $select = ['full_check', 'video_id', 'duration'];
                $table = 'video_progress';
                $getData = getData($table, $select, $where);
                // if (isset($updateUserAboutme) && $updateUserAboutme['status'] == TRUE) {
                return json_encode(['code' => 200, 'title' => __('response.successfully_updated'), "message" => __('response.about_me_updated_successfully'), "icon" => "success", "data" => $getData]);
                // }
                // return json_encode(['code' => 404, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_login_again_try'), 'records' => '', 'remark' => 'warning']);


            } else {
                return json_encode(['code' => 201, 'title' => __('response.user_not_available'), 'message' => __('response.user_not_exit'), 'remark' => 'warning']);
            }
        }
    }






    public function redirectToGoogle($role)
    {
        // session::put('roleGoogle',$role);
        $scopes = [
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/user.birthday.read',
            'https://www.googleapis.com/auth/user.gender.read',
            'https://www.googleapis.com/auth/user.phonenumbers.read',
            'https://www.googleapis.com/auth/user.addresses.read'
        ];

        $redirectUrl = Socialite::driver('google')
            ->scopes($scopes) // Pass the scopes to the scopes method
            ->with(['state' => 'role_id=' . $role])
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return redirect($redirectUrl);
    }



    public function handleGoogleCallback(Request $request)
    {


        if ($request->has('error')) {
            // Handle the error
            $error = $request->input('error');
            $errorDescription = $request->input('error_description', 'No description provided');

            return redirect()->route('login')->withErrors(['msg' => 'Google OAuth process was canceled or an error occurred.']);
        }

        $googleUser = Socialite::driver('google')->stateless()->user();

        parse_str($request->input('state'), $state);
        $role_id = $state['role_id'] ?? null;

        $client = new GoogleClient();
        $client->setAccessToken($googleUser->token);
        $peopleService = new PeopleService($client);
        $person = $peopleService->people->get('people/me', [
            'personFields' => 'birthdays,genders,phoneNumbers,addresses'
        ]);

        $birthdays = $person->getBirthdays();
        $birthday = null;
        if (!empty($birthdays)) {
            $birthday = $birthdays[0]->getDate(); // Assuming the first birthday is the correct one
            $datofbirth = $birthday->getYear() . '-' . $birthday->getMonth() . '-' . $birthday->getDay();
        }
        $phoneNumber = null;
        $phoneNumbers = $person->getPhoneNumbers();
        $firstThreeCharacters = '+91';
        if (!empty($phoneNumbers)) {
            $phoneNumber = $phoneNumbers[0]->getValue();
            $phoneNumber = str_ireplace(' ', '', $phoneNumber);
            $canonicalForm = $phoneNumbers[0]->getCanonicalForm();
            $firstThreeCharacters = substr($canonicalForm, 0, 3);
        }
        $address = null;
        $addresses = $person->getAddresses();
        if (!empty($addresses)) {
            $address = $addresses[0]->getFormattedValue();
        }
        $gender = null;
        $genders = $person->getGenders();
        if (!empty($genders)) {
            $gender = $genders[0]->getValue();
        }
        $user = User::where('email', $googleUser->email)->first();
        $role_id = 'user';
        $timestamp = Carbon::now('Europe/Malta')->format('Y-m-d H:i:s');
        // if ($role_id === 'admin') {
        // $url = 'admin/dashboard';
        // } else if ($role_id === 'user') {
        $url = 'student/dashboard';
        // } else if ($role_id === 'instructor') {
        //     $url = 'instructor/dashboard';
        // } else if ($role_id === 'institute') {
        //     $url = 'institute/dashboard';
        // }

        if (!empty($googleUser->user['family_name'])) {
            if (!$user) {

                $userDocs = User::create(['name' => $googleUser->user['given_name'], 'last_name' => $googleUser->user['family_name'], 'email' => $googleUser->email, 'password' =>  \Hash::make(rand(100000, 999999)), 'last_seen' => $timestamp, 'last_session_ip' => '', 'user_agent' => '', 'role' => $role_id, 'mob_code' => $firstThreeCharacters, 'phone' => $phoneNumber]);
                if (isset($userDocs->id)) {
                    StudentDocument::create(['student_id' => $userDocs->id]);
                    StudentProfile::create(['student_id' => $userDocs->id, 'gender' => $gender, 'dob' => $datofbirth, 'address' => $address]);
                }
            }
        } else {

            return redirect()->route('login')->withErrors(['msg' => 'Last Name is Mandatory.']);
        }


        $message = ['message' => 'Enroll Successfully', 'type' => 'success'];

        $userDocs = User::where('email', $googleUser->email)->first();

        $user = User::find($userDocs->id); // Retrieve the user object

        Auth::login($user);

        return redirect()->intended($url)->with($message);
    }

    public function verifyMail($id)
    {

        if (!empty($id)) {
            $id =  Crypt::decrypt($id);
            $is_exist =   is_exist('users', ['id' => $id]);
            if ($is_exist === 1) {
                $verifidData =   User::where(['id' => $id])->first();
                if ($verifidData->email_verified_at != '') {
                    return redirect('page-expired');
                }

                $mobileNo = $verifidData->mob_code . ' ' . $verifidData->phone;
                $registrationDate = $verifidData->created_at;

                $verifid =   User::where(['id' => $id])->update(['email_verified_at' => now()]);

                $recipients = [$verifidData->email, env('RECIPIENT_EMAIL')];

                $unsubscribeRoute = url('/unsubscribe/' . base64_encode($verifidData->email));

                mail_send(1, ['#Name#', '#Username#', '#unsubscribeRoute#'], [$verifidData->name . " " . $verifidData->last_name, $verifidData->email, $unsubscribeRoute], $verifidData->email);

                mail_send(53, ['#Name#', '#UserName#', '#MobileNo#', '#RegistrationDate#', '#unsubscribeRoute#'], [$verifidData->name . " " . $verifidData->last_name, $verifidData->email, $mobileNo, $registrationDate, $unsubscribeRoute], env('RECIPIENT_EMAIL'));


                if ($verifid === 1) {
                    $url = 'email-verified';
                    session(['statusEmail' => $verifidData->email]);
                    return redirect()->intended($url)->with('statusEmail', $verifidData->email);
                    return redirect('email-verified')->with('msg', __('response.you_are_Successfully_verified'))->with('status', 'true');
                } else {
                    return redirect('email-verified')->with('msg', __('response.unable_to_verify'))->with('status', 'false');
                }
            } else {
                return redirect('email-verified')->with('msg', __('response.cart.something_went_wrong'))->with('status', 'false');
            }
        } else {
            return redirect('email-verified')->with('msg', __('response.link_has_been_expired'))->with('status', 'false');
        }
    }


    public function thankYouVerification($email)
    {

        return view('frontend.thank-you-verification', compact('email'));
    }

    public function StudentLogin(Request $request)
    {

        $email = isset($request->email) ? base64_decode($request->email) : '';
        $form_data = isset($request->form_data) ? base64_decode($request->form_data) : '';
        $intended_action_cart = isset($request->intended_action_cart) ? base64_decode($request->intended_action_cart) : '';
        $intended_action_wishlist = isset($request->intended_action_wishlist) ? base64_decode($request->intended_action_wishlist) : '';



        $user = User::where('email', $email)->first();
        $status = isset($request->status) ? $request->status : '';
        if ($user) {
            Session::forget('registered');

            Auth::login($user);
            if ($request->status == 'emailverified') {

                if ($intended_action_cart != "null" && $intended_action_cart != "") {
                    $response = App::call('App\Http\Controllers\CartController@addtocart');
                    $decodedResponse = json_decode($response, true);
                    $url = 'index';
                    session()->forget('intended_action_cart');
                    return json_encode(['code' => 200, 'title' => $decodedResponse['title'], 'message' => $decodedResponse['message'], "icon" => "success", "data" => $url]);
                } else if ($intended_action_wishlist != "null" && $intended_action_wishlist != "") {
                    $response = App::call('App\Http\Controllers\CartController@addWishlist');
                    $decodedResponse = json_decode($response, true);
                    $url = 'index';
                    session()->forget('intended_action_wishlist');
                    return json_encode(['code' => 200, 'title' => $decodedResponse['title'], 'message' => $decodedResponse['message'], "icon" => "success", "data" => $url]);
                } else if ($form_data != "null" && $form_data != '') {
                    $formData =  json_decode($form_data);
                    if (session()->has('form_data')) {
                        $url = 'checkout';
                        return json_encode(['code' => 200, 'title' => "", 'message' => '', "icon" => "success", "data" => $url, 'formData' => $formData]);
                    } else {
                        $url = 'index';
                        return json_encode(['code' => 200, 'title' => "", 'message' => '', "icon" => "success", "data" => $url]);
                    }
                } else {
                    $url = 'index';
                    return json_encode(['code' => 200, 'title' => "", 'message' => '', "icon" => "success", "data" => $url]);
                }
            } else {
                $url = 'index';
                return redirect()->intended($url);
            }
        }
    }


    public function StudentVerified(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!empty($user['email_verified_at']) && $user['email_verified_at'] != '' && $user['email_verified_at'] != null) {
            Session::forget('registered');

            Auth::login($user);

            $url = 'index';

            if (session()->has('intended_action_cart')) {
                $response = App::call('App\Http\Controllers\CartController@addtocart');
                $decodedResponse = json_decode($response, true);
                $url = 'index';
                session()->forget('intended_action_cart');
                return json_encode(['code' => 200, 'title' => $decodedResponse['title'], 'message' => $decodedResponse['message'], "icon" => "success", "data" => $url]);
            } else if (session()->has('intended_action_wishlist')) {
                $response = App::call('App\Http\Controllers\CartController@addWishlist');
                $decodedResponse = json_decode($response, true);
                $url = 'index';
                session()->forget('intended_action_wishlist'); // Clear the session after use
                return json_encode(['code' => 200, 'title' => $decodedResponse['title'], 'message' => $decodedResponse['message'], "icon" => "success", "data" => $url]);
            } else if (session()->has('form_data')) {
                $formData =  session('form_data');
                $url = 'checkout';
                return json_encode(['code' => 200, 'title' => "", 'message' => '', "icon" => "success", "data" => $url, 'formData' => $formData]);
            } else {
                return json_encode(['code' => 200, 'title' => "", 'message' => '', "icon" => "success", "data" => $url]);
            }
        } else {

            return json_encode(['code' => 201, 'title' => __('response.verify_email'), 'message' =>  __('response.please_verify_your_email'), 'remark' => 'warning']);
        }
    }
    public function EmentorfilesUpload($file, $DocCategory, $records)
    {

        if (isset($DocCategory) && !empty($DocCategory) && isset($file) && !empty($file)) {
            $userDocs =  $this->EmentorProfile->getCurrentEmentorDocInfo();

            $user_id = $userDocs['user']['id'];

            if ($DocCategory === 'PROFILE') {
                $old_file = $userDocs['user']['photo'];
            } elseif ($DocCategory === 'PROFILE_BACKGORUND') {
                $old_file = $userDocs['user']['profile_background'];
            }

            if (isset($userDocs['folder_name']) && !empty($userDocs['folder_name'])) {
                $folder = $userDocs['folder_name'];
            } else {

                $folder = "Student_" . time() . "_" . $userDocs['user']['name'];

                $makeFolder = File::makeDirectory(public_path("storage/ementorDOcs/" . $folder), $mode = 0777, true, true);
                if (!isset($makeFolder) && $makeFolder === 0 && is_numeric($makeFolder)) {
                    return false;
                }
            }

            try {
                $docUpload = UploadFiles($file, 'ementorDocs/' . $folder, $old_file);
                $updateUser  = FALSE;
                if ($docUpload['status'] === TRUE) {
                    if ($DocCategory === 'PROFILE') {
                        $where = ['id' => $user_id];
                        $select = [
                            'photo' => $docUpload['url'],
                        ];
                        $updateUser = processData(['users', 'id'], $select, $where);

                        return ['code' => 200, 'url' => $docUpload['url'], 'old_url' => $old_file];
                    } elseif ($DocCategory === 'PROFILE_BACKGORUND') {
                        $where = ['id' => $user_id];
                        $select = [
                            'profile_background' => $docUpload['url'],
                        ];
                        $updateUser = processData(['users', 'id'], $select, $where);
                        return ['code' => 200, 'url' => $docUpload['url'], 'old_url' => $old_file];
                    }
                    if ($updateUser['status']) {
                        return ['code' => 200];
                    }
                    return ['code' => 202];
                }
                return ['code' => 203];
            } catch (\Exception $th) {

                return ['code' => 404];
            }
        }
        return ['code' => 404];
    }
    public function InstitutefilesUpload($file, $DocCategory, $records)
    {

        if (isset($DocCategory) && !empty($DocCategory) && isset($file) && !empty($file)) {

            $where = ['institute_id' => auth()->user()->id];
            $instituteDocs = $this->instituteProfile->getInstituteProfile($where);

            $user_id = $instituteDocs[0]['user']['id'];
            $old_file = '';
            if ($DocCategory === 'PROFILE') {
                $old_file = $instituteDocs[0]['user']['photo'];
            }

            if (isset($$instituteDocs[0]['user']['folder_name']) && !empty($$instituteDocs[0]['user']['folder_name'])) {
                $folder = $$instituteDocs[0]['user']['folder_name'];
            } else {

                $folder = "Institute_" . time() . "_" . $instituteDocs[0]['user']['name'];

                $makeFolder = File::makeDirectory(public_path("storage/instituteDocs/" . $folder), $mode = 0777, true, true);
                if (!isset($makeFolder) && $makeFolder === 0 && is_numeric($makeFolder)) {
                    return false;
                }
            }

            try {
                $docUpload = UploadFiles($file, 'instituteDocs/' . $folder, $old_file);
                $updateUser  = FALSE;
                if ($docUpload['status'] === TRUE) {

                    if ($DocCategory === 'PROFILE') {

                        $where = ['institute_id' => $user_id];
                        $select = [
                            'folder_name' => $folder,
                        ];
                        $updateEmentorProfile = processData(['institute_profile_master', 'id'], $select, $where);


                        $where = ['id' => $user_id];
                        $select = [
                            'photo' => $docUpload['url'],
                        ];
                        $updateUser = processData(['users', 'id'], $select, $where);

                        return ['code' => 200, 'url' => $docUpload['url'], 'old_url' => $old_file];
                    }
                    if ($updateUser['status']) {
                        return ['code' => 200];
                    }
                    return ['code' => 202];
                }
                return ['code' => 203];
            } catch (\Exception $th) {

                return ['code' => 404];
            }
        }
        return ['code' => 404];
    }

    public function mobileNumberVerification($email)
    {
        if (isset($email) && !empty($email)) {
            $user = DB::table('users')->where('email', base64_decode($email))->first();
            if (isset($user->email_verified_at) && !empty($user->email_verified_at) && isset($user->otp_verified_at) && !empty($user->otp_verified_at)) {
                return redirect()->route('dashboard');
            } else {
                return view('frontend/mobile-number-verification', compact('email'));
            }
        }
    }

    public function verifyOTP(Request $request)
    {
        // $messageId = $request->input('messageId');
        $email = base64_decode($request->input('email'));
        $otpArray = $request->input('otp');
        $otp = implode('', $otpArray);
        if (session('verification_code')) {
            $storedOtp = decrypt(session('verification_code'));
        } else {
            $storedOtp = null;
        }
        // $storedOtp = decrypt(session('verification_code'));
        if ($otp == $storedOtp) {
            session()->forget('verification_code');
            $user = DB::table('users')->where('email', $email)->first();
            if (isset($user) && !empty($user)) {
                if ($user->email_verified_at != null || !empty($user->email_verified_at)) {
                    $updateData = [
                        'otp_verified_at' => now(),
                    ];
                    $user = DB::table('users')->where('email', $email)->update($updateData);
                    return redirect()->route('index');
                } else {
                    $url = 'email-id-verification';
                    $email = $email;
                    $updateData = [
                        'otp_verified_at' => now(),
                    ];
                    $user = DB::table('users')->where('email', $email)->update($updateData);

                    $userData = DB::table('users')
                        ->where('email', $email)
                        ->where('role', 'user')
                        ->first();

                    $dyc_id = Crypt::encrypt($userData->id);
                    $email = $email;
                    $link =  env('APP_URL') . "/verfiy-mail/" . $dyc_id;
                    $unsubscribeRoute = url('/unsubscribe/' . base64_encode($email));
                    mail_send(32, ['#Name#', '#Link#', '#unsubscribeRoute#'], [$userData->name . " " . $userData->last_name, $link, $unsubscribeRoute], $email);
                    return redirect()->intended($url)->with('statusEmail', $email);
                }
            }
        } else {
            // $url ='email-id-verification';
            $email = $email;
            return redirect()->back()->withErrors(['error' => 'Invalid OTP.']);
        }
    }

    public function resendOTP($mobile, $email)
    {
        $ipAddress = RequestFacade::ip();
        $key = 'rate_limit:' . $ipAddress;
        $maxAttempts = 1;
        $decayMinutes = 5;
        $seconds = RateLimiter::availableIn($key);
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;

            return json_encode([
                'code' => 429,
                'message' => __('response.too_many_requests') . $minutes .  __("response.minute_and")  . $remainingSeconds . __('response.second')
            ]);
        }

        if (isset($mobile) && !empty($mobile) && isset($email) && !empty($email)) {
            if (session()->has('verification_code')) {
                $randomNumber = decrypt(session('verification_code'));
            } else {
                $randomNumber = rand(1000, 9999);
            }


            $OTPResponse = $this->user->sendOTP($mobile, $randomNumber, $key);
            if (is_array($OTPResponse) && !empty($OTPResponse)) {
                if (isset($OTPResponse['data']['Success']) && $OTPResponse['data']['Success'] === 'True') {
                    $messageId = $OTPResponse['data']['MessageUUID'];
                    $verifyOTPResponse = $this->user->sendOtpApiRequest('GET', $messageId);
                    if (is_array($verifyOTPResponse) && !empty($verifyOTPResponse) && $verifyOTPResponse['code'] == 200) {
                        session(['verification_code' => encrypt($randomNumber)]);
                        $emai = $email;
                        return json_encode(['code' => 200]);
                    } else {
                        return redirect()->intended('student-enrollment')->withErrors(['error' => 'Invalid Mobile Number.']);
                    }
                } else {

                    return json_encode([
                        'code' => 429,
                        'message' => __('response.too_many_requests') . $minutes .  __("response.minute_and")  . $remainingSeconds . __('response.second')
                    ]);
                }
            } else {
                return json_encode([
                    'code' => 429,
                    'message' => __('response.too_many_requests') . $minutes .  __("response.minute_and")  . $remainingSeconds . __('response.second')
                ]);
            }
        }
        return ['code' => 404];
    }

    public function changeMobileNumber(Request $request)
    {

        $ipAddress = RequestFacade::ip();
        $key = 'rate_limit:' . $ipAddress;
        $maxAttempts = 1;
        $decayMinutes = 5;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;

            return redirect()->back()->with('rate_limit_error', __('response.too_many_requests') . $minutes .  __("response.minute_and")  . $remainingSeconds . __('response.second'));
        }
        $seconds = RateLimiter::availableIn($key);
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        $email = isset($request->email) ? htmlspecialchars($request->input('email')) : '';
        $mob_code = isset($request->mob_code) ? htmlspecialchars($request->input('mob_code')) : '';
        $mobile = isset($request->phone) ? htmlspecialchars($request->input('phone')) : '';


        $exists = is_exist('users', ['mob_code' => $mob_code, 'phone' => $mobile]);
        if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {
            return redirect()->back()->with('error', __('response.mobile_no_exit'));
        }

        try {
            $data = $request->validate([
                'mob_code' => ['required', 'string', 'min:1'],
                'phone' => ['required', 'string', 'min:6', 'max:20', 'unique:users,phone'],
            ], [
                'phone.required' => 'Please select country code and enter mobile number.',
                'mob_code.required' => 'Please select country code.',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $select = [
            'mob_code' => $mob_code,
            'phone' => $mobile,
        ];

        $where = ['email' => base64_decode($email)];
        $updateUser = processData(['users', 'id'], $select, $where);

        if (isset($updateUser) && !is_array($updateUser) && $updateUser === FALSE) {
            return redirect()->back();
        } else {
            if (session()->has('verification_code')) {
                $randomNumber = decrypt(session('verification_code'));
            } else {
                $randomNumber = rand(1000, 9999);
            }
            $mobileWithCode = ltrim($mob_code . $mobile, '+');

            $OTPResponse = $this->user->sendOTP($mobileWithCode, $randomNumber, $key);
            if (is_array($OTPResponse) && !empty($OTPResponse)) {
                if (isset($OTPResponse['data']['Success']) && $OTPResponse['data']['Success'] === 'True') {
                    $messageId = $OTPResponse['data']['MessageUUID'];
                    $verifyOTPResponse = $this->user->sendOtpApiRequest('GET', $messageId);
                    if (is_array($verifyOTPResponse) && !empty($verifyOTPResponse) && $verifyOTPResponse['code'] == 200) {
                        session(['verification_code' => encrypt($randomNumber)]);
                        $email = $email;
                        return redirect()->route('mobile-number-verification', ['email' => $email]);
                    } else {
                        return redirect()->back()->with('rate_limit_error', __('response.too_many_requests') . $minutes .  __("response.minute_and")  . $remainingSeconds . __('response.second'));
                    }
                } else {
                    return redirect()->back()->with('rate_limit_error', __('response.too_many_requests') . $minutes .  __("response.minute_and")  . $remainingSeconds . __('response.second'));
                }
            } else {
                return redirect()->back()->with('rate_limit_error', __('response.too_many_requests') . $minutes .  __("response.minute_and")  . $remainingSeconds . __('response.second'));
            }
        }
    }

    public function unsubscribe($email)
    {
        $email = isset($email) ? base64_decode($email) : '';
        $userData = getData('users', ['id'], ['email' => $email]);
        $exists = is_exist('unsubscribe_emails', ['email' => $email]);
        if (isset($exists) && is_numeric($exists) && $exists == 0) {
            $select = [
                'user_id' => isset($userData[0]) ? $userData[0]->id : '0',
                'email' => $email,
                'created_at' => now(),
                'updated_at' =>  now(),
            ];
            $where = [];
            $updateCourse = processData(['unsubscribe_emails', 'id'], $select, $where);
        }
        return view('frontend/unsubscribe');
    }


    public function saveEnglishVideoProgress(Request $req)
    {

        if ($req->isMethod('POST') && $req->ajax() && Auth::check()) {

            $user_id = Auth::user()->id;
            $duration = isset($req->duration) ? ($req->input('duration')) : '';
            $video_id = isset($req->video_id) ? base64_decode($req->input('video_id')) : '';
            $whereCourse = ['user_id' => $user_id, 'video_id' => $video_id];
            $exists =   is_exist('english_video_progress', $whereCourse);
            if (isset($exists) && is_numeric($exists) && $exists == 0) {
                $selectData = [
                    'user_id' => $user_id,
                    'video_id' => $video_id,
                    'duration' => $duration,
                    'full_check' => 'Yes'
                ];

                $updateUserAboutme = processData(['english_video_progress', 'id'], $selectData, $whereCourse);

                if (isset($updateUserAboutme) && $updateUserAboutme['status'] == TRUE) {
                    return json_encode(['code' => 200, 'title' => __('response.successfully_updated'), "message" => __('response.about_me_updated_successfully'), "icon" => "success", "data" => $duration]);
                }
            }
            return json_encode(['code' => 404, 'title' => __('response.cart.something_went_wrong'), 'message' => __('response.please_login_again_try'), 'records' => '', 'remark' => 'warning']);
        } else {
            return json_encode(['code' => 201, 'title' => __('response.user_not_available'), 'message' => __('response.user_not_exit'), 'remark' => 'warning']);
        }
    }

    //video Progress
    public function showlastwatch($courseId, $videoId)
    {
        $userId = Auth::user()->id;


        $decodedCourseId = base64_decode($courseId, true);

        if ($decodedCourseId === false) {
            return response()->json(['error' => 'Invalid course ID'], 400);
        }

        $progress = DB::table('video_progress')->where(['user_id' => $userId,'course_id' => $decodedCourseId,'video_id' => $videoId,])->first();
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

    public function storelastwatch(Request $req)
    {
        $user_id = auth()->id();
        $course_id = $req->has('course_id') ? base64_decode($req->input('course_id')) : '';
        $video_id = isset($req->video_id) ? $req->input('video_id') : '';
        $newWatchTime = isset($req->watchTime) ? $req->input('watchTime', 0) : '';
        $duration = isset($req->duration) ? ($req->input('duration')) : '';
        $lastRecordedTime = isset($req->lastRecordedTime) ? $req->input('lastRecordedTime', 0) : '';
        $watched_segments = isset($req->watched_segments) ? ($req->input('watched_segments')) : '';

        $progressData = getData('video_progress', ['last_watch_time', 'watchedRanges', 'full_check','last_record_time'], [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'video_id' => $video_id
        ]);

       if (!empty($progressData) && $progressData[0]->full_check == 'Yes') {
            return response()->json(['status' => false, 'message' => 'Already watched full video'], 200);
        }
        $lastWatchTime = (!empty($progressData) && isset($progressData[0]->last_watch_time)) ? $progressData[0]->last_watch_time : 0;
        $lastRecordedTimestore = (!empty($progressData) && isset($progressData[0]->last_record_time)) ? $progressData[0]->last_record_time : 0;
        $existingSegments = [];
        if (!empty($progressData) && !empty($progressData[0]->watchedRanges)) {
                $existingSegments = json_decode($progressData[0]->watchedRanges, true);
        }

        $newSegments = json_decode($watched_segments, true);
        function mergeRanges($ranges)
        {
            usort($ranges, function ($a, $b) {
                return $a['start'] <=> $b['start'];
            });

            $merged = [];
            foreach ($ranges as $range) {
                if (empty($merged)) {
                    $merged[] = $range;
                } else {
                    $last = &$merged[count($merged) - 1];
                    if ($range['start'] <= $last['end']) {
                        $last['end'] = max($last['end'], $range['end']);
                    } else {
                        $merged[] = $range;
                    }
                }
            }
            return $merged;
        }
        function getTotalWatchedTime($segments)
        {
            $total = 0;
            foreach ($segments as $range) {
                $total += $range['end'] - $range['start'];
            }
            return $total;
        }
        $allSegments = array_merge($existingSegments, $newSegments);
        $mergedSegments = mergeRanges($allSegments);
        $watched_segments = json_encode($mergedSegments);


        if ($newWatchTime <= 0 || $newWatchTime < 5) {
            return response()->json(['status' => false, 'message' => 'Watch time too low to update'], 400);
        }
        $whereCourse = ['user_id' => $user_id, 'course_id' => $course_id, ['course_expired_on', '>', now()]];
        $selectCourse = ['id'];
        $tableCourse = 'student_course_master';
        $getCourse = getData($tableCourse, $selectCourse, $whereCourse, '', 'id', 'desc');

        $finalWatchTime = $lastWatchTime + $newWatchTime;

        $tableInfo = ['video_progress', 'id'];

        $data = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'video_id' => $video_id,
            'last_watch_time' => $finalWatchTime,
            'student_course_master_id' => $getCourse[0]->id,
            'duration' => $duration,
            "watchedRanges" =>  $mergedSegments,
            'created_at'=>$this->time
        ];

    
        $totalWatchedTime = getTotalWatchedTime($mergedSegments);
        $videoDuration = (float) $duration;
        $buffer = 10;
        if ($totalWatchedTime >= ($videoDuration - $buffer)) {
            $data['full_check'] = "Yes";
        }
        if ($lastRecordedTimestore < ($videoDuration - $buffer)) {
            $data['last_record_time'] = $lastRecordedTime;
        }
        $where = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'video_id' => $video_id,
            'student_course_master_id' => $getCourse[0]->id
        ];

        $result = processData($tableInfo, $data, $where);
        $today = Carbon::today()->format('Y-m-d');
        $previous = DB::table('video_progress_daily')
        ->where('user_id', $user_id)
        ->where('course_id', $course_id)
        ->where('student_course_master_id', $getCourse[0]->id)
        ->whereDate('date', '<', now()->format('Y-m-d')) // optional: only dates <= today
        ->orderBy('date', 'desc')
        ->orderBy('id', 'desc') // ensures latest entry if multiple on same date
        ->first();
        $todayWatched = $finalWatchTime;  
        $previousTotal = $previous ? floatval($previous->total_seconds) : 0;
        $todayWatched = $finalWatchTime - $previousTotal;
        $data = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'total_seconds' => $todayWatched,
            'student_course_master_id' => $getCourse[0]->id,
            'created_at'=> $this->time,
            'date' => $today
        ];
        $where = [
            'user_id' => $user_id,
            'course_id' => $course_id,
            'student_course_master_id' => $getCourse[0]->id,
            'date'=> $today
        ];
        $updateUser = processData(['video_progress_daily', 'id'], $data, $where);
 
        $fullcheck = isset($data['full_check']) ? $data['full_check'] : '';
        if ($result && $result['status']) {
            return response()->json(['status' => true, 'message' => 'Progress saved', 'id' => $result['id'], 'full_check' => $fullcheck]);
        }


        return response()->json(['status' => false, 'message' => 'Unable to save progress'], 500);
    }
    public function checkDailyLimit($courseId)
    {
        $userId = auth()->id();
        $courseId = base64_decode($courseId);

        $today = Carbon::today()->format('Y-m-d');
        $DAILY_LIMIT = env('dailyLimitVideo');
        $whereCourse = ['user_id' => $userId, 'course_id' => $courseId, ['course_expired_on', '>', now()]];
        $selectCourse = ['id'];
        $tableCourse = 'student_course_master';
        $getCourse = getData($tableCourse, $selectCourse, $whereCourse, '', 'id', 'desc');

        $daily = DB::table('video_progress_daily')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('student_course_master_id',$getCourse[0]->id)
            ->where('date', $today)
            ->first();

            $limitReached = $daily && $daily->total_seconds >= $DAILY_LIMIT;

            return response()->json([
                'limitReached' => $limitReached,
                'watched_today' => $daily->total_seconds ?? 0
            ]);
    }


    public function downloadCertificate($id){
           $user_id = auth()->id();
            $encode_id=base64_decode($id);
            $certificate = getData('student_course_master',['attendance_certificate'],['user_id'=> $user_id,'id'=> $encode_id],1);

            $certificateimage = $certificate[0]->attendance_certificate;
            if (!$certificateimage || !Storage::disk('public')->exists($certificateimage)) {
                return back()->with('error', 'Certificate not found.');
            }

            $imagePath = storage_path('app/public/' . $certificateimage);
            [$width, $height] = getimagesize($imagePath);
            $pdfWidth = $width * 0.75;
            $pdfHeight = $height * 0.75;

            $imageBase64 = base64_encode(file_get_contents($imagePath));
            $pdf = PDF::loadView('frontend.student.certificate-pdf', [
                'image' => $imageBase64,
            ])->setPaper([0, 0, $pdfWidth, $pdfHeight]);

            return $pdf->download('attendance_certificate.pdf');
    }
}
