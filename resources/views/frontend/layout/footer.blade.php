{{-- @php
$setting = App\Models\SiteSetting::find(1);
@endphp --}}

        <!-- footer -->
        <footer class="pt-6 footer">
            {{-- @php
            $staticPages = ['about-us', 'contact-us', 'faq', 'cookies','privacy-policy','terms-and-conditions'];
            $currentPath = request()->path();
        @endphp
        @if(in_array($currentPath, $staticPages) || Str::startsWith($currentPath, $staticPages)) --}}
        <div class="container ">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-6">
                    <!-- about company -->
                    <div class="mb-4">
                        <!-- list -->
                        <h3 class="fw-bold mb-2">{!! __('footer.line_1')!!}</h3>
                        <ul class="list-unstyled nav nav-footer flex-column nav-x-0">
                            <li><a href="{{route('about-us')}}" class="nav-link d-inline-block">{!! __('footer.line_2')!!}</a></li>
                            <li><a href="{{route('our-teachers')}}" class="nav-link d-inline-block">{!! __('footer.line_3')!!}</a></li>
                            <li><a href="{{route('contact-us')}}" class="nav-link d-inline-block">{!! __('footer.line_4')!!}</a></li>
                            <li><a href="{{route('partner-university')}}" class="nav-link d-inline-block">{!! __('footer.line_5')!!}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="mb-4">
                        <h3 class="fw-bold mb-2">{!! __('footer.line_6')!!}</h3>
                        <ul class="list-unstyled nav nav-footer flex-column nav-x-0">
                            {{-- @php
                            $atheLevel3 =
                            getData('course_master',['course_title','id','selling_price','ects','course_final_price','course_old_price','course_thumbnail_file','status'],['category_id'=>'6',['status','!=','2']],'4',DB::raw('IFNULL(published_on, "NULL")'),'asc');
                            @endphp
                            @if(count($atheLevel3) > 0)
                            <li><a href="{{route('level-3-course')}}" class="nav-link d-inline-block">{!! __('footer.line_24')!!}</a></li>
                            @endif
                            @php
                            $atheLevel4 =
                            getData('course_master',['course_title','id','selling_price','ects','course_final_price','course_old_price','course_thumbnail_file','status'],['category_id'=>'7',['status','!=','2']],'4',DB::raw('IFNULL(published_on, "NULL")'),'asc');
                            @endphp
                            @if(count($atheLevel4) > 0)
                            <li><a href="{{route('level-4-course')}}" class="nav-link d-inline-block">{!! __('footer.line_25')!!}</a></li>
                            @endif
                            @php
                            $atheLevel5 =
                            getData('course_master',['course_title','id','selling_price','ects','course_final_price','course_old_price','course_thumbnail_file','status'],['category_id'=>'8',['status','!=','2']],'4',DB::raw('IFNULL(published_on, "NULL")'),'asc');
                            @endphp
                            @if(count($atheLevel5) > 0)
                            <li><a href="{{route('level-5-course')}}" class="nav-link d-inline-block">{!! __('footer.line_26')!!}</a></li>
                            @endif
                            <li><a href="{{route('award-courses')}}" class="nav-link d-inline-block">{!! __('footer.line_7')!!}</a></li>
                            <li><a href="{{route('post-graduate-certificates')}}" class="nav-link d-inline-block">{!! __('static.certificate_name')!!}</a></li>
                            <li><a href="{{route('diploma-courses')}}" class="nav-link d-inline-block">{!! __('footer.line_9')!!}</a></li>
                            <li><a href="{{route('masters-courses')}}" class="nav-link d-inline-block">{!! __('footer.line_10')!!}</a></li>
                            <li><a href="{{route('dba-courses')}}" class="nav-link d-inline-block">{!! __('footer.line_11')!!}</a></li> --}}
                            @php
                            $ExtendDiploma = getData('course_master',['course_title','id','selling_price','ects','course_final_price','course_old_price','course_thumbnail_file','status'],['category_id'=>'9',['status','!=','2']],'',DB::raw('IFNULL(published_on, "NULL")'),'asc');
                            @endphp
                            @if(count($ExtendDiploma) > 0)
                                <li><a href="{{route('extended-diploma-courses')}}" class="nav-link d-inline-block">{!! __('footer.line_27')!!}</a></li>
                            @endif
                            @php
                            $AtheDiploma = getData('course_master',['temp_count','course_title','id','selling_price','ects','course_final_price','course_old_price','course_thumbnail_file','status','category_id'],['category_id'=>'10',['status','!=','2']],'',DB::raw('IFNULL(published_on, "NULL")'),'asc');
                            @endphp 
                            @if(count($AtheDiploma) > 0)
                                <li><a href="{{route('athe-diploma-course')}}" class="nav-link d-inline-block">{!! __('footer.line_28')!!}</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-12">
                    <div class="mb-4">
                        <!-- list -->
                        <h3 class="fw-bold mb-2">{!! __('footer.line_12')!!}</h3>
                        <ul class="list-unstyled nav nav-footer flex-column nav-x-0">
                            <li><a href="{{route('terms-and-conditions')}}" class="nav-link d-inline-block">{!! __('footer.line_13')!!}</a></li>
                            <li><a href="{{route('privacy-policy')}}" class="nav-link d-inline-block">{!! __('footer.line_14')!!}</a></li>
                            <li><a href="{{route('faq')}}" class="nav-link d-inline-block">{!! __('footer.line_15')!!}</a></li>
                            <li><a href="{{route('cookies')}}" class="nav-link d-inline-block">{!! __('footer.line_16')!!}</a></li>
                            {{-- <li><a href="#" onclick="return false;" class="nav-link">Sitemap</a></li> --}}
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-12">
                    <!-- contact info -->
                    <div class="mb-3">
                        <h3 class="fw-bold mb-2">{!! __('footer.line_17')!!}</h3>
                        {{-- <p class="mb-1">23, Vincenzo Dimech Street, Malta</p> --}}

                        @if (Auth::guest() || (Auth::check() && Auth::user()->role == 'user'))
                        @if (Route::current()->getName() == '/' || Route::current()->getName() == 'index')
                        {{-- <div class="mb-3">
                            <form class="newsletter-form" action="" method="POST">
                                <input type="email" name="email" placeholder="Enter your email" required class="form-control">
                                <button type="submit" class="btn btn-primary rounded-0">Subscribe</button>
                            </form>
                        </div> --}}
                        @endif
                        @endif
                        <p class="mb-0">
                            {!! __('footer.line_18')!!}
                            <a href="mailto:info@eascencia.mt" class=" d-inline-block">{!! __('footer.line_19')!!}</a>
                        </p>
                        {{-- <p class="mb-1">
                            {!! __('footer.line_20')!!}
                            <a href="mailto:support@eascencia.mt" class=" d-inline-block">{!! __('footer.line_21')!!}</a>
                        </p> --}}
                        {{-- <p>
                            Phone:
                            <span class="text-dark fw-semibold"><a href="tel:+91 740017795" class="text-dark">+91 7400177951</a></span>
                        </p> --}}
                    </div>
                    <div class="fs-6 mt-3">
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
                    {{-- <a href="#" target="_blank" onclick="return false"><img class="social-logo mb-2 "
                            src="{{ asset('frontend/images/social/social-media-07.png') }}" alt="social logo"></a> --}}
                    {{-- <a href="https://www.quora.com/profile/E-Ascencia-Malta" target="_blank"><img class="social-logo mb-2 "
                            src="{{ asset('frontend/images/social/social-media-08.png') }}" alt="social logo"></a> --}}
                    </div>
                </div>
            </div>
            <div class="row align-items-center g-0 py-2">
                <div class="col-lg-12 col-md-12 col-12">
                    <nav class="nav nav-footer justify-content-center">
                         @php
                           $staticPages = ['checkout'];
                            $currentPath = request()->path();
                            $isStatic = in_array($currentPath, $staticPages);
                           @endphp
                    <a class="nav-link langcode{{ app()->getLocale() == 'en' ? ' active' : '' }}{{ $isStatic ? ' disableClick' : '' }}" href="{{ route('lang.switch', 'en') }}" style="padding: 0.5rem 1rem;">English</a>
                    <a class="nav-link langcode{{ app()->getLocale() == 'zh' ? ' active' : '' }}{{ $isStatic ? ' disableClick' : '' }} mx-2" href="{{ route('lang.switch', 'zh') }}" style="padding: 0.5rem 1rem;">中文</a>
                    <a class="nav-link langcode{{ app()->getLocale() == 'es' ? ' active' : '' }}{{ $isStatic ? ' disableClick' : '' }}" href="{{ route('lang.switch', 'es') }}" style="padding: 0.5rem 1rem;">Español</a>
                    <a class="nav-link langcode{{ app()->getLocale() == 'fr' ? ' active' : '' }}{{ $isStatic ? ' disableClick' : '' }} mx-2" href="{{ route('lang.switch', 'fr') }}" style="padding: 0.5rem 1rem;">Français</a>
                    <a class="nav-link langcode{{ app()->getLocale() == 'ar' ? ' active' : '' }}{{ $isStatic ? ' disableClick' : '' }}" href="{{ route('lang.switch', 'ar') }}" style="padding: 0.5rem 1rem;">اللغة العربية</a>

                    </nav>
                </div>
            </div>
            <div class="row align-items-center g-0 border-top py-2 mt-3">
                <!-- Desc -->
                <div class="col-lg-12 col-md-12 col-12">
                    <span>
                        ©
                        <span id="copyright2">
                            <script>
                                document.getElementById("copyright2").appendChild(document.createTextNode(new Date().getFullYear()));
                            </script>
                        </span>
                        {!! __('footer.line_23')!!}
                    </span>
                </div>


            </div>
        </div>
        {{-- @else
            <div class="container ">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-6">

                        <div class="mb-4">

                            <h3 class="fw-bold mb-2">E-Paris Business Academy</h3>
                            <ul class="list-unstyled nav nav-footer flex-column nav-x-0">
                                <li><a href="{{route('about-us')}}" class="nav-link d-inline-block">About Us</a></li>
                                <li><a href="{{route('our-teachers')}}" class="nav-link d-inline-block">Our Teachers</a></li>
                                <li><a href="{{route('contact-us')}}" class="nav-link d-inline-block">Contact Us</a></li>
                                <li><a href="{{route('partner-university')}}" class="nav-link d-inline-block">Approved Partners</a></li>



                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="mb-4">

                            <h3 class="fw-bold mb-2">Course</h3>
                            <ul class="list-unstyled nav nav-footer flex-column nav-x-0">
                                <li><a href="{{route('award-courses')}}" class="nav-link d-inline-block">Award</a></li>
                                <li><a href="{{route('certificate-courses')}}" class="nav-link d-inline-block">Certificate</a></li>
                                <li><a href="{{route('diploma-courses')}}" class="nav-link d-inline-block">Diploma</a></li>
                                <li><a href="{{route('masters-courses')}}" class="nav-link d-inline-block">Masters</a></li>
                                <li><a href="{{route('dba-courses')}}" class="nav-link d-inline-block">DBA</a></li>
                                <li><a href="{{route('english-course-program')}}" class="nav-link d-inline-block">Language Courses</a></li>


                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="mb-4">

                            <h3 class="fw-bold mb-2">Support</h3>
                            <ul class="list-unstyled nav nav-footer flex-column nav-x-0">
                                <li><a href="{{route('terms-and-conditions')}}" class="nav-link d-inline-block">Terms and Conditions</a></li>
                                <li><a href="{{route('privacy-policy')}}" class="nav-link d-inline-block">Privacy Policy</a></li>
                                <li><a href="{{route('faq')}}" class="nav-link d-inline-block">FAQ's</a></li>
                                <li><a href="{{route('cookies')}}" class="nav-link d-inline-block">Cookies</a></li>

                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-12">

                        <div class="mb-3">
                            <h3 class="fw-bold mb-2">Get in Touch</h3>


                            @if (Auth::guest() || (Auth::check() && Auth::user()->role == 'user'))
                            @if (Route::current()->getName() == '/' || Route::current()->getName() == 'index')

                            @endif
                            @endif
                            <p class="mb-0">
                                Contact:
                                <a href="mailto:info@eascencia.mt" class="color-blue d-inline-block">info@eascencia.mt</a>
                            </p>
                            <p class="mb-1">
                                Support:
                                <a href="mailto:support@eascencia.mt" class="color-blue d-inline-block">support@eascencia.mt</a>
                            </p>

                        </div>
                        <div class="fs-6 mt-3">
                        <a href="https://www.facebook.com/people/E-Ascencia-Malta/61559646792486/" target="_blank"><img class="social-logo mb-2 "
                                src="{{ asset('frontend/images/social/social-media-01.png') }}" alt="social logo"></a>
                        <a href="https://www.instagram.com/eascencia/" target="_blank"><img class="social-logo mb-2 "
                                src="{{ asset('frontend/images/social/social-media-02.png') }}" alt="social logo"></a>
                        <a href="https://www.linkedin.com/company/ascencia-malta-business-school/" target="_blank"><img class="social-logo mb-2 "
                                src="{{ asset('frontend/images/social/social-media-03.png') }}" alt="social logo"></a>
                        <a href="https://x.com/eascenciamalta" target="_blank"><img class="social-logo mb-2 twitterLogoStyle"
                                src="{{ asset('frontend/images/social/social-media-09.png') }} " alt="social logo"></a>

                        </div>
                    </div>
                </div>
                <div class="row align-items-center g-0 border-top py-2 mt-3">

                    <div class="col-lg-12 col-md-12 col-12">
                        <span>
                            ©
                            <span id="copyright2">
                                <script>
                                    document.getElementById("copyright2").appendChild(document.createTextNode(new Date().getFullYear()));
                                </script>
                            </span>
                            E-Paris Business Academy, Inc. All Rights Reserved.
                        </span>
                    </div>


                </div>
            </div>
            @endif --}}
        </footer>
