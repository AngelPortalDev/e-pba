
                    <!-- Side navbar -->
                    <nav class="navbar navbar-expand-md shadow-sm mb-4 mb-lg-0 sidenav">
                        <!-- Menu -->
                        <a class="d-xl-none d-lg-none d-md-none text-inherit fw-bold" href="#">Menu</a>
                        <!-- Button -->
                        <button
                            class="navbar-toggler d-md-none icon-shape icon-sm rounded bg-primary text-light"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#sidenav"
                            aria-controls="sidenav"
                            aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="fe fe-menu"></span>
                        </button>
                        <!-- Collapse navbar -->
                        <div class="collapse navbar-collapse" id="sidenav">
                            <div class="navbar-nav flex-column">

                                {{-- <span class="navbar-header">Account Settings</span> --}}
                                <span class="navbar-header">{{ __('studentdashborad.account_settings') }}</span>

                                <!-- List -->
                                <ul class="list-unstyled ms-n2 mb-0">
                                    <!-- Nav item -->
                                    <li class="nav-item sp-1">
                                        <a class="nav-link " href="{{route('dashboard')}}">
                                            <i class="bi bi-person-circle nav-icon"></i>
                                            {{-- My Profile --}}
                                            {{ __('studentdashborad.my_profile') }}
                                        </a>
                                    </li>
                                    <!-- Nav item -->
                                    {{-- @if(Auth::user()->apply_dba == 'Yes')
                                        <li class="nav-item sp-3">
                                            <a class="nav-link" href="{{route('student-document-verification')}}">
                                                <i class="fe fe-clipboard nav-icon"></i>
                                                Document Verification
                                            </a>
                                        </li>
                                    @else
                                        @if (is_exist('student_course_master',['user_id'=>Auth::user()->id]) > 0 && is_exist('orders',['user_id'=>Auth::user()->id,'status'=>'0']) > 0) --}}
                                        <li class="nav-item sp-3">
                                            <a class="nav-link" href="{{route('student-document-verification')}}">
                                                <i class="fe fe-clipboard nav-icon"></i>
                                                {{-- Document Verification --}}
                                                {{ __('studentdashborad.document_verification') }}
                                            </a>
                                        </li>
                                        {{-- @endif --}}
                                    {{-- @endif --}}
                                    @php $payment_installment =  DB::table('payment_installment')->where('user_id', Auth::user()->id)->where('paid_install_status','0')->first(); @endphp
                                    @if(!empty($payment_installment))
                                    <li class="nav-item sp-7">
                                        <a class="nav-link {{ buyNowDisabledClass() }}" href="{{route('student-payment-details')}}">
                                            <i class="bi bi-currency-euro nav-icon"></i>
                                            {{-- Invoice --}}
                                            {{-- {{ __('studentdashborad.paymentdetails') }} --}}
                                            Payment Details
                                        </a>
                                    </li>
                                    @endif

                                    <!-- Nav item -->
                                       <li class="nav-item sp-5">
                                        <a class="nav-link {{ buyNowDisabledClass() }}" href="{{route('student-invoice')}}">
                                            <i class="bi bi-currency-euro nav-icon"></i>
                                            {{-- Invoice --}}
                                            {{ __('studentdashborad.invoice') }}
                                        </a>
                                    </li>

                                    {{-- <li class="nav-item sp-8">
                                        <a class="nav-link " href="{{route('student-about-me')}}">
                                            <i class="fe fe-settings nav-icon"></i>
                                            About Me
                                        </a>
                                    </li> --}}
                                    <!-- Nav item -->
                                    {{-- <li class="nav-item sp-2">
                                        <a class="nav-link" href="{{route('student-social-profile')}}">
                                            <i class="fe fe-calendar nav-icon"></i>
                                            Social Profile
                                        </a>
                                    </li> --}}

                                    <!-- Nav item -->
                                    <li class="nav-item sp-4">
                                        <a class="nav-link {{ buyNowDisabledClass() }}" href="{{route('student-account-security')}}" >
                                            <i class="bi bi-shield-lock nav-icon"></i>
                                            {{-- Account Security --}}
                                            {{ __('studentdashborad.account_security') }}
                                        </a>
                                    </li>

                                    <!-- Nav item -->
                                    <li class="nav-item sp-6">
                                        <a class="nav-link {{ buyNowDisabledClass() }}" href="{{route('student-deactivate-account')}}">
                                            <i class="bi bi-person-x nav-icon"></i>
                                            {{-- Deactivate Account --}}
                                            {{ __('studentdashborad.deactivate_account') }}
                                        </a>
                                    </li>

                                    <!-- Nav item -->
                                    <li class="nav-item sp-7">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                        <a class="nav-link {{ buyNowDisabledClass() }}" href="{{route('logout')}}"  onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                            <i class="fe fe-power nav-icon"></i>
                                            {{-- Sign Out--}}
                                            {{ __('studentdashborad.sign_out') }}
                                        </a></form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
