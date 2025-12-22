@extends('frontend.master')
@section('content')
            <!-- Page Content -->
            <section class="container-fluid py-lg-8">
                <div class="row align-items-center justify-content-center ">
                    <!-- col -->
                    <div class="offset-xl-1 col-xl-5 col-lg-5 col-md-12 col-12 mt-7">
                        <div class="py-lg-8 me-xl-4 pe-xl-4 ps-md-5 ">
                            <!-- content -->

                            <div>
                               
                                <h1 class="display-4 fw-bold">{{ __('contact.title') }}</h1>
                                <p class="lead text-dark">{{ __('contact.subtitle') }}</p>
                                <div class="mt-4 fs-4">

                                    <!-- address -->
                                    <p class="mb-0">
                                       {{ __('contact.contact_email_label') }}
                                        <a href="mailto:info@eascencia.mt" class="color-blue">info@eascencia.mt</a>
                                    </p>
                                    {{-- <p class="mb-1">
                                        {{ __('contact.support_email_label') }}:
                                        <a href="mailto:support@eascencia.mt" class="color-blue">support@eascencia.mt</a>
                                    </p> --}}
                                    
                                </div>
                                <div class="fs-6 mt-4">
                                    <a href="https://www.facebook.com/people/E-Ascencia-Malta/61559646792486/" target="_blank"><img class="social-logo mb-2 "
                                            src="{{ asset('frontend/images/social/social-media-01.png') }}" alt="social logo"></a>
                                    <a href="https://www.instagram.com/eascencia/" target="_blank"><img class="social-logo mb-2 "
                                            src="{{ asset('frontend/images/social/social-media-02.png') }}" alt="social logo"></a>
                                    <a href="https://www.linkedin.com/company/ascencia-malta-business-school/" target="_blank"><img class="social-logo mb-2 "
                                            src="{{ asset('frontend/images/social/social-media-03.png') }}" alt="social logo"></a>
                                    <a href="https://x.com/eascenciamalta" target="_blank"><img class="social-logo mb-2 twitterLogoStyle"
                                            src="{{ asset('frontend/images/social/social-media-09.png') }} " alt="social logo"></a>
                                    <a href="https://www.youtube.com/@E-Ascencia" target="_blank"><img class="social-logo mb-2 twitterLogoStyle"
                                            src="{{ asset('frontend/images/social/social-media-06.png') }} " alt="social logo"></a>
                                    {{-- <a href="#" target="_blank" onclick=" return false"><img class="social-logo mb-2 "
                                            src="{{ asset('frontend/images/social/social-media-07.png') }}" alt="social logo"></a> --}}
                                    {{-- <a href="https://www.quora.com/profile/E-Ascencia-Malta" target="_blank"><img class="social-logo mb-2 "
                                            src="{{ asset('frontend/images/social/social-media-08.png') }}" alt="social logo"></a>  --}}
                                    </div>
                            </div>
                        </div>
                    </div>

                    <!-- background color -->
                    <div class="col-lg-7 col-xl-7 d-lg-flex align-items-center w-lg-50  bg-cover mt-5 mt-lg-7">
                        <div class=" px-xl-8 mx-xl-4 py-3 py-lg-0">
                            <!-- form section -->
                            <div>
                                <!-- form row -->
                                <form class="row contactForm">
                                    <!-- form group -->
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="fname">
                                            {{ __('contact.first_name') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control" type="text" name="first_name" id="first_name" placeholder="{{ __('contact.placeholder_first_name') }}"  />
                                        <div class="invalid-feedback" id="first_name_error">Please enter your first name.</div>
                                    </div>
                                    <!-- form group -->
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="lname">
                                            {{ __('contact.last_name') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control" type="text" name="last_name" id="last_name" placeholder="{{ __('contact.placeholder_last_name') }}"  />
                                        <div class="invalid-feedback" id="last_name_error">Please enter your last name.</div>
                                    </div>
                                    <!-- form group -->
                                    <div class="mb-3 col-12 col-md-6">
                                        <label class="form-label" for="email">
                                            {{ __('contact.email') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input class="form-control" type="email" id="email" name="email" placeholder="{{ __('contact.placeholder_email') }}"  />
                                        <div class="invalid-feedback" id="email_error">Please enter your email.</div>
                                    </div>
                                    <!-- form group -->
                                    <?php
                                        $countryData = getCountryCodeByIp();
                                        $country_code = $countryData['country_code'];
                                        $country_flag = $countryData['country_flag'];
                                    ?>
                                    <div class="mb-3 col-12 col-md-6">
                                        <label for="MobileNumber" class="form-label">{{ __('contact.mobile_number') }}   <span class="text-danger">*</span></label>
                                        <div class="mobile-with-country-code contact-us">
                                            
                                            {{-- <select class="form-select select2" name="mob_code" id="mob_code" data-live-search="true" style="width: auto">
                                                <option value="" selected>Choose Code</option>
                                                @foreach (getDropDownlist('country_master', ['id', 'country_code', 'country_flag']) as $mob_code)
                                                    <option value="+{{$mob_code->country_code}}"
                                                            data-content="{{ Storage::url("country_flags/" . $mob_code->country_flag) }}">
                                                        +{{$mob_code->country_code}}
                                                    </option>
                                                @endforeach
                                            </select> --}}
                                            <select name="mob_code" id="mob_code" class="form-select">
                                                <option value="" selected>Choose Code</option>
                                                @foreach (getDropDownlist('country_master', ['id', 'country_code', 'country_flag', 'country_name']) as $mob_code)
                                                    <option value="+{{ $mob_code->country_code }}"
                                                            {{ old('mob_code', "+$country_code") == "+$mob_code->country_code" ? 'selected' : '' }}
                                                            data-content="{{ Storage::url('country_flags/' . $mob_code->country_flag) }}"
                                                            data-name="{{ $mob_code->country_name }}">
                                                        +{{ $mob_code->country_code }} - {{ $mob_code->country_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            
                                            <input type="number" id="phone" class="form-control" name="phone" placeholder="123 4567 890" >
                                        </div>
                                        <div class="invalid-feedback" ></div>
                                        <div class="invalid-feedback" id="phone_error" >Please select the country code and enter mobile number.</div>
                                    </div>
                                    <!-- form group -->
                                    <div class="mb-3 col-12">
                                        <label class="text-dark form-label" for="contactReason">
                                            {{ __('contact.who_are_you') }}:
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="contact_role" name="contact_role" >
                                            <option value="">{{ __('contact.placeholder_who_are_you_select') }}</option>
                                            <option value="Student">{{ __('contact.placeholder_student') }}</option>
                                            <option value="Teacher">{{ __('contact.placeholder_teacher') }}</option>
                                            <option value="Institute">{{ __('contact.placeholder_institute') }}</option>
                                        </select>
                                        <div class="invalid-feedback" id="contact_role_error">Please select your type.</div>
                                    </div>
                                    <!-- form group -->
                                    <div class="mb-3 col-12">
                                        <label class="text-dark form-label" for="messages">{{ __('contact.message') }} <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="{{ __('contact.placeholder_message') }}"></textarea>
                                        <div class="invalid-feedback" id="message_error">Please enter message.</div>

                                    </div>
                                    <!-- button -->
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary contactformSubmit">{{ __('contact.submit') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
<script>
        // $(document).ready(function() {
        //     $('#mob_code').select2({
        //         templateResult: formatState,
        //         templateSelection: formatState,
        //         escapeMarkup: function(markup) {
        //             return markup;
        //         }
        //     });

        //     function formatState(state) {
        //         if (!state.id) {
        //             return state.text;
        //         }

        //         var $state = $(
        //             '<span><img src="' + $(state.element).data('content') + '" class="img-flag" style="width: 20px;" /> ' + state.text + '</span>'
        //         );
        //         return $state;
        //     }
        // });
        
        $(document).ready(function() {
            $('#mob_code').select2({
                templateResult: formatOption,
                templateSelection: formatSelection,
                escapeMarkup: function (markup) { return markup; }
            });

            function formatOption(option) {
                if (!option.id) { return option.text; }
                
                const flagUrl = $(option.element).data('content');
                const countryCode = option.text.split(' - ')[0];
                const countryName = option.text.split(' - ')[1];
                
                return $(
                    '<span><img src="' + flagUrl + '" class="img-flag" style="width: 24px; height: 16px; margin-right: 5px;" /> ' +
                    countryCode + ' - ' + countryName + '</span>'
                );
            }

            function formatSelection(option) {
                if (!option.id) { return option.text; }
                
                const flagUrl = $(option.element).data('content');
                const countryCode = option.text.split(' - ')[0];
                
                return $(
                    '<span><img src="' + flagUrl + '" class="img-flag" style="width: 24px; height: 16px; margin-right: 5px;" /> ' +
                    countryCode + '</span>'
                );
            }
            
        });
</script>
@endsection
