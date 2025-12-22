@extends('frontend.master')
@section('content')

<style>
    .sidenav.navbar .navbar-nav .sp-1>.nav-link {
    color: #a30a1b !important;
    background-color:#ffe7ea;
    }
    .input-group .form-select, .input-group .form-control {
        height: calc(2.5rem + 2px);
}
</style>
<main>
    <section class="pt-5 pb-5">
        <div class="container">
            <!-- User info -->
            @include('frontend.student.layout.student-common')
                <div class="col-lg-9 col-md-8 col-12">
                    <!-- Card -->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header">
                            {{-- <h3 class="mb-0">Student Profile</h3>
                             <p class="mb-4">Edit your personal information and address.</p> --}}
                              <h3 class="mb-0">{{ __('studentdashborad.student_profile') }}</h3>
                             <p class="mb-4">{{ __('studentdashborad.edit_info') }}</p>
                        </div>
                        {{-- {{dd($studentData)}} --}}
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="d-lg-flex align-items-center justify-content-between">
                                {{-- <div class="d-flex align-items-center mb-4 mb-lg-0">
                                    <img src="{{ asset('frontend/images/avatar/avatar-2.jpg')}}" id="img-uploaded" class="avatar-xl rounded-circle imagePreview" alt="avatar" />
                                    <div class="ms-3">
                                        <h4 class="mb-0">Profile Photo</h4>
                                        <p class="mb-0" id="img_size_error">PNG or JPG no bigger than 800px wide and tall.</p>
                                    </div>
                                </div> --}}
                                <div>
                                    {{-- <input type="file" name="profile_img" class="btn btn-outline-secondary btn-sm image" accept=".jpg,.png,.jpeg"> --}}
                                    {{-- <a href="#" class="btn btn-outline-danger btn-sm">Delete</a> --}}
                                </div>
                            </div>
                            {{-- <hr class="my-5" /> --}}
                            <div>
                                <!-- Form -->
                                <form class="row gx-3 needs-validation ProfileData" novalidate>
                                    <!-- Selection -->
                                    {{-- <div class="mb-3 col-12 col-md-12">
                                        <label class="form-label" for="editState">Are you?</label>
                                        <select class="form-select" id="editState" required>
                                            <option value="">Select</option>
                                            <option value="1">Student</option>
                                            <option value="2">Employed</option>
                                            <option value="2">Unemployed</option>

                                        </select>
                                        <div class="invalid-feedback">Please choose Gender.</div>
                                    </div> --}}
                                    <!-- First name -->
                                    <div class="mb-3 col-12 col-md-6">
                                        {{-- <label class="form-label" for="occupation">Are you ?</label> --}}
                                        <label class="form-label" for="occupation">{{ __('studentdashborad.are_you') }}</label>

                                        <select class="form-select" id="occupation" name="occupation" required>
                                            <option value="{{ !empty($studentData->occupation) ? $studentData->occupation : '' }}">
                                                {{ !empty($studentData->occupation) ? $studentData->occupation : 'Select' }}
                                            </option>
                                             {{-- <option value="Student">Student</option>
                                             <option value="Employed">Employed</option>
                                             <option value="Unemployed">Unemployed</option> --}}
                                              <option value="Student">{{ __('studentdashborad.student') }}</option>
                                             <option value="Employed">{{ __('studentdashborad.employed') }}</option>
                                             <option value="Unemployed">{{ __('studentdashborad.unemployed') }}</option>
                                        </select>
                                        <div class="invalid-feedback" id="occupation_error">Please select.</div>
                                    </div>
                                    <div class="mb-3 col-12 col-md-6">
                                        {{-- <label class="form-label" for="fname">First Name <span class="text-danger">*</span></label> --}}
                                        <label class="form-label" for="fname">{{ __('studentdashborad.first_name') }} <span class="text-danger">*</span></label>

                                        <input type="text" id="fname" name="first_name" class="form-control" value="{{isset($studentData->user['name']) ? $studentData->user['name'] : '' }}" placeholder="First Name" required />

                                        <div class="invalid-feedback" id="first_name_error">Please enter first name.</div>
                                    </div>
                                    <!-- Last name -->
                                    <div class="mb-3 col-12 col-md-6">
                                        {{-- <label class="form-label" for="lname">Last Name <span class="text-danger">*</span></label> --}}
                                        <label class="form-label" for="lname">{{ __('studentdashborad.last_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" id="lname" value="{{isset($studentData->user['last_name']) ? $studentData->user['last_name'] : '' }}" class="form-control" placeholder="Last Name" name="last_name" required />
                                        <div class="invalid-feedback" id="last_name_error">Please enter last name.</div>
                                    </div>

                                    <!-- DOB -->
                                    <div class="mb-3 col-12 col-md-6">
                                        {{-- <label class="form-label" for="birth">Date of Birth<span class="text-danger">*</span></label> --}}
                                        <label class="form-label" for="birth">{{ __('studentdashborad.dob') }}<span class="text-danger">*</span></label>

                                        <input class="form-control flatpickr" value="{{isset($studentData->dob) ? $studentData->dob : '' }}" type="date" placeholder="Date of Birth" id="birth" name="dob" />
                                        <div class="invalid-feedback" id="dob_error">Please choose a date of birth.</div>
                                    </div>

                                    <?php
                                        $countryData = getCountryCodeByIp();
                                        $country_code = $countryData['country_code'];
                                        $country_flag = $countryData['country_flag'];
                                        $country_name = $countryData['country_name'];
                                        $country_id = $countryData['country_id'];
                                    ?>
                                    <div class="mb-3 col-12 col-md-6">
                                        {{-- <label for="mobile" class="form-label">Mobile No. <span class="text-danger">*</span></label> --}}
                                        <label for="mobile" class="form-label">{{ __('studentdashborad.mobile') }} <span class="text-danger">*</span></label>

                                        <div class="mobile-with-country-code">
                                            <select class="form-select" name="mob_code" aria-label="Default select example" disabled >
                                                @foreach (getDropDownlist('country_master', ['id', 'country_code', 'country_flag', 'country_name']) as $mob_code)
                                                    <option value="+{{ $mob_code->country_code }}"
                                                            {{ old('mob_code', "+$country_code") == "+$mob_code->country_code" ? 'selected' : '' }}
                                                            data-content="{{ Storage::url('country_flags/' . $mob_code->country_flag) }}"
                                                            data-name="{{ $mob_code->country_name }}">
                                                        +{{ $mob_code->country_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="mob_code_error">Please choose Country Code.</div>
                                            <input type="number" id="mobile" class="form-control" name="mobile" value="{{isset($studentData->user['phone']) ? $studentData->user['phone'] : '' }}"
                                                placeholder="123 4567 890" disabled  required>
                                        </div>
                                        <div class="invalid-feedback" id="mobile_error">Please enter Mobile.</div>
                                    </div>
                                    <!-- Gender -->
                                    <div class="mb-3 col-12 col-md-6">
                                        {{-- <label class="form-label" for="gender">Gender </label> --}}
                                             <label class="form-label" for="gender">{{ __('studentdashborad.gender') }}</label>

                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="{{isset($studentData->gender) ? $studentData->gender : '' }}">
                                                {{-- {{isset($studentData->gender) ? $studentData->gender : 'Select' }} --}}
                                                {{isset($studentData->gender) ? $studentData->gender : __('studentdashborad.select') }}

                                            </option>
                                            @if (!empty($studentData->gender) && $studentData->gender === 'Male')
                                            {{-- <option value="Female">Female</option> --}}
                                            <option value="Female">{{ __('studentdashborad.female') }}</option>

                                            @elseif(!empty($studentData->gender) && $studentData->gender === 'Female')
                                                 {{-- <option value="Male">Male</option> --}}
                                                 <option value="Male">{{ __('studentdashborad.male') }}</option>
                                            @elseif(!empty($studentData->gender) && $studentData->gender === 'Not Disclose')
                                            {{-- <option value="Female">Female</option>
                                                <option value="Male">Male</option> --}}
                                                  <option value="Female">{{ __('studentdashborad.female') }}</option>
                                                <option value="Male">{{ __('studentdashborad.male') }}</option>
                                                @else
                                                  {{-- <option value="Female">Female</option>
                                                <option value="Male">Male</option> --}}
                                                <option value="Female">{{ __('studentdashborad.female') }}</option>
                                                <option value="Male">{{ __('studentdashborad.male') }}</option>
                                            @endif
                                        </select>
                                        <div class="invalid-feedback" id="gender_error">Please choose Gender.</div>
                                    </div>

                                    <!-- Country -->
                                    <div class="mb-3 col-12 col-md-6">
                                        {{-- <label class="form-label" for="country">Country <span class="text-danger">*</span></label> --}}
                                        <label class="form-label" for="country">{{ __('studentdashborad.country') }} <span class="text-danger">*</span></label>

                                        <select class="form-select" id="country" name="country" required>
                                            {{-- <option value="{{isset($studentData->country_id) ? $studentData->country_id : '' }}">{{isset($studentData->name) ? $studentData->name : 'Select' }}</option>     --}}
                                                <option value="">Select</option>
                                                {{-- <option value="">{{ __('studentdashborad.select') }}</option> --}}

                                                @foreach (getDropDownlist('country_master', ['id','country_name']) as $country)
                                                    @if(!empty($studentData->country_id))

                                                    <option value="{{ $country->id}}" @if($country->id == $studentData->country_id) selected @endif>{{ $country->country_name}}</option>

                                                    @else

                                                    <option value="{{$country->id}}" @if($country_id == $country->id) selected @endif>{{$country->country_name}} </option>

                                                    @endif
                                                @endforeach
                                            </select>

                                        <div class="invalid-feedback" id="country_error">Please select country.</div>
                                    </div>
                                     <div class="mb-3 col-12 col-md-6">
                                        {{-- <label class="form-label" for="city">City <span class="text-danger">*</span></label> --}}
                                        <label class="form-label" for="city">{{ __('studentdashborad.city') }} <span class="text-danger">*</span></label>

                                        <input type="text" id="city" class="form-control" value="{{isset($studentData->city_id) ? $studentData->city_id : '' }}" placeholder="{{ __('studentdashborad.city') }}" name="city" required />
                                        <div class="invalid-feedback" id="city_error" >Please enter city.</div>
                                    </div>

                                    <!-- Nationality -->
                                    <div class="mb-3 col-12 col-md-6">
                                        {{-- <label class="form-label" for="nationality">Nationality </label> --}}
                                        <label class="form-label" for="nationality">{{ __('studentdashborad.nationality') }} </label>

                                        <input type="text" id="nationality" name="nationality" value="{{isset($studentData->nationality) && !empty($studentData->nationality) ? $studentData->nationality : $country_name }}" class="form-control" placeholder="Nationality" required />
                                        <div class="invalid-feedback" id="nationality_error">Please enter nationality.</div>
                                    </div>

                                    <div class="mb-3 col-12 col-md-6">
                                        {{-- <label class="form-label" for="nationality">Postal Code</label> --}}
                                        <label class="form-label" for="nationality">{{ __('studentdashborad.postal_code') }}</label>

                                        <input type="text" id="zip" name="zip" value="{{isset($studentData->zip) ? $studentData->zip : '' }}" class="form-control" placeholder="{{ __('studentdashborad.postal_code') }}" required />
                                        <div class="invalid-feedback" id="zip_error">Please enter postal code.</div>
                                    </div>
                                    <!-- Address -->
                                    <div class="mb-3 col-12 col-md-12 address">
                                        {{-- <label for="textarea-input" class="form-label">Address (Max 100 characters)</label> --}}
                                        <label for="textarea-input" class="form-label">{{ __('studentdashborad.address') }}</label>

                                        <textarea class="form-control" id="address" name="address" rows="2" placeholder="{{ __('studentdashborad.address_placeholder') }}">{{isset($studentData->address) ? $studentData->address : '' }}</textarea>
                                        {{-- <small id="charCountWarning" class="text-danger" style="display: none;">The address must be less than 100 characters.</small>--}}
                                        <div class="invalid-feedback" id="address_error">Please enter address.</div>
                                    </div>

                                    <div class="col-12">
                                        <!-- Button -->
                                        {{-- <button class="btn btn-primary updateProfile" type="button">Save</button> --}}
                                        <button class="btn btn-primary updateProfile" type="button"> {{ __('studentdashborad.save') }}</button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header">
                          {{-- <h3 class="mb-0">Social Profile</h3>
                          <p class="mb-0">Add your social profile links in below social accounts.</p> --}}
                          <h3 class="mb-0">{{ __('studentdashborad.social_profile')}}</h3>
                          <p class="mb-0">{{ __('studentdashborad.edit_social')}}</p>
                        </div>
                        <!-- Card body -->
                        <form class="socialProfileForm">
                        <div class="card-body">
                            <!-- WhatsApp -->
                            <div class="row mb-3">
                              <div class="col-lg-2 col-md-4 col-12">
                                {{-- <h5>WhatsApp No.</h5> --}}
                                <h5>{{ __('studentdashborad.whatsapp')}}</h5>

                              </div>
                              {{-- <div class="col-lg-10 col-md-8 col-12">
                                <input type="number" class="form-control mb-1 whatsapp" min="10" max="15" value="{{isset($studentData->whatsapp) ? $studentData->whatsapp : '' }}" name="whatsapp" placeholder="https://www.whatsapp.com/">
                                <div class="invalid-feedback whatsapp_error d-block text-dark">Add your whatsapp number.</div>
                              </div> --}}
                              <div class="col-lg-10 col-md-8 col-12  d-block">
                                <div class="mobile-with-country-codewhatsapp">
                                  <select name="whatsapp_country_code" id="mob_code" class="form-select">
                                    <option value="" selected>Choose Code</option>
                                    @foreach (getDropDownlist('country_master', ['id', 'country_code', 'country_flag', 'country_name']) as $mob_code)
                                        <option value="+{{ $mob_code->country_code }}"
                                                 {{ isset($studentData->whatsapp_country_code) && !empty($studentData->whatsapp_country_code) && old('mob_code', $studentData->whatsapp_country_code) == $mob_code->country_code ? 'selected' : ($mob_code->country_code == $country_code ? 'selected' : '') }}
                                                data-content="{{ Storage::url('country_flags/' . $mob_code->country_flag) }}"
                                                data-name="{{ $mob_code->country_name }}">
                                            +{{ $mob_code->country_code }} - {{ $mob_code->country_name }}
                                        </option>
                                    @endforeach
                                    {{-- @foreach (getDropDownlist('country_master', ['id', 'country_code', 'country_flag', 'country_name']) as $mob_code)
                                        <option value="+{{ $mob_code->country_code }}"
                                            {{ old('mob_code', isset($whatsapp_country_code) ? "+$whatsapp_country_code" : "") == "+$mob_code->country_code" ? 'selected' : '' }}
                                            data-content="{{ Storage::url('country_flags/' . $mob_code->country_flag) }}"
                                            data-name="{{ $mob_code->country_name }}">
                                            +{{ $mob_code->country_code }} - {{ $mob_code->country_name }}
                                        </option>
                                    @endforeach --}}

                                </select>
                                    <input type="tel" class="form-control mb-1 whatsapp" maxlength="15"
                                           value="{{ isset($studentData->whatsapp) ? $studentData->whatsapp : (isset($studentData->user['phone']) ? $studentData->user['phone'] : '') }}"
                                           name="whatsapp" placeholder="{{ __('studentdashborad.whatsapp_placeholder') }}" required >
                                </div>
                                <div class="invalid-feedback whatsapp_error d-block text-dark mt-0">Add your whatsapp number.</div>
                            </div>
                            </div>
                          <!-- Facebook -->
                          <div class="row mb-3">
                            <div class="col-lg-2 col-md-4 col-12">
                              {{-- <h5>Facebook</h5> --}}
                              <h5>{{ __('studentdashborad.facebook') }}</h5>

                            </div>
                            <div class="col-lg-10 col-md-8 col-12">
                              <input type="url" class="form-control mb-1 facebook" value="{{isset($studentData->facebook) ? $studentData->facebook : '' }}" name="facebook" placeholder="{{ __('studentdashborad.facebook_placeholder') }}">
                               {{-- <div class="invalid-feedback facebook_error d-block text-dark">Add your facebook profile url.</div> --}}
                               <div class="invalid-feedback facebook_error d-block text-dark">{{ __('studentdashborad.facebook_placeholder') }}</div>

                            </div>
                          </div>
                          <!-- Instagram -->
                          <div class="row mb-5">
                            <div class="col-lg-2 col-md-4 col-12">
                              {{-- <h5>Instagram</h5>--}}
                              <h5>{{ __('studentdashborad.instagram') }}</h5>

                            </div>
                            <div class="col-lg-10 col-md-8 col-12">
                              <input type="url" class="form-control mb-1 insta" value="{{isset($studentData->instagram) ? $studentData->instagram : '' }}" name="insta" placeholder="{{ __('studentdashborad.instagram_placeholder') }}">
                              {{-- <div class="invalid-feedback insta_error d-block text-dark">Add your instagram profile url.</div> --}}
                              <div class="invalid-feedback insta_error d-block text-dark">{{ __('studentdashborad.instagram_placeholder') }}</div>

                            </div>
                          </div>
                          <!-- Linked in -->
                          {{-- <div class="row mb-5">
                            <div class="col-lg-2 col-md-4 col-12">
                              <h5>LinkedIn</h5>
                            </div>
                            <div class="col-lg-10 col-md-8 col-12">
                              <input type="url" class="form-control mb-1 linkedin" value="{{isset($studentData->linkedIn) ? $studentData->linkedIn : '' }}" name="linkedin" placeholder="https://www.linkedin.com/">
                              <div class="invalid-feedback linkedin_error d-block text-dark" >Add your linkedin profile url.</div>
                            </div>
                          </div> --}}
                          <!-- twitter -->
                          {{-- <div class="row mb-3">
                            <div class="col-lg-2 col-md-4 col-12">
                              <h5>X (Twitter)</h5>
                            </div>
                            <div class="col-lg-10 col-md-8 col-12">
                              <input type="url" class="form-control mb-1 twitter" value="{{isset($studentData->twitter) ? $studentData->twitter : '' }}" name="twitter" placeholder="https://twitter.com/">
                              <div class="invalid-feedback twitter_error d-block text-dark">Add your x (twitter) profile url.</div>
                            </div>
                          </div> --}}
                          <!-- Button -->
                          <div class="row">
                            <div class="offset-lg-2 col-lg-6 col-12">
                              {{-- <a href="#" class="btn btn-primary updateSocialProfile">Save Social Profile</a> --}}
                              <a href="#" class="btn btn-primary updateSocialProfile">{{ __('studentdashborad.save_social') }}</a>

                            </div>
                          </div>
                        </div>
                      </form>
                      </div>
                </div>
            </div>
            </div>
        </div>
    </section>
</main>
  <script>
      //  const textarea = document.getElementById('textarea-input');
      //   const warning = document.getElementById('charCountWarning');

      //   textarea.addEventListener('input', () => {
      //       const charCount = textarea.value.length;
      //       if (charCount > 100) {
      //           warning.style.display = 'block';
      //       } else {
      //           warning.style.display = 'none';
      //       }
      //   });


        const whatsappInput = document.querySelector('.whatsapp');
        whatsappInput.addEventListener('input', function() {
            this.value = this.value.replace(/(?!^\+)\D/g, '');
        });


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
                countryCode +  '</span>'
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
