@extends('frontend.master')
@section('content')

<style>
    .answer-content {
    margin-top: 10px;
    background-color: #f9f9f9; 
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.answer-text {
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 0px;
    white-space: pre-line;
    margin-top: -20px;
}
#turnitin_marks{
    padding: 11px 22px;
}

.not-attempted {
    color: #777; 
    font-style: italic;
}
.custom-file-label {
    display: inline-block;
    cursor: pointer;
    padding: 0.375rem 0.75rem;
    margin-right: 0.5rem;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    color: #495057;
    background-color: #fff;
    white-space: nowrap;
    width: 100%;
    }
    .custom-file-label::after {
    content: 'Choose file';
    display: inline-block;
    padding: 0.375rem 0.75rem;
    margin-left: 0.5rem;
    border-left: 1px solid #ced4da;
    }
    .custom-file-label.selected::after {
    content: attr(data-filename);
    }
</style>
<main>
    <section class="p-lg-5 py-7">
        <div class="container">


            @php
                    $exam = $examData[0];
                    $assginExam = $exam['assginment_exam'][0];
                    $draftExam = $exam['draft_exam'];
                    $turnitinExam = $exam['turnitin_exam'];
                    if ($draftExam) {
                        if (
                            $exam['course_id'] != $draftExam['course_id'] || 
                            $exam['exam_type'] != $draftExam['exam_type'] || 
                            $exam['exam_id'] != $draftExam['exam_id']
                        ) {
                            $draftExam = null; // Set $draftExam to null if any condition fails.
                        }
                    }
                    if ($turnitinExam) {
                        if (
                            $exam['course_id'] != $turnitinExam['course_id'] || 
                            $exam['exam_type'] != $turnitinExam['exam_type'] || 
                            $exam['exam_id'] != $turnitinExam['exam_id']
                        ) {
                            $turnitinExam = null; // Set $draftExam to null if any condition fails.
                        }
                    }
                    $assginQuestion =$assginExam['assig_question'];
                    $studentData = $exam['exam_student'][0];
                    $courseData = $exam['exam_course'][0];
                    $totalMarks = 0;
                    $totalObtain = 0;
                    $PdfAssigmentName = '';
                    $PdfAssigmentUrl = '';
                    $PdfTurnitinUrl = '';
                    $PdfTurnitinName = '';
                    foreach ($assginQuestion as $key => $value) {
                          $totalMarks += isset($value['assignment_mark']) && !empty($value['assignment_mark']) ?  $value['assignment_mark'] : 0;
                            $totalObtain += isset($value['assig_anwers'][0]['marks_given']) && !empty($value['assig_anwers'][0]['marks_given']) ?  $value['assig_anwers'][0]['marks_given'] : 0;
                        
                        
                    }

                    if($turnitinExam){
                        $PdfAssigmentName = isset($turnitinExam['answer_file_name']) && !empty($turnitinExam['answer_file_name']) ?  $turnitinExam['answer_file_name'] : '';
                        $PdfAssigmentUrl  = isset($turnitinExam['answer_file_url']) && !empty($turnitinExam['answer_file_url']) ?  $turnitinExam['answer_file_url'] : '';
                        $PdfTurnitinUrl  = isset($turnitinExam['turnitin_file_url']) && !empty($turnitinExam['turnitin_file_url']) ?  $turnitinExam['turnitin_file_url'] : '';
                        $PdfTurnitinName  = isset($turnitinExam['turnitin_file_name']) && !empty($turnitinExam['turnitin_file_name']) ?  $turnitinExam['turnitin_file_name'] : '';
                        $PdfTurnitinMarks  = isset($turnitinExam['turnitin_marks']) && !empty($turnitinExam['turnitin_marks']) ?  $turnitinExam['turnitin_marks'] : '';


                    }
            @endphp
            <!-- Content -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-3 mb-xl-0">
                    <!-- Card -->
                    <div class="card mb-4">
                        <!-- Card body -->
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="fw-bold mb-0 color-blue ">{{isset($assginExam['assignment_tittle'])  ? html_entity_decode($assginExam['assignment_tittle']) : ''}} ({{isset($assginExam['assignment_percentage'])  ? $assginExam['assignment_percentage'] : 0}}%) <span
                                        class="fs-6 fw-semibold">{{isset($courseData['course_title'])  ? $courseData['course_title'] : ''}}</span>
                                </h3>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="lh-1">
                                        <h4 class="mb-1"> Student Name: <span class="color-blue">{{isset($studentData['name']) || isset($studentData['last_name'])   ? $studentData['name']." ". $studentData['last_name'] : ''}}</span>
                                        </h4>
                                    </div>
                                </div>
                                <div>
                                    {{-- <a href="javascript:history.back()" class="btn btn-outline-primary mobileViewButton">Back</a> --}}
                                    {{-- <a href="{{route('e-mentor-students-exam')}}" class="btn btn-outline-primary mobileViewButton">Back</a> --}}


                                        @if ($turnitinExam != null)

                                            @if(Auth::user()->role == 'turnitin-instructor')

                                                <?php  $path = in_array(auth()->user()->role, ['admin','superadmin'])
                                                ? 'admin/e-mentors-edit/' . base64_encode($turnitinExam['ementor_id'])
                                                : 'ementor/e-mentor-students-exam'; 
                                                ?>

                                                <a href="{{ url($path) }}" class="btn btn-outline-primary mobileViewButton">
                                                    Back
                                                </a>
                                            @else
                                                <?php $routeName = in_array(auth()->user()->role, ['admin', 'superadmin']) 
                                                ? 'admin-e-mentor-students-exam-details' 
                                                : 'e-mentor-students-exam-details'; ?>

                                                <a href="{{ route($routeName, [
                                                    'id' => base64_encode($studentData['id']),
                                                    'course_id' => base64_encode($courseId),
                                                    'student_course_master_id' => base64_encode($exam['student_course_master_id'])
                                                ]) }}" class="btn btn-outline-primary mobileViewButton">Back</a>
                                                
                                            @endif
                                        @else
                                            <?php $routeName = in_array(auth()->user()->role, ['admin', 'superadmin']) 
                                                ? 'admin-e-mentor-students-exam-details' 
                                                : 'e-mentor-students-exam-details'; ?>

                                            <a href="{{ route($routeName, [
                                                'id' => base64_encode($studentData['id']),
                                                'course_id' => base64_encode($courseId),
                                                'student_course_master_id' => base64_encode($exam['student_course_master_id'])
                                            ]) }}" class="btn btn-outline-primary mobileViewButton">Back</a>
                                        @endif
                                        
                                    
                                    
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
                                                        <p class="text-dark mb-0 fw-semibold"></p>
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
                                                        <p class="text-dark mb-0 fw-semibold">{{isset($exam['submitted_on'])  ? \Carbon\Carbon::parse($exam['submitted_on'])->format('Y-m-d') : ''}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
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
                                                        <p class="mb-0 fw-semibold text-dark">{{isset($exam['last_update_at'])  ? \Carbon\Carbon::parse($exam['last_update_at'])->format('Y-m-d') : ''}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">

                                        <li class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    fill="currentColor" class="bi bi-award text-primary"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702z" />
                                                    <path
                                                        d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1z" />
                                                </svg>
                                                    <div class="ms-2">
                                                        <h5 class="mb-0 text-body"> Marks Obtained</h5>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div>
                                                        <p id="total-obtain-display" class="text-success mb-0 fw-semibold">{{$totalObtain}}/ {{$totalMarks}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>

                    </div>
                    @if(Auth::user()->role == 'turnitin-instructor')
                    @if($turnitinExam != null)
                   
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-3 mb-xl-0">
                            <!-- Card -->
                            <div class="card mb-4">
                                <!-- Card body -->
                                @if($PdfTurnitinUrl == '')
                                <div class="card-body py-3">
                                    <a href="{{ Storage::disk('local')->url($PdfAssigmentUrl) }}"
                                            download="{{ isset($PdfAssigmentName) ? $PdfAssigmentName : ' ' }}"><button
                                                class="btn btn-primary ms-0">Download Answer Pdf <i class="bi bi-download"></i></button></a>   
                                    
                                    <form id="examTurnitinForm">
                                        <input type="hidden" name="actual_course_id" value="{{ base64_encode($courseId)}}">
                                        <input type="hidden" name="course_id" value="{{ base64_encode($courseData['id'])}}">
                                        <input type="hidden" name="exam_id" class="exam_id" value="{{ base64_encode($assginExam['id'])}}">
                                        <input type="hidden" name="student_id" class="student_id"   value="{{ base64_encode($studentData['id'])}}">
                                        <input type="hidden" name="exam_type" class="exam_type"  value="{{ base64_encode(1)}}">
                                        <input type="hidden" name="student_course_master_id"  class="student_course_master_id"  value="{{ base64_encode($exam['student_course_master_id'])}}">
                                        <br>


                                    <div class="row">
                                        <!-- PDF Upload -->
                                        <div class="col-md-6">
                                            <label for="turnitin_file" class="pb-1"><strong>Upload Similarity Report</strong></label>
                                            <label class="custom-file-label" for="customFile" id="turnitinLabel">Choose PDF file</label>
                                            <input type="file" class="custom-file-turnitin d-none" name="turnitin_file" id="turnitin_file" accept="application/pdf">
                                            <div class="invalid-feedback" id="turnitin_file_error">Please select similarity report file.</div>
                                        </div>
                                
                                        <!-- Marks -->
                                        <div class="col-md-6 mb-3">
                                            <label for="turnitin_marks" class="pb-1 mt-2 mt-md-0"><strong>Similarity(%)</strong></label>
                                            <input type="text" name="turnitin_marks" id="turnitin_marks" class="form-control" placeholder="Similarity Percentage" required>
                                            <div class="invalid-feedback" id="turnitin_marks_error">Please enter similarity percentage.</div>
                                        </div>
                                    </div>
                                
                                    <!-- Submit Button -->
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-secondary turnitinSubmit mt-1" style="white-space: nowrap">Save</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                @else

                                <div class="card-body py-3">
                                <a href="{{ Storage::disk('local')->url($PdfTurnitinUrl) }}"
                                        download="{{ isset($PdfTurnitinName) ? $PdfTurnitinName : ' ' }}"><button
                                            class="btn btn-primary ms-0 ">Similarity Report PDF<i class="bi bi-download ms-2"></i></button></a>   
                                &nbsp;&nbsp;&nbsp;
                                <label class="mt-2 mt-md-0"><b>Similarity Percentage </b></label>: <?=  isset($PdfTurnitinMarks) ? $PdfTurnitinMarks : ' ' ?>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    

                    @endif
                    @else

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-3 mb-xl-0">
                            <!-- Card -->
                            <div class="card mb-4">
                                <!-- Card body -->
                                @if($PdfTurnitinUrl != '')

                                <div class="card-body py-3">
                                    <a href="{{ Storage::disk('local')->url($PdfTurnitinUrl) }}"
                                            download="{{ isset($PdfTurnitinName) ? $PdfTurnitinName : ' ' }}"><button
                                                class="btn btn-primary ms-2">Similarity Report PDF <i class="bi bi-download me-2"></i></button></a>   
                                    
                                    &nbsp;&nbsp;&nbsp;
                                    <label><b>Similarity Percentage <b></label>: <?=  isset($PdfTurnitinMarks) ? $PdfTurnitinMarks : ' ' ?>
                                </div>
                                @endif
                                
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(in_array(auth()->user()->role, ['admin', 'superadmin']) )
                    @if($turnitinExam != null)
                   
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-12 mb-3 mb-xl-0">
                            <!-- Card -->
                            <div class="card mb-4">
                                <!-- Card body -->
                                @if($PdfTurnitinUrl == '')
                                    <div class="card-body py-3">
                                        <a href="{{ Storage::disk('local')->url($PdfAssigmentUrl) }}"
                                                download="{{ isset($PdfAssigmentName) ? $PdfAssigmentName : ' ' }}"><button
                                                    class="btn btn-primary ms-0">Download Answer Pdf <i class="bi bi-download"></i></button></a>   
                                        
                                    
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    <!-- Card -->
                    <div class="card rounded-3">
                        <!-- Card body -->
                        <section class="container px-4 pt-4">
                             <form id="examMarksForm">
                                <input type="hidden" name="actual_course_id" value="{{ base64_encode($courseId)}}">
                                <input type="hidden" name="course_id" value="{{ base64_encode($courseData['id'])}}">
                                <input type="hidden" name="exam_id"  value="{{ base64_encode($assginExam['id'])}}">
                                <input type="hidden" name="student_id"  value="{{ base64_encode($studentData['id'])}}">
                                <input type="hidden" name="exam_type"  value="{{ base64_encode(1)}}">
                                <input type="hidden" name="student_course_master_id"  value="{{ base64_encode($exam['student_course_master_id'])}}">

                            <div class="row justify-content-center">
                                <div class="col-md-12 title">
                                @php
                                     echo isset($assginExam['instructions']) ? html_entity_decode($assginExam['instructions']) : ""; 
                                     $num = 1;
                                @endphp
                                </div>
                                <hr>
                                @foreach ($assginQuestion as $item)
                                    <div class="col-md-12 mb-3">
                                        <h5 class="color-blue mb-0 assignmentementorquestion">
                                            Question {{$num}}
                                            <p class="d-inline mb-0 assignmentementorquestion" style="display: inline; margin: 0; float:inline-end">[{{ isset($item['assignment_mark']) ? $item['assignment_mark'] : 0}} Marks]</p>
                                            {!! isset($item['question']) ? $item['question'] : '' !!}
                                        </h5>
                                        <input type="hidden" name="question_id[]" id="question_id" value="{{ base64_encode($item['id'])}}">
                                        <h5 class="assignment_ementor_answer mb-0">Answer:</h5>
                                        {{-- <p> {{ isset($item['assig_anwers'][0]) && !empty($item['assig_anwers'][0]['answer']) ? $item['assig_anwers'][0]['answer'] : '--Not Attempt--'}} </p> --}}

                                        <div class="answer-content">
                                            <p class="answer-text" style="white-space: pre-line;">
                                                @if(isset($item['assig_anwers'][0]) && !empty($item['assig_anwers'][0]['answer']))
                                                    {{ $item['assig_anwers'][0]['answer'] }}
                                                @else
                                                    <span class="not-attempted">--Not Attempted--</span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="row">
                                            <div class="color-blue d-flex align-items-end">
                                                <div class="">
                                                    @if($draftExam != null)
                                                        <textarea name="suggestion" id="suggestion" cols="45" rows="5" class="form-control mb-2" placeholder="Please give your suggestions...">{{isset($draftExam['suggestion']) != '' ? $draftExam['suggestion'] : ''}}</textarea>
                                                    @endif
                                                    <input type="number" name="marks[]" class="form-control marks-input" placeholder="Give Marks" max="{{ isset($item['assignment_mark']) ? $item['assignment_mark'] : 0}}" value="{{ isset($item['assig_anwers'][0]['marks_given']) ? $item['assig_anwers'][0]['marks_given'] : 0}}" required {{Auth::user()->role == "instructor" || Auth::user()->role === 'sub-instructor' ? '' : 'disabled' }} style="width:8rem" >
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                <hr>
                                @php
                                    $num++;
                                @endphp
                                @endforeach
                            </div>
                            @if(Auth::user()->role == 'instructor' || Auth::user()->role === 'sub-instructor')
                                <div class="mb-3 d-flex">
                                    
                                    @if($turnitinExam != null && $PdfTurnitinUrl != '')
                                        <div class="me-2">
                                            <button type="button" class="btn btn-primary submitEmentor" data-type="1" style="white-space: nowrap">Final Submit</button>
                                        </div>
                                    @elseif($turnitinExam == null)
                                        <div class="me-2">
                                            <button type="button" class="btn btn-primary submitEmentor" data-type="1" style="white-space: nowrap">Final Submit</button>
                                        </div>
                                    @endif
                                    <div class="">
                                        <button type="button" class="btn btn-secondary updateEmentor" data-type="0" style="white-space: nowrap">Save Draft</button>
                                    </div>
                                </div>
                            @endif
                            </form>
                        </section>
                    </div>
                </div>


            </div>
        </div>
    </section>
</main>
<script src="{{ asset('admin/js/examCommon.js')}}"></script>
<script>
    
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
    $('#turnitinLabel').on('click', function () {
        $('#turnitin_file').trigger('click'); // open file selector when label is clicked
    });

    $('#turnitin_file').on('change', function (event) {
        const file = event.target.files[0];
        if (!file) return;

        const fileName = file.name;
        $('#turnitinLabel').text(fileName);
    });
    </script>
@endsection