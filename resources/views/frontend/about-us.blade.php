@extends('frontend.master')
@section('content')
    <section class="py-lg-8 py-6">
        <div class="container my-lg-8">
            <div class="row d-flex align-items-center">
                <div class="col-xxl-5 col-xl-6 col-lg-6 col-12">
                    <h3 class="display-4 fw-bold mb-3">
                        {!! __('about.about_title') !!}

                    </h3>
                    <p><span class="text-dark learning_para">{!! __('about.digital_flexibility') !!}</span></p>
                    <p class="about_us_para mb-4">{!! __('about.about_content') !!}</p>
                    <p>{!! __('about.about_content1') !!} </p>
                    {{-- <a href="#!" class="btn btn-dark btn-lg">Explore Online Courses</a> --}}
                </div>
                <div class="col-xxl-5 offset-xxl-1 col-xl-6 col-lg-6 col-12 d-lg-flex justify-content-end">
                    <div class="mt-8 mt-lg-0 position-relative">
                        <div class="position-absolute top-0 start-0 translate-middle d-none d-md-block">
                             <img src="{{ asset('frontend/images/team/graphics-2-01.png') }}" alt="graphics-2" style="height: 135px; width: auto;">
                        </div>
                        <img src="{{ asset('frontend/images/team/about_us_5.webp') }}" alt="online course"
                            class="img-fluid rounded-4 w-100 z-1 position-relative">
                        <div class="position-absolute top-100 start-100 translate-middle d-none d-md-block">
                            <img src="{{ asset('frontend/images/team/graphics_1.svg') }}" alt="graphics-1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="whoweare bg-white pb-8 pt-6">
        <div class="container">
            <h1 class="fw-bold display-5">{!! __('about.who_we_are') !!}</h1>

            <p class="">{!! __('about.who_we_are_para_1') !!}</p>
            <p class="mb-0">{!! __('about.who_we_are_para_2') !!}</p>
        </div>
    </section>

    <section class="py-lg-8 py-6 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="badge bg-opacity-10 bg-primary text-white px-3 py-2 rounded-pill mb-3 d-none">
                        <svg width="16" height="16" class="me-1" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Officially Accredited
                    </span>
                    <h2 class="display-4 fw-bold mb-4">{!! __('about.mqf_program_title') !!}</h2>
                    <p class=" mb-4" style="font-size: 16px;">{!! __('about.mqf_program_para') !!}
                        </p>
                    <p class=" mb-4" style="font-size: 16px;font-weight: 700;">{!! __('about.mqf_program_para_2') !!}</p>



                    {{-- <div class="d-flex align-items-start mb-3">
                        <div class="flex-shrink-0">
                            <svg width="24" height="24" class="text-dark mt-1" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 13L9 17L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">{!! __('about.mqf_title1') !!}</h5>
                        </div>
                    </div> --}}
{{-- 
                    <div class="d-flex align-items-start mb-5">
                        <div class="flex-shrink-0">
                            <svg width="24" height="24" class="text-dark mt-1" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 13L9 17L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">{!! __('about.mqf_title1') !!}</h5>
                        </div>
                    </div> --}}

                    {{-- <div class="bg-white rounded-3 p-4 border-start border-4 border-success shadow-sm mt-5 pt-4"> --}}
                        <p class="mb-0 text-muted" style="font-size: 16px;">{!! __('about.MQF_description') !!}
                        </p>
                    {{-- </div> --}}
                </div>
    
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-gradient-primary p-4">
                            <h3 class="mb-0 d-flex align-items-center">
                                <svg width="24" height="24" class="me-2" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 14L21 9L12 4L3 9L12 14Z" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M21 16L12 21L3 16" stroke="white" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M21 12L12 17L3 12" stroke="white" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                {!! __('about.accredited_heading') !!}
                            </h3>
                        </div>

                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">

                                <!-- ITEM 1 -->
                                <div class="list-group-item p-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-wrapper">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                <circle cx="12" cy="12" r="10" stroke="#00875A"
                                                    stroke-width="2" />
                                                <path d="M2 12H22" stroke="#00875A" stroke-width="2" />
                                                <path
                                                    d="M12 2C14.5 4.74 16 8.29 16 12C16 15.71 14.5 19.26 12 22C9.5 19.26 8 15.71 8 12C8 8.29 9.5 4.74 12 2Z"
                                                    stroke="#00875A" stroke-width="2" />
                                            </svg>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-1">
                                                {!! __('about.accredited_title_one') !!}
                                                </h5>
                                        </div>
                                    </div>
                                </div>

                                <!-- ITEM 2 -->
                                <div class="list-group-item p-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-wrapper">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                <path d="M3 3H21V21H3V3Z" stroke="#754FFE" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M3 9H21M9 21V9" stroke="#754FFE" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-1">
                                                {!! __('about.accredited_title_two') !!}
                                                </h5>
                                        </div>
                                    </div>
                                </div>

                                <!-- ITEM 3 -->
                                <div class="list-group-item p-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-wrapper">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z"
                                                    stroke="#0095FF" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M9 22V12H15V22" stroke="#0095FF" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-1">
                                                {!! __('about.accredited_title_three') !!}
                                                </h5>
                                        </div>
                                    </div>
                                </div>

                                <!-- ITEM 4 -->
                                <div class="list-group-item p-4">
                                    <div class="d-flex align-items-start">
                                        <div class="icon-wrapper">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21"
                                                    stroke="#FF8B00" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z"
                                                    stroke="#FF8B00" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13"
                                                    stroke="#FF8B00" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M16 3.13C16.8604 3.3503 17.623 3.85067 18.1676 4.55232C18.7122 5.25397 19.0078 6.11683 19.0078 7.005C19.0078 7.89317 18.7122 8.75603 18.1676 9.45768C17.623 10.1593 16.8604 10.6597 16 10.88"
                                                    stroke="#FF8B00" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-1">{!! __('about.accredited_title_four') !!}</h5>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
    </section>

    <section class="py-lg-8 py-6 bg-white">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-10 mx-auto text-center">
                    <h2 class="display-4 fw-bold mb-4">{!! __('about.why_choose') !!}</h2>
                    <p class="lead text-muted">{!! __('about.why_choose_content') !!}</p>
                </div>
            </div>

            <div class="row g-4">
                <!-- Accreditation Card -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 digitalcampusCard">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="icon-xxl bg-opacity-10 rounded-circle digitalIcons_1">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                                stroke="#00875A" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <h3 class="h4 mb-3">{!! __('about.why_choose_feature_one') !!}</h3>
                                    <p class="text-muted mb-0">{!! __('about.why_choose_feature_one_content') !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credibility Card -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 digitalcampusCard">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="icon-xxl bg-opacity-10 rounded-circle digitalIcons_2">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19 21V5C19 4.46957 18.7893 3.96086 18.4142 3.58579C18.0391 3.21071 17.5304 3 17 3H7C6.46957 3 5.96086 3.21071 5.58579 3.58579C5.21071 3.96086 5 4.46957 5 5V21M3 21H21M9 7H10M9 11H10M14 7H15M14 11H15"
                                                stroke="#754FFE" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <h3 class="h4 mb-3">{!! __('about.why_choose_feature_two') !!}</h3>
                                    <p class="text-muted mb-0">{!! __('about.why_choose_feature_two_content') !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flexibility Card -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 digitalcampusCard">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="icon-xxl bg-opacity-10 rounded-circle digitalIcons_3">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 8V12L15 15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                                stroke="#0095FF" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <h3 class="h4 mb-3">{!! __('about.why_choose_feature_three') !!}</h3>
                                    <p class="text-muted mb-0">{!! __('about.why_choose_feature_three_content') !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quality Teaching Card -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 digitalcampusCard">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="icon-xxl bg-opacity-10 rounded-circle digitalIcons_4">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 14L21 9L12 4L3 9L12 14ZM12 14L18.16 10.84C18.38 11.86 18.5 12.91 18.5 14C18.5 15.09 18.38 16.14 18.16 17.16L12 20.5L5.84 17.16C5.62 16.14 5.5 15.09 5.5 14C5.5 12.91 5.62 11.86 5.84 10.84L12 14Z"
                                                stroke="#FF8B00" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <h3 class="h4 mb-3">{!! __('about.why_choose_feature_four') !!}</h3>
                                    <p class="text-muted mb-0">{!! __('about.why_choose_feature_four_content') !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supportive Community Card -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 digitalcampusCard">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class=" icon-xxl bg-opacity-10 rounded-circle digitalIcons_5">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13M16 3.13C16.8604 3.3503 17.623 3.8507 18.1676 4.55231C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89317 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88M13 7C13 9.20914 11.2091 11 9 11C6.79086 11 5 9.20914 5 7C5 4.79086 6.79086 3 9 3C11.2091 3 13 4.79086 13 7Z"
                                                stroke="#E53935" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <h3 class="h4 mb-3">{!! __('about.why_choose_feature_five') !!}</h3>
                                    <p class="text-muted mb-0">{!! __('about.why_choose_feature_five_content') !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- International Relevance Card -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100 digitalcampusCard">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="icon-xxl bg-opacity-10 rounded-circle digitalIcons_6">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="12" r="10" stroke="#754FFE"
                                                stroke-width="2" />
                                            <path d="M2 12H22" stroke="#754FFE" stroke-width="2"
                                                stroke-linecap="round" />
                                            <path
                                                d="M12 2C14.5013 4.73835 15.9228 8.29203 16 12C15.9228 15.708 14.5013 19.2616 12 22C9.49872 19.2616 8.07725 15.708 8 12C8.07725 8.29203 9.49872 4.73835 12 2Z"
                                                stroke="#754FFE" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ms-4">
                                    <h3 class="h4 mb-3">{!! __('about.why_choose_feature_six') !!}</h3>
                                    <p class="text-muted mb-0">{!! __('about.why_choose_feature_six_content') !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-lg-8 py-5 bg-light">
        <div class="container px-lg-6 my-lg-8">
            <div class="row align-items-center gy-4 gy-lg-0">
                <div class="col-lg-5">
                    <div class="row align-items-end g-3 mb-3">
                        <div class="col-6">
                            <img src="{{ asset('frontend/images/team/about_us_6.webp') }}" alt=""
                                class="img-fluid rounded-3 w-100">
                        </div>
                        <div class="col-6">
                            <img src="{{ asset('frontend/images/team/about_us_7.webp') }}" alt=""
                                class="img-fluid rounded-3 w-100">
                        </div>
                    </div>
                    <img src="{{ asset('frontend/images/team/about_us_9.webp') }}" alt=""
                        class="img-fluid rounded-3 w-100">
                </div>
                <div class="col-lg-6 col-12 ms-lg-8">
                    <div class="mb-5">
                        {{-- <span class="fw-semibold text-primary">About - Your Trusted Partner</span> --}}
                        {{-- <h2 class="h1 my-3">{{ __('about.about_academic') }}</h2>
                        <p class="mb-0">
                            {!! __('about.about_academic_content') !!}
                        </p> --}}
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.2"
                                    d="M16.5 12.3037C16.5 13.1937 16.2361 14.0638 15.7416 14.8038C15.2471 15.5438 14.5443 16.1206 13.7221 16.4612C12.8998 16.8018 11.995 16.8909 11.1221 16.7172C10.2492 16.5436 9.44736 16.115 8.81802 15.4857C8.18869 14.8564 7.7601 14.0545 7.58647 13.1816C7.41283 12.3087 7.50195 11.4039 7.84254 10.5816C8.18314 9.75937 8.75991 9.05656 9.49994 8.5621C10.24 8.06763 11.11 7.80371 12 7.80371C13.1935 7.80371 14.3381 8.27782 15.182 9.12173C16.0259 9.96564 16.5 11.1102 16.5 12.3037Z"
                                    fill="#E53935"></path>
                                <path
                                    d="M20.8004 8.09997C21.8412 10.2767 22.0387 12.7617 21.355 15.0755C20.6713 17.3893 19.1547 19.3678 17.098 20.6292C15.0413 21.8906 12.5902 22.3454 10.2179 21.9059C7.84557 21.4664 5.72013 20.1637 4.25179 18.2492C2.78344 16.3348 2.0763 13.9443 2.26682 11.5391C2.45735 9.13396 3.53204 6.88461 5.28348 5.22522C7.03492 3.56583 9.33895 2.61402 11.7509 2.55349C14.1628 2.49296 16.5117 3.32801 18.3442 4.89747L20.4695 2.77122C20.6102 2.63049 20.8011 2.55143 21.0001 2.55143C21.1991 2.55143 21.39 2.63049 21.5307 2.77122C21.6715 2.91195 21.7505 3.10282 21.7505 3.30184C21.7505 3.50087 21.6715 3.69174 21.5307 3.83247L12.5307 12.8325C12.39 12.9732 12.1991 13.0523 12.0001 13.0523C11.8011 13.0523 11.6102 12.9732 11.4695 12.8325C11.3288 12.6917 11.2497 12.5009 11.2497 12.3018C11.2497 12.1028 11.3288 11.9119 11.4695 11.7712L14.0682 9.17247C13.3639 8.70668 12.5231 8.49221 11.6817 8.56379C10.8404 8.63538 10.0478 8.98881 9.43231 9.56689C8.81683 10.145 8.41446 10.9139 8.29034 11.7491C8.16622 12.5843 8.32762 13.437 8.7484 14.169C9.16919 14.9011 9.82473 15.4697 10.6089 15.7829C11.3931 16.096 12.26 16.1354 13.0693 15.8945C13.8786 15.6536 14.5829 15.1467 15.0683 14.4557C15.5536 13.7647 15.7915 12.9302 15.7435 12.0872C15.738 11.9887 15.7519 11.89 15.7845 11.7969C15.8171 11.7038 15.8677 11.618 15.9334 11.5445C15.9991 11.4709 16.0787 11.411 16.1676 11.3682C16.2564 11.3254 16.3529 11.3005 16.4514 11.295C16.6503 11.2838 16.8455 11.3521 16.994 11.4848C17.0676 11.5505 17.1275 11.6301 17.1703 11.719C17.2131 11.8079 17.238 11.9043 17.2435 12.0028C17.3119 13.196 16.9711 14.3769 16.2774 15.3501C15.5838 16.3234 14.5788 17.0309 13.4285 17.3556C12.2783 17.6804 11.0517 17.6029 9.95148 17.1361C8.85123 16.6692 7.94322 15.8409 7.3775 14.7881C6.81178 13.7353 6.62223 12.521 6.84017 11.3458C7.05811 10.1707 7.67049 9.10504 8.57611 8.32509C9.48173 7.54514 10.6264 7.09753 11.8208 7.05626C13.0153 7.01499 14.1881 7.38251 15.1454 8.09809L17.2782 5.96528C15.7152 4.66759 13.7279 3.99312 11.6979 4.07141C9.66793 4.14971 7.73844 4.97524 6.28003 6.38947C4.82163 7.80369 3.93715 9.70688 3.79646 11.7335C3.65578 13.7601 4.26881 15.7673 5.51782 17.3694C6.76683 18.9716 8.56375 20.0558 10.5634 20.4138C12.5631 20.7719 14.6246 20.3785 16.3519 19.3092C18.0792 18.2399 19.3506 16.5701 19.9218 14.6206C20.493 12.6711 20.3238 10.5792 19.4467 8.74684C19.3609 8.56733 19.3499 8.36108 19.4162 8.17349C19.4825 7.98589 19.6206 7.83231 19.8001 7.74653C19.9796 7.66075 20.1859 7.6498 20.3735 7.71608C20.5611 7.78236 20.7146 7.92045 20.8004 8.09997Z"
                                    fill="#E53935"></path>
                            </svg>

                            <h3 class="mb-0">{{ __('about.mission') }}</h3>
                        </div>
                        <p class="mb-0">
                            {!! __('about.misson_content') !!}
                        </p>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.2"
                                    d="M12 5.55371C4.5 5.55371 1.5 12.3037 1.5 12.3037C1.5 12.3037 4.5 19.0537 12 19.0537C19.5 19.0537 22.5 12.3037 22.5 12.3037C22.5 12.3037 19.5 5.55371 12 5.55371ZM12 16.0537C11.2583 16.0537 10.5333 15.8338 9.91661 15.4217C9.29993 15.0097 8.81928 14.424 8.53545 13.7388C8.25162 13.0536 8.17736 12.2996 8.32205 11.5721C8.46675 10.8447 8.8239 10.1765 9.34835 9.65206C9.8728 9.12761 10.541 8.77046 11.2684 8.62577C11.9958 8.48107 12.7498 8.55533 13.4351 8.83916C14.1203 9.12299 14.706 9.60364 15.118 10.2203C15.5301 10.837 15.75 11.562 15.75 12.3037C15.75 13.2983 15.3549 14.2521 14.6517 14.9554C13.9484 15.6586 12.9946 16.0537 12 16.0537Z"
                                    fill="#E53935"></path>
                                <path
                                    d="M23.1853 12C23.1525 11.9259 22.3584 10.1643 20.5931 8.39902C18.2409 6.04684 15.27 4.80371 12 4.80371C8.72999 4.80371 5.75905 6.04684 3.40687 8.39902C1.64155 10.1643 0.843741 11.9287 0.814679 12C0.772035 12.0959 0.75 12.1997 0.75 12.3046C0.75 12.4096 0.772035 12.5134 0.814679 12.6093C0.847491 12.6834 1.64155 14.444 3.40687 16.2093C5.75905 18.5606 8.72999 19.8037 12 19.8037C15.27 19.8037 18.2409 18.5606 20.5931 16.2093C22.3584 14.444 23.1525 12.6834 23.1853 12.6093C23.2279 12.5134 23.25 12.4096 23.25 12.3046C23.25 12.1997 23.2279 12.0959 23.1853 12ZM12 18.3037C9.11437 18.3037 6.59343 17.2546 4.50655 15.1865C3.65028 14.335 2.92179 13.364 2.34374 12.3037C2.92164 11.2433 3.65014 10.2723 4.50655 9.4209C6.59343 7.35277 9.11437 6.30371 12 6.30371C14.8856 6.30371 17.4066 7.35277 19.4934 9.4209C20.3514 10.2721 21.0815 11.2431 21.6609 12.3037C20.985 13.5656 18.0403 18.3037 12 18.3037ZM12 7.80371C11.11 7.80371 10.2399 8.06763 9.49993 8.5621C8.7599 9.05656 8.18313 9.75937 7.84253 10.5816C7.50194 11.4039 7.41282 12.3087 7.58646 13.1816C7.76009 14.0545 8.18867 14.8564 8.81801 15.4857C9.44735 16.115 10.2492 16.5436 11.1221 16.7172C11.995 16.8909 12.8998 16.8018 13.7221 16.4612C14.5443 16.1206 15.2471 15.5438 15.7416 14.8038C16.2361 14.0638 16.5 13.1937 16.5 12.3037C16.4988 11.1106 16.0242 9.96675 15.1806 9.1231C14.337 8.27946 13.1931 7.80495 12 7.80371ZM12 15.3037C11.4066 15.3037 10.8266 15.1278 10.3333 14.7981C9.83993 14.4685 9.45542 13.9999 9.22835 13.4518C9.00129 12.9036 8.94188 12.3004 9.05764 11.7184C9.17339 11.1365 9.45911 10.6019 9.87867 10.1824C10.2982 9.76283 10.8328 9.47711 11.4147 9.36135C11.9967 9.2456 12.5999 9.30501 13.148 9.53207C13.6962 9.75913 14.1648 10.1437 14.4944 10.637C14.824 11.1303 15 11.7104 15 12.3037C15 13.0994 14.6839 13.8624 14.1213 14.425C13.5587 14.9876 12.7956 15.3037 12 15.3037Z"
                                    fill="#E53935"></path>
                            </svg>

                            <h3 class="mb-0">{{ __('about.vision') }}</h3>
                        </div>

                        <p class="mb-0">
                            {{ __('about.vision_content') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- features -->
    <section class="pb-4 pt-6 about-us-values bg-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-8 col-12 mb-3">
                    <!-- caption -->
                    <h2 class="display-5 fw-bold">{{ __('about.values') }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12 d-flex">
                    <!-- card -->
                    <div class="card mb-4  valuescard">
                        <!-- card body -->
                        <div class="card-body p-5">
                            <!-- icon -->
                            <div class="mb-3">
                                <img src="{{ asset('frontend/images/icon/about-us-icon-1.svg') }}" alt="">
                            </div>
                            <h3 class="mb-2">{{ __('about.excellence') }}</h3>
                            <p class="mb-0"> {!! __('about.excellence_content') !!}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 d-flex">
                    <!-- card -->
                    <div class="card mb-4  valuescard">
                        <!-- card body -->
                        <div class="card-body p-5">
                            <!-- icon -->
                            <div class="mb-3">
                                <img src="{{ asset('frontend/images/icon/about-us-icons-02.svg') }}" alt="">
                            </div>
                            <h3 class="mb-2">{{ __('about.accessibility') }}</h3>
                            <p class="mb-0">{{ __('about.accessibility_conetnt') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 d-flex">
                    <!-- card -->
                    <div class="card mb-4 valuescard ">
                        <!-- card body -->
                        <div class="card-body p-5">
                            <!-- icon -->
                            <div class="mb-3">
                                <img src="{{ asset('frontend/images/icon/about-us-icon-3.svg') }}" alt="">
                            </div>
                            <h3 class="mb-2">{{ __('about.innovation') }}</h3>
                            <p class="mb-0">{{ __('about.innovation_content') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 d-flex">
                    <!-- card -->
                    <div class="card mb-4  valuescard">
                        <!-- card body -->
                        <div class="card-body p-5">
                            <!-- icon -->
                            <div class="mb-3">
                                <img src="{{ asset('frontend/images/icon/about-us-icon-4.svg') }}" alt="">
                            </div>
                            <h3 class="mb-2">{{ __('about.integrity') }}</h3>
                            <p class="mb-0"> {{ __('about.integrity_content') }} </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 d-flex">
                    <!-- card -->
                    <div class="card mb-4  valuescard">
                        <!-- card body -->
                        <div class="card-body p-5">
                            <!-- icon -->
                            <div class="mb-3">
                                <img src="{{ asset('frontend/images/icon/about-us-icon-5.svg') }}" alt="">
                            </div>
                            <h3 class="mb-2">{{ __('about.empowerment') }} </h3>
                            <p class="mb-0">{{ __('about.empowerment_content') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 d-flex">
                    <!-- card -->
                    <div class="card mb-4 valuescard">
                        <!-- card body -->
                        <div class="card-body p-5">
                            <!-- icon -->
                            <div class="mb-3">
                                <img src="{{ asset('frontend/images/icon/about-us-icon-6.svg') }}" alt="">
                            </div>
                            <h3 class="mb-2">{{ __('about.collaboration') }}</h3>
                            <p class="mb-0">{{ __('about.collaboration_content') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-8 pt-6 about-us-learning-works bg-light">
        <div class="container">
            <!-- Section Header -->
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-4 fw-bold mb-3">{!! __('about.how_learning_works') !!}</h2>
                    <p class="lead text-muted">{!! __('about.how_learning_works_content') !!}</p>
                </div>
            </div>

            <!-- Learning Features Grid -->
            <div class="row g-4 mb-6">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-lift learning-works-body">
                        <div class="card-body p-4 leaning-works-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="about-us-learning-icon icon-lg bg-opacity-10 rounded-3 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#754FFE" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2 17L12 22L22 17" stroke="#754FFE" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2 12L12 17L22 12" stroke="#754FFE" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            {{-- <h5 class="mb-2">Enrol Online</h5> --}}
                            <h4 class="mb-2">{!! __('about.learning_step_one') !!}</h4>

                            {{-- <p class="text-muted mb-0"></p> --}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-lift learning-works-body">
                        <div class="card-body p-4 leaning-works-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="about-us-learning-icon icon-lg bg-opacity-10 rounded-3 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 16L8 12L9.41 10.59L12 13.17L16.59 8.58L18 10L12 16Z"
                                            fill="#00875A" />
                                    </svg>
                                </div>
                            </div>
                            {{-- <h5 class="mb-2">Learn at Your Pace</h5> --}}
                            <h4 class="mb-2">{!! __('about.learning_step_two') !!}</h4>
                        
                            {{-- <p class="text-muted mb-0">Access lectures, materials, and assignments whenever suits you best.
                            </p> --}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-lift learning-works-body">
                        <div class="card-body p-4 leaning-works-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="about-us-learning-icon icon-lg bg-opacity-10 rounded-3 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M17 8H20C20.5304 8 21.0391 8.21071 21.4142 8.58579C21.7893 8.96086 22 9.46957 22 10V20C22 20.5304 21.7893 21.0391 21.4142 21.4142C21.0391 21.7893 20.5304 22 20 22H4C3.46957 22 2.96086 21.7893 2.58579 21.4142C2.21071 21.0391 2 20.5304 2 20V10C2 9.46957 2.21071 8.96086 2.58579 8.58579C2.96086 8.21071 3.46957 8 4 8H7"
                                            stroke="#FF8B00" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M15 2H9C8.44772 2 8 2.44772 8 3V8H16V3C16 2.44772 15.5523 2 15 2Z"
                                            stroke="#FF8B00" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            {{-- <h5 class="mb-2">Live Virtual Sessions</h5> --}}
                            <h4 class="mb-2">{!! __('about.learning_step_three') !!}</h4>

                            {{-- <p class="text-muted mb-0">Participate in interactive sessions, forums, and collaborative group
                                tasks.</p> --}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-lift learning-works-body">
                        <div class="card-body p-4 leaning-works-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="about-us-learning-icon icon-lg bg-opacity-10 rounded-3 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21"
                                            stroke="#0095FF" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                                            stroke="#0095FF" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            {{-- <h5 class="mb-2">One-to-One Support</h5> --}}
                            <h4 class="mb-2">{!! __('about.learning_step_four') !!}</h4>
                            {{-- <p class="text-muted mb-0">Receive personalized guidance and support from dedicated academic
                                staff.</p> --}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-lift learning-works-body">
                        <div class="card-body p-4 leaning-works-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="about-us-learning-icon icon-lg bg-opacity-10 rounded-3 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="12" r="10" stroke="#E53935"
                                            stroke-width="2" />
                                        <path d="M2 12H22" stroke="#E53935" stroke-width="2" stroke-linecap="round" />
                                        <path
                                            d="M12 2C14.5013 4.73835 15.9228 8.29203 16 12C15.9228 15.708 14.5013 19.2616 12 22C9.49872 19.2616 8.07725 15.708 8 12C8.07725 8.29203 9.49872 4.73835 12 2Z"
                                            stroke="#E53935" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            {{-- <h5 class="mb-2">Global Community</h5> --}}
                            <h4 class="mb-2">{!! __('about.learning_step_five') !!}</h4>

                            {{-- <p class="text-muted mb-0">Learn alongside students from multiple countries and diverse
                                backgrounds.</p> --}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-lift learning-works-body">
                        <div class="card-body p-4 leaning-works-body">
                            <div class="d-flex align-items-start mb-3">
                                <div class="about-us-learning-icon icon-lg  bg-opacity-10 rounded-3 me-3">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M22 10V16C22 17.0609 21.5786 18.0783 20.8284 18.8284C20.0783 19.5786 19.0609 20 18 20H6C4.93913 20 3.92172 19.5786 3.17157 18.8284C2.42143 18.0783 2 17.0609 2 16V10"
                                            stroke="#754FFE" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M12 14L2 9L12 4L22 9L12 14Z" stroke="#754FFE" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12 14V20" stroke="#754FFE" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </div>
                            {{-- <h5 class="mb-2">Accredited Qualifications</h5> --}}
                            <h4 class="mb-2">{!! __('about.learning_step_six') !!}</h4>

                            {{-- <p class="text-muted mb-0">Graduate with qualifications accredited by MFHEA, mapped to MQF and
                                EQF.</p> --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Digital Campus Feature -->
            {{-- <div class="row mb-6">
                <div class="col-lg-12 mx-auto">
                    <div class="card border-0 shadow-lg ">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                 
                                    <p class="mb-0">Our digital campus is designed to fit your lifestyle, giving you the freedom to balance study, work, and personal commitments.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
              <p class="mb-2" style="font-size: 1.10rem;text-align:center;">{!! __('about.learning_commitment') !!}</p>

            {{-- <div class="text-center" style="font-size: 1.10rem;"> Our digital campus is designed to fit your lifestyle, giving you the freedom to balance study, work, and personal commitments.</div> --}}


        </div>
    </section>

    <section class="pb-4 pt-6 about-us-digital bg-white">
        <div class="container">
            <!-- Digital Community Section -->
            <div class="row align-items-center mb-6">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="position-relative">
                        <img src="{{ asset('frontend/images/team/about_us_9.webp') }}" alt="Digital Campus"
                            class="img-fluid rounded-4 shadow">
                        {{-- <div class="position-absolute bottom-0 start-0 p-4">
                            <div class="badge bg-white text-dark px-3 py-2 rounded-pill shadow">
                                Backed by Ascencia Malta
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ps-lg-5">
                        {{-- <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">Digital
                            Excellence</span> --}}
                        <h2 class="display-5 fw-bold mb-4">{!! __('about.digital_institution_headline') !!}</h2>
                        <p class="text-muted mb-4">{!! __('about.digital_institution_content') !!}</p>
                        {{-- <div class="bg-light rounded-3 p-4">
                           
                        </div> --}}
                         <p class="">{!! __('about.digital_institution_trust') !!}</p>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <section class="bg-light pb-4 pt-6">
        <div class="container">
            <!-- CTA Section -->
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card border-0 bg-dark text-white overflow-hidden">
                        <div class="card-body p-5 position-relative">
                            <div class="row align-items-center position-relative" style="z-index: 2;">
                                <div class="col-lg-8 mb-4 mb-lg-0">
                                    <h2 class="h1 text-white mb-3">{!! __('about.journey_heading') !!}</h2>
                                    <p class="lead  mb-3 about_us_future_text">{!! __('about.journey_subheading') !!}</p>
                                    <p class="mb-2 about_us_explore_program">{!! __('about.about_us_explore_program') !!}</p>
                                    <p class="mb-0 about_us_future_text">{!! __('about.journey_content') !!}</p>
                                    
                                </div>
                                {{-- <div class="col-lg-4 text-lg-end">
                                    <a href="#!"
                                        class="btn explore-program-about-us btn-md mb-2 d-block d-sm-inline-block">Explore
                                        Programmes</a>
                                    <a href="#!"
                                        class="btn btn-outline-light btn-md d-block d-sm-inline-block">Contact
                                        Our Team</a>
                                </div> --}}
                            </div>
                            <!-- Decorative Elements -->
                            <div class="position-absolute top-0 end-0 mt-n5 me-n5" style="opacity: 0.1;">
                                <svg width="300" height="300" viewBox="0 0 200 200"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor"
                                        d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,79.6,-45.8C87.4,-32.6,90,-16.3,88.5,-0.9C87,14.6,81.4,29.2,73.1,42.3C64.8,55.4,53.8,67,40.6,74.3C27.4,81.6,13.7,84.6,-0.7,85.9C-15.1,87.2,-30.2,86.8,-43.8,80.2C-57.4,73.6,-69.5,60.8,-77.4,45.8C-85.3,30.8,-89,13.7,-88.1,-3.2C-87.2,-20.1,-81.7,-36.8,-72.8,-50.6C-63.9,-64.4,-51.6,-75.3,-37.8,-82.7C-24,-90.1,-8.7,-94,5.3,-92.3C19.3,-90.6,30.6,-83.6,44.7,-76.4Z"
                                        transform="translate(100 100)" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    </main>
@endsection
