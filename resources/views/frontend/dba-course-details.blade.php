@extends('frontend.master')
@section('content')
    <style>
        .add_background {
            background-color: #a30a1b;
            color: #ffffff;
            padding: 12px;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 8px;
            font-weight: bold;
        }

        .add_circle {
            background-color: #ffffff;
            color: #a30a1b;
            padding: 5px;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            display: flex;
            justify-content: center;
            margin-right: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            align-items: center;
            border: 1px solid #a30a1b;
        }

        .download_btn{
            border: 1px solid #a30a1b;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            color: #a30a1b;
        }

        .download_btn:hover{
            background-color: #a30a1b;
            color: #fff;
            border: none;
        }

        .course-item{
            background-color: #f8f8f8;
            padding: 12px 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            transition: transform 0.2s ease, background-color 0.2s ease;
        }

        .course-item h4{
            font-size: 16px;
            color: #333;
            margin: 0;
            font-weight: 600;

        }
        .section_heading{
            font-size: 20px;
        }
        .dba_proposal_title{
            font-weight: 500;
        }
        .wring-proposal-tab_list{
            margin-left: 1.5rem;
        }
    </style>


    <main>
        <!-- Page header -->
        <section class="pt-lg-8 pt-5 pb-8 bg-primary order-1">
            <div class="container pb-lg-6">
                <div class="row align-items-center">
                    <div class="col-xl-8 col-lg-8 col-md-12">
                        <div>
                            <h1 class="text-white display-5 fw-bold color-green ">{{__('dba.title')}}</h1>
                            <p class="text-white mb-6 fs-5">
                                {{__('dba.subtitle')}}
                            </p>
                            <div class="d-flex align-items-center">
                                <span class="text-white">
                                    <img src="{{ asset('frontend/images/icon/mqf-icon.svg') }}" alt="" width="15px">
                                     {{ __('static.course_details.mqf_eqf') }} {{ __('static.course_details.level') }}: 8
                                </span>
                                {{-- <span class="text-white ms-3">
                                    <i class="bi bi-star-fill color-green  rating-star"></i>
                                    ECTS: NA
                                </span> --}}
                                <span class="text-white ms-3">
                                    <i class="fe fe-user color-green"></i>
                                    45 {{ __('static.Enrolled') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Page content -->
        <section class="pb-8">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-12 col-12 mb-4 mb-lg-0 order-3 order-lg-2">
                        <!-- Card -->
                        <div class="card rounded-3 mt-0 mt-md-3">
                            <!-- Card header -->
                            <div class="card-header border-bottom-0 p-0">
                                <div>
                                    <!-- Nav -->
                                    <ul class="nav nav-lb-tab" id="tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="overview-tab" data-bs-toggle="pill"
                                                href="#overview" role="tab" aria-controls="overview"
                                                aria-selected="false">
                                                {{-- Overview --}}
                                                {{ __('static.course_details.tab.Overview') }}

                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="entry-requirements-tab" data-bs-toggle="pill"
                                                href="#entry-requirements" role="tab" aria-controls="entry-requirements"
                                                aria-selected="false">
                                                {{-- Entry Requirements --}}
                                                {{ __('static.course_details.tab.Entry_requirements') }}

                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " id="application-form" data-bs-toggle="pill"
                                                href="#progress" role="tab" aria-controls="progress"
                                                aria-selected="true">{{__('dba.application_form.tab')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="course-content-tab" data-bs-toggle="pill"
                                                href="#course-content" role="tab" aria-controls="course-content"
                                                aria-selected="false">
                                                {{__('dba.dbaresearchtab')}}</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="writing-proposal-tab" data-bs-toggle="pill"
                                                href="#assessment" role="tab" aria-controls="assessment"
                                                aria-selected="false">{{__('dba.writtingtab')}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="tab-content" id="tabContent">
                                    <div class="tab-pane fade show active" id="overview" role="tabpanel"
                                        aria-labelledby="overview-tab">
                                        <h2 class="mb-3 section_heading">{{__('dba.overview.heading')}}</h2>

                                        <div class="mb-4">
                                             {!! __('dba.overview.subtext') !!}


                                            <div class="mb-3">
                                                <div class="course-item">
                                                    <h4>1. {!! __('dba.overview.list.list1') !!}</h4>
                                                </div>
                                                <div class="course-item">
                                                    <h4>2. {!! __('dba.overview.list.list2') !!}</h4>
                                                </div>
                                                <div class="course-item">
                                                    <h4>3. {!! __('dba.overview.list.list3') !!}</h4>
                                                </div>
                                                <div class="course-item">
                                                    <h4>4. {!! __('dba.overview.list.list4') !!}</h4>
                                                </div>
                                                <div class="course-item">
                                                    <h4>5. {!! __('dba.overview.list.list5') !!}</h4>
                                                </div>
                                                <div class="course-item">
                                                    <h4>6. {!! __('dba.overview.list.list6') !!}</h4>
                                                </div>
                                                 <div class="course-item">
                                                    <h4>7. {!! __('dba.overview.list.list7') !!}</h4>
                                                </div>
                                            </div>


                                            {!! __('dba.overview.overviewtext') !!}


                                            <a href="{{ asset('frontend/images/pdf/Doctorate of Business Administration _DBA.pdf') }}"
                                                download="Download DBA Brochure" class="download_btn"> {!! __('dba.overview.btn') !!} <i
                                                    class="fe fe-download fs-5"></i></a>
                                        </div>


                                    </div>
                                    <div class="tab-pane fade" id="progress" role="tabpanel"
                                        aria-labelledby="application-form">
                                        <!-- Card -->

                                        <div class="mb-4">
                                            {!! __('dba.application_form.data') !!}
                                            <a href="{{ asset('frontend/images/pdf/Application Form-DBA.pdf') }}"
                                                download="Application_Form-DBA" class="download_btn">  {!! __('dba.application_form.data1') !!} <i
                                                    class="fe fe-download fs-5"></i></a>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="entry-requirements" role="tabpanel"
                                        aria-labelledby="entry-requirements-tab">
                                        <div class="mb-4">
                                           {!! __('dba.entry_requirment') !!}
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="course-content" role="tabpanel"
                                        aria-labelledby="course-content-tab">
                                        {!! __('dba.dbaresearch') !!}
                                    </div>
                                    <div class="tab-pane fade" id="assessment" role="tabpanel"
                                        aria-labelledby="wring-proposal-tab">
                                        <div>
                                            <div class="row">
                                                <div class="col-lg-12 col-12">
                                                    <div class="mb-3">

                                                        <h2 class="mb-3 section_heading">     {!! __('dba.writtingresearch1') !!}</h2>

                                                        <div class="">
                                                            {!! __('dba.writtingresearch') !!}

                                                            <a href="{{ asset('frontend/images/pdf/Research proposal application.pdf') }}"
                                                                download="Research proposal application" class="download_btn">     {!! __('dba.writtingresearchbtn') !!} <i
                                                                    class="fe fe-download fs-5"></i></a>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
                    @php $videoUrls = ''; @endphp 
                    <div class="col-lg-3 col-md-12 col-12 course-preview-column order-2 order-lg-3">
                        <!-- Card -->
                        @php
                        $currentUrl = $_SERVER['REQUEST_URI'];
                        $urlSegments = explode('/', $currentUrl);
                        $course_id = end($urlSegments);
                        $data = DB::table('course_master')->select('id','selling_price','ects','course_thumbnail_file','course_old_price','course_final_price','scholarship','status','category_id','trailer_thumbnail_file','lecturer_id','youtube_id')->where('id',base64_decode($course_id))->limit(1)->get();
                        $dataPodcast = DB::table('course_other_videos')->select('bn_video_url_id')->where('course_master_id',base64_decode($course_id))->limit(1)->get();
                        if($data[0]->youtube_id != ''){
                            $videoUrls = 'https://www.youtube.com/watch?v='.$data[0]->youtube_id; 
                        }
                        @endphp
                        @if (isset($dataPodcast[0]->bn_video_url_id) && !empty($dataPodcast[0]->bn_video_url_id))
                        @php 
                        if($data[0]->youtube_id == ''){
                            $videoUrls = 'https://iframe.mediadelivery.net/embed/'.env('MASTER_LIBRARY_ID').'/'. $dataPodcast[0]->bn_video_url_id.'?autoplay=true'; 
                        }
                        // $videoUrls = 'https://iframe.mediadelivery.net/embed/'.env('MASTER_LIBRARY_ID').'/'. $dataPodcast[0]->bn_video_url_id.'?autoplay=true'; 
                        @endphp
                        <div class="card mb-3 mb-2">
                            <div class="p-1">
                                <div class="d-flex justify-content-center cursor-pointer align-items-center rounded border-white border rounded-3 bg-cover openVideoModal"
                                    data-videourl="{{$videoUrls}}"
                                    style="position: relative; overflow: hidden;">
                                    <img src="{{ Storage::url($data[0]->trailer_thumbnail_file) }}" alt="Trailer Thumbnail"
                                        style="width: 100%; height: 100%; object-fit: cover;"/>
                                    <i class="bi bi-play-fill fs-3 course-details-play-icon"
                                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                </div>
                            </div>
                            <!-- Card body -->
                            <div class="card-body p-3">
                                @if($data[0]->course_final_price > 0)
                                    <div class="mb-3 text-center">
                                        <div class="text-dark fw-bold h2 color-blue">€{{isset($data[0]->course_final_price) ? htmlspecialchars($data[0]->course_final_price) : '' }}/<span class="color-blue h5">{{__('dba.peryear')}}</span></div>
                                        @if(isset($data[0]->course_old_price) && $data[0]->course_old_price > 0)<del class="fs-4">€{{isset($data[0]->course_old_price) ? htmlspecialchars($data[0]->course_old_price) : '' }}</del>
                                        <span class="course-off-discount">{{ (!empty($data[0]->course_final_price) && $data[0]->course_final_price > 1) ? (isset($data[0]->scholarship) && $data[0]->scholarship > 0 ? intval(round($data[0]->scholarship)).'%'.' Scholarship' : 'Scholarship') : 'Introductory Fees' }}</span>@endif
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center">
                                        @php $promoCode = getCoursePromoCode($data[0]->id); @endphp
                                        @if($promoCode)
                                            <small class="promo-code font-weight-bold text-primary rounded p-1" style="background: #d15863">
                                            <span class="badge badge-success text-primary fs-5" ><span style="user-select: none">{{__('static.promo')}}:</span> <span class="fw-bold">{{$promoCode}}</span></span>
                                            </small>
                                        @endif
                                    </div>
                                    <br>
                                    <div class="d-grid">
                                        @if (Auth::check() && Auth::user()->role =='superadmin')
                                            <a href="{{route('admin-master-course-panel',['course_id'=>base64_encode($data[0]->id)])}}" class="btn btn-outline-primary mt-2 d-flex align-items-center justify-content-center"><i class="fe fe-play btn-outline-primary"></i> Play </a>
                                        @elseif (Auth::check() && (Auth::user()->role =='admin'))
                                            <a href="{{route('admin-master-course-panel',['course_id'=>base64_encode($data[0]->id)])}}" class="btn btn-outline-primary mt-2 d-flex align-items-center justify-content-center"><i class="fe fe-play btn-outline-primary"></i> Play </a>
                                        @elseif (Auth::check() && (Auth::user()->role =='instructor' || Auth::user()->role =='sub-instructor' || Auth::user()->role =='institute'))
                                        @elseif (Auth::check() && Auth::user()->role =='user')
                                                @php
                                                    $isPaid = is_exist('orders', ['user_id' => Auth::user()->id,'status' => '0','course_id'=> $data[0]->id]);
                                                    $doc_verified = getData('student_doc_verification',['english_score','identity_is_approved','edu_is_approved','identity_doc_file','edu_doc_file','resume_file','edu_trail_attempt','identity_trail_attempt','english_test_attempt','proposal_is_approved'],['student_id'=>Auth::user()->id]);
                                                @endphp

                                                @if(Auth::user()->apply_dba == 'Yes')
                                                    @if($doc_verified[0]->identity_is_approved == "Approved" && $doc_verified[0]->edu_is_approved == "Approved" && $doc_verified[0]->resume_file != '' && $doc_verified[0]->english_score >= 10 && $doc_verified[0]->proposal_is_approved == 'Approved')
                                                        @php
                                                        $studentCourseMaster = getData('student_course_master',['course_expired_on','exam_attempt_remain','exam_remark'],['user_id' => Auth::user()->id, 'course_id'=> $data[0]->id,'is_deleted'=>'No'], "", 'created_at');
                                                        $playLink = "master-course-panel";
                                                        $getExistMasterCourse = getDataArray('master_course_management', ['course_id','optional_course_id'], ['award_id' => $data[0]->id, 'is_deleted' => 'No']);
                                                        $DBAunderAward = '';
                                                        if (empty($getExistMasterCourse)) {
                                                            $DBAunderAward = 'd-none';
                                                        }
                                                        @endphp
                                                        @if(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() &&    $studentCourseMaster[0]->exam_attempt_remain == '1' &&  $studentCourseMaster[0]->exam_remark == '0')
                                                            <a  href="{{route($playLink,['course_id'=>base64_encode($data[0]->id)])}}" class="mt-0 d-flex align-items-center justify-content-center playBtnStyle {!! $DBAunderAward !!}" ><i class="bi bi-play-circle btn-outline-primary me-1 fs-4" ></i> {{ __('static.play') }}</a>
                                                        @elseif(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() &&    $studentCourseMaster[0]->exam_attempt_remain == '2')
                                                            <a  href="{{route($playLink,['course_id'=>base64_encode($data[0]->id)])}}" class="mt-0 d-flex align-items-center justify-content-center playBtnStyle {!! $DBAunderAward !!}" ><i class="bi bi-play-circle btn-outline-primary me-1 fs-4" ></i> {{ __('static.play') }}</a>
                                                        @else
                                                            <form class="checkoutform">
                                                                @csrf <!-- CSRF protection -->
                                                                @php
                                                                    $total_full_price = $data[0]->course_old_price - ($data[0]->course_old_price - $data[0]->course_final_price);
                                                                @endphp
                                                                <input type='hidden' value="{{base64_encode($data[0]->id)}}" name="course_id" id="course_id">
                                                                <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($data[0]->course_old_price)}}">
                                                                <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($data[0]->course_old_price-$data[0]->course_final_price)}}">
                                                                <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                                                                <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                                                                <div class="d-grid">
                                                                    <button class="btn btn-primary mb-2 color-green fs-4 buyCourse">{{--Buy Course--}} {{ __('static.course_details.button1') }}</button>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('student-document-verification') }}"><button class="btn btn-primary mb-2 color-green fs-4">{{--Buy Course--}}{{ __('static.course_details.button1') }}</button></a>
                                                    @endif
                                                @else

                                                    @if($doc_verified[0]->identity_is_approved == "Approved" && $doc_verified[0]->edu_is_approved == "Approved" && $doc_verified[0]->resume_file != '' && $doc_verified[0]->english_score >= 10 && $doc_verified[0]->proposal_is_approved == 'Approved')
                                                        @php
                                                        $studentCourseMaster = getData('student_course_master',['course_expired_on','exam_attempt_remain','exam_remark'],['user_id' => Auth::user()->id, 'course_id'=> $data[0]->id,'is_deleted'=>'No'], "", 'created_at');
                                                        $playLink = "master-course-panel";
                                                        $getExistMasterCourse = getDataArray('master_course_management', ['course_id','optional_course_id'], ['award_id' => $data[0]->id, 'is_deleted' => 'No']);
                                                        $DBAunderAward = '';
                                                        if (empty($getExistMasterCourse)) {
                                                            $DBAunderAward = 'd-none';
                                                        }
                                                        @endphp
                                                        @if(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() &&    $studentCourseMaster[0]->exam_attempt_remain == '1' &&  $studentCourseMaster[0]->exam_remark == '0')
                                                            <a  href="{{route($playLink,['course_id'=>base64_encode($data[0]->id)])}}" class="mt-0 d-flex align-items-center justify-content-center playBtnStyle {!! $DBAunderAward !!}" ><i class="bi bi-play-circle btn-outline-primary me-1 fs-4" ></i> {{ __('static.play') }}</a>
                                                        @elseif(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() &&    $studentCourseMaster[0]->exam_attempt_remain == '2')
                                                            <a  href="{{route($playLink,['course_id'=>base64_encode($data[0]->id)])}}" class="mt-0 d-flex align-items-center justify-content-center playBtnStyle {!! $DBAunderAward !!}" ><i class="bi bi-play-circle btn-outline-primary me-1 fs-4" ></i> {{ __('static.play') }}</a>
                                                        @else
                                                            <form class="checkoutform">
                                                                @csrf <!-- CSRF protection -->
                                                                @php
                                                                $total_full_price = $data[0]->course_old_price - ($data[0]->course_old_price - $data[0]->course_final_price);
                                                                @endphp
                                                                <input type='hidden' value="{{base64_encode($data[0]->id)}}" name="course_id" id="course_id">
                                                                <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($data[0]->course_old_price)}}">
                                                                <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($data[0]->course_old_price-$data[0]->course_final_price)}}">
                                                                <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                                                                <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                                                                <div class="d-grid">
                                                                    <button class="btn btn-primary mb-2 color-green fs-4 buyCourse">{{ __('static.course_details.button1') }}</button>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('student-document-verification') }}"><div class="d-grid"><button class="btn btn-primary mb-2 color-green fs-4">{{ __('static.course_details.button1') }}</button> </div></a>
                                                    @endif
                                                @endif
                                                <div class="d-grid">
                                                    {{-- <a href="#" class="btn btn-outline-primary addtocart coursedetailshoppingcarticon" id="addtocart" data-course-id="{{base64_encode($data[0]->id)}}" data-action="{{base64_encode('add')}}"><i class="fe fe-shopping-cart"></i> Add to Cart</a> --}}
                                                </div>
                                        @else
                                            <form class="checkoutform">
                                                @csrf <!-- CSRF protection -->
                                                @php
                                                    $total_full_price = $data[0]->course_old_price - ($data[0]->course_old_price - $data[0]->course_final_price);
                                                @endphp
                                                <input type='hidden' value="{{base64_encode($data[0]->id)}}" name="course_id" id="course_id">
                                                <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($data[0]->course_old_price)}}">
                                                <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($data[0]->course_old_price-$data[0]->course_final_price)}}">
                                                <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                                                <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary mb-2 color-green fs-4 buyCourse">{{--Buy Course--}}{{ __('static.course_details.button1') }}</button>
                                                </div>
                                            </form>
                                            <div class="d-grid">
                                                {{-- <a href="{{route('login')}}" class="btn btn-outline-primary"><i class="fe fe-shopping-cart text-primary"></i> Add to Cart</a> --}}
                                                {{-- <a href="#" class="btn btn-outline-primary addtocart coursedetailshoppingcarticon" id="addtocart" data-course-id="{{base64_encode($data[0]->id)}}" data-action="{{base64_encode('add')}}"  data-withcart="withcart"><i class="fe fe-shopping-cart"></i> Add to Cart</a> --}}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        </div>
                    @else
                        @if (isset($data[0]->trailer_thumbnail_file) && !empty($data[0]->trailer_thumbnail_file))
                            <div class="card mb-3 mb-2 trailer_thumbnail_file_style">
                                <div class="p-1">
                                    <div class="d-flex justify-content-center cursor-pointer align-items-center rounded border-white border rounded-3 bg-cover"
                                        style="position: relative; overflow: hidden;">
                                        <img src="{{ Storage::url($data[0]->trailer_thumbnail_file) }}" alt="Trailer Thumbnail"
                                            style="width: 100%; height: 100%; object-fit: cover;"/>
                                    </div>
                                </div>
                                <!-- Card body -->
                                <div class="card-body p-3">
                                    @if($data[0]->course_final_price > 0)
                                        <div class="mb-3 text-center">
                                            <div class="text-dark fw-bold h2 color-blue">€{{isset($data[0]->course_final_price) ? htmlspecialchars($data[0]->course_final_price) : '' }}/<span class="color-blue h5">{{__('dba.peryear')}}</span></div>
                                            @if(isset($data[0]->course_old_price) && $data[0]->course_old_price > 0)<del class="fs-4">€{{isset($data[0]->course_old_price) ? htmlspecialchars($data[0]->course_old_price) : '' }}</del>
                                            <span class="course-off-discount">{{ (!empty($data[0]->course_final_price) && $data[0]->course_final_price > 1) ? (isset($data[0]->scholarship) && $data[0]->scholarship > 0 ? intval(round($data[0]->scholarship)).'%'.' Scholarship' : 'Scholarship') : 'Introductory Fees' }}</span>@endif
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center">
                                            @php $promoCode = getCoursePromoCode($data[0]->id); @endphp
                                            @if($promoCode)
                                                <small class="promo-code font-weight-bold text-primary rounded p-1" style="background: #d15863">
                                                <span class="badge badge-success text-primary fs-5 {{ app()->getLocale() == 'ar' ? 'arabic_promocode_style' : '' }}" ><span style="user-select: none">{{__('static.promo')}}:</span> <span class="fw-bold">{{$promoCode}}</span></span>
                                                </small>
                                            @endif
                                        </div>
                                        <br>
                                        <div class="d-grid">
                                            @if (Auth::check() && Auth::user()->role =='superadmin')
                                                <a href="{{route('admin-master-course-panel',['course_id'=>base64_encode($data[0]->id)])}}" class="btn btn-outline-primary mt-2 d-flex align-items-center justify-content-center"><i class="fe fe-play btn-outline-primary"></i> Play </a>
                                            @elseif (Auth::check() && (Auth::user()->role =='admin' || Auth::user()->role =='instructor' || Auth::user()->role =='sub-instructor' || Auth::user()->role =='institute'))
                                                <a href="{{route('admin-master-course-panel',['course_id'=>base64_encode($data[0]->id)])}}" class="btn btn-outline-primary mt-2 d-flex align-items-center justify-content-center"><i class="fe fe-play btn-outline-primary"></i> Play </a>
                                            @elseif (Auth::check() && Auth::user()->role =='user')
                                                @php
                                                    $isPaid = is_exist('orders', ['user_id' => Auth::user()->id,'status' => '0','course_id'=> $data[0]->id]);
                                                    $doc_verified = getData('student_doc_verification',['english_score','identity_is_approved','edu_is_approved','identity_doc_file','edu_doc_file','resume_file','edu_trail_attempt','identity_trail_attempt','english_test_attempt','proposal_is_approved'],['student_id'=>Auth::user()->id]);
                                                @endphp

                                                @if(Auth::user()->apply_dba == 'Yes')
                                                    @if($doc_verified[0]->identity_is_approved == "Approved" && $doc_verified[0]->edu_is_approved == "Approved" && $doc_verified[0]->resume_file != '' && $doc_verified[0]->english_score >= 10  && $doc_verified[0]->proposal_is_approved == 'Approved')
                                                        @php
                                                        $studentCourseMaster = getData('student_course_master',['course_expired_on','exam_attempt_remain','exam_remark'],['user_id' => Auth::user()->id, 'course_id'=> $data[0]->id,'is_deleted'=>'No'], "", 'created_at');
                                                        $playLink = "master-course-panel";
                                                        $getExistMasterCourse = getDataArray('master_course_management', ['course_id','optional_course_id'], ['award_id' => $data[0]->id, 'is_deleted' => 'No']);
                                                        $DBAunderAward = '';
                                                        if (empty($getExistMasterCourse)) {
                                                            $DBAunderAward = 'd-none';
                                                        }
                                                        @endphp
                                                        @if(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() &&    $studentCourseMaster[0]->exam_attempt_remain == '1' &&  $studentCourseMaster[0]->exam_remark == '0')
                                                            <a  href="{{route($playLink,['course_id'=>base64_encode($data[0]->id)])}}" class="mt-0 d-flex align-items-center justify-content-center playBtnStyle" ><i class="bi bi-play-circle btn-outline-primary me-1 fs-4" ></i> {{ __('static.play') }}</a>
                                                        @elseif(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() &&    $studentCourseMaster[0]->exam_attempt_remain == '2')
                                                            <a  href="{{route($playLink,['course_id'=>base64_encode($data[0]->id)])}}" class="mt-0 d-flex align-items-center justify-content-center playBtnStyle" ><i class="bi bi-play-circle btn-outline-primary me-1 fs-4" ></i> {{ __('static.play') }}</a>
                                                        @else
                                                            <form class="checkoutform">
                                                                @csrf <!-- CSRF protection -->
                                                                @php
                                                                    $total_full_price = $data[0]->course_old_price - ($data[0]->course_old_price - $data[0]->course_final_price);
                                                                @endphp
                                                                <input type='hidden' value="{{base64_encode($data[0]->id)}}" name="course_id" id="course_id">
                                                                <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($data[0]->course_old_price)}}">
                                                                <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($data[0]->course_old_price-$data[0]->course_final_price)}}">
                                                                <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                                                                <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                                                                <div class="d-grid">
                                                                    <button class="btn btn-primary mb-2 color-green fs-4 buyCourse">{{--Buy Course--}}{{ __('static.course_details.button1') }}</button>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('student-document-verification') }}"><div class="d-grid"><button class="btn btn-primary mb-2 color-green fs-4">Buy Course</button> </div></a>
                                                    @endif
                                                @else
                                                    @if($doc_verified[0]->identity_is_approved == "Approved" && $doc_verified[0]->edu_is_approved == "Approved" && $doc_verified[0]->resume_file != '' && $doc_verified[0]->english_score >= 10  && $doc_verified[0]->proposal_is_approved == 'Approved')
                                                    @php
                                                    $studentCourseMaster = getData('student_course_master',['course_expired_on','exam_attempt_remain','exam_remark'],['user_id' => Auth::user()->id, 'course_id'=> $data[0]->id,'is_deleted'=>'No'], "", 'created_at');
                                                    $playLink = "master-course-panel";
                                                    $getExistMasterCourse = getDataArray('master_course_management', ['course_id','optional_course_id'], ['award_id' => $data[0]->id, 'is_deleted' => 'No']);
                                                        $DBAunderAward = '';
                                                        if (empty($getExistMasterCourse)) {
                                                            $DBAunderAward = 'd-none';
                                                        }
                                                    @endphp
                                                    @if(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() &&    $studentCourseMaster[0]->exam_attempt_remain == '1' &&  $studentCourseMaster[0]->exam_remark == '0')
                                                        <a  href="{{route($playLink,['course_id'=>base64_encode($data[0]->id)])}}" class="mt-0 d-flex align-items-center justify-content-center playBtnStyle {!! $DBAunderAward !!}" ><i class="bi bi-play-circle btn-outline-primary me-1 fs-4" ></i> {{ __('static.play') }}</a>
                                                    @elseif(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() &&    $studentCourseMaster[0]->exam_attempt_remain == '2')
                                                        <a  href="{{route($playLink,['course_id'=>base64_encode($data[0]->id)])}}" class="mt-0 d-flex align-items-center justify-content-center playBtnStyle {!! $DBAunderAward !!}" ><i class="bi bi-play-circle btn-outline-primary me-1 fs-4" ></i> {{ __('static.play') }}</a>
                                                    @else
                                                        <form class="checkoutform">
                                                            @csrf <!-- CSRF protection -->
                                                            @php
                                                                $total_full_price = $data[0]->course_old_price - ($data[0]->course_old_price - $data[0]->course_final_price);
                                                            @endphp
                                                            <input type='hidden' value="{{base64_encode($data[0]->id)}}" name="course_id" id="course_id">
                                                            <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($data[0]->course_old_price)}}">
                                                            <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($data[0]->course_old_price-$data[0]->course_final_price)}}">
                                                            <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                                                            <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                                                            <div class="d-grid">
                                                                <button class="btn btn-primary mb-2 color-green fs-4 buyCourse">{{--Buy Course--}}{{ __('static.course_details.button1') }}</button>
                                                            </div>
                                                        </form>
                                                    @endif
                                                    @else
                                                        <a href="{{ route('student-document-verification') }}"><div class="d-grid"><button class="btn btn-primary mb-2 color-green fs-4">{{ __('static.course_details.button1') }}</button> </div></a>
                                                    @endif
                                                @endif
                                            @else
                                                <form class="checkoutform">
                                                    @csrf <!-- CSRF protection -->
                                                    @php
                                                        $total_full_price = $data[0]->course_old_price - ($data[0]->course_old_price - $data[0]->course_final_price);
                                                    @endphp
                                                    <input type='hidden' value="{{base64_encode($data[0]->id)}}" name="course_id" id="course_id">
                                                    <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($data[0]->course_old_price)}}">
                                                    <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($data[0]->course_old_price-$data[0]->course_final_price)}}">
                                                    <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                                                    <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                                                    <div class="d-grid">
                                                        <button class="btn btn-primary mb-2 color-green fs-4 buyCourse">{{--Buy Course--}} {{ __('static.course_details.button1') }}</button>
                                                    </div>
                                                </form>
                                                {{-- <a href="#" class="btn btn-outline-primary addtocart coursedetailshoppingcarticon" id="addtocart" data-course-id="{{base64_encode($data[0]->id)}}" data-action="{{base64_encode('add')}}"  data-withcart="withcart"><i class="fe fe-shopping-cart"></i> Add to Cart</a> --}}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                        <!-- Card -->
                        <div class="card mb-4">
                            <div>
                                <!-- Card header -->
                                <div class="card-header">
                                    <h4 class="mb-0">{{__('static.course_details.include.title')}}</h4>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item bg-transparent">
                                        <i class="bi bi-clock align-middle me-2" style="color: #a30a1b"></i>
                                        3 {{__('dba.yearsduration')}}
                                    </li>
                                    <li class="list-group-item bg-transparent">
                                        <i class="fe fe-calendar align-middle me-2" style="color: #a30a1b"></i>
                                        8 {{__('dba.modules')}}
                                    </li>
                                    {{-- <li class="list-group-item bg-transparent">
                                        <i class="fe fe-book align-middle me-2 text-success"></i>
                                        N/D Lectures
                                    </li> --}}
                                    <li class="list-group-item bg-transparent">
                                        <i class="fe fe-play-circle align-middle me-2"></i>
                                        2000+ {{__('dba.learning')}}
                                    </li>
                                    <li class="list-group-item bg-transparent">
                                        <i class="fe fe-award me-2 align-middle text-danger"></i>
                                        {{ __('static.course_details.include.data.3data') }}
                                        <!-- tooltip on top -->
                                        {{-- <i class="fe fe-info me-2 align-middle text-grey" data-bs-toggle="tooltip"
                                            data-placement="top"
                                            title="Certificate of Attendance OR MQF/EQF Certificate"></i> --}}
                                    </li>

                                    <li class="list-group-item bg-transparent">
                                        <i class="fe fe-video align-middle me-2 text-secondary"></i>
                                        {{__('dba.access_mobile')}}
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <!-- Card -->
                        <!-- Card -->
                        <div class="card">
                            @php $t=1; @endphp
                            @if (isset($data[0]->lecturer_id) && !empty($data[0]->lecturer_id) )
                            <div class="card">

                                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">

                                    <div class="carousel-inner">

                                    @if (isset($data[0]->lecturer_id) && !empty($data[0]->lecturer_id) )
                                    @php

                                        $implodeLecturer = explode(",",$data[0]->lecturer_id);

                                    @endphp



                                    @foreach($implodeLecturer as $key => $lecturesId)

                                    @php $show = '' @endphp
                                        @if($key == 0)
                                        @php $show = 'active' @endphp
                                    @endif

                                    @php

                                    $id = base64_decode($lecturesId);

                                    $lecData = getData('lecturers_master',['id','lactrure_name','discription','designation','image'],['id'=>$id,'is_deleted'=>'No','status'=>'0']);

                                    $lactrure_name = getTranslatedLectureField($id, 'lactrure_name',$lecData[0]->lactrure_name);
                                        $discription = getTranslatedLectureField($id,'discription',$lecData[0]->discription);
                                        $designation = getTranslatedLectureField($id, 'designation',$lecData[0]->designation);
                                    @endphp

                                    @if (isset($lecData[0]->lactrure_name) && !empty($lecData[0]->lactrure_name) )

                                        <div class="carousel-item {{$show}}" data-bs-interval="3000">

                                            <div class="card-body">

                                                <div class="d-flex align-items-center">

                                                    <div class="position-relative">

                                                        <img src="{{ !empty($lecData[0]->image) && Storage::exists($lecData[0]->image) ? Storage::url($lecData[0]->image) : Storage::url('instructor_no_img.jpg')}}"

                                                            alt="avatar" class="rounded-circle avatar-xl">

                                                        <a href="#" class="position-absolute mt-2 ms-n3"

                                                            data-bs-toggle="tooltip" data-placement="top" title="Verified">

                                                            <img src="{{ asset('frontend/images/svg/checked-mark.svg') }}"

                                                                alt="checked-mark" height="30" width="30">

                                                        </a>

                                                    </div>

                                                    <div class="ms-4">

                                                        {{-- <h4 class="mb-0">{{isset($lecData[0]->lactrure_name) ?  $lecData[0]->lactrure_name : ''}}</h4> --}}
                                                        <h4 class="mb-0">{{isset( $lactrure_name) ?  $lactrure_name : ''}}</h4>

                                                        {{-- <p class="mb-1 fs-6">{{isset($lecData[0]->designation) ?  htmlspecialchars_decode($lecData[0]->designation) : ''}}</p> --}}
                                                        <p class="mb-1 fs-6">{{isset($designation) ?  htmlspecialchars_decode($designation) : ''}}</p>

                                                    </div>

                                                </div>

                                                <div class="border-top row pt-2 pb-3 mt-3 g-0">

                                                    <div class="col">

                                                    <?php echo isset($discription) ? htmlspecialchars_decode($discription) : '' ?>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    @endif
                                    @php $t++; @endphp
                                    @endforeach
                                    @else

                                        <div class="carousel-item {{$show}}" data-bs-interval="3000">

                                            <div class="card-body">

                                                <div class="d-flex align-items-center">

                                                    <div class="position-relative">

                                                        <img src="{{ Storage::url('instructor_no_img.jpg')}}"

                                                            alt="avatar" class="rounded-circle avatar-xl">

                                                        <a href="#" class="position-absolute mt-2 ms-n3"

                                                            data-bs-toggle="tooltip" data-placement="top" title="Verified">

                                                            <img src="{{ asset('frontend/images/svg/checked-mark.svg') }}"

                                                                alt="checked-mark" height="30" width="30">

                                                        </a>

                                                    </div>

                                                    <div class="ms-4">

                                                        <h4 class="mb-0">{{ 'Instructor Not Available'}}</h4>

                                                        <p class="mb-1 fs-6">Lecturer</p>

                                                    </div>

                                                </div>

                                                <div class="border-top row pt-2 pb-3 mt-3 g-0">

                                                    <div class="col">



                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    @endif

                                    </div>
                                    @if($t > 2)
                                    <div class="d-flex justify-content-center main-carousel gap-3 mb-2">

                                        <button class="carousel-control-prev" type="button"

                                            data-bs-target="#carouselExample" data-bs-slide="prev">

                                            <span class="left-icon"><i class="bi bi-chevron-left"></i></span>

                                            <span class="visually-hidden">Previous</span>

                                        </button>

                                        <button class="carousel-control-next" type="button"

                                            data-bs-target="#carouselExample" data-bs-slide="next">

                                            <span class="right-icon"><i class="bi bi-chevron-right"></i></span>

                                            <span class="visually-hidden">Next</span>

                                        </button>

                                    </div>
                                    @endif

                                </div>

                            </div>
                            @endif
                        </div>
                        {{-- <div class="card mb-5">
                            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active" data-bs-interval="3000">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative">
                                                    <img src="{{ asset('frontend/images/team/italo-esposito-photo.jpg') }}"
                                                        alt="avatar" class="rounded-circle avatar-xl">
                                                    <a href="#" class="position-absolute mt-2 ms-n3"
                                                        data-bs-toggle="tooltip" data-placement="top" title="Verified">
                                                        <img src="{{ asset('frontend/images/svg/checked-mark.svg') }}"
                                                            alt="checked-mark" height="30" width="30">
                                                    </a>
                                                </div>
                                                <div class="ms-4">
                                                    <h4 class="mb-0">Italo Esposito</h4>
                                                    <p class="mb-1 fs-6">Lecturer</p>
                                                </div>
                                            </div>
                                            <div class="border-top row pt-2 pb-3 mt-3 g-0">
                                                <div class="col">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur
                                                    natus accusantium impedit quae nobis ad totam, corporis pariatur
                                                    recusandae in.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item " data-bs-interval="3000">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative">
                                                    <img src="{{ asset('frontend/images/team/italo-esposito-photo.jpg') }}"
                                                        alt="avatar" class="rounded-circle avatar-xl">
                                                    <a href="#" class="position-absolute mt-2 ms-n3"
                                                        data-bs-toggle="tooltip" data-placement="top" title="Verified">
                                                        <img src="{{ asset('frontend/images/svg/checked-mark.svg') }}"
                                                            alt="checked-mark" height="30" width="30">
                                                    </a>
                                                </div>
                                                <div class="ms-4">
                                                    <h4 class="mb-0">Italo Esposito</h4>
                                                    <p class="mb-1 fs-6">Lecturer</p>
                                                </div>
                                            </div>
                                            <div class="border-top row pt-2 pb-3 mt-3 g-0">
                                                <div class="col">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur
                                                    natus accusantium impedit quae nobis ad totam, corporis pariatur
                                                    recusandae in.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item " data-bs-interval="3000">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative">
                                                    <img src="{{ asset('frontend/images/team/italo-esposito-photo.jpg') }}"
                                                        alt="avatar" class="rounded-circle avatar-xl">
                                                    <a href="#" class="position-absolute mt-2 ms-n3"
                                                        data-bs-toggle="tooltip" data-placement="top" title="Verified">
                                                        <img src="{{ asset('frontend/images/svg/checked-mark.svg') }}"
                                                            alt="checked-mark" height="30" width="30">
                                                    </a>
                                                </div>
                                                <div class="ms-4">
                                                    <h4 class="mb-0">Italo Esposito</h4>
                                                    <p class="mb-1 fs-6">Lecturer</p>
                                                </div>
                                            </div>
                                            <div class="border-top row pt-2 pb-3 mt-3 g-0">
                                                <div class="col">
                                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur
                                                    natus accusantium impedit quae nobis ad totam, corporis pariatur
                                                    recusandae in.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center main-carousel gap-3 mb-2">
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselExample" data-bs-slide="prev">
                                        <span class="left-icon"><i class="bi bi-chevron-left"></i></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselExample" data-bs-slide="next">
                                        <span class="right-icon"><i class="bi bi-chevron-right"></i></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="modal fade modal-lg videoOpen " tabindex="-1" role="dialog" aria-labelledby="addLecturerModalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content position-relative" style="background: none; border: none">
                        {{-- <button type="button" class="btn-close videoclose" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                        <i class="bi bi-x fs-2 text-white couser-detail-modal-close-button" data-bs-dismiss="modal" aria-label="Close"></i>

                        <div class="previouseVideo mb-4" style="position:relative;padding-top:56.25%;"><iframe src="" class="videoFrame" id="videoFrame" loading="lazy" style="border:0;position:absolute;top:0;height:100%;width:100%;" allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" allowfullscreen="true"></iframe></div>
                </div>
            </div>
            </div>
        </section>
    </main>

    
    <script>
        $(document).ready(function () {
            var videourl = "<?php echo $videoUrls; ?>";
            if(videourl){
                $('.videoOpen').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                // $(".save_loader").addClass("d-none").fadeOut();
                $(".videoOpen").modal('show');
                $('.videoFrame').attr('src', videourl);
                const videoFrame = document.querySelector(".videoFrame");

                if (videoFrame) {
                    if(videourl.includes("youtube.com/watch?v=")) {
                        let videoId = videourl.split("v=")[1].split("&")[0];
                        videourl = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";
                    }
                    // Set the src attribute of the iframe
                    videoFrame.src = videourl; // Replace with your video URL
                    const player = new playerjs.Player(videoFrame);
                    const icons = document.querySelectorAll('.plyr__control svg');

                    icons.forEach(icon => {
                        icon.style.width = '10px';
                        icon.style.height = '10px';
                    });

                    player.on('ready', () => {
                        // Set CSS variable for icon size
                        document.documentElement.style.setProperty('--plyr-control-icon-size', '10px');
                        // Adjust the size of control icons
                        const icons = document.querySelectorAll('.plyr__control svg');

                        icons.forEach(icon => {
                            icon.style.width = '10px';
                            icon.style.height = '10px';
                        });
                    });
                }
            }
        });
    </script> 
@endsection
