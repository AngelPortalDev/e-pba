<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentDocument;
use App\Models\StudentProfile;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use App\Models\InstituteProfile;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Rules\ValidEmailDomain;
use File;
use App\Rules\WordCount;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function store(Request $request): RedirectResponse
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->has('honeypot') && !empty($request->input('honeypot'))) {
            // Potential bot detected, handle accordingly
            // return response()->json(['error' => 'Bot detected!'], 400);
            return redirect()->back()->with('error', 'Bot detected!');
        }
        // return base64_decode($request->role);
        $role = isset($request->role) ? base64_decode($request->role) : '';
        $name = isset($request->name) ? htmlspecialchars_decode($request->input('name')) : '';
        $last_name = isset($request->last_name) ? htmlspecialchars_decode($request->input('last_name')) : '';
        $email = isset($request->email) ? htmlspecialchars($request->input('email')) : '';
        $mob_code = isset($request->mob_code) ? htmlspecialchars($request->input('mob_code')) : '';
        $mobile = isset($request->mobile) ? htmlspecialchars($request->input('mobile')) : '';
        $university_code = isset($request->university_code) ? htmlspecialchars($request->input('university_code')) : '';
        $apply_dba = 'No';
        $apply_dba_check = isset($request->apply_dba) ? htmlspecialchars($request->input('apply_dba')) : '';
        if($apply_dba_check == 'on'){
            $apply_dba = 'Yes';
        }


        // User Agent Details
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();
        $timestamp = Carbon::now('Europe/Malta')->format('Y-m-d H:i:s');

        $checkIp =  block_ipaddress();
        if($checkIp == TRUE){
            abort(403, 'Access denied. Your IP address has been blocked.');
        }
        // check bots
        // $bots = ['bot', 'crawl', 'spider', 'curl', 'wget'];
        $bots = ['bot'];

        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                \Log::info('Blocked bot: ' . $userAgent);
                return response('Access denied. You appear to be a bot.', 403);
            }
        }

        // rate limiter
        $key = 'rate_limit:' . $ipAddress;

        if (isset($role) && !empty($role)) {
            $url = '';
            if ($role === 'admin') {
                $url = '/admin/dashboard';
            } elseif ($role === 'user') {
                $url = '';
            } elseif ($role === 'instructor') {
                $url = '/instructor/dashboard';
            } elseif ($role === 'institute') {
                $url = '/institute/dashboard';
            } else {
                return redirect()->intended('index')->with(['message' => 'Something Went Wrong', 'type' => 'error']);
            }

            $exists = is_exist('users', ['mob_code' => $mob_code,'phone'=>$mobile]);
            if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {
                return redirect()->back()->with('mob_code', 'Mobile number already exists')->withInput();
            }
            try {
                $validationRules = [
                    'name' => ['required', 'string', 'max:20','regex:/^[a-zA-Z\s]+$/'],
                    'last_name' => ['required', 'string', 'max:255','regex:/^[a-zA-Z\s]+$/'],
                    'mob_code' => ['required', 'string', 'min:1'],
                    'mobile' => ['required', 'string', 'min:6', 'max:15'],
                    'tnc' => ['required', 'accepted'],
                    'email' => ['bail','required', 'string', 'email', 'max:255', 'regex:/(.+)@(.+)\.(.+)/i', 'unique:' . User::class,new ValidEmailDomain(),],
                    'password' => ['required', 'string', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
                    'password_confirmation' => ['required', 'same:password'],
                ];

                // Conditionally add the `university_code` validation rule
                if (isset($university_code) && !empty($university_code)) {
                    $validationRules['university_code'] = [
                        function ($attribute, $value, $fail) {
                            if (!InstituteProfile::where('university_code', $value)->where('is_approved','1')->where('status','0')->exists()) {
                                $fail('The provided university code does not exist.');
                            }
                        },
                    ];
                }

                // Perform the validation
                $data = $request->validate($validationRules, [
                    'name.required' => 'Please enter your first name.',
                    'name.max'=>'First name should be less than 20 characters.',
                    'name.regex' => 'The name may only contain letters and spaces.',
                    'last_name.required' => 'Please enter your last name.',
                    'last_name.max'=>'Last name should be less than 20 characters',
                    'last_name.regex' => 'The last name may only contain letters and spaces.',
                    'email.required'=> 'Please enter a valid email address.',
                    'mobile.required' => 'Please select country code and enter mobile number.',
                    'mob_code.required' => 'Please select country code.',
                    'password.required' => 'Create a strong alphanumeric password like Abc@1234.',
                    'password.min'=> 'Your password must be at least 8 characters, like Abc@1234.',
                    'password.max'=>'Password should be less than 20 characters.',
                    'password_confirmation.same' => 'The passwords you entered do not match.',
                    'password_confirmation' => 'Please confirm your password.',
                    'tnc.required'=>'You must accept our Terms of Service and Privacy Policy to create an account.',
                    'mobile.min' => 'The mobile must be at least 6 digits.',
                    'mobile.max'=>'The mobile number should be less than 15 digits.',
                ]);

                // Validation passed, continue processing the data...
            } catch (ValidationException $e) {

                return redirect()->back()->withErrors($e->errors())->withInput();
            }

            // $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            //     'secret' => env('GOOGLE_SECRET_KEY'),
            //     'response' => $request->input('g-recaptcha-response'),
            //     ]);

            // $responseBody = json_decode($response->getBody());

            if ((request()->getHost() === 'www.eascencia.mt' || request()->getHost() === 'www.dev.eascencia.mt') && env('GOOGLE_SECRET_KEY')) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => env('GOOGLE_SECRET_KEY'),
                    'response' => $request->input('g-recaptcha-response'),
                ]);

                $responseBody = json_decode($response->getBody());

                if (!$responseBody->success) {
                    return back()->withErrors(['captcha' => 'Invalid reCAPTCHA response. Please try again.']);
                }
            } else {
                $responseBody = (object)['success' => true];
            }

            if ($responseBody->success) {

                // $mobileWithCode = ltrim($mob_code . $mobile, '+');
                // $randomNumber = rand(1000, 9999);
                // $OTPResponse = $this->user->sendOTP($mobileWithCode, $randomNumber, $key);

                // if (is_array($OTPResponse) && !empty($OTPResponse) && $OTPResponse['code'] === 200) {
                //     if (isset($OTPResponse['data']['Success']) && $OTPResponse['data']['Success'] === 'True') {
                //         $messageId = $OTPResponse['data']['MessageUUID'];
                //         $verifyOTPResponse = $this->user->sendOtpApiRequest('GET', $messageId);
                //         if (is_array($verifyOTPResponse) && !empty($verifyOTPResponse) && $verifyOTPResponse['code'] !== 200) {
                //             return redirect()->intended('student-enrollment')->withErrors(['error' => 'Invalid Mobile Number.']);
                //         }
                //     }else{
                //         return redirect()->intended('login-view')->withErrors(['error' => 'Something Went Wrong.']);
                //     }
                // }else{
                //     $seconds = RateLimiter::availableIn($key);
                //     $minutes = floor($seconds / 60);
                //     $remainingSeconds = $seconds % 60;
                //     return redirect()->back()->with('rate_limit_error', 'Too many requests. Please try again in ' . $minutes . ' minute and ' . $remainingSeconds . ' second.');
                // }

                $data = [
                    'name' => $name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'mob_code' => $mob_code,
                    'phone' => $mobile,
                    'role' => $role,
                    'last_seen' => $timestamp,
                    'last_session_ip' => $ipAddress,
                    'user_agent' => $userAgent,
                    'password' => Hash::make($request->password),
                    'university_code'=>$university_code,
                    'apply_dba'=>$apply_dba
                ];

                if ((request()->getHost() === 'www.eascencia.mt')) {
                    if($data['role'] == 'user' &&  $apply_dba == 'No'){
                        $student = $this->user->createStudentOnZohoApiRequest($data);
                    }
                }

                $user = User::create($data);

                if (isset($user->id)) {
                    StudentDocument::create(['student_id' => $user->id]);
                    StudentProfile::create(['student_id' => $user->id]);
                }
                Session::put('registered', true);

                event(new Registered($user));

                $dashboardController = new DashboardController();
                $dashboardData = $dashboardController->getDashboardData();

                // Auth::login($user);

                // $message = ['message' => 'Enroll Successfully', 'type' => 'success'];

                // mail_send(1, ['#Name#', '#Username#', '#Password#'], [$name." ".$last_name, $email, $request->password], $email);
                // return redirect()->route('email-id-verification');
                $url ='email-id-verification';

                $dyc_id = Crypt::encrypt($user->id);
                $link =  env('APP_URL') . "/verfiy-mail/" . $dyc_id;
                $message = $email;
                mail_send(32, ['#Name#', '#Link#', '#unsubscribeRoute#'], [$user->name." ".$user->last_name, $link, $email], $email);
                // session(['verification_code' => encrypt($randomNumber)]);
                // $email = $email;
                // return redirect()->route('mobile-number-verification', ['email' => base64_encode($email)]);

                // return redirect()->route('mobile-number-verification', ['email' => base64_encode($email)]);


                return redirect()->intended($url)->with('statusEmail', $message);
                // return redirect()->intended($url);
            }else{
                $message = ['message' => 'Please Try Again', 'type' => 'error'];
                return redirect()->back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.']);
            }
            return redirect()->intended($url)->with($message);

            // return redirect(RouteServiceProvider::HOME);
        } else {
            return redirect()->intended('index')->with(['message' => 'Something Went Wrong', 'type' => 'error']);
            // return redirect()->back()->with(['message' => 'Something Went Wrong', 'type' => 'error']);
        }

    }


    public function instituteRegister(Request $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->has('honeypot') && !empty($request->input('honeypot'))) {
            // Potential bot detected, handle accordingly
            // return response()->json(['error' => 'Bot detected!'], 400);
            return redirect()->back()->with('error', 'Bot detected!');
        }
        // return base64_decode($request->role);
        $role = isset($request->role) ? base64_decode($request->role) : '';
        $name = isset($request->name) ? htmlspecialchars_decode($request->input('name')) : '';
        $email = isset($request->email) ? htmlspecialchars($request->input('email')) : '';
        $mob_code = isset($request->mob_code) ? htmlspecialchars($request->input('mob_code')) : '';
        $mobile = isset($request->mobile) ? htmlspecialchars($request->input('mobile')) : '';
        $website = isset($request->website) ? htmlspecialchars($request->input('website')) : '';
        $address = isset($request->address) ? htmlspecialchars_decode($request->input('address')) : '';
        $billing_city = isset($request->billing_city) ? htmlspecialchars_decode($request->input('billing_city')) : '';
        $billing_state = isset($request->billing_state) ? htmlspecialchars_decode($request->input('billing_state')) : '';
        $billing_country = isset($request->billing_country) ? htmlspecialchars_decode($request->input('billing_country')) : '';
        $apply_dba = 'No';
        $contact_person_name = isset($request->contact_person_name) ? htmlspecialchars_decode($request->input('contact_person_name')) : '';
        $contact_person_email = isset($request->contact_person_email) ? htmlspecialchars_decode($request->input('contact_person_email')) : '';
        $contact_person_mob_code = isset($request->contact_person_mob_code) ? htmlspecialchars_decode($request->input('contact_person_mob_code')) : '';
        $contact_person_mobile = isset($request->contact_person_mobile) ? htmlspecialchars_decode($request->input('contact_person_mobile')) : '';
        $contact_person_designation = isset($request->contact_person_designation) ? htmlspecialchars_decode($request->input('contact_person_designation')) : '';


        // User Agent Details
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();
        $timestamp = Carbon::now('Europe/Malta')->format('Y-m-d H:i:s');

        $checkIp =  block_ipaddress();
        if($checkIp == TRUE){
            abort(403, 'Access denied. Your IP address has been blocked.');
        }
        // check bots
        // $bots = ['bot', 'crawl', 'spider', 'curl', 'wget'];
        $bots = ['bot'];

        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                \Log::info('Blocked bot: ' . $userAgent);
                return response('Access denied. You appear to be a bot.', 403);
            }
        }

        // rate limiter
        $key = 'rate_limit:' . $ipAddress;

        if (isset($role) && !empty($role)) {
            $url = '';
            if ($role === 'admin') {
                $url = '/admin/dashboard';
            } elseif ($role === 'user') {
                $url = '';
            } elseif ($role === 'instructor') {
                $url = '/instructor/dashboard';
            } elseif ($role === 'institute') {
                $url = '/institute/dashboard';
            } else {
                return redirect()->intended('index')->with(['message' => 'Something Went Wrong', 'type' => 'error']);
            }

            $exists = is_exist('users', ['mob_code' => $mob_code,'phone'=>$mobile]);
            if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0) {
                return redirect()->back()->with('mob_code', 'Mobile number already exists')->withInput();
            }
            try {
                $validationRules = [
                    'name' => ['required', 'string', 'min:4', 'max:300','regex:/^(?=(?:.*[a-zA-Z]){2,})[a-zA-Z\s&\-\(\)]+$/'],
                    'mob_code' => ['required', 'string', 'min:1'],
                    'mobile' => ['required', 'string', 'min:6', 'max:15'],
                    'website' => ['required'],
                    'tnc' => ['required', 'accepted'],
                    // 'email' => ['required', 'string', 'email', 'max:255', 'regex:/(.+)@(.+)\.(.+)/i', 'unique:' . User::class],
                    'email' => ['bail','required','email','max:255',Rule::unique('users'),new ValidEmailDomain()],
                    'password' => ['required', 'string', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
                    'password_confirmation' => ['required', 'same:password'],
                    'contact_person_name' => ['required', 'string', 'max:100','regex:/^[a-zA-Z\s]+$/'],
                    'contact_person_email' => ['required', 'string', 'email', 'max:255', 'regex:/(.+)@(.+)\.(.+)/i', 'unique:' . InstituteProfile::class],
                    'contact_person_mob_code' => ['required'],
                    'contact_person_mobile' => ['required', 'string', 'min:6', 'max:15'],
                    'contact_person_designation' => ['required','max:100','regex:/^(?=(?:.*[a-zA-Z]){2,})[a-zA-Z\s&\-\(\)]+$/'],
                    'logo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:width=302,height=272'],
                    'photo_id' => ['required','mimes:jpg,jpeg,png,pdf', 'max:5120'],
                    'licence' => ['required','mimes:jpg,jpeg,png,pdf', 'max:5120'],
                    'address' => ['required', 'string','max:300'],
                    'billing_city' => ['required', 'string','max:100'],
                    'billing_state' => ['required', 'string','max:100'],
                    'billing_country' => ['required', 'string'],
                ];

                // Perform the validation
                $data = $request->validate($validationRules, [
                    'name.required' => 'Please enter your institute name.',
                    'name.min' => 'Institute name must be at least 4 characters.',
                    'name.max'=>'Institute name should be less than 300 characters.',
                    'name.regex' => 'Institution Name may only contain letters and spaces.',
                    'email.email' => 'Please enter a valid email address.',
                    'email.unique' => 'This email is already taken.',
                    'mobile.required' => 'Please select country code and enter mobile number.',
                    'mob_code.required' => 'Please select country code.',
                    'website.required' => 'The website URL is required.',
                    'website.url' => 'Please enter a valid URL (e.g., https://example.com).',
                    'password.required' => 'Create a strong alphanumeric password like Abc@1234.',
                    'password.min'=> 'Your password must be at least 8 characters, like Abc@1234.',
                    'password.max'=>'Password should be less than 20 characters.',
                    'password_confirmation.same' => 'The passwords you entered do not match.',
                    'password_confirmation' => 'Please confirm your password.',
                    'tnc.required'=>'You must accept our Terms of Service and Privacy Policy to create an account.',
                    'mobile.min' => 'The mobile must be at least 6 digits.',
                    'mobile.max'=>'The mobile number should be less than 15 digits.',
                    'contact_person_name.required' => 'Please enter your contact person name.',
                    'contact_person_name.max'=>'Contact person name should be less than 100 characters.',
                    'contact_person_name.regex' => 'The contact person name may only contain letters and spaces.',
                    'contact_person_designation.required' => 'Please enter your contact person designation.',
                    'contact_person_designation.max'=>'Contact person designation should be less than 100 characters.',
                    'contact_person_designation.regex' => 'Designation may only contain letters and spaces.',
                    'billing_city.required' => 'Please enter your billing city.',
                    'billing_city.max'=>'Billing City should be less than 100 characters.',
                    'billing_state.required' => 'Please enter your billing state.',
                    'billing_state.max'=>'Billing State should be less than 100 characters.',
                    'address.required' => 'Please enter your address.',
                    'address.max'=>'Address should be less than 300 characters.',
                    'contact_person_email.required'=> 'Please enter a valid email address.',
                    'contact_person_mobile.min' => 'The mobile must be at least 6 digits.',
                    'contact_person_mobile.max'=>'The mobile number should be less than 15 digits.',
                    'logo.required' => 'Logo image is required.',
                    'logo.image' => 'Logo must be a valid JPG, JPEG, or PNG image (Width: 302px, Height: 272px, max 2MB).',
                    'logo.mimes' => 'Logo must be a valid JPG, JPEG, or PNG image (Width: 302px, Height: 272px, max 2MB).',
                    'logo.max' => 'Logo must be a valid JPG, JPEG, or PNG image (Width: 302px, Height: 272px, max 2MB).',
                    'logo.dimensions' => 'Logo must be a valid JPG, JPEG, or PNG image (Width: 302px, Height: 272px, max 2MB).',
                    'photo_id.required' => 'Photo ID is required.',
                    'photo_id' => 'Photo ID must be a valid JPG, JPEG, PNG image or PDF (max 5MB).',
                    'photo_id.mimes' => 'Photo ID must be a valid JPG, JPEG, PNG image or PDF  (max 5MB).',
                    'photo_id.max' => 'Photo ID must be a valid JPG, JPEG, PNG image or PDF (max 5MB).',
                    'licence.required' => 'License is required.',
                    'licence' => 'License must be a valid JPG, JPEG, PNG image or PDF (max 5MB).',
                    'licence.mimes' => 'License must be a valid JPG, JPEG, PNG image or PDF (max 5MB).',
                    'licence.max' => 'License must be a valid JPG, JPEG, PNG image or PDF (max 5MB).',
                ]);

                // Validation passed, continue processing the data...
            } catch (ValidationException $e) {

                return redirect()->back()->withErrors($e->errors())->withInput();
            }


            // $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            //     'secret' => env('GOOGLE_SECRET_KEY'),
            //     'response' => $request->input('g-recaptcha-response'),
            //     ]);

            // $responseBody = json_decode($response->getBody());

            if ((request()->getHost() === 'www.eascencia.mt' || request()->getHost() === 'www.dev.eascencia.mt') && env('GOOGLE_SECRET_KEY')) {
            // if ((request()->getHost() === 'www.eascencia.mt') && env('GOOGLE_SECRET_KEY')) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => env('GOOGLE_SECRET_KEY'),
                    'response' => $request->input('g-recaptcha-response'),
                ]);

                $responseBody = json_decode($response->getBody());

                if (!$responseBody->success) {
                    return back()->withErrors(['captcha' => 'Invalid reCAPTCHA response. Please try again.']);
                }
            } else {
                $responseBody = (object)['success' => true];
            }

            if ($responseBody->success) {

                $data = [
                    'name' => $name,
                    'email' => $email,
                    'mob_code' => $mob_code,
                    'phone' => $mobile,
                    'role' => $role,
                    'last_seen' => $timestamp,
                    'last_session_ip' => $ipAddress,
                    'user_agent' => $userAgent,
                    'password' => Hash::make($request->password),
                    'apply_dba'=> $apply_dba,
                ];



                $user = User::create($data);

                $photo_id = null;
                $licence = null;

                $institute_id = base64_encode($user->id);

                if ($request->hasFile('logo') && $institute_id) {
                    $directory = "instituteDocs/$institute_id";
                    if (!Storage::exists($directory)) {
                        Storage::makeDirectory($directory);
                    }
                    $originalFileName = $request->file('logo')->getClientOriginalName();
                    $logo = $request->file('logo')->storeAs($directory, $originalFileName);
                }
                $user->update(['photo' => $logo ?: null]);

                if ($request->hasFile('photo_id') && $institute_id) {
                    $directory = "instituteDocs/$institute_id";
                    if (!Storage::exists($directory)) {
                        Storage::makeDirectory($directory);
                    }
                    $originalFileName = $request->file('photo_id')->getClientOriginalName();
                    $photo_id = $request->file('photo_id')->storeAs($directory, $originalFileName);
                }

                if ($request->hasFile('licence') && $institute_id) {
                    $directory = "instituteDocs/$institute_id";
                    if (!Storage::exists($directory)) {
                        Storage::makeDirectory($directory);
                    }
                    $originalFileName = $request->file('licence')->getClientOriginalName();
                    $licence = $request->file('licence')->storeAs($directory, $originalFileName);
                }

                if (isset($user->id)) {
                    do {
                        $code = rand(100000, 999999);
                    } while (DB::table('institute_profile_master')->where('university_code', $code)->exists());

                    do {
                        $englistTestPassCode = rand(100000, 999999);
                        $isUnique = !is_exist('institute_profile_master', ['englist_test_pass_code' => $englistTestPassCode]);
                    } while (!$isUnique);

                    DB::table('institute_profile_master')->insert([
                        'institute_id' => $user->id,
                        'university_code' => $code,
                        'website' => $website,
                        'address' => $address,
                        'billing_city' => $billing_city,
                        'billing_state' => $billing_state,
                        'billing_country' => $billing_country,
                        'englist_test_pass_code' => $englistTestPassCode,
                        'contact_person_name' => $contact_person_name,
                        'contact_person_email' => $contact_person_email,
                        'contact_person_mob_code' => $contact_person_mob_code,
                        'contact_person_mobile' => $contact_person_mobile,
                        'contact_person_designation' => $contact_person_designation,
                        'logo' => isset($logo) ? $logo : null,
                        'photo_id' => isset($photo_id) ? $photo_id : null,
                        'licence' => isset($licence) ? $licence : null,
                        'created_by' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    if ((request()->getHost() === 'www.eascencia.mt')) {
                        if($data['role'] == 'institute'){
                            $data = [
                                'name' => $name,
                                'email' => $email,
                                'mob_code' => $mob_code,
                                'phone' => $mobile,
                                'role' => $role,
                                'last_seen' => $timestamp,
                                'last_session_ip' => $ipAddress,
                                'user_agent' => $userAgent,
                                'password' => Hash::make($request->password),
                                'apply_dba' => $apply_dba,

                                // Additional Institute Fields
                                'institute_id' => $user->id,
                                'university_code' => $code,
                                'website' => $website,
                                'address' => $address,
                                'billing_city' => $billing_city,
                                'billing_state' => $billing_state,
                                'billing_country' => $billing_country,
                                'englist_test_pass_code' => $englistTestPassCode,
                                'contact_person_name' => $contact_person_name,
                                'contact_person_email' => $contact_person_email,
                                'contact_person_mob_code' => $contact_person_mob_code,
                                'contact_person_mobile' => $contact_person_mobile,
                                'contact_person_designation' => $contact_person_designation,
                                'logo' => $logo ?? null,
                                'photo_id' => $photo_id ?? null,
                                'licence' => $licence ?? null,
                            ];

                            // $institute = $this->user->createInstituteOnZohoApiRequest($data);
                            $institute = $this->user->syncInstituteWithZoho($data);
                            // dd($institute);
                        }
                    }

                }
                Session::put('registered', true);

                event(new Registered($user));
                if ((request()->getHost() === 'www.eascencia.mt')) {
                    $email = 'asyed@collegedeparis.fr';
                    $ccEmail = 'mrodrigues@ascenciamalta.mt';
                }else{
                    $email = 'chetan@angel-portal.com';
                    $ccEmail = 'ankita@angel-portal.com';
                }
                $unsubscribeRoute = url('/unsubscribe/'.base64_encode($email));
                mail_send(57, ['#instututeName#', '#unsubscribeRoute#'], [$user->name, $unsubscribeRoute], $email, $ccEmail);

                // return redirect()->route('institute-pending-approval');
                return redirect()->route('login')->with('success', 'You have successfully registered. Please log in.');


                // $url ='email-id-verification';

                // $dyc_id = Crypt::encrypt($user->id);
                // $link =  env('APP_URL') . "/verfiy-mail/" . $dyc_id;
                // $message = $email;
                // mail_send(32, ['#Name#', '#Link#', '#unsubscribeRoute#'], [$user->name." ".$user->last_name, $link, $email], $email);

                // return redirect()->intended($url)->with('statusEmail', $message);
            }else{
                $message = ['message' => 'Please Try Again', 'type' => 'error'];
                return redirect()->back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.']);
            }
            return redirect()->intended($url)->with($message);

        } else {
            return redirect()->intended('index')->with(['message' => 'Something Went Wrong', 'type' => 'error']);
        }

    }

    public function teacherRegister(Request $request): \Illuminate\Http\RedirectResponse
    {
        if ($request->has('honeypot') && !empty($request->input('honeypot'))) {
            return redirect()->back()->withErrors(['error' => 'Bot detected!']);
        }

        if ($request->isMethod('POST')) {
            try {
                $request->validate([
                    'first_name' => ['required', 'string', 'min:3', 'max:255','regex:/^[a-zA-Z\s]+$/'],
                    'last_name' => ['required', 'string', 'min:3', 'max:255','regex:/^[a-zA-Z\s]+$/'],
                    'mobile' => ['required', 'string', 'min:6', 'max:20'],
                    'email' => ['bail','required', 'email', 'unique:lecturers_master,email',new ValidEmailDomain()],
                    'designation' => ['required', 'string','regex:/^(?=(?:.*[a-zA-Z]){2,})[a-zA-Z\s&\-\(\)]+$/'],
                    'specialization' => ['required', 'string', new WordCount(50)],
                    'about_teacher' => ['required', 'string', new WordCount(50)],
                    'category_id'=> ['required'],
                    'university_code' => ['required', 'exists:institute_profile_master,university_code'],
                    'image_file' => 'required|mimes:jpeg,png,jpg,svg|max:2048',
                    'resume_file' => 'required|mimes:jpeg,png,jpg,svg,pdf|max:2048',

                ], [
                    'specialization' => 'The specialization must not be greater than 50 words.',
                    'about_teacher.required' => 'The about teacher field is required.',
                    'about_teacher' => 'The about teacher must not be greater than 50 words.',
                    'image_file.required' => 'Profile picture is required.',
                    'image_file.mimes' => 'Only JPEG, JPG, PNG, and SVG formats are allowed.',
                    'image_file.max' => 'The image must be less than 2MB.',
                    'resume_file.required' => 'Resume is required.',
                    'university_code.exists' => 'The institute name is invalid.',
                    'university_code.required' => 'The institute name is required.',
                    'first_name.regex' => 'The first name may only contain letters and spaces.',
                    'last_name.regex' => 'The last name may only contain letters and spaces.',
                    'designation.regex' => 'The designation must contain letters.',

                ]);
            } catch (ValidationException $e) {
                return redirect()->back()->withErrors($e->errors())->withInput();
            }

            $firstName = htmlspecialchars_decode($request->input('first_name'));
            $lastName = htmlspecialchars_decode($request->input('last_name'));
            $email = htmlspecialchars($request->input('email'));
            $mobCode = htmlspecialchars($request->input('mob_code'));
            $mobile = htmlspecialchars($request->input('mobile'));
            $designation = htmlspecialchars_decode($request->input('designation'));
            $aboutTeacher = htmlspecialchars_decode($request->input('about_teacher'));
            $specialization = htmlspecialchars_decode($request->input('specialization'));
            $university_code = htmlspecialchars_decode($request->input('university_code'));
            $categoryId = $request->input('category_id');
            $imageFile = $request->file('image_file');
            $resumeFile = $request->file('resume_file');


            // Check if lecturer already exists
            $where = ['email' => $email];
            $existingLecturer = is_exist('lecturers_master', $where);

            if ($existingLecturer) {
                return back()->withErrors(['error' => 'Email is already taken.'])->withInput();
            }

            // File upload
            $docUpload = UploadFiles($imageFile, 'teacherDocs', '');
            if ($docUpload === FALSE) {
                return back()->withErrors(['error' => 'File is corrupt.'])->withInput();
            }

            $folder = 'teacherDocs/resume';
            if (!Storage::exists($folder)) {
                Storage::makeDirectory($folder);
            }

            $docUploadResume = UploadFiles($resumeFile, $folder, '');
            if ($docUploadResume === FALSE) {
                return back()->withErrors(['resume_file' => 'File is corrupt.'])->withInput();
            }

            $data = [
                'lactrure_name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'mobile' => $mobCode . ' ' . $mobile,
                'designation' => $designation,
                'discription' => $aboutTeacher,
                'specialization' => $specialization,
                'university_code' => $university_code,
                'created_at' => now(),
                'created_by' => 0,
                'image' => $docUpload['url'] ?? null,
                'category_id' => $categoryId,
                'resume'=> $docUploadResume['url'] ?? null
            ];

            $saved = processData(['lecturers_master', 'id'], $data);

            if (!$saved) {
                return redirect()->back()->withErrors(['error' => 'Something went wrong while saving data.']);
            }

            return redirect()->route('teacher-registration-success')->with('success', 'You have successfully registered.');
        }

        return redirect()->back()->withErrors(['error' => 'Unauthorized or invalid request.']);
    }
}
