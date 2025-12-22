@extends('frontend.master')
@section('content')
    <style>
         #pdfDisply {
        /* pointer-events: none; */
    }
    canvas {
            display: flex;
            justify-content: center;
            margin: 0 auto;
            height: auto;
            overflow: scroll !important;
        }
    .button-wrapper{
        display: flex;
        flex-direction: row-reverse;
        justify-content: space-between;
    }
    .studentAwardCourseTitle.active {
        color: #bc0f2c;
        font-weight: 600;
        background-color: #f1f5f9;
        padding: 3px;
        border-radius: 5px;
}
    .studentAwardCourseTitle{
        padding: 3px;
    }
    </style>
    <!-- Wrapper -->
    <div id="db-wrapper" class="course-video-player-page toggled">
        <!-- Sidebar -->
        {{-- @php dd($courseDetails); @endphp --}}
<nav class="navbar-vertical navbar bg-white customeNavbar">
<div class="mobileviewsection" data-simplebar>
<section class="card " id="courseAccordion">
<!-- List group -->
<ul class="list-group list-group-flush" style="height: 100vh" data-simplebar="init">
    @php
        $i = 1;
    @endphp
    <div class="simplebar-wrapper" style="margin: 0px;">
        <div class="simplebar-height-auto-observer-wrapper">
            <div class="simplebar-height-auto-observer"></div>
        </div>

        <div class="simplebar-mask">
            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                <div class="simplebar-content-wrapper" tabindex="0" role="region"
                    aria-label="scrollable content"
style="height: 100%; overflow-y:auto; overflow-x:hidden !important;">
<div class="simplebar-content student-award-panel-scrollbar" style="padding: 0px;">
<li class="list-group-item">
<h4 class="mb-0">
    {{-- @php $DocumentVerified = ''; 
        $doc_verified = getData('student_doc_verification',['english_score','identity_is_approved','edu_is_approved','identity_doc_file','edu_doc_file','resume_file','edu_trail_attempt','identity_trail_attempt','english_test_attempt','edu_level','edu_athe_approved'],['student_id'=>Auth::user()->id]);
    @endphp
    @if($doc_verified[0]->identity_is_approved == "Approved" && $doc_verified[0]->edu_is_approved == "Approved" && $doc_verified[0]->resume_file != '' && $doc_verified[0]->english_score >= 10)
        @php $DocumentVerified = "";  @endphp
    @elseif(($doc_verified[0]->identity_is_approved == "Reject" && $doc_verified[0]->identity_trail_attempt == 0 ) || ($doc_verified[0]->edu_is_approved == "Reject" && $doc_verified[0]->edu_trail_attempt == 0))
        @php $DocumentVerified = "";   @endphp
    @elseif($doc_verified[0]->english_test_attempt == "1" && $doc_verified[0]->english_score <= 10)
        @php $DocumentVerified = "englishVerified";   @endphp
    @elseif($doc_verified[0]->english_test_attempt == "0" && $doc_verified[0]->english_score <= 10)
        @php $DocumentVerified = "englishAttempt";   @endphp
    @elseif($doc_verified[0]->edu_doc_file != "" && $doc_verified[0]->identity_doc_file != "" &&  $doc_verified[0]->resume_file != '' && $doc_verified[0]->english_score != '')
    @php $DocumentVerified = ""; @endphp
    @else
        @php $DocumentVerified = "NotVerified"; @endphp
    @endif --}}
    {{ htmlspecialchars_decode($courseDetails[0]['course'][0]['course_title']) }}
</h4>
@php
    $urlSegments = request()->segments();
    $CourseId = base64_decode(end($urlSegments));
    // $DocumentVerified = getDocumentVerificationStatus($CourseId);
    $doc_verified = getData('student_doc_verification',['english_score','identity_is_approved','edu_is_approved','identity_doc_file','edu_doc_file','resume_file','edu_trail_attempt','identity_trail_attempt','english_test_attempt','edu_athe_approved','edu_level','student_id','edu_master_approved'],['student_id'=>Auth::user()->id]);

    $CourseMaster = getData('course_master',['category_id','course_title','course_old_price','course_final_price','id','installment_amount'],['id'=>$CourseId]);
    $player = "video";

    $canAccess = getDocumentStatusClass($CourseMaster[0], $doc_verified[0],$player,null);

@endphp
@php $j=0; $totalLocks = '';
@endphp
@php
$CourseWatchData = getData('student_course_master',['watch_content_id','course_progress','course_expired_on','id','total_course_price','payment_installment_type'],['user_id'=>auth()->user()->id,'course_id'=>$courseDetails[0]['course'][0]['id']],'','id','DESC');

if($CourseWatchData[0]->payment_installment_type == "InstallmentPayment"){
    $CourseWatchNextData = getData('payment_installment',['next_install_date','paid_install_no'],['user_id'=>auth()->user()->id,'course_id'=>$courseDetails[0]['course'][0]['id'],'student_course_master_id'=> $CourseWatchData[0]->id,'paid_install_status'=>'0'],'1','id','DESC');

    $CourseWatchNextDataAmount = getData('payment_installment',['next_install_date','paid_install_amount'],['user_id'=>auth()->user()->id,'course_id'=>$courseDetails[0]['course'][0]['id'],'student_course_master_id'=> $CourseWatchData[0]->id,'paid_install_status'=>'0'],'','','id','DESC');


    $totalPaid = 0;
    $totalAmount = 0;
    if (!empty($CourseWatchNextDataAmount)) {
        foreach ($CourseWatchNextDataAmount as $installment) {
            $totalPaid += $installment->paid_install_amount;
        }
    } 
    if($totalPaid == $CourseWatchData[0]->total_course_price){
        $expiryDate = '';
        $paidCount = '';
        $totalLocks = 0;
    }else{
        $expiryDate = $CourseWatchNextData[0]->next_install_date ?? null;
        $paidCount = $CourseWatchNextData[0]->paid_install_no ?? '';
    }
}else{
    $expiryDate = '';
    $paidCount = '';
}
@endphp

<div class="mb-3 mt-3">
<div class="progress" style="height: 6px">

<div class="progress-bar bg-blue progress-bar-striped progress-bar-animated total_progress_display_value"
    role="progressbar"
    value="";
    style="width:{{ isset($CourseWatchData[0]->course_progress)  ?  $CourseWatchData[0]->course_progress.'%' : ''}} "
    aria-valuenow="10"
    aria-valuemin="0"
    aria-valuemax="100">
</div>

</div>
<small class="total_progress_display_complete"> {{ isset($CourseWatchData[0]->course_progress)  ?  $CourseWatchData[0]->course_progress : '0'}} %
Completed</small>
</div>
</li>

<!-- Orientation Section -->
{{--
<li class="list-group-item">
<!-- Toggle -->
<a class="d-flex align-items-center h4 mb-0"
data-bs-toggle="collapse" href="#orientaion" role="button"
aria-expanded="false" aria-controls="orientaion">
<div class="me-auto accrodanTitle">
Orientation</div>
<!-- Chevron -->
<span class="chevron-arrow ms-4">
<i class="fe fe-chevron-down fs-lg-4 fs-sm-3"></i>
</span>
</a>
<!-- Row -->
<!-- Collapse -->
@php
$getModuleData = getData('course_section_masters', ['id'], ['section_category' => 2]);
$orientationData = getData('course_modules_videos', ['bn_collection_id', 'bn_video_url_id', 'video_title', 'id', 'video_duration'], ['section_id' => $getModuleData[0]->id, 'is_deleted' => 'No']); @endphp
<div class="collapse show" id="orientaion"
data-bs-parent="#courseAccordion">
<div class="py-3 nav" id="course-orientaion" role="tablist"
aria-orientation="vertical" style="display: inherit">
<div class="mb-3">
<div class="progress" style="height: 6px">
    <div class="progress-bar bg-blue progress-bar-striped progress-bar-animated"
        role="progressbar" style="width: 45%"
        aria-valuenow="10" aria-valuemin="0"
        aria-valuemax="100">
    </div>
</div>
<small>45%
    Completed</small>
</div>

@if (isset($orientationData) && !empty($orientationData) && count($orientationData) > 0)
    @foreach ($orientationData as $key => $data)
       @php
        $oriId = base64_encode($data->id);
        $libraryId = env('MASTER_LIBRARY_ID');
        $videoUrl = "https://iframe.mediadelivery.net/embed/$libraryId/$data->bn_video_url_id?&loop=true&muted=true&preload=true&responsive=true";
        $video_duration = $data->video_duration;
        $video_duration = floatval($video_duration);
        $minutes = floor($video_duration / 60);
        $remaining_seconds = $video_duration % 60;
        $video_duration = $minutes . 'm ' . $remaining_seconds . 's';
        if ($data->bn_collection_id == '') {
            $icons = 'pdf';
        } else {
            $icons = '';
        }
      @endphp
        @if ($data->bn_collection_id != '')
            <a href="#ori-{{ $oriId }}"
                class="mb-2 d-flex justify-content-between align-items-center tab-link {{ $key === 0 ? 'active' : '' }}"
                id="ori-{{ $oriId }}-tab"
                data-ori-id="{{ $oriId }}"
                data-video-url="{{ $videoUrl }}"
                data-bs-toggle="pill" role="tab"
                style="{{ $key === 0 ? 'background-color: whitesmoke;  border-radius: 3px;' : '' }}"
                data-icons="{{ $icons }}">
                <div class="text-truncate">
                    <span
                        class="icon-shape bg-light text-primary icon-sm rounded-circle me-2 bg-blue">
                        <i class="bi {{ $key === 0 ? 'bi-pause-fill' : 'bi-play-fill' }}  color-green fs-lg-4 fs-sm-3 fw-bold"
                            id="bi-{{ $oriId }}"></i>
                    </span>
                    <span
                        class="preview-course-heading">
                        {{ strlen($data->video_title) > 35 ? Str::limit($data->video_title, 35) . '...' : $data->video_title }}
                    </span>
                </div>
                <div style="white-space: nowrap"
                    class="timeduration">
                    <span
                        class="timeduration">{{ $video_duration }}</span>
                </div>
            </a>
        @else
            <a href="#ori-{{ $oriId }}"
                class="mb-2 d-flex justify-content-between align-items-center tab-link {{ $key === 0 ? 'active' : '' }}"
                id="ori-{{ $oriId }}-tab"
                data-ori-id="{{ $oriId }}"
                data-video-url="{{ $videoUrl }}"
                data-bs-toggle="pill" role="tab"
                style="{{ $key === 0 ? 'background-color: whitesmoke;  border-radius: 3px;' : '' }}"
                data-icons="{{ $icons }}">
                <div class="text-truncate">
                    <span
                        class="icon-shape bg-light text-primary icon-sm rounded-circle me-2 bg-blue">
                        <i class="bi bi-file-pdf  color-green fs-lg-4 fs-sm-3 fw-bold"
                            id="bi-{{ $oriId }}"></i>
                    </span>
                    <span
                        class="preview-course-heading">
                        {{ strlen($data->video_title) > 35 ? Str::limit($data->video_title, 35) . '...' : $data->video_title }}
                    </span>
                </div>
                <div style="white-space: nowrap"
                    class="timeduration">
                    <span
                        class="timeduration">{{ $video_duration }}</span>
                </div>
            </a>
        @endif
    @endforeach
@endif
</div>
</div>
</li>  --}}
<!--Course Content Section -->

@php $totalProgress = 0;@endphp
@if (isset($courseDetails) && !empty($courseDetails) && count($courseDetails) > 0)
@php
$quizsteper = 1;
$CourseIconData = getData('student_course_master',['watch_content_id','id'],['user_id'=>auth()->user()->id,'course_id'=>$courseDetails[0]['course'][0]['id']],'','id','DESC');
@endphp

@php
$sectionData = getData('course_managment_master', ['section_id'], ['course_master_id' => $courseDetails[0]['course'][0]['id'], 'is_deleted' => 'No'],'','id','ASC');
$VideoData = getData('course_modules_videos', ['bn_collection_id', 'bn_video_url_id', 'video_title', 'id', 'video_duration'], ['section_id' => $sectionData[0]->section_id, 'is_deleted' => 'No']);
@endphp
<?php 
$installmentCount = $courseDetails[0]['course'][0]['no_of_installment'];
$totalSectionCount = 0;
?>
@foreach ($courseDetails as $keys => $sections)
@php
// if($totalLocks > 0){
if($totalLocks == 0){
    $totalSectionCount = 0;
}else{
    if (!empty($sections['sections']) && is_array($sections['sections'])) {
        $totalSectionCount += count(array_filter(array_column($sections['sections'], 'section_name')));
    }
}
// }
@endphp

<li class="list-group-item">
<!-- Toggle -->
<a class="d-flex align-items-center h4 mb-0 "
    data-bs-toggle="collapse"
    href="#course{{ $i }}" role="button"
    aria-expanded="{{ $i === 1 ? 'true' : 'false' }}"
    aria-controls="course{{ $i }}">
    <div class="me-auto accrodanTitle" style="font-size: 15px">
        {{ isset($sections['sections'][0]['section_name']) ? htmlspecialchars_decode($sections['sections'][0]['section_name']) :'' }}
    </div>
    <!-- Chevron -->
    <span class="chevron-arrow ms-4">
        <i class="fe fe-chevron-down fs-4"></i>
    </span>
</a>
<!-- Row -->
<!-- Collapse -->

<div class="collapse {{ $i === 1 ? 'show' : '' }}" id="course{{ $i }}"
    data-bs-parent="#courseAccordion">
    <div class="pt-3 nav"
        id="course-tab{{ $i }}"
        role="tablist" aria-orientation="vertical"
        style="display: inherit">

        {{-- <div class="mb-3">
            <div class="progress" style="height: 6px">
                <div class="progress-bar bg-blue progress-bar-striped progress-bar-animated"
                    role="progressbar"
                    style="width: 45%"
                    aria-valuenow="10"
                    aria-valuemin="0"
                    aria-valuemax="100">
                </div>
            </div>
            <small>45%
                Completed</small>
        </div> --}}
        @if ((isset($sections['sections'][0]['section_manage']) && is_array($sections['sections'][0]['section_manage'])))
        @foreach ($sections['sections'][0]['section_manage'] as $key => $section_manage)
            @php $sectionIndex = $totalSectionCount; @endphp 
            @if (!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 1)
                @foreach ($section_manage['course_video'] as $video)
                <a class="mb-0 d-flex justify-content-between align-items-center text-inherit studentAwardCourseTitle tab-link {{ $j === 0 ?  'active' : '' }}"
                data-section-index="{{ $sectionIndex }}"
                id="course-project-tab"
                data-bs-toggle="pill"
                href="#course-project"
                onclick="videoDisplay('{{ $video['bn_video_url_id'] }}','{{$video['id']}}')"
                role="tab"
                aria-controls="course-project"
                aria-selected="false">
                 <div class="d-flex align-items-center">
                     <span class="icon-shape icon_bg text-primary icon-sm rounded-circle me-2">
                        @php $CourseProgressData = getData('video_progress',['full_check'],['video_id'=>$video['id'],'course_id' => $courseDetails[0]['course'][0]['id'],'user_id'=>auth()->user()->id,'student_course_master_id'=>$CourseIconData[0]->id]);@endphp
                        {{-- @if($isLocked)
                            <i class="bi bi-lock-fill"></i>
                        @else --}}
                            @if(!empty($CourseProgressData[0]))
                                @if($CourseProgressData[0]->full_check == 'Yes')
                                    <i id="play-pause-icon-{{ $video['id'] }}" class="bi bi-check2 playIconStudentCoursePanel"></i>
                                @else
                                    <i id="play-pause-icon-{{ $video['id'] }}" class="bi bi-play-fill playIconStudentCoursePanel"></i>
                                @endif
                            @else
                                <i id="play-pause-icon-{{ $video['id'] }}" class="bi bi-play-fill playIconStudentCoursePanel"></i>
                            @endif
                        {{-- @endif --}}
                        </span>
                    </span>
                     <div class="ms-1">
                         <span class="d-inline-block preview-course-heading" style="font-size: 13px">{{ isset($video['video_title']) ? htmlspecialchars_decode($video['video_title']) : '' }}</span>
                     </div>
                 </div>
             </a>
             <div class="text-muted mb-2" style="margin-left: 2.8rem;">
                <p class="preview-course-heading" style="font-size: 13px">{{$video['video_duration']}}</p>
            </div>
            @php $totalProgress++; @endphp
            @endforeach
            @elseif(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 2)
                @foreach ($section_manage['course_article'] as $docs)
                @php $extensionFile = pathinfo( $docs['doc_file_name']); @endphp
                    @if(!empty($extensionFile['extension']))
                    @if($extensionFile['extension']  == 'xls' || $extensionFile['extension']  == 'xlsx')
                    <a class=" mb-2 d-flex justify-content-between align-items-center text-inherit studentAwardCourseTitle tab-link"
                        data-section-index="{{ $sectionIndex }}"
                        id="resource-excel-tab"
                        data-bs-toggle="pill"
                        onclick="ExcelContentDisplay('{{ $docs['file'] }}','{{ $docs['docs_title'] }}','{{$docs['id']}}','{{ $docs['doc_file_name'] }}')"
                        href="#resource-excel"
                        role="tab"
                        aria-controls="resource-excel"
                        aria-selected="false">
                        <div class="d-flex align-items-center">
                            <span
                                class="icon-shape text-primary icon-sm rounded-circle me-2 icon_bg">
                                @if(!empty($CourseIconData[0]->watch_content_id))
                                @php  $Watcharray = explode(",", $CourseIconData[0]->watch_content_id); @endphp
                                    @if (in_array('do_'.$docs['id'], $Watcharray))
                                        <i id="play-pause-icon-{{ $docs['id'] }}" data-filetype="excel"
                                        class="bi bi-check2 nav-icon fs-6 color-blue playIconStudentCoursePanel"></i>
                                    @else
                                        <i id="play-pause-icon-{{ $docs['id'] }}" data-filetype="excel" class="bi bi-filetype-exe nav-icon fs-6 color-blue playIconStudentCoursePanel"></i>
                                    @endif
                                @else
                                    <i id="play-pause-icon-{{ $docs['id'] }}" data-filetype="excel"
                                        class="bi bi-filetype-exe nav-icon fs-6 color-blue playIconStudentCoursePanel"></i>
                                @endif
                            </span>

                            <span class="d-inline-block preview-course-heading" style="font-size: 13px">{{ isset($docs['docs_title']) ? htmlspecialchars_decode($docs['docs_title']) : '' }}</span>
                        </div>
                        <div class="text-truncate">
                            {{-- <span></span> --}}
                        </div>
                    </a>

                    @else
                    <a class=" mb-2 d-flex justify-content-between align-items-center text-inherit studentAwardCourseTitle tab-link"
                    data-section-index="{{ $sectionIndex }}"
                    id="resource-tab"
                    data-bs-toggle="pill"
                    onclick="PdfContentDisplay('{{ $docs['file'] }}','{{ $docs['docs_title'] }}','{{$docs['id']}}')"
                    href="#resource"
                    role="tab"
                    aria-controls="resource"
                    aria-selected="false">
                    <div class="d-flex align-items-center">
                        <span
                            class="icon-shape text-primary icon-sm rounded-circle me-2 icon_bg">
                            @if(!empty($CourseIconData[0]->watch_content_id))
                            @php  $Watcharray = explode(",", $CourseIconData[0]->watch_content_id); @endphp
                                @if (in_array('pd_'.$docs['id'], $Watcharray))
                                    <i id="play-pause-icon-{{ $docs['id'] }}" data-filetype="pdf"
                                    class="bi bi-check2 nav-icon fs-6 color-blue playIconStudentCoursePanel"></i>
                                @else
                                    <i id="play-pause-icon-{{ $docs['id'] }}" data-filetype="pdf" class="bi bi-file-earmark-pdf nav-icon fs-6 color-blue playIconStudentCoursePanel"></i>
                                @endif
                            @else
                                <i id="play-pause-icon-{{ $docs['id'] }}" data-filetype="pdf"
                                class="bi bi-file-earmark-pdf nav-icon fs-6 color-blue playIconStudentCoursePanel"></i>
                            @endif
                        </span>
                        <span class="d-inline-block preview-course-heading" style="font-size: 13px">{{ isset($docs['docs_title']) ? htmlspecialchars_decode($docs['docs_title']) : '' }}</span>
                    </div>
                    <div class="text-truncate">
                        {{-- <span></span> --}}
                    </div>
                    </a>

                @endif
                @endif
                @php $totalProgress++; @endphp
                @endforeach
            @elseif(!empty($section_manage['content_type_id']) && $section_manage['content_type_id'] === 3)

                @foreach ($section_manage['course_quiz'] as $quiz)
                <a class="mb-2 d-flex justify-content-between align-items-center text-inherit quizActive studentAwardCourseTitle tab-link"
                    data-section-index="{{ $sectionIndex }}"
                    id="quiz-tab"
                    data-quiz_id="{{base64_encode($section_manage['content_id'])}}"
                    data-course_id="{{base64_encode($sections['course'][0]['id'])}}"
                    data-bs-toggle="pill"
                    href="javascript:void(0);" role="tab"
                    aria-controls="quiz"
                    aria-selected="false">
                    <div class="text-truncate">
                        <span
                            class="icon-shape text-primary icon-sm rounded-circle me-2 color-light-cyan icon_bg">
                            @if(!empty($CourseIconData[0]->watch_content_id))
                            @php  $Watcharray = explode(",", $CourseIconData[0]->watch_content_id); @endphp
                            @if (in_array('qu_'.$section_manage['content_id'], $Watcharray))
                                <i id="play-pause-icon-{{ $section_manage['content_id'] }}" data-filetype="quiz" class="bi bi-check2 nav-icon fs-6 color-blue playIconStudentCoursePanel"></i>
                            @else
                                <i id="play-pause-icon-{{ $section_manage['content_id'] }}" class="fe fe-help-circle nav-icon fs-6 color-primary playIconStudentCoursePanel" data-filetype="quiz"></i>
                            @endif
                        @else
                            <i id="play-pause-icon-{{ $section_manage['content_id'] }}" data-filetype="quiz"
                            class="fe fe-help-circle nav-icon fs-6 color-primary playIconStudentCoursePanel"></i>
                        @endif
                        </span>
                        <span class="text-inherit preview-course-heading" style="font-size: 13px">Quiz : {{htmlspecialchars_decode($quiz['quiz_tittle'])}}</span>

                    </div>
                    <div class="text-truncate">
                        <span></span>
                    </div>
                </a>
                @php
                    $quizsteper ++;
                @endphp
                @php $totalProgress++; @endphp

                @endforeach
            @endif

                <div class="progress_bar" value="{{$j}}"></div>
            {{-- @php $totalLockDisplay++; @endphp --}}
            @php $j++; @endphp
        @endforeach
        @endif
    </div>
</div>
</li>
@php
$i++;
// $key++;

@endphp

@endforeach

@endif
@php 
$totalLock  = 0;
if($CourseWatchData[0]->payment_installment_type == "InstallmentPayment" && $totalSectionCount > 0){
    $totalLock = $totalSectionCount/$installmentCount * $paidCount;
    $totalLock = $totalLock < 1 ? 1 : floor($totalLock);
}
@endphp

<div class="progress_count" data-progress="{{$totalProgress}}"></div>

<!-- List group item -->
</div>
</div>
</div>
</div>
<div class="simplebar-placeholder" style="width: 380px; height: 691px;"></div>
</div>
<div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
<div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
</div>
<div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
<div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
</div>
</ul>
</section>
</div>
</nav>
        <!-- Page Content -->
        <main id="page-content" style="width: 100%">
            <div class="header">
                <nav class="navbar-default navbar navbar-expand-lg p-0"
                    style="background-color: #f1f5f9;box-shadow: none;">
                    <a id="nav-toggle" href="#" class="color-blue fs-4">
                        <div class="desktop-button">
                            <button class="button is-text is-opened" id="menu-button" onclick="buttonToggle()">
                                <div class="button-inner-wrapper">
                                    <i class="bi bi-x" style="font-size: x-large"></i>
                                </div>
                            </button>
                        </div>
                    </a>
                </nav>
            </div>
            <!-- Page Header -->
            <!-- Container fluid -->
            <section class="container-fluid p-2">
                <div class="row">
                    <div class="col-12">
                        <!-- Tab content -->
                        <div class="tab-content content" id="course-tabContent">
                            <!-- Tab pane -->
                            <div class="tab-pane fade  show active" id="course-project" role="tabpanel"
                                aria-labelledby="course-project-tab">
                                <!-- Video -->
                                <div class="embed-responsive position-relative w-100 d-block overflow-hidden p-0 "
                                    style="height: 100%">
                                    <div style="position:relative;height:88vh;background:#000;">
                                        <iframe id="videoDisply" loading="lazy" class=""
                                            style="border:0;top:0;left:0;height:100%;width:100%;"
                                            allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;"
                                            allowfullscreen="true"></iframe>
                                    </div>
                                </div>
                            </div>
                            <!-- Quiz Tab pane -->
                          @include('frontend/student/part-quiz-award-course-panel',['QuizCourse'=>$courseDetails])

                            <!-- Resource 1 Tab pane -->
                            <div class="tab-pane fade" id="resource" role="tabpanel"
                                aria-labelledby="resource-tab">
                                <!-- Video -->
                                <div class="embed-responsive position-relative w-100 d-block overflow-hidden p-0 "
                                    style="height: 100vh; overflow-y: auto">
                                    <div style="text-align:center; height: 100% !important;" >
                                        {{-- <h4>Pdf Viewer</h4> --}}
                                        {{-- <iframe id="pdfDisply"
                                            class="position-absolute top-0 end-0 start-0 end-0 bottom-0 h-100 w-100"
                                            width="560" height="315" src="" title=""
                                            frameborder="0"></iframe> --}}
                                            <div id="pdfDisplay" style="height: 100%; overflow-y: auto"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="resource-excel" role="tabpanel"
                                aria-labelledby="resource-excel-tab">
                                <!-- Video -->
                                <div class="embed-responsive position-relative w-100 d-block overflow-hidden p-0"
                                    style="height: 100vh">
                                    <div  class="d-flex justify-content-center align-items-center h-100 flex-column">
                                        <h4 class="text-center">Please download the Excel file below to view its content.</h4>
                                        <a id="excelDisplay" class="btn btn-primary mt-2">Downlaod Excel File <i class="bi bi-download"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div>
                <h4 class="mt-0 course-selected-title" id="selected-title" style="padding-left: 24px; padding-bottom:0.9rem"></h4>
            </div>
        </main>
    </div>
    <div class="modal fade" id="SecondinstallmentModel" tabindex="-1" role="dialog" aria-labelledby="SecondinstallmentModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="SecondinstallmentModelLabel">Payment Reminder</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="fs-5 fw-bold text-danger installmentReminder">
                       €{{$CourseMaster[0]->installment_amount}} due on {{\Carbon\Carbon::parse($expiryDate)->format('d M Y')}}. <br>Pay to unlock next course content.
                </p>
              
                <div class="d-flex justify-content-around" style="padding: 0px">
                    <form class="checkoutform">
                    @csrf <!-- CSRF protection -->
                    @php $total_full_price = $CourseMaster[0]->course_old_price - ($CourseMaster[0]->course_old_price - $CourseMaster[0]->course_final_price) ; @endphp
                    <input type='hidden' value="{{base64_encode($CourseMaster[0]->id)}}" name="course_id" id="course_id">
                    <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($CourseMaster[0]->course_old_price)}}">
                    <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($CourseMaster[0]->course_old_price -$CourseMaster[0]->course_final_price)}}">
                    <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                    <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                    <input type="hidden" class="form-control payment_type_installment" name="payment_type_installment" value="InstallmentPayment">
                    <input type="hidden" class="form-control student_course_master_id" name="student_course_master_id" value="{{base64_encode($CourseWatchData[0]->id)}}">

                    {{-- <button class="btn btn-primary mb-2 color-green fs-4 buyCourseSecond">{{ __('static.buynow') }}</button> --}}
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <!-- Pay Now -->
                        <button type="button" class="btn btn-success fs-5 buyCourseSecond">
                          Pay Now
                        </button>
                        <!-- Later -->
                        <button type="button" class="btn btn-secondary fs-5 buyCancel" data-dismiss="modal">
                          Later
                        </button>
                      </div>
                    </form>
                </div>
                {{-- <button class="btn btn-primary w-100" id="installmentBtn">Confirm</button> --}}
            </div>
            {{-- <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div> --}}
          </div>
        </div>
    </div>
    </body>

    </html>
<script src="{{ asset('frontend/js/examJs.js')}}"></script>
<script src="{{ asset('frontend/js/studentJs.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/plyr/3.6.7/plyr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js"></script>
<script src="https://assets.mediadelivery.net/castjs/5.2.0/cast.min.js"></script>
<script src="https://assets.mediadelivery.net/hls/1.5.15/hls.min.js"></script>


<script>
    // var courseExpiryDate = "{{ $expiryDate }}"; // e.g. 2025-09-28
    var courseExpiryDate = "{{ $expiryDate ?? '' }}"; // empty string if null
    var expiryInstallNo = "{{ $paidCount ?? '' }}"; // empty string if null
    var isVideoLockedUser = @json(isVideoLockedUser());

</script>


<script>
    let playerInstance = null;
    let currentPauseIcon = null;
    // let stepper;
    let previousTabId = null; // Variable to store the previous tab ID
    // var totalProgress = $(".progress_count").data("progress");
    let currentVideoId = null;


    // document.addEventListener('DOMContentLoaded', function() {
    //     var videoId = "<?php echo $VideoData[0]->bn_video_url_id;?>";
    //     var newUrl = "https://iframe.mediadelivery.net/embed/300583/" +
    //         videoId +
    //         "?autoplay=false&loop=false&muted=false&preload=true&responsive=true";
    //     $('#videoDisply').prop('src', newUrl);
    // });
    function ordinalSuffix(i) {
        let j = i % 10, k = i % 100;
        if (j === 1 && k !== 11) return i + "st";
        if (j === 2 && k !== 12) return i + "nd";
        if (j === 3 && k !== 13) return i + "rd";
        return i + "th";
    }
    $(document).on("click", ".buyCourseSecond", function (e) {
        e.preventDefault(); // extra safety
        var baseUrl = window.location.origin;
        // alert("Buy Now clicked!");
        var $form = $(".checkoutform");
        $form.attr('action', baseUrl + "/checkout");   // change if your route is different
        // $form.attr('target', '_blank');
        $form.attr('method', 'POST');                  // force POST
        $form[0].submit();
    });
    function UploadDocument() {
        swal({
            title: "Verification Process",
            text: "Please click on verify now to upload your Documents for verification and to proceed with the English Language Proficiency Test.",
            icon: "warning",
            buttons: true,
            buttons: ["Do later", "Verify now"], // Customize button names here
            dangerMode: true,
        }).then((willVerified) => {
            if (willVerified) {
                var baseUrl = window.location.origin;
                window.location.href =
                    baseUrl + "/student/student-document-verification";
            }
        });

    }
    function EnglishTest() {
        swal({
            title: "English Test Failed",
            text: "You have one final attempt remaining to improve your score. Please review carefully and try again.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            buttons: ["Do later", "Start English Test"], // Customize button names here
        }).then((willVerified) => {
            if (willVerified) {
                var baseUrl = window.location.origin;
                window.location.href =
                    baseUrl + "/student/english-test";
            }
        });
    }
    function englishAttempt(){
        swal({
            title: "Your English Test has Failed",
            text: "All attempts have been used. You are no longer enrolled for the exam or certificate but can still access your course materials.",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "", // Text for the cancel button
                    value: null, // Return null if "Ok" is clicked
                    visible: false, // Ensure the button is visible
                    className: "", // Optional: add custom class
                    closeModal: true // Close the modal on click
                },
                confirm: {
                    text: "Ok", // Text for the confirmation button
                }
            },
            dangerMode: true,
        }).then((willVerified) => {
            if (willVerified) {
                window.location.refresh();
            }
        });
    }

    function triggerAccess(canAccess) {
        const accessMap = {
            documentVerified: ".documentVerified",
            documentRejected: ".documentRejected",
            englishAttempt: ".englishAttempt",
            documentNotUploaded: ".documentNotUploaded",
            documentNotUploadedDoc: ".documentNotUploadedDoc",
            documentNotEligible: ".documentNotEligible",
            documentEnglishTestPending: ".documentEnglishTestPending",
            documentNotUploadedATHE: ".documentNotUploadedATHE",
            englishVerified: ".englishVerified",
            documentUploadGreaterSix: ".documentUploadGreaterSix"
        };
        const selector = accessMap[canAccess];
        if (selector) {
            $(selector).trigger("click");
        }
    }
    $(document).ready(function () {
        var canAccess = "{{ $canAccess }}";
        
        
        if (typeof courseExpiryDate !== "undefined" && courseExpiryDate) {
            let expiry = new Date(courseExpiryDate);
            let LastInstallment = expiryInstallNo;
            let today = new Date();

            // calculate difference in days
            let diffTime = expiry - today;
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            console.log("Course expires in:", diffDays, "days");

            let nextInstallDateObj = new Date(expiry);
            let diffSinceDue = Math.ceil((today - nextInstallDateObj) / (1000 * 60 * 60 * 24));

            console.log("Course expires in:", diffDays, "days");
            console.log("Days since next installment due:", diffSinceDue);
            console.log(LastInstallment);

            if (diffDays <= 10 && diffDays >= 0) {

                $("#SecondinstallmentModel").modal("show"); // Bootstrap modal example
                $('.btn-secondary[data-dismiss="modal"]').addClass('buyCancel');

            }else if(diffSinceDue >= 1){
                $(".installmentReminder").html(
                    `Your ${ordinalSuffix(LastInstallment)} installment has expired. Pay your next installment immediately to unlock the next course content and continue your learning.`
                );
                $("#SecondinstallmentModel").modal("show"); // Bootstrap modal example
                $('.btn-secondary[data-dismiss="modal"]').addClass('buyCancel');
            }else{
                triggerAccess(canAccess);
            }
        }
        // $(".buyCancel").on("click", function (event) {
        //     if (canAccess === "documentVerified") {
        //         $(".documentVerified").trigger("click");
        //     }else if (canAccess === "documentRejected") {
        //         $(".documentRejected").trigger("click");
        //     }else if (canAccess === "englishAttempt") {
        //         $(".englishAttempt").trigger("click");
        //     }else if (canAccess === "documentNotUploaded") {
        //         $(".documentNotUploaded").trigger("click");
        //     }else if (canAccess === "documentNotUploadedDoc") {
        //         $(".documentNotUploadedDoc").trigger("click");
        //     }else if (canAccess === "documentNotEligible") {
        //         $(".documentNotEligible").trigger("click");
        //     }else if (canAccess === "documentEnglishTestPending") {
        //         $(".documentEnglishTestPending").trigger("click");
        //     }else if (canAccess === "documentNotUploadedATHE") {
        //         $(".documentNotUploadedATHE").trigger("click");
        //     }else if (canAccess === "englishVerified") {
        //         $(".englishVerified").trigger("click");
        //     }else if (canAccess === "documentUploadGreaterSix") {
        //         $(".documentUploadGreaterSix").trigger("click");
        //     }
        // });
        if (courseExpiryDate === "") {
            // $("#SecondinstallmentModel").modal("hide");
            if (!isVideoLockedUser) {
                triggerAccess(canAccess);
            }
        }
        // Buy Cancel click handler
        $(".buyCancel").on("click", function (event) {
            event.preventDefault();
            $("#SecondinstallmentModel").modal("hide");
            triggerAccess(canAccess);
        });
        
    });

    document.addEventListener("DOMContentLoaded", function() {

        var defaultVideoId = "<?php echo $VideoData[0]->bn_video_url_id;?>";
        var defaultId = "<?php echo $VideoData[0]->id;?>";

        var totalLock = {{ $totalLock }};
        if(totalLock > 0){
            var items = document.querySelectorAll('.studentAwardCourseTitle');
            var unlockSections = totalLock; // e.g., 1 section unlocked      
            var lockVideos = isVideoLockedUser; // helper-controlled locking
            items.forEach(function(el) {
                // console.log(""unlockSections);
                var originalClick = el.getAttribute('onclick');  

                var sectionIndex = parseInt(el.getAttribute('data-section-index'), 10);
                if (isNaN(sectionIndex)) sectionIndex = 0; // fallback
                var videoTitle = el.textContent.trim();
                var firstVideoTitle = items[0].textContent.trim();
                if (lockVideos) {
                    if (videoTitle !== firstVideoTitle) {
                        el.style.pointerEvents = 'none'; // unclickable
                        el.style.opacity = '0.6';
                        el.removeAttribute('onclick'); // optional
                        var icon = el.querySelector('i.playIconStudentCoursePanel');
                        if(icon){
                            icon.className = 'bi bi-lock playIconStudentCoursePanel'; // change to lock
                        }
                        el.onclick = function(e){
                            e.preventDefault(); // prevent original click
                        }
                        el.setAttribute('href', '#');
                        el.removeAttribute('data-quiz_id');
                        el.removeAttribute('data-course_id');
                        el.classList.remove('tab-link');
                    } else {
                        el.style.pointerEvents = 'auto';
                        el.style.opacity = '1';
                    }
                } else {
                    if(sectionIndex > unlockSections){ // lock everything beyond unlocked sections

                        var icon = el.querySelector('i.playIconStudentCoursePanel');
                        if(icon){
                            icon.className = 'bi bi-lock playIconStudentCoursePanel'; // change to lock
                        }
                        el.onclick = function(e){
                            e.preventDefault(); // prevent original click
                        }
                        el.setAttribute('href', '#');
                        el.removeAttribute('data-quiz_id');
                        el.removeAttribute('data-course_id');
                        el.classList.remove('tab-link');

                    }else {
                        // Unlocked: restore original onclick if needed
                        if(originalClick){
                            el.setAttribute('onclick', originalClick);
                        }
                    }
                }
            });
        }else{
            var lockVideos = isVideoLockedUser; // helper-controlled locking
            var items = document.querySelectorAll('.studentAwardCourseTitle');
            items.forEach(function(el) {
                if (lockVideos) {
                    var videoTitle = el.textContent.trim();
                    var firstVideoTitle = items[0].textContent.trim();
                    if (videoTitle !== firstVideoTitle) {
                            el.style.pointerEvents = 'none'; // unclickable
                            el.style.opacity = '0.6';
                            el.removeAttribute('onclick'); // optional
                            var icon = el.querySelector('i.playIconStudentCoursePanel');
                            if(icon){
                                icon.className = 'bi bi-lock playIconStudentCoursePanel'; // change to lock
                            }
                            el.onclick = function(e){
                                e.preventDefault(); // prevent original click
                            }
                            el.setAttribute('href', '#');
                            el.removeAttribute('data-quiz_id');
                            el.removeAttribute('data-course_id');
                            el.classList.remove('tab-link');
                    } else {
                        el.style.pointerEvents = 'auto';
                        el.style.opacity = '1';
                    }
                }
            });
        }
        // Call videoDisplay to set the default video
        videoDisplay(defaultVideoId, defaultId);

    });

    // function videoDisplay(videoId,vid) {
    //     currentVideoId = videoId;
    //     console.log("currentVideoId", currentVideoId);
    //     console.log("vid",vid);
    //     var newUrl = "https://iframe.mediadelivery.net/embed/253882/" +
    //         videoId +
    //         "?autoplay=false&loop=false&muted=false&preload=true&responsive=true";
    //     $('#videoDisply').prop('src', newUrl);
    //     $('.quizTab').removeClass("active show");
    //     $('#resource').hide();
    //     $('#course-project').show();
	// 	$('#course-project').addClass('active show')
    //     $("#resource-excel").hide();
    //     $("#videoDisply").addClass("videoDisply_"+videoId);
    //     var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";

    //     var csrfToken = $('meta[name="csrf-token"]').attr("content");

    //     if (currentPauseIcon) {
    //         currentPauseIcon.classList.remove('bi-pause-fill');
    //         currentPauseIcon.classList.add('bi-play-fill');
    //     }

    //     if (playerInstance) {
    //         playerInstance.pause(); // Clean up previous instance if exists
    //     }

    //     playerInstance = new playerjs.Player(document.querySelector(".videoDisply_" + videoId));
    //     let duration = 0;
    //     let watchTime = 0;
    //     let timeStarted = -1;
    //     let playbackSpeed = 1; // Default playback speed
    //     const buffer = 1; // Allowable buffer time in seconds




    //     // Event listener for when the video is paused
    //     playerInstance.on('pause', () => {
    //         if (timeStarted > 0) {
    //             const timeEnded = new Date().getTime() / 1000;
    //             watchTime += (timeEnded - timeStarted);
    //             timeStarted = -1; // Reset timeStarted
    //             lasttimestore(course_id,vid,watchTime)
    //         }
    //         var iconElement = document.getElementById("play-pause-icon-" + vid);
    //         if (iconElement) {
    //             iconElement.classList.remove('bi-pause-fill');
    //             iconElement.classList.add('bi-play-fill');
    //         }
    //         //console.log("watchTime",watchTime,course_id,vid)

    //         // checkIfWatchedFully(vid);
    //     });

    //     // Event listener for when the video ends
    //     playerInstance.on('ended', () => {
    //         if (timeStarted > 0) {
    //             const timeEnded = new Date().getTime() / 1000;
    //             watchTime += (timeEnded - timeStarted) ;
    //             timeStarted = -1;
    //         }
    //         checkIfWatchedFully(vid);
    //     });

    //     playerInstance.on('timeupdate', (data) => {
    //         if (timeStarted > 0) {
    //             const currentTime = new Date().getTime() / 1000;
    //             watchTime += (currentTime - timeStarted);
    //             timeStarted = currentTime;
    //         }

    //         if (duration <= 0) {
    //             duration = data.duration || playerInstance.duration;
    //             if (duration) {
    //                 console.log('Video duration set to:', duration);
    //             }
    //         }
    //     });

    //     var iconElement = $("#play-pause-icon-" + vid);

    //     if (iconElement.hasClass("bi-play-fill")) {
    //         console.log("Found bi-play-fill class");
    //         iconElement.removeClass("bi-play-fill").addClass("bi-pause-fill");
    //     } else if (iconElement.hasClass("bi-pause-fill")) {
    //         console.log("Found bi-pause-fill class sdfdfdsf");
    //         iconElement.removeClass("bi-pause-fill").addClass("bi-play-fill");
    //     } else if (iconElement.hasClass("bi-check2")) {
    //         var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";
    //         $.ajax({
    //             url: studentBaseUrl + "/student-getprogess/",
    //             type: "post",
    //             data: {
    //                 course_id: course_id,
    //             },
    //             dataType: "json",
    //             headers: {
    //                 "X-CSRF-TOKEN": csrfToken,
    //             },
    //             success: function (response) {
    //                 if(response.data != undefined){
    //                     response.data.forEach(item => {
    //                         if (iconElement) {
    //                             if (item.full_check === 'Yes' && vid == item.video_id) {
    //                                 iconElement.classList.remove('bi-pause-fill', 'bi-play-fill');
    //                                 iconElement.classList.add('bi-check2');
    //                             }
    //                         }
    //                     });
    //                 }else{
    //                     iconElement.removeClass("bi-check2").addClass("bi-pause-fill");
    //                 }
    //             }
    //         });
    //     } else{
    //         // Handle case where neither class is present (optional)
    //         console.log("No class found or unknown state");
    //     }


    //         const currentTabId = vid; // Get the ID of the currently clicked tab

    //         if (previousTabId) {
    //             console.log("Previous Tab ID:", previousTabId);

    //             var iconElement = $("#play-pause-icon-" + previousTabId);
    //             var filetype = iconElement.data('filetype');
    //             if (iconElement.hasClass("bi-play-fill")) {
    //                 console.log("Found bi-play-fill class");
    //                 iconElement.removeClass("bi-play-fill").addClass("bi-pause-fill");
    //             }else if (iconElement.hasClass("bi-pause-fill")) {
    //                 console.log("Found bi-pause-fill class testing");
    //                 iconElement.removeClass("bi-pause-fill").addClass("bi-play-fill");
    //             }
    //             if(filetype == 'pdf'){
    //                     iconElement.removeClass("bi-file-earmark-pdf").addClass("bi-check2");
    //             }
    //             if(filetype == 'excel'){
    //                 iconElement.removeClass("bi-filetype-exe").addClass("bi-check2");
    //             }

    //             // Retrieve the course ID from PHP variable
    //             var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";

    //             // AJAX request to get progress
    //             $.ajax({
    //                 url: studentBaseUrl + "/student-getprogess/",
    //                 type: "post",
    //                 data: {
    //                     course_id: course_id,
    //                 },
    //                 dataType: "json",
    //                 headers: {
    //                     "X-CSRF-TOKEN": csrfToken,
    //                 },
    //                 success: function (response) {
    //                     console.log(response.data);
    //                     if(response.data != undefined){
    //                         response.data.forEach(item => {
    //                             // console.log(`Full Check: ${item.full_check}, Video ID: ${item.video_id}`);
    //                             var iconElement = document.getElementById("play-pause-icon-" + item.video_id);
    //                             if (iconElement) {
    //                                 if (item.full_check === 'Yes' && previousTabId == item.video_id) {
    //                                     // Remove existing classes and add the new one
    //                                     iconElement.classList.remove('bi-pause-fill', 'bi-play-fill');
    //                                     iconElement.classList.add('bi-check2');
    //                                 }
    //                             }
    //                         });
    //                     }
    //                     var iconElement = $("#play-pause-icon-" + previousTabId);
    //                     // iconElement.addClass("bi-check2").removeClass("bi-pause-fill");

    //                     // Update previousTabId for the next use
    //                     previousTabId = currentTabId;
    //                     // var iconElement = $("#play-pause-icon-" + currentTabId);

    //                     // if (iconElement.hasClass("bi-play-fill")) {
    //                     //     console.log("Found bi-play-fill class");
    //                     //     iconElement.removeClass("bi-play-fill").addClass("bi-pause-fill");
    //                     // } else if (iconElement.hasClass("bi-pause-fill")) {
    //                     //     console.log("Found bi-pause-fill class");
    //                     //     iconElement.removeClass("bi-pause-fill").addClass("bi-play-fill");
    //                     // } else {
    //                     //     // Handle case where neither class is present (optional)
    //                     //     console.log("No class found or unknown state");
    //                     // }
    //                 },
    //                 error: function (xhr, status, error) {
    //                     console.error("Error fetching data:", status, error);
    //                 }
    //             });

    //         } else {
    //             // No previousTabId to compare with, just set previousTabId to the current one
    //             previousTabId = currentTabId;
    //             var iconElement = $("#play-pause-icon-" + currentTabId);

    //             // if (iconElement.hasClass("bi-play-fill")) {
    //             //     console.log("Found bi-play-fill class");
    //             //     iconElement.removeClass("bi-play-fill").addClass("bi-pause-fill");
    //             // } else if (iconElement.hasClass("bi-pause-fill")) {
    //             //     console.log("Found bi-pause-fill class test");
    //             //     iconElement.removeClass("bi-pause-fill").addClass("bi-play-fill");
    //             // } else {
    //             //     // Handle case where neither class is present (optional)
    //             //     console.log("No class found or unknown state");
    //             // }
    //         }

    //     // } else {
    //     //     console.error("Element not found:", "play-pause-icon-" + vid);
    //     // }        // Function to check if the video has been watched fully
    //     function checkIfWatchedFully(vid) {
    //         console.log('Checking watch status...');
    //         console.log('Duration:', duration);
    //         console.log('Watch Time:', watchTime);
    //         console.log('Buffer:', buffer);
    //         var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";
    //         var studentBaseUrl = window.location.origin + "/student";
    //         $.ajax({
    //             url: studentBaseUrl + "/student-videoprogess/",
    //             type: "post",
    //             data: {
    //                 course_id: course_id,
    //                 watch_content: vid,
    //                 duration: duration,
    //             },
    //             dataType: "json",
    //             headers: {
    //                 "X-CSRF-TOKEN": csrfToken,
    //             },
    //             success: function (response) {
    //                 // console.log("Progress saved successfully");
    //             }
    //         });

    //         if (duration > 0 && Math.abs(duration - watchTime) <= buffer) {
    //             var studentBaseUrl = window.location.origin + "/student";
    //             $.ajax({
    //             url: studentBaseUrl + "/student-watchprogess-check/",
    //             type: "post",
    //             data: {
    //                 course_id:course_id,
    //                 watch_content:"v_"+vid,
    //             },
    //             dataType: "json",
    //             headers: {
    //                 "X-CSRF-TOKEN": csrfToken,
    //             },
    //             success: function (response) {
    //                 var iconElement = document.getElementById("play-pause-icon-" + vid);
    //                 console.log(iconElement);
    //                 iconElement.classList.remove('bi-pause-fill', 'bi-play-fill');
    //                 iconElement.classList.add('bi-check2');
    //                 console.log(response.data);
    //                 if(response.data == "FALSE"){
    //                     var watch_content = "v_"+vid;
    //                     var currentValue = $('.total_progress_display_value').attr('value');
    //                     var progress_count_total = $(".progress_count").data("progress");
    //                     if(response.count == '0'){
    //                         var total_progress_display_count = 1;
    //                     }else{
    //                         var total_progress_display_count = Number(response.count) + 1;
    //                     }
    //                     var total_progress_display =  (total_progress_display_count/progress_count_total)*100;
    //                     $(".total_progress_display_value").css('width', total_progress_display.toFixed(0)+"%");
    //                     $('.total_progress_display_value').attr('value', total_progress_display_count);
    //                     document.querySelector('.total_progress_display_complete').textContent =  total_progress_display.toFixed(0) + '% Completed';
    //                     var progress_bar = $('.progress_bar').attr('value');
    //                     var studentBaseUrl = window.location.origin + "/student";
    //                     var csrfToken = $('meta[name="csrf-token"]').attr("content");
    //                     $.ajax({
    //                         url: studentBaseUrl + "/student-watchprogess",
    //                         type: "post",
    //                         data: {
    //                             progress_bar:total_progress_display.toFixed(0),
    //                             total_progress_display_value:watch_content,
    //                             total_progress_display_count:total_progress_display_count,
    //                             course_id:course_id
    //                         },
    //                         dataType: "json",
    //                         headers: {
    //                             "X-CSRF-TOKEN": csrfToken,
    //                         },
    //                         success: function (response) {

    //                         },
    //                     });
    //                 }

    //             }
    //             });
    //         } else {
    //             watchTime = 0;
    //             console.log("Video has not been watched completely.");
    //             // sendCompletionStatusToServer(false);
    //         }
    //     }



    //     function lasttimestore(course_id,vid,watchTime){
    //          console.log('inside_lastcourse_id',course_id,vid,watchTime)
    //         // alert('datain_store',course_id);

    //         var studentBaseUrl = window.location.origin + "/student";
    //         var watchTime = watchTime
    //          $.ajax({
    //             url: studentBaseUrl + "/video-progress/",
    //             type: "post",
    //             data: {
    //                 course_id: course_id,
    //                 video_id: vid,
    //                 watchTime: watchTime,
    //             },
    //             dataType: "json",
    //             headers: {
    //                 "X-CSRF-TOKEN": csrfToken,
    //             },
    //             success: function (response) {
    //               console.log("Progress saved successfully",response);
    //             }
    //         });
    //     }
    //     if(course_id||vid){
    //         fetch(`/student/video-progress/${course_id}/${vid}`, {
    //                 headers: {
    //                     // if needed
    //                 }
    //             })
    //             .then(res => res.json())
    //             .then(data => {
    //                 const resumeTime = Math.floor(data.last_watch_time || 0);
    //                 //console.log("inside_Data",data.last_watch_time);
    //                 playerInstance = new playerjs.Player(document.querySelector(".videoDisply_" + videoId));
    //                 playerInstance.on('ready', () => {
    //                     playerInstance.setCurrentTime(resumeTime);
    //                 });

    //                 playerInstance.on('play', () => {
    //                     if (timeStarted < 0) {
    //                         timeStarted = new Date().getTime() / 1000;
    //                     }
    //                 });



    //         });
    //      }


    // }

    function videoDisplay(videoId, vid) {
        currentVideoId = videoId;
        // console.log("currentVideoId", currentVideoId);
        console.log("vid",vid);

        const course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        const newUrl = "https://iframe.mediadelivery.net/embed/253882/" + videoId + "?autoplay=false&loop=false&muted=false&preload=true&responsive=true";
        $('#videoDisply').prop('src', newUrl);
        $("#videoDisply").addClass("videoDisply_" + videoId);

        $('.quizTab').removeClass("active show");
        $('#resource').hide();
        $('#course-project').show().addClass('active show');
        $('#resource-excel').hide();

        if (currentPauseIcon) {
            currentPauseIcon.classList.remove('bi-pause-fill');
            currentPauseIcon.classList.add('bi-play-fill');
        }

        if (playerInstance) {
            playerInstance.pause();
        }

        playerInstance = new playerjs.Player(document.querySelector(".videoDisply_" + videoId));

        let duration = 0;
        let watchTime = 0;
        let timeStarted = -1;
        const buffer = 1;
        let hasFastForwarded = false;
        let lastRecordedTime = 0;
        let interval;
        let watchedRanges = [];
        if (course_id || vid) {
            fetch(`/student/video-progress/${course_id}/${vid}`)
                .then(res => res.json())
                .then(data => {
                    const resumeTime = Number(data.last_watch_time || 0);
                    //console.log(resumeTime);
                    playerInstance = new playerjs.Player(document.querySelector(".videoDisply_" + videoId));

                    playerInstance.on('ready', () => {
                        playerInstance.setCurrentTime(resumeTime);
                        lastRecordedTime = resumeTime;
                    });

                    playerInstance.on('play', () => {
                        if (timeStarted < 0) {
                            timeStarted = new Date().getTime() / 1000;
                        }
                        playerInstance.getCurrentTime((ct) => {
                            lastRecordedTime = ct;
                        });
                        interval = setInterval(() => {
                            playerInstance.getCurrentTime((currentTime) => {
                                const delta = currentTime - lastRecordedTime;

                                if (delta > 0 && delta <= 5) {
                                    watchTime += delta;
                                    watchedRanges.push({ start: lastRecordedTime, end: currentTime });
                                } else if (delta > 5) {
                                    //console.log(`Fast forward detected: skipped ${delta.toFixed(2)}s`);
                                    hasFastForwarded = true;
                                }

                                lastRecordedTime = currentTime;

                            });
                        }, 3000);
                    });


                    playerInstance.on('pause', () => {
                        if (timeStarted > 0) {
                            const timeEnded = new Date().getTime() / 1000;
                            const delta = timeEnded - timeStarted;
                            if (delta > 0 && delta <= 5) {
                                watchTime += delta;
                                console.log('wastchtimedelta',watchTime);
                            }
                            timeStarted = -1;
                        }
                        const iconElement = document.getElementById("play-pause-icon-" + vid);
                        if (iconElement) {
                            iconElement.classList.remove('bi-pause-fill');
                            iconElement.classList.add('bi-play-fill');
                        }
                        checkIfWatchedFully(vid);
                        clearInterval(interval);
                        lasttimestore(course_id, vid, watchTime, hasFastForwarded,lastRecordedTime);
                        //console.log("Watched Ranges:", watchedRanges);
                        //console.log("Merged Ranges:", mergeRanges(watchedRanges));
                        //console.log("Accurate Time:", calculateTotalWatchedTime(mergeRanges(watchedRanges)));
                        watchTime = 0;
                    });


                    playerInstance.on('ended', () => {
                        if (timeStarted > 0) {
                            const timeEnded = new Date().getTime() / 1000;
                            console.log('whenend',timeEnded);
                            const delta = timeEnded - timeStarted;
                            if (delta > 0 && delta <= 5) {
                                watchTime += delta;
                            }
                            timeStarted = -1;
                        }
                        clearInterval(interval);
                        checkIfWatchedFully(vid);
                        lasttimestore(course_id, vid, watchTime, hasFastForwarded,lastRecordedTime);
                        watchTime = 0;
                    });

                    window.addEventListener("beforeunload", () => {
                        lasttimestore(course_id, vid, watchTime, hasFastForwarded, lastRecordedTime);
                    });
                });
        }

        playerInstance.on('timeupdate', (data) => {
            if (duration <= 0) {
                duration = data.duration || playerInstance.duration;
                if (duration) {
                    //console.log('Video duration set to:', duration);
                }
            }
        });


        function mergeRanges(ranges) {
            if (ranges.length === 0) return [];

            ranges.sort((a, b) => a.start - b.start);
            const merged = [ranges[0]];

            for (let i = 1; i < ranges.length; i++) {
                const last = merged[merged.length - 1];
                const current = ranges[i];

                if (current.start <= last.end) {
                    last.end = Math.max(last.end, current.end);
                } else {
                    merged.push(current);
                }
            }

            return merged;
        }

        function calculateTotalWatchedTime(mergedRanges) {
            return mergedRanges.reduce((sum, r) => sum + (r.end - r.start), 0);
        }

        function lasttimestore(course_id, vid, watchTime, hasFastForwarded, lastRecordedTime) {
            // console.log("watchTime:", watchTime);
            // console.log("Watched Ranges:", watchedRanges);
            // console.log("Merged Ranges:", mergeRanges(watchedRanges));
            // console.log("Accurate Time:", calculateTotalWatchedTime(mergeRanges(watchedRanges)));
            // return;
            if (hasFastForwarded) {
                console.log("Not saving because user fast-forwarded.");
                return;
            }

            if (!watchTime || watchTime <= 5 || lastRecordedTime < 10) {
                console.log("Not saving — too short");
                return;
            }
            // console.log('watchtime',watchTime);
            //  return;
            const merged = mergeRanges(watchedRanges);
            const studentBaseUrl = window.location.origin + "/student";

            $.ajax({
                url: studentBaseUrl + "/video-progress/",
                type: "POST",
                data: {
                    course_id: course_id,
                    video_id: vid,
                    watchTime: watchTime,
                    duration: duration,
                    lastRecordedTime:lastRecordedTime,
                    watched_segments: JSON.stringify(merged),
                },
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function (response) {
                    console.log("Progress saved successfully", response);
                    if(response.full_check == 'Yes'){
                        $.ajax({
                            url: studentBaseUrl + "/student-watchprogess-check/",
                            type: "post",
                            data: {
                                course_id:course_id,
                                watch_content:"v_"+vid,
                            },
                            dataType: "json",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                            },
                            success: function (response) {
                                var iconElement = document.getElementById("play-pause-icon-" + vid);
                                console.log(iconElement);
                                iconElement.classList.remove('bi-pause-fill', 'bi-play-fill');
                                iconElement.classList.add('bi-check2');
                                console.log(response.data);
                                if(response.data == "FALSE"){
                                    var watch_content = "v_"+vid;
                                    var currentValue = $('.total_progress_display_value').attr('value');
                                    var progress_count_total = $(".progress_count").data("progress");
                                    if(response.count == '0'){
                                        var total_progress_display_count = 1;
                                    }else{
                                        var total_progress_display_count = Number(response.count) + 1;
                                    }
                                    var total_progress_display =  (total_progress_display_count/progress_count_total)*100;
                                    $(".total_progress_display_value").css('width', total_progress_display.toFixed(0)+"%");
                                    $('.total_progress_display_value').attr('value', total_progress_display_count);
                                    document.querySelector('.total_progress_display_complete').textContent =  total_progress_display.toFixed(0) + '% Completed';
                                    var progress_bar = $('.progress_bar').attr('value');
                                    var studentBaseUrl = window.location.origin + "/student";
                                    var csrfToken = $('meta[name="csrf-token"]').attr("content");
                                    $.ajax({
                                        url: studentBaseUrl + "/student-watchprogess",
                                        type: "post",
                                        data: {
                                            progress_bar:total_progress_display.toFixed(0),
                                            total_progress_display_value:watch_content,
                                            total_progress_display_count:total_progress_display_count,
                                            course_id:course_id
                                        },
                                        dataType: "json",
                                        headers: {
                                            "X-CSRF-TOKEN": csrfToken,
                                        },
                                        success: function (response) {

                                        },
                                    });
                                }

                            }
                        });
                    }
                }
            });
        }


        const currentTabId = vid; // Get the ID of the currently clicked tab

            if (previousTabId) {
                console.log("Previous Tab ID:", previousTabId);

                var iconElement = $("#play-pause-icon-" + previousTabId);
                var filetype = iconElement.data('filetype');
                if (iconElement.hasClass("bi-play-fill")) {
                    console.log("Found bi-play-fill class");
                    iconElement.removeClass("bi-play-fill").addClass("bi-pause-fill");
                }else if (iconElement.hasClass("bi-pause-fill")) {
                    console.log("Found bi-pause-fill class testing");
                    iconElement.removeClass("bi-pause-fill").addClass("bi-play-fill");
                }
                if(filetype == 'pdf'){
                        iconElement.removeClass("bi-file-earmark-pdf").addClass("bi-check2");
                }
                if(filetype == 'excel'){
                    iconElement.removeClass("bi-filetype-exe").addClass("bi-check2");
                }

                // Retrieve the course ID from PHP variable
                // var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";

                // AJAX request to get progress
                $.ajax({
                    url: studentBaseUrl + "/student-getprogess/",
                    type: "post",
                    data: {
                        course_id: course_id,
                    },
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function (response) {
                        console.log(response.data);
                        if(response.data != undefined){
                            response.data.forEach(item => {
                                // console.log(`Full Check: ${item.full_check}, Video ID: ${item.video_id}`);
                                var iconElement = document.getElementById("play-pause-icon-" + item.video_id);
                                if (iconElement) {
                                    if (item.full_check === 'Yes' && previousTabId == item.video_id) {
                                        // Remove existing classes and add the new one
                                        iconElement.classList.remove('bi-pause-fill', 'bi-play-fill');
                                        iconElement.classList.add('bi-check2');
                                    }
                                }
                            });
                        }
                        var iconElement = $("#play-pause-icon-" + previousTabId);
                        // iconElement.addClass("bi-check2").removeClass("bi-pause-fill");

                        // Update previousTabId for the next use
                        previousTabId = currentTabId;
                        // var iconElement = $("#play-pause-icon-" + currentTabId);

                        // if (iconElement.hasClass("bi-play-fill")) {
                        //     console.log("Found bi-play-fill class");
                        //     iconElement.removeClass("bi-play-fill").addClass("bi-pause-fill");
                        // } else if (iconElement.hasClass("bi-pause-fill")) {
                        //     console.log("Found bi-pause-fill class");
                        //     iconElement.removeClass("bi-pause-fill").addClass("bi-play-fill");
                        // } else {
                        //     // Handle case where neither class is present (optional)
                        //     console.log("No class found or unknown state");
                        // }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", status, error);
                    }
                });

            } else {
                // No previousTabId to compare with, just set previousTabId to the current one
                previousTabId = currentTabId;
                var iconElement = $("#play-pause-icon-" + currentTabId);

                // if (iconElement.hasClass("bi-play-fill")) {
                //     console.log("Found bi-play-fill class");
                //     iconElement.removeClass("bi-play-fill").addClass("bi-pause-fill");
                // } else if (iconElement.hasClass("bi-pause-fill")) {
                //     console.log("Found bi-pause-fill class test");
                //     iconElement.removeClass("bi-pause-fill").addClass("bi-play-fill");
                // } else {
                //     // Handle case where neither class is present (optional)
                //     console.log("No class found or unknown state");
                // }
            }

        // } else {
        //     console.error("Element not found:", "play-pause-icon-" + vid);
        // }        // Function to check if the video has been watched fully
        function checkIfWatchedFully(vid) {
            // console.log('Checking watch status...');
             console.log('Duration:', duration);
            // console.log('Watch Time:', watchTime);
            // console.log('Buffer:', buffer);
            var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";
            var studentBaseUrl = window.location.origin + "/student";
            $.ajax({
                url: studentBaseUrl + "/student-videoprogess/",
                type: "post",
                data: {
                    course_id: course_id,
                    watch_content: vid,
                    duration: duration,
                },
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function (response) {
                    // console.log("Progress saved successfully");
                }
            });

            // if (duration > 0 && Math.abs(duration - watchTime) <= buffer) {
            //     var studentBaseUrl = window.location.origin + "/student";
            //     $.ajax({
            //     url: studentBaseUrl + "/student-watchprogess-check/",
            //     type: "post",
            //     data: {
            //         course_id:course_id,
            //         watch_content:"v_"+vid,
            //     },
            //     dataType: "json",
            //     headers: {
            //         "X-CSRF-TOKEN": csrfToken,
            //     },
            //     success: function (response) {
            //         var iconElement = document.getElementById("play-pause-icon-" + vid);
            //         console.log(iconElement);
            //         iconElement.classList.remove('bi-pause-fill', 'bi-play-fill');
            //         iconElement.classList.add('bi-check2');
            //         console.log(response.data);
            //         if(response.data == "FALSE"){
            //             var watch_content = "v_"+vid;
            //             var currentValue = $('.total_progress_display_value').attr('value');
            //             var progress_count_total = $(".progress_count").data("progress");
            //             if(response.count == '0'){
            //                 var total_progress_display_count = 1;
            //             }else{
            //                 var total_progress_display_count = Number(response.count) + 1;
            //             }
            //             var total_progress_display =  (total_progress_display_count/progress_count_total)*100;
            //             $(".total_progress_display_value").css('width', total_progress_display.toFixed(0)+"%");
            //             $('.total_progress_display_value').attr('value', total_progress_display_count);
            //             document.querySelector('.total_progress_display_complete').textContent =  total_progress_display.toFixed(0) + '% Completed';
            //             var progress_bar = $('.progress_bar').attr('value');
            //             var studentBaseUrl = window.location.origin + "/student";
            //             var csrfToken = $('meta[name="csrf-token"]').attr("content");
            //             $.ajax({
            //                 url: studentBaseUrl + "/student-watchprogess",
            //                 type: "post",
            //                 data: {
            //                     progress_bar:total_progress_display.toFixed(0),
            //                     total_progress_display_value:watch_content,
            //                     total_progress_display_count:total_progress_display_count,
            //                     course_id:course_id
            //                 },
            //                 dataType: "json",
            //                 headers: {
            //                     "X-CSRF-TOKEN": csrfToken,
            //                 },
            //                 success: function (response) {

            //                 },
            //             });
            //         }

            //     }
            //     });
            // } else {
            //     watchTime = 0;
            //     //console.log("Video has not been watched completely.");
            //     // sendCompletionStatusToServer(false);
            // }
        }

    }

    // Function to disable print functionality
    function PdfContentDisplay(file, title,pdid) {
        if(playerInstance){
            playerInstance.pause();
        }
        var newUrl = "{{ Storage::url('') }}" + file + "#toolbar=0&navpanes=0&scrollbar=0";
        $('#pdfDisplay').html('');
        // New Added
        const currentTabId = pdid; // Get the ID of the currently clicked tab

        var iconElement = $("#play-pause-icon-" + currentTabId);
        var filetype = iconElement.data('filetype');


        if(filetype == 'pdf'){
            if (iconElement.hasClass("bi-check2")) {
                console.log("sfsffsdf");
                iconElement.removeClass("bi-check2").addClass("bi-file-earmark-pdf");
            }
        }

            if (previousTabId) {

                console.log(previousTabId);
                console.log("Previous Tab ID:", previousTabId);

                var iconElement = $("#play-pause-icon-" + previousTabId);
                console.log(iconElement);
                var filetype = iconElement.data('filetype');
                if (iconElement.hasClass("bi-play-fill")) {
                    iconElement.removeClass("bi-play-fill").addClass("bi-pause-fill");
                } else if (iconElement.hasClass("bi-pause-fill")) {
                    iconElement.removeClass("bi-pause-fill").addClass("bi-play-fill");
                }else if (iconElement.hasClass("bi-check2")) {
                    iconElement.removeClass("bi-check2").addClass("bi-check2");
                }else{
                    console.log("Found bi-pause-fill class testisdfdsfng");

                }
                if(filetype) {
                    if(filetype == 'pdf'){
                        if (iconElement.hasClass("bi-check2")) {
                            console.log("previosucheck");
                            iconElement.removeClass("bi-check2").addClass("bi-file-earmark-pdf");
                        } else if (iconElement.hasClass("bi-file-earmark-pdf")) {
                            console.log("previosupdf");
                            iconElement.removeClass("bi-file-earmark-pdf").addClass("bi-check2");
                        }
                    }
                    if(filetype == 'excel'){
                        // iconElement.removeClass("bi-filetype-exe").addClass("bi-check2");
                         if (iconElement.hasClass("bi-check2")) {
                            console.log("previosucheck");
                            iconElement.removeClass("bi-check2").addClass("bi-filetype-exe");
                        } else if (iconElement.hasClass("bi-filetype-exe")) {
                            console.log("previosuexcel");
                            iconElement.removeClass("bi-filetype-exe").addClass("bi-check2");
                        }
                    }
                }
                // Retrieve the course ID from PHP variable
                var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";

                // AJAX request to get progress
                $.ajax({
                    url: studentBaseUrl + "/student-getprogess/",
                    type: "post",
                    data: {
                        course_id: course_id,
                    },
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function (response) {
                        console.log(response.data);
                        if(response.data != undefined){
                            response.data.forEach(item => {
                                // console.log(`Full Check: ${item.full_check}, Video ID: ${item.video_id}`);
                                var iconElement = document.getElementById("play-pause-icon-" + item.video_id);
                                if (iconElement) {
                                    if (item.full_check === 'Yes' && previousTabId == item.video_id) {
                                        console.log("testvideo");
                                        // Remove existing classes and add the new one
                                        iconElement.classList.remove('bi-pause-fill', 'bi-play-fill');
                                        iconElement.classList.add('bi-check2');
                                    }
                                }
                            });
                        }

                        // Update previousTabId for the next use
                        previousTabId = currentTabId;
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", status, error);
                    }
                });

            } else {

                // No previousTabId to compare with, just set previousTabId to the current one
                previousTabId = currentTabId;
                var iconElement = $("#play-pause-icon-" + currentTabId);

                console.log("testcureent");

            }

            $.ajax({
                    url: studentBaseUrl + "/student-getprogess/",
                    type: "post",
                    data: {
                        course_id: course_id,
                    },
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function (response) {
                        console.log(response.data);
                        if(response.data != undefined){
                            response.data.forEach(item => {
                                // console.log(`Full Check: ${item.full_check}, Video ID: ${item.video_id}`);
                                var iconElement = document.getElementById("play-pause-icon-" + item.video_id);
                                if (iconElement) {
                                    if (item.full_check === 'Yes') {
                                        console.log("testvideo");
                                        // Remove existing classes and add the new one
                                        iconElement.classList.remove('bi-pause-fill', 'bi-play-fill');
                                        iconElement.classList.add('bi-check2');
                                    }
                                }
                            });
                        }

                        // Update previousTabId for the next use
                        previousTabId = currentTabId;
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", status, error);
                    }
                });
            pdfjsLib.getDocument(newUrl).promise.then(pdfDoc=>{
            let pages = pdfDoc._pdfInfo.numPages;
            for(let i=0;i<=pages;i++){
                pdfDoc.getPage(i).then(page=>{
                let pdfCanvas = document.createElement('canvas')
                let context = pdfCanvas.getContext('2d');
                let scale = 1.6;
                let pageViewPort = page.getViewport({scale:scale});
                // console.log("pageViewPort",pageViewPort)
                pdfCanvas.width = pageViewPort.width;
                pdfCanvas.height = pageViewPort.height;
                $("#pdfDisplay").append(pdfCanvas)
                page.render({
                    canvasContext: context,
                    viewport: pageViewPort
                })
            })
            }

        }).catch(err=>{
            console.log(err,"error loading")
        })
        // End

        $('#pdfDisply').prop('src', newUrl);
        $('#course-project').hide();
        $('.quizTab').removeClass("active show");
        $('#resource').show();
        $("#resource-excel").hide();
        var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";

        $.ajax({
            url: studentBaseUrl + "/student-watchprogess-check/",
            type: "post",
            data: {
                course_id:course_id,
                watch_content:"pd_"+pdid
            },
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
            success: function (response) {

                console.log(response.data);
                if(response.data == "FALSE"){
                    var watch_content = "pd_"+pdid;
                    var currentValue = $('.total_progress_display_value').attr('value');
                    var progress_count_total = $(".progress_count").data("progress");
                    if(response.count == '0'){
                        var total_progress_display_count = 1;
                    }else{
                        var total_progress_display_count = Number(response.count) + 1;
                    }

                    var total_progress_display =  (total_progress_display_count/progress_count_total)*100;
                    console.log(total_progress_display_count);
                    console.log(progress_count_total);
                    console.log(total_progress_display);


                    $(".total_progress_display_value").css('width', total_progress_display.toFixed(0)+"%");
                    $('.total_progress_display_value').attr('value', total_progress_display_count);
                    document.querySelector('.total_progress_display_complete').textContent =  total_progress_display.toFixed(0) + '% Completed';


                    var progress_bar = $('.progress_bar').attr('value');

                    var studentBaseUrl = window.location.origin + "/student";
                    var csrfToken = $('meta[name="csrf-token"]').attr("content");
                    $.ajax({
                        url: studentBaseUrl + "/student-watchprogess",
                        type: "post",
                        data: {
                            progress_bar:total_progress_display.toFixed(0),
                            total_progress_display_value:watch_content,
                            total_progress_display_count:total_progress_display_count,
                            course_id:course_id
                        },
                        dataType: "json",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        success: function (response) {

                        },
                    });


                }
            },
        });
        // if (!watch_content_array_js.includes("pd_" + pdid)) {


    }
    function ExcelContentDisplay(file, title,doid,file_name) {
        if(playerInstance){
            playerInstance.pause();
        }
        var newUrl = "{{ Storage::url('') }}" + file + "#toolbar=0&navpanes=0&scrollbar=0";
        $('#excelDisplay')
            .attr("href", newUrl)
            .attr("download", file_name);
        $('#course-project').hide();
        $("#course-project").removeClass("active show");
        $('.quizTab').removeClass("active show");
        $('#resource').hide();
        $("#resource-excel").show();
            const currentTabId = doid; // Get the ID of the currently clicked tab
            var iconElement = $("#play-pause-icon-" + currentTabId);
            console.log("sdfsdf");
            if (iconElement.hasClass("bi-check2")) {
                iconElement.removeClass("bi-check2").addClass("bi-filetype-exe");
            } else if (iconElement.hasClass("bi-filetype-exe")) {
                iconElement.removeClass("bi-filetype-exe").addClass("bi-check2");
            }
            if (previousTabId) {
                console.log(previousTabId);
                console.log("Previous Tab ID:", previousTabId);

                var iconElement = $("#play-pause-icon-" + previousTabId);
                var filetype = iconElement.data('filetype');
                if (iconElement.hasClass("bi-play-fill")) {
                    console.log("Found bi-play-fill class");
                    iconElement.removeClass("bi-play-fill").addClass("bi-pause-fill");
                } else if (iconElement.hasClass("bi-pause-fill")) {
                    console.log("Found bi-pause-fill class testing");
                    iconElement.removeClass("bi-pause-fill").addClass("bi-play-fill");
                }
                if(filetype) {
                    if(filetype == 'pdf'){
                        iconElement.removeClass("bi-file-earmark-pdf").addClass("bi-check2");
                    }
                    if(filetype == 'excel'){
                        iconElement.removeClass("bi-filetype-exe").addClass("bi-check2");
                    }
                }
                // Retrieve the course ID from PHP variable
                var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";

                // AJAX request to get progress
                $.ajax({
                    url: studentBaseUrl + "/student-getprogess/",
                    type: "post",
                    data: {
                        course_id: course_id,
                    },
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function (response) {
                        console.log(response.data);
                        if(response.data != undefined){
                            response.data.forEach(item => {
                                // console.log(`Full Check: ${item.full_check}, Video ID: ${item.video_id}`);
                                var iconElement = document.getElementById("play-pause-icon-" + item.video_id);
                                if (iconElement) {
                                    if (item.full_check === 'Yes' && previousTabId == item.video_id) {
                                        // Remove existing classes and add the new one
                                        iconElement.classList.remove('bi-pause-fill', 'bi-play-fill');
                                        iconElement.classList.add('bi-check2');
                                    }
                                }
                            });
                        }
                        previousTabId = currentTabId;
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", status, error);
                    }
                });

            } else {
                // No previousTabId to compare with, just set previousTabId to the current one
                previousTabId = currentTabId;
            }
            var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";
            $.ajax({
                url: studentBaseUrl + "/student-watchprogess-check/",
                type: "post",
                data: {
                    course_id:course_id,
                    watch_content:"do_"+doid
                },
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function (response) {

                    console.log(response.data);
                    if(response.data == "FALSE"){
                        var watch_content = "do_"+doid;
                        var currentValue = $('.total_progress_display_value').attr('value');
                        var progress_count_total = $(".progress_count").data("progress");
                        if(response.count == '0'){
                            var total_progress_display_count = 1;
                        }else{
                            var total_progress_display_count = Number(response.count) + 1;
                        }

                        var total_progress_display =  (total_progress_display_count/progress_count_total)*100;



                        $(".total_progress_display_value").css('width', total_progress_display.toFixed(0)+"%");
                        $('.total_progress_display_value').attr('value', total_progress_display_count);
                        document.querySelector('.total_progress_display_complete').textContent =  total_progress_display.toFixed(0) + '% Completed';


                        var progress_bar = $('.progress_bar').attr('value');

                        var studentBaseUrl = window.location.origin + "/student";
                        var csrfToken = $('meta[name="csrf-token"]').attr("content");
                        $.ajax({
                            url: studentBaseUrl + "/student-watchprogess",
                            type: "post",
                            data: {
                                progress_bar:total_progress_display.toFixed(0),
                                total_progress_display_value:watch_content,
                                total_progress_display_count:total_progress_display_count,
                                course_id:course_id
                            },

                            dataType: "json",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                            },
                            success: function (response) {

                            },
                        });


                    }
                },
            });
    }
    var studentBaseUrl = window.location.origin + "/student";
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $(".quizActive").on("click", function (event) {
           event.preventDefault();
           var quiz_id = $(this).data("quiz_id");
           var course_id = $(this).data("course_id");
        if(course_id != undefined){
            $("#course_id").attr("value",course_id);
            $("#quizHeader").find('.newAddedStep').remove();
            $("#quizForm").find('.newAddedPane').remove();
            $("#quizForm").find("#test-start").removeClass('dstepper-block bs-stepper-block fade active');
            $("#test-last").removeClass('bs-stepper-block fade active');
            $(".save_loader").removeClass("d-none").addClass("d-block");
            var progress_count_total = $(".progress_count").data("progress");

            const currentTabId = atob(quiz_id); // Get the ID of the currently clicked tab
            if (previousTabId) {
                var iconElement = $("#play-pause-icon-" + previousTabId);
                var filetype = iconElement.data('filetype');
                if (iconElement.hasClass("bi-play-fill")) {
                    console.log("Found bi-play-fill class");
                    iconElement.removeClass("bi-play-fill").addClass("bi-pause-fill");
                } else if (iconElement.hasClass("bi-pause-fill")) {
                    console.log("Found bi-pause-fill class testing");
                    iconElement.removeClass("bi-pause-fill").addClass("bi-play-fill");
                }
                if(filetype) {
                    if(filetype == 'pdf'){
                        iconElement.removeClass("bi-file-earmark-pdf").addClass("bi-check2");
                    }
                    if(filetype == 'excel'){
                        iconElement.removeClass("bi-filetype-exe").addClass("bi-check2");
                    }
                }
                // Retrieve the course ID from PHP variable
                var course_id = "<?php echo base64_encode($courseDetails[0]['course'][0]['id']); ?>";
                $.ajax({
                    url: studentBaseUrl + "/student-getprogess/",
                    type: "post",
                    data: {
                        course_id: course_id,
                    },
                    dataType: "json",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function (response) {
                        console.log(response.data);
                        if(response.data != undefined){
                            response.data.forEach(item => {
                                // console.log(`Full Check: ${item.full_check}, Video ID: ${item.video_id}`);
                                var iconElement = document.getElementById("play-pause-icon-" + item.video_id);
                                if (iconElement) {
                                    if (item.full_check === 'Yes' && previousTabId == item.video_id) {
                                        // Remove existing classes and add the new one
                                        iconElement.classList.remove('bi-pause-fill', 'bi-play-fill');
                                        iconElement.classList.add('bi-check2');
                                    }
                                }
                            });
                        }
                        previousTabId = currentTabId;
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", status, error);
                    }
                });

            } else {
                previousTabId = currentTabId;
            }
            if(playerInstance){
                playerInstance.pause();
            }

            $.ajax({
                url: studentBaseUrl + "/quiz-view",
                type: "POST",
                data: { id: quiz_id,course_id: course_id },
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                success: function (response) {
                    $(".save_loader").addClass("d-none").removeClass("d-block");
                    $("#loader").fadeOut();
                    if (response.code === 200) {
                        $("#test-start").addClass('dstepper-block bs-stepper-block fade active');
                        var data = Object.keys(response.data[0]['quiz_question']);
                        var values = Object.values(response.data[0]['quiz_question']);
                        var num = 1;
                        var length = data.length;

                            data.forEach(function (key) {
                                var questionId = btoa(values[key]['id']);
                                var question = values[key]['question'];
                                var quiz_id = btoa(values[key]['quiz_id']);
                                var option1 = values[key]['option1'];
                                var option2 = values[key]['option2'];
                                var option3 = values[key]['option3'];
                                if(option3 == 'Unspecified'){
                                    option3 = '';
                                }
                                var option4 = values[key]['option4'];
                                if(option4 == 'Unspecified'){
                                    option4 = '';
                                }
                                var next = num +1;

                                var progress = (num/length) * 100;
                                var previous = '';
                                var nextBlock = '';
                                if (num > 1) {
                                            var previous =  `<div class="mt-0 d-flex justify-content-start">
                                                <button class="btn btn-secondary color-white" type="button" onclick="previousStep(`+num+`)"><i class="fe fe-arrow-left"></i>Previous
                                                    </button>
                                            </div>`;
                                            }
                                    if (length < next) {
                                        var nextBlock =  `<div class="mt-0 d-flex justify-content-end">
                                                <button class="btn btn-primary color-green quizFinalSubmit" data-quizid="` + quiz_id + `" data-courseid="` +course_id+ `"  data-progress_count_total="`+ progress_count_total +`"  type="button">Final Submit
                                                    <i class="fe fe-arrow-right"></i></button>
                                            </div>`;
                                            }else{
                                            var nextBlock =  `<div class="mt-0 d-flex justify-content-end">
                                                <button class="btn btn-primary color-green" type="button" onclick="nextStep(`+next+`)">Next
                                                    <i class="fe fe-arrow-right"></i></button>
                                            </div>`;

                                            }

                                var steperDiv = $(
                                `<div class="step newAddedStep" data-target="#test-l-`+num+`">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="test-l-`+num+`"
                                            id="courseFormtrigger`+num+`">
                                        </button>
                                    </div>`
                                );

                                var stepcontain = `<div id="test-l-`+num+`" role="tabpanel" class="bs-stepper-pane  newAddedPane"
                                            aria-labelledby="courseFormtrigger`+num+`">
                                            <div class="card mb-4 rounded-top-0">
                                                <!-- Card body -->
                                                <div class="card-body">
                                                    <!-- quiz -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <!-- quiz img -->
                                                            <a href="#"><img src="{{ asset('frontend/images/quiz-image.jpg') }}"
                                                                    alt="course" class="rounded img-4by3-lg" /></a>
                                                            <!-- quiz content -->
                                                            <div class="ms-3">
                                                                <h3 class="mb-0"><p class="text-inherit mcqheading mb-0">Multiple Choice Questions</p></h3>
                                                                <input value="`+questionId+`" type="text" name="question_id[]" hidden   />
                                                                <input value="`+quiz_id+`" type="text" name="quiz_id" hidden  />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <!-- text -->
                                                        <div class="d-flex justify-content-between">
                                                            <span>Exam Progress:</span>
                                                            <span>Question `+num+` out of `+length+`</span>
                                                        </div>
                                                        <!-- progress bar -->
                                                        <div class="mt-2">
                                                            <div class="progress" style="height: 6px">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                    style="width: `+progress+`%" aria-valuenow="15" aria-valuemin="0"
                                                                    aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- text -->
                                                    <div class="mt-5">
                                                        <span>Question `+num+`</span>
                                                        <h3 class="mcq_question_title mb-3 color-blue  mt-1">`+question+`</h3>
                                                        <!-- list group -->
                                                        <div class="list-group">
                                                            <div class="list-group-item list-group-item-action" aria-current="true">
                                                                <!-- form check -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="answer`+num+`[]" value="1" id="flexRadioDefault1`+num+`" />
                                                                    <label class="form-check-label stretched-link"
                                                                        for="flexRadioDefault1`+num+`">`+option1+`</label>
                                                                </div>
                                                            </div>
                                                            <!-- list group -->
                                                            <div class="list-group-item list-group-item-action">
                                                                <!-- form check -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="answer`+num+`[]" value="2" id="flexRadioDefault2`+num+`" />
                                                                    <label class="form-check-label stretched-link"
                                                                        for="flexRadioDefault2`+num+`">`+option2+`</label>
                                                                </div>
                                                            </div>`;
                                                            if (option3 !== '') {
                                                                stepcontain += `<!-- list group -->
                                                            <div class="list-group-item list-group-item-action">
                                                                <!-- form check -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="answer`+num+`[]" value="3" id="flexRadioDefault3`+num+`" />
                                                                    <label class="form-check-label stretched-link"
                                                                        for="flexRadioDefault3`+num+`">`+option3+`</label>
                                                                </div>
                                                            </div>`;
                                                            }
                                                            if (option4 !== '') {
                                                            stepcontain += `<!-- list group -->
                                                            <div class="list-group-item list-group-item-action">
                                                                <!-- form check -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="answer`+num+`[]" value="4" id="flexRadioDefault4`+num+`" />
                                                                    <label class="form-check-label stretched-link"
                                                                        for="flexRadioDefault4`+num+`">`+option4+`</label>
                                                                </div>
                                                            </div>`;
                                                            }
                                                    stepcontain += `
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Button -->
                                            <div class="button-wrapper ">
                                            ` + nextBlock + ` ` + previous + `
                                            </div>
                                        </div>`;
                                $("#quizHeader").append(steperDiv);
                                $("#quizForm").append(stepcontain);

                                num++;
                            });
                    }else if(response.code === 203){
                            $("#test-last").addClass('bs-stepper-block fade active');

                            $(".score").html("Your score is : "+response.score+'%');
                        }else{
                            swal({
                                title: response.title,
                                text: response.message,
                                icon: response.icon,
                            }).then(function () {
                                window.location.href =
                                    baseUrl + "/admin/admin";
                            });
                        }
                        $("#resource").hide();
                        // $(".quizActive").addClass("active show");
                        // $(".quizTab").collapse("hide");
                        $('.quizTab').addClass("active show");
                        $("#course-project").removeClass("active show");
                        $("#course-project").hide();
                        $(".quizPane").addClass("active");
                        $(".quizActive").show();
                        $(".quizPane").collapse("show");
                        $("#resource-excel").hide();
                },
            });
        }


       });



       let buttonToggle = () => {
            const button = document.getElementById("menu-button");
            const icon = button.querySelector("i.bi");
            const isOpen = button.classList.contains("is-opened");

            if (isOpen) {
                button.classList.remove("is-opened");
                icon.classList.remove("bi-x");
                icon.classList.add("bi-arrow-right");
            } else {
                button.classList.add("is-opened");
                icon.classList.remove("bi-arrow-right");
                icon.classList.add("bi-x");
            }
    }

    document.addEventListener("DOMContentLoaded", () => {
        const button = document.getElementById("menu-button");
        const icon = button.querySelector("i.bi");
        if (!button.classList.contains("is-opened")) {
            icon.classList.add("bi-x");
            icon.classList.remove("bi-arrow-right");
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const titleLinks = document.querySelectorAll('.tab-link');
        const h1Element = document.getElementById('selected-title');
        titleLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const title = this.querySelector(
                        '.preview-course-heading')
                    .textContent;
                h1Element.textContent = title;
            });
        });
        const title = this.querySelector('.preview-course-heading').textContent;
        h1Element.textContent = title;
    });
    document.addEventListener('DOMContentLoaded', function(){
        const tabs = document.querySelectorAll('.tab-link');
        tabs.forEach(tab => {
            tab.addEventListener('click', function(event) {
                tabs.forEach(t=>t.classList.remove('active'));
                event.currentTarget.classList.add('active');
            })
        })
    })


window.courseForm = new Stepper(document.querySelector('#courseFormtrigger0'), {
    linear: false,
    animation: true
});

function nextStep(id) {

    var remove = id-1;
if (id === 1) {
      $('#test-start').removeClass('fade');
      $('#test-start').removeClass('dstepper-block active');

}else{
      $('#test-l-'+remove).removeClass('fade');
    $('#test-l-'+remove).removeClass('active show');
}

    $('#test-l-'+id).addClass('active show');
}
function previousStep(id) {

    var remove = id-1;
    $('#test-l-'+id).removeClass('fade');
    $('#test-l-'+id).removeClass('active show');
    $('#test-l-'+remove).addClass('active show');
}

</script>
@endsection


