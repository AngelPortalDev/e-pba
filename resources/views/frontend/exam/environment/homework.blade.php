<style>
    /* .custom-file-input {
           display: none;
       } */
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
       .mainHomeworkSection{
           border: 1px solid #e2e8f0;
           border-radius: 0.5rem;
           padding: 2rem;
           background-color: #fff;
           box-shadow: 0 4px 8px rgba(0,0,0,0.1);
       }
       .homeworkexamstyle p{
            margin-bottom: 0px;
       }

       .section_name_style{
            font-size: 15px;
       }
       .downloadFilestyle{
        padding: 1rem 0rem;
       }
       .mockjobDesc{
        padding: 10px 2px !important; 
       }
</style>

<div class="header" >
   <nav class="navbar-default navbar navbar-expand-lg" style="background-color: #f1f5f9;box-shadow: none;">
       <a id="nav-toggle" href="#" class="color-blue fs-4 d-none">
           <button class="button is-text toggle-button" onclick="buttonToggleNew(this)">
               <div class="button-inner-wrapper">
                   <i class="bi bi-x toggle-icon" style="font-size: x-large"></i>
               </div>
           </button>
       </a>
       
       <a id="nav-toggle" href="#" class="color-blue fs-4">
           <button class="button is-text toggle-button" onclick="buttonToggleNew(this)">
               <div class="button-inner-wrapper">
                   <i class="bi bi-x toggle-icon" style="font-size: x-large"></i>
               </div>
           </button>
       </a>
       <div class="d-flex align-items-center justify-content-between ps-3">
           <div>
               <h3 class="mb-0 text-truncate-line-2 color-blue studentExamTitle"> {{isset($QuestionData[0]['homework_title']) ? html_entity_decode($QuestionData[0]['homework_title']) : '' }}</h3>
           </div>

       </div>

   </nav>
</div>

<!-- Page Header -->
{{-- @php print_r($QuestionData); @endphp --}}
{{-- @if (isset($QuestionData[0]['id']) && is_exist('exam_remark_master', ['user_id' => Auth::user()->id, 'course_id' => $QuestionData[0]['award_id'], 'student_course_master_id' => $student_course_master_id, 'exam_id' => $QuestionData[0]['id'], 'exam_type' => 10, 'attempt_remain' => 0, 'is_active' => 1]) > 0)
@include('frontend.exam.environment.submitted-successfully',['message' => isset($QuestionData[0]['homework_title']) ? html_entity_decode($QuestionData[0]['homework_title']) : ''])
@else
   @php
       $studentCourseMaster = DB::table('student_course_master')
           ->where('id', $student_course_master_id)
           // ->where('user_id', Auth::id())
           // ->where('course_id', $QuestionData[0]['award_id'])
           // ->latest()
           ->first();

   @endphp
   @if (isset($studentCourseMaster) && !empty($studentCourseMaster) && ($studentCourseMaster->exam_attempt_remain == '0' || $studentCourseMaster->exam_remark == '1'))
       @include('frontend.exam.environment.submitted-successfully',['message' => isset($QuestionData[0]['homework_title']) ? html_entity_decode($QuestionData[0]['homework_title']) : '']);
   @else --}}
       <!-- Container fluid -->
       <section class="container-fluid ps-5 pe-lg-3 pe-sm-1 pt-2">
               {{-- Assignment 1 --}}
               <div class="row justify-content-center">
                   <div class="col-md-12 mb-3">
                       
               @php
                   echo isset($QuestionData[0]['instructions']) ? html_entity_decode($QuestionData[0]['instructions']) : '';
                   @endphp
                       @if (isset($QuestionData[0]['instrcution_file_url']) && !empty($QuestionData[0]['instrcution_file_url']) && Storage::disk('local')->exists($QuestionData[0]['instrcution_file_url']))
                       <div class="mockjobDesc">
                           <a href="{{Storage::disk('local')->url($QuestionData[0]['instrcution_file_url'])}}" download="E-Ascencian Jd (Exam)"> Download Instruction File <i class="fe fe-download fs-5"></i></a>
                       </div>
                       @endif
                   </div>
               @php
               $i = 1;
               @endphp
               <div class="mainHomeworkSection">
                @if(isset($QuestionData[0]))
                @foreach ($QuestionData[0]['homework_question'] as $item)
                    @php $QuestionAnswer = DB::table('exam_homework_answers')->where('question_id',$item['id'])->where('user_id', Auth::user()->id)->first(); 
                        $SectionName = DB::table('course_section_masters')->where('id',$item['section_id'])->first();
                    @endphp 
                   <form id="homeworkExamFormData-{{$QuestionData[0]['award_id']}}-{{$item['id']}}-{{$index}}" class="homeworkExamFormData" enctype="multipart/form-data">
                       <input type="hidden" name="exam_id" id="exam_id" value="{{isset($QuestionData[0]['id']) ? base64_encode($QuestionData[0]['id']) : 0}}">
                       <input type="hidden" name="course_id" id="course_id" value="{{isset($QuestionData[0]['award_id']) ? base64_encode($QuestionData[0]['award_id']) : ''}}">
                       <input type="hidden" name="master_course_id" id="master_course_id" value="{{ isset($master_course_id) ? base64_encode($master_course_id) : '' }}">
                       <input type="hidden" name="student_course_master_id" id="student_course_master_id" value="{{ isset($student_course_master_id) ? base64_encode($student_course_master_id) : '' }}">
                       <input type="hidden" name="index" id="index" value="{{$index}}">
                       <input type="hidden" name="question_id" id="question_id" value="{{base64_encode($item['id'])}}">

                               <div class="col-md-12 mb-5">
                                   
                                   <div class="">
                                       <h5 class="color-blue mb-0 homeworkexamstyle">
                                       <span class="section_name_style">Section:  {!! isset($item['section_id']) ? ($SectionName->section_name) : '' !!}  </span>
                                        <br>
                                        Question {{$i}}: {!! isset($item['question']) ? html_entity_decode($item['question']) : '' !!}

                                        @if (isset($item['question_file_url']) && !empty($item['question_file_url']) && Storage::disk('local')->exists($item['question_file_url']))
                                        <div class="mockjobDesc">
                                            <a href="{{Storage::disk('local')->url($item['question_file_url'])}}" download="E-Ascencian Jd (Exam)"> Download Question <i class="fe fe-download fs-5"></i></a>
                                        </div>
                                        @endif
                                    </h5>
                                   </div>

                                   @php 
                                   $selectedMimes = $item['mimes'];
                                   $selectedMimesEx = explode(',',$item['mimes']);
                                   @endphp
                                   @if (!empty($selectedMimesEx) && is_array($selectedMimesEx))
                                   
                                 
                                        @if (in_array('write', $selectedMimesEx) )
                                        <div class="col-md-12 mt-2">
                                            <textarea class="form-control" id="answer_text" name="answer_text" placeholder="Write here..." required="" rows="10" value="">{{isset($QuestionAnswer->answer_text) ? $QuestionAnswer->answer_text : ''}}</textarea>
                                        </div>
                                        @endif
                                        
                                        @if (in_array('pdf', $selectedMimesEx))
                                        <br> 
                                        <br>
                                        <div class="col-md-6 mt-2 homework-interview-custom">
                                        <div class="mb-0 d-flex">
                                        <div class="input-group homework-interview-customInput" style="flex-wrap: inherit">
                                            <label class="custom-file-label" for="customFileInput{{$item['id']}}" data-filename="">Choose PDF file</label>
                                            <input type="file" class="custom-file-input-homework"  data-content="{{$item['id']}}" id="customFileInput{{$item['id']}}" name="docFile" accept=".pdf,.doc,.docx,.xls,.xlsx" style="display: none !important">
                                            {{-- <button type="button"  class="btn btn-primary homeworkContentUpload" data-id="{{$i}}">Upload</button> --}}
                                        </div>
                                        
                                        </div>
                                        </div>
                                        <small>File size should be less than 5 MB.</small>
                                        @if (isset($QuestionAnswer->answer_file_url) &&
                                            !empty($QuestionAnswer->answer_file_url) &&
                                            Storage::disk('local')->exists($QuestionAnswer->answer_file_url))     
                                            @php
                                                $fileName = basename($QuestionAnswer->answer_file_url);
                                                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                            @endphp                         
                                        <div class="d-flex justify-content-between text-primary fw-bold downloadFilestyle">  
                                            @if(in_array($extension, ['pdf']))
                                                <div>
                                                    <a href="{{ Storage::disk('local')->url($QuestionAnswer->answer_file_url) }}"
                                                        target="_blank" ><span
                                                            class=" btn btn-success">View File</span></a>
                                                </div>   
                                            @else     
                                                <div>
                                                    <a href="{{ Storage::disk('local')->url($QuestionAnswer->answer_file_url) }}"
                                                        target="_blank" ><span
                                                            class=" btn btn-success">Download File</span></a>
                                                </div>       
                                            @endif     
                                            <div>
                                                {{-- <a href="javascript:void(0)"
                                                    class="me-1 text-inherit deletePdfFile"
                                                    ><i
                                                        class="fe fe-trash-2 fs-5"></i></a> --}}
                                            </div>
                                        </div>
                                        @endif
                                        @endif
                                            
                                    @endif
                               </div>
                               
                               <div class="col-12 mb-6">
                                <button type="button" data-id="{{$item['id']}}" class="btn btn-primary submitHomeworkExam">Submit Now</button>
                               </div>
                      
                   </form>
                
                    @php
                        $i++;
                    @endphp
                @endforeach
                @endif
               </div>
            
       </section>
       
       {{-- @include('frontend.exam.environment.declaration-form', [
           'modalId' => 'instructionModal-' . $QuestionData[0]['award_id'] . '-' . $index,
           'exam_name' => isset($QuestionData[0]['homework_title']) 
               ? html_entity_decode($QuestionData[0]['homework_title']) 
               : 'homework',
           'submit_button_class' => 'submitHomeworkExam',
           'extraRequirement' => ' data-index="' . e($index) . '" data-course_id="' . e($QuestionData[0]['award_id']) . '" data-action="submit"'
       ]) --}}
   {{-- @endif --}}
{{-- @endif --}}
<script>
//    $(".custom-file-input-homework").on("change", function (event) {
//        var file = event.target.files[0];
//        var fileType = file.type;
//        var content = $(this).data("content");
//     //    if (fileType === "application/pdf") {
//            const label = document.querySelector(`label[for="${this.id}"]`);
//            if (label) {
//                label.setAttribute('data-filename', file.name);
//                label.classList.add('selected');
//            }
           
//     //   } else {
//     //        const label = document.querySelector(`label[for="${this.id}"]`);
//     //        if (label) {
//     //            label.setAttribute('data-filename', file.name);
//     //            label.classList.add('selected');
//     //        }
//     //    }
//    });
$(document).on('click', '.custom-file-label', function () {
    const inputId = $(this).attr('for');
    const input = $('#' + inputId);

    // Clear previous value so same file can be selected again
    input.val('');
    
    // Trigger file selection dialog
    // input.trigger('click');

    // Attach a one-time change event
    input.one('change', function () {
        const fileName = $(this).val().split('\\').pop();
        $("label[for='" + inputId + "']").text(fileName);
    });
});

    $(document).ready(function() {

        document.addEventListener('contextmenu', (event) => {
            event.preventDefault();
        });
        document.addEventListener('copy', function(e) {
            e.preventDefault();
        });
    });
</script>