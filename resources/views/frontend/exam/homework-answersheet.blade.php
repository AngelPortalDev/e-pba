@extends('frontend.master')
@section('content')
<style>
    /* #examMarksForm:first-child p{
        font-weight: bold;
        font-size: 1rem;
        color: #2b3990;
    } */

    .homeworkjobdesc{
        background: #f7f7f7;
        padding: 10px;
        border-radius: 8px;
        display: inline-block;
        font-weight: 600;
    }
    .homewokanswersheetstyle{
        /* white-space: pre-line; */
        /* margin-top: -80px;
        margin-bottom: -40px; */
    }
    .mock_interview_ementor_question p{
        margin-bottom: 5px;
    }
    .mock_interview_ementor_question>span{
        margin-bottom: 10px;
    }
</style>
<main>
    <section class="p-lg-5 py-7">
        <div class="container">


            @php
                    $exam = $examData[0];
                    $homeworkExam = $exam['homework_exam'][0];
                    $questions =$homeworkExam['homework_question'];
                    $studentData = $exam['exam_student'][0];
                    $courseData = $exam['exam_course'][0];
                    $totalMarks = 0;
                    $totalObtain = 0;
                    // foreach ($questions as $value) {
                    //     $totalMarks += isset($value['marks']) && !empty($value['marks']) ?  $value['marks'] : 0;
                    //     $totalObtain += isset($value['homework_anwers'][0]['marks_given']) && !empty($value['homework_anwers'][0]['marks_given']) ?  $value['homework_anwers'][0]['marks_given'] : 0;
                        
                    // }
            @endphp
            <!-- Content -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-3 mb-xl-0">
                    <!-- Card -->
                    <div class="card mb-4">
                        <!-- Card body -->
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="fw-bold mb-0 color-blue mentor-answersheet-header">{{isset($homeworkExam['homework_title'])  ? html_entity_decode($homeworkExam['homework_title']) : ''}} <span
                                        class="fs-6 fw-semibold">{{isset($courseData['course_title'])  ? $courseData['course_title'] : ''}}</span>
                                </h3>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">

                                    <div class="lh-1">
                                         <h4 class="mb-1 mentor-studentname mobileviewtext"> Student Name: <span class="color-blue">{{isset($studentData['name']) || isset($studentData['last_name'])   ? $studentData['name']." ". $studentData['last_name'] : ''}}</span>
                                        </h4>
                                    </div>
                                </div>
                                <div>
                                    {{-- <a href="javascript:history.back()" class="btn btn-outline-primary mobileViewButton">Back</a> --}}
                                    {{-- <a href="{{route('e-mentor-students-exam')}}" class="btn btn-outline-primary mobileViewButton">Back</a> --}}
                                    {{-- @php
                                        $routeName = auth()->user()->role == 'admin' ? 'admin-e-mentor-students-exam-details' : 'e-mentor-students-exam-details';
                                    @endphp --}}

                                    @php
                                        $routeName = in_array(auth()->user()->role, ['admin', 'superadmin']) 
                                            ? 'admin-e-mentor-students-exam-details' 
                                            : 'e-mentor-students-exam-details';
                                    @endphp
                                    
                                    <a href="{{ route($routeName, [
                                        'id' => base64_encode($studentData['id']),
                                        'course_id' => base64_encode($courseId),
                                        'student_course_master_id' => base64_encode($exam['student_course_master_id'])
                                    ]) }}?attempt=homework" class="btn btn-outline-primary mobileViewButton">Back</a>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        {{-- <li class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-calendar4 text-primary"
                                                        viewBox="0 0 16 16">
                                                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5
                                                            0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0
                                                            1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1
                                                            2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0
                                                            0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1
                                                            1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"></path>
                                                    </svg>
                                                    <div class="ms-2">
                                                        <h5 class="mb-0 text-body">Start Date</h5>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div>
                                                        <p class="text-dark mb-0 fw-semibold">01 May, 2024</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li> --}}
                                        <li class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-calendar4"
                                                        viewBox="0 0 16 16">
                                                        <path d="M3.5 0a.5.5 0 0 1
                                                                    .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0
                                                                    1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0
                                                                    1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1
                                                                    .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0
                                                                    0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1
                                                                    1 0 0 0 1-1V5z"></path>
                                                    </svg>
                                                    <div class="ms-2">
                                                        <h5 class="mb-0 text-body">Submitted On</h5>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div>
                                                          <p class="text-dark mb-0 fw-semibold">{{isset($exam['submitted_on'])  ?  \Carbon\Carbon::parse($exam['submitted_on'])->format('Y-m-d') : ''}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        {{-- <li class="list-group-item px-0 pb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        fill="currentColor" class="bi bi-clock text-primary" viewBox="0 0 16 16">
                                                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5
                                                            0 0 0 .252.434l3.5 2a.5.5 0 0 0
                                                            .496-.868L8 8.71V3.5z"></path>
                                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0
                                                                0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7
                                                                0 0 1 14 0z"></path>
                                                    </svg>
                                                    <div class="ms-2">
                                                        <h5 class="mb-0 text-body">Last Update</h5>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div>
                                                        <p class="mb-0 fw-semibold text-dark">{{isset($exam['last_update_at'])  ?  \Carbon\Carbon::parse($exam['last_update_at'])->format('Y-m-d') : ''}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li> --}}
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0 pb-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                        fill="currentColor" class="bi bi-clock text-primary" viewBox="0 0 16 16">
                                                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5
                                                            0 0 0 .252.434l3.5 2a.5.5 0 0 0
                                                            .496-.868L8 8.71V3.5z"></path>
                                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0
                                                                0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7
                                                                0 0 1 14 0z"></path>
                                                    </svg>
                                                    <div class="ms-2">
                                                        <h5 class="mb-0 text-body">Last Update</h5>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div>
                                                        <p class="mb-0 fw-semibold text-dark">{{isset($exam['last_update_at'])  ?  \Carbon\Carbon::parse($exam['last_update_at'])->format('Y-m-d') : ''}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>

                    </div>
                    <!-- Card -->
                    <div class="card rounded-3">
                        <!-- Card body -->
                        <section class="container px-4 pt-4">

                            <form id="examMarksForm">
                                <input type="hidden" name="actual_course_id" value="{{ base64_encode($courseId)}}">
                                <input type="hidden" name="course_id" value="{{ base64_encode($courseData['id'])}}">
                                <input type="hidden" name="exam_id"  value="{{ base64_encode($homeworkExam['id'])}}">
                                <input type="hidden" name="student_id"  value="{{ base64_encode($studentData['id'])}}">
                                <input type="hidden" name="exam_type"  value="{{ base64_encode(2)}}">
                                <input type="hidden" name="student_course_master_id"  value="{{ base64_encode($exam['student_course_master_id'])}}">
                                <input type="hidden" name="type"  value="Mock Interview">
                                
                            <div class="row justify-content-center">
                                <div class="col-md-12 mb-3">
                                    @php
                                     echo isset($homeworkExam['instructions']) ? html_entity_decode($homeworkExam['instructions']) : ""; 
                                     $num = 1;
                                    @endphp
                                    
                                </div>
                                <hr>
                                 @foreach ($questions as $key =>  $item)
                                 @php  $SectionName = DB::table('course_section_masters')->where('id',$item['section_id'])->first(); @endphp

                                <div class="col-md-12 mb-5">
                                    <div class="">
                                        <h5 class="color-blue mb-0 mock_interview_ementor_question">
                                            <span>Section: {!! isset($item['section_id']) ? ($SectionName->section_name) : '' !!} </span>
                                          
                                            <br>
                                            <span class="text-inherit">Question {{ $num }}  : </span>
                                            {{-- <p class="d-inline mb-0 text-inherit" style="display: inline; margin: 0; float: inline-end">[{{ isset($item['marks']) ? $item['marks'] : 0 }} Marks]</p> --}}
                                            <p>{!! isset($item['question']) ? $item['question'] : '' !!}</p>
                                            @if (isset($item['question_file_url']) && !empty($item['question_file_url']) && Storage::disk('local')->exists($item['question_file_url']))
                                            <div class="mockjobDesc">
                                                <a href="{{Storage::disk('local')->url($item['question_file_url'])}}" download="E-Ascencian Jd (Exam)"> Download Question.pdf <i class="fe fe-download fs-5"></i></a>
                                            </div>
                                            @endif
                                        </h5>
                                        <p class="fw-bold text-inherit">
                                            <!-- Optional additional content goes here -->
                                        </p>
                                    </div>
                                    
                                    <input type="hidden" name="question_id[]" id="question_id" value="{{ base64_encode($item['id']) }}">
                                    
                                    <h5 class="homework_interview_ementor_answer">Answer:</h5>
                                    @php
                                        $hasFile = isset($item['homework_answers'][0]['answer_file_url']) 
                                                && !empty($item['homework_answers'][0]['answer_file_url']) 
                                                && Storage::exists($item['homework_answers'][0]['answer_file_url']);

                                        $hasText = isset($item['homework_answers'][0]['answer_text']) 
                                                && !empty($item['homework_answers'][0]['answer_text']);

                                        $fileName = $hasFile ? basename($item['homework_answers'][0]['answer_file_url']) : '';
                                        $extension = $hasFile ? strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) : '';

                                        $isPdf = $hasFile && $extension === 'pdf';

                                        $columnClass = $isPdf ? 'col-md-sm-12 col-md-4 col-lg-3' : 'col-md-sm-12 col-md-12 col-lg-12';
                                    @endphp
                                 <div class="row mt-0">
                                    <div class="{{ $columnClass }} homewokanswersheetstyle">
                                        @if ($isPdf)
                                            <div class="d-flex justify-content-center align-items-center rounded border-white border rounded-3 bg-cover"
                                                style="background-image:url({{ asset('frontend/images/document-thumbnail-01.png') }});height:155px;">
                                                <a class="icon-shape rounded-circle btn-play icon-xl bg-blue" href="javascript:void(0)"
                                                onclick="openPdf('{{ $item['homework_answers'][0]['answer_file_url'] }}')">
                                                    <i class="bi color-green fs-4 fw-bold bi-eye"></i>
                                                </a>
                                            </div>
                                        @elseif ($hasFile)
                                            <a href="{{ Storage::disk('local')->url($item['homework_answers'][0]['answer_file_url']) }}">
                                                <span class="btn btn-warning">Download Answer File</span>
                                            </a>
                                        @endif

                                        @if ($hasText)
                                            <div class="mt-0" style="white-space: pre-line">
                                                {{ $item['homework_answers'][0]['answer_text'] }}
                                            </div>
                                        @endif

                                        @if (!$hasFile && !$hasText)
                                            <h6><span class="badge text-bg-warning">Not Attempt</span></h6>
                                        @endif
                                    </div>
                                </div>
                                </div>
                                @php
                                    $num++;
                                @endphp
                                 @endforeach
                                <hr>
                            </div>
                            {{-- @if(Auth::user()->role == 'instructor' || Auth::user()->role === 'sub-instructor')
                                <div class="mb-3 d-flex">
                                     <div class="me-2">
                                        <button type="button" class="btn btn-primary submitEmentor" data-type="1" data-examTypeId="2" style="white-space: nowrap">Final Submit</button>
                                    </div>
                                    <div class="">
                                        <button type="button" class="btn btn-secondary updateEmentor" data-type="0" data-examTypeId='2' style="white-space: nowrap">Save Draft</button>
                                    </div>
                                </div>
                            @endif --}}
                            </form>
                        </section>
                    </div>
                </div>


            </div>
        </div>
    </section>



<!-- Modal -->
<div class="modal fade" id="exampleModalOpenPdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Answer PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive position-relative w-100 d-block overflow-hidden p-0" style="height: 600px">
                    <div style="text-align:center">
                        <h4>PDF viewer testing</h4>
                        <iframe
                        class="position-absolute top-0 end-0 start-0 end-0 bottom-0 h-100 w-100"
                        width="560"
                        height="315"
                        src="{{ asset('frontend/images/pdf/Unit-4.pdf')}}"
                        title="E-Ascencia - Academy and LMS Template"
                        frameborder="0"></iframe>
                          </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</main>

<script> 

    function openPdf(url) {
        urlFile = window.location.origin + "/storage/"+url;
        $("#exampleModalOpenPdf").modal('show');
        $("#exampleModalOpenPdf").find('iframe').attr('src',urlFile);
    }
    function videoDisplay(videoId) {
        
        var newUrl = "https://iframe.mediadelivery.net/embed/253882/" +
            videoId +
            "?autoplay=false&loop=false&muted=false&preload=true&responsive=true";
        //        $('#resource').collapse('hide');
        // $('#orientation').collapse('hide');
        // $('#quiz').collapse('hide');
           $('#videoDisply').prop('src', newUrl);
           $('.quizTab').removeClass("active show"); 
        //     $('#resource').hide(); 
        //    $('#course-project').show();
           
        // $('#course-project').collapse('show');
      
    }

    $(document).ready(function(){
        $('.marks-input').on('input', function() {
            let totalObtain = 0;
            $('.marks-input').each(function() {
                let marks = parseFloat($(this).val()) || 0;
                totalObtain += marks;
            });
            $('#total-obtain-display').text(totalObtain + ' / {{ $totalMarks }}');
        });
    });

</script>
<script src="{{ asset('admin/js/examCommon.js')}}"></script>
@endsection
