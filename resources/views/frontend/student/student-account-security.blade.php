@extends('frontend.master')
@section('content')


<style>
    .sidenav.navbar .navbar-nav .sp-4>.nav-link {
     color: #a30a1b !important;
    background-color:#ffe7ea;
    }

    .strength {
            display: inline-block;
            padding: 5px 10px;
            color: white;
            border-radius: 3px;
            margin-top: 5px;
        }
        .weak { background-color: red; }
        .medium { background-color: orange; }
        .strong { background-color: green; }
        .password-container {
            position: relative;
            /* margin-bottom: 10px; */
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
    }
        .error {
            color: red;
            margin-top: 5px;
        }

</style>


<main>
    <section class="pt-5 pb-5">
        <div class="container">
            <!-- Content -->
                @include('frontend.student.layout.student-common')
                <div class="col-lg-9 col-md-8 col-12">

                            <!-- Card -->
                            <div class="card">
                                <!-- Card header -->
                                <div class="card-header">
                                    {{-- <h3 class="mb-0">Account Security</h3>
                                    <p class="mb-0">Account settings and change your password here.</p> --}}
                                    <h3>{{ __('studentdashborad.account_security') }}</h3>
                                    <p>{{ __('studentdashborad.account_info') }}</p>
                                </div>
                                <!-- Card body -->
                                <div class="card-body">

                                    <form class="row"  novalidate>
                                        <div class="mb-1 col-lg-6 col-md-6 col-12 mb-2">
                                            {{-- <label class="form-label" for="email">Email Address</label> --}}
                                            <label class="form-label" for="email">{{ __('studentdashborad.email') }}</label>

                                            <input id="email" type="email" name="email" class="form-control" placeholder="" required value="{{isset($studentData->user->email) ? $studentData->user->email : '' }}" disabled>
                                        </div>
                                          <div class="mb-1 col-lg-6 col-md-6 col-12 mb-2">
                                            {{-- <label class="form-label" for="number">Mobile Number</label> --}}
                                            <label class="form-label" for="number">{{ __('studentdashborad.mobile') }}</label>

                                            <input id="number" type="text" name="mobile" class="form-control" placeholder="" required value="{{isset($studentData->mob_code) ? $studentData->mob_code : '' }} {{ isset($studentData->user->phone) ? $studentData->user->mob_code.' '.$studentData->user->phone : '' }}" disabled>
                                        </div>
                                    </form>
                                    <hr class="my-5">
                                    <div>

                                        {{-- <h4 class="mb-0">Change Password </h4> --}}
                                        <h4 class="mb-0">{{ __('studentdashborad.change_password') }}</h4>

                                        {{-- <p class="mb-3">We will email you a confirmation when changing your password, so please expect that email after submitting.</p> --}}
                                        <!-- Form -->
                                        <p class="mb-3">{{ __('studentdashborad.change_password_note') }}</p>

                                        <form class="row ChangePassword" method="POST" action="{{ route('password.update') }}" novalidate>
                                                @csrf
                                                 @method('put')

                                            <!-- Password Reset Token -->

                                            <div class="col-lg-6 col-md-12 col-12">
                                                <!-- Current password -->
                                                <div class="mb-3">
                                                    {{-- <label class="form-label" for="current_password">Old Password <span class="text-danger">*</span> </label> --}}
                                                    <label class="form-label" for="current_password">{{ __('studentdashborad.old_password') }} <span class="text-danger">*</span> </label>


                                                    <input id="current_password" type="password" name="current_password" class="form-control" placeholder="{{ __('studentdashborad.old_password_placeholder') }}">
                                                        @if($errors->updatePassword->has('current_password'))
                                                            @foreach($errors->updatePassword->get('current_password') as $error)
                                                            <div class="invalid-feedback d-block">{{$error}} </div>
                                                            @endforeach
                                                        @endif

                                                </div>

                                                {{-- <div class="password-container">
                                                    <input type="password" id="password" placeholder="Enter your password" onkeyup="checkPasswordStrength()">
                                                    <span class="toggle-password" onclick="togglePasswordVisibility()">Show</span>
                                                </div>
                                                <div id="strengthMessage"></div>
                                                <div id="lengthMessage" class="error"></div> --}}
                                                <!-- New password -->
                                                <div class="mb-3 password-field">
                                                    {{-- <label class="form-label" for="newpassword">New Password <span class="text-danger">*</span></label> --}}
                                                    <label class="form-label" for="newpassword">{{ __('studentdashborad.new_password') }} <span class="text-danger">*</span></label>

                                                    <input id="newpassword" type="password" name="password" class="form-control mb-2" placeholder="{{ __('studentdashborad.new_password_placeholder') }}" required  onkeyup="checkPasswordStrength()">
                                                    <span class="toggle-password" toggle="#newpassword">
                                                        {{-- <i class="fe fe-eye toggle-password-eye field-icon show-password-eye-security"></i> --}}
                                                    </span>
                                                        @if($errors->updatePassword->has('password'))
                                                            @foreach($errors->updatePassword->get('password') as $error)
                                                            <div class="invalid-feedback d-block">{{$error}} </div>
                                                            @endforeach
                                                        @endif
                                                    {{-- <div class="invalid-feedback" id="newpassword2_error">Password Formate Should be like . "Examples@123" </div> --}}
                                                    <div class="invalid-feedback" id="newpassword2_error">Password Formate Should be like . "Examples@123" </div>

                                                    <div class="row align-items-center g-0">
                                                        <div class="col-6">
                                                            <span
                                                                >
                                                                {{-- Password strength --}}
                                                                {{ __('studentdashborad.password_strength') }}
                                                                <i class="fe fe-help-circle ms-1"
                                                                data-bs-toggle="tooltip"
                                                                data-placement="right"
                                                                title="Test it by typing a new password in the field above. To reach full strength, use at least 8 characters, a capital letter, a symbol and a digit, e.g. 'Abc@1010'"></i>
                                                                <div id="strengthMessage"></div>

                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="mb-3 password-container">
                                                    <!-- Confirm new password -->
                                                    <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                                    <input id="confirmpassword" type="password" name="password_confirmation" class="form-control mb-2" placeholder="Password (e.g., Abc@1234)" required>
                                                    <span toggle="#confirmpassword" class="toggle-password">
                                                        <i class="bi bi-eye" id="toggle-icon" style="top: 40px; position:absolute; right: 10px;"></i>
                                                    </span>
                                                    @if($errors->updatePassword->has('password_confirmation'))
                                                        @foreach($errors->updatePassword->get('password_confirmation') as $error)
                                                            <div class="invalid-feedback d-block">{{$error}}</div>
                                                        @endforeach
                                                    @endif
                                                </div> --}}

                                                <div class="mb-3 password-container">
                                                    {{-- <label for="" class="form-label">Confirm Password <span class="text-danger">*</span></label> --}}
                                                    <label for="" class="form-label">{{ __('studentdashborad.confirm_password') }} <span class="text-danger">*</span></label>

                                                    <input type="password" id="confirmpassword" class="form-control" name="password_confirmation"
                                                        placeholder="{{ __('studentdashborad.confirm_password_placeholder') }}"   required>
                                                        <span class="toggle-password" toggle="#confirmpassword" style="position: initial !important">
                                                            <i class="fe fe-eye toggle-password-eye field-icon show-password-eye bi bi-eye"></i>
                                                        </span>
                                                        @if($errors->updatePassword->has('password_confirmation'))
                                                        @foreach($errors->updatePassword->get('password_confirmation') as $error)
                                                            <div class="invalid-feedback d-block">{{$error}}</div>
                                                        @endforeach
                                                    @endif
                                                </div>


                                                <!-- Button -->
                                                {{-- <button type="submit" class="btn btn-primary" name="submit">Save Password</button> --}}
                                                <button type="submit" class="btn btn-primary" name="submit">{{ __('studentdashborad.save_password') }}</button>


                                                <div class="col-6"></div>
                                            </div>
                                            {{-- <div class="col-12 mt-4">
                                                <p class="mb-0">
                                                    Can't remember your current password?
                                                    <a href="#">Reset your password via email</a>
                                                </p>
                                            </div> --}}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </section>
</main>

<script>
    $(document).ready(function() {
        $('.toggle-password').click(function() {
            const input = $('#confirmpassword');
            const icon = $(this).find('i');
            const type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            icon.toggleClass('bi-eye bi-eye-slash');
        });
    });
const translations = {
        password_update: @json(__('response.password_update')),

    };
</script>
 @if (session('status') === 'password-updated')
                            <script>
                                    swal({
                                    title: translations.password_update,
                                    text: '',
                                    icon: 'success',
                                });
                                </script>
                     @endif
@endsection
