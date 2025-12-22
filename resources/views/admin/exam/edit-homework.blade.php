<!-- Header import -->
@extends('admin.layouts.main')
@section('content')

<style>
    .assignmnetquestiontitle p{
        display: inline-block;
    }
</style>

<!-- Container fluid -->
<section class="p-4">
    <div class="container">
        <div id="courseForm" class="bs-stepper">
            <div class="row justify-content-center">
                <div class="col-md-10 col-12">
                    <form class="w-100 HomeworkFormData">
                        <!-- Card -->
                        <div class="card mb-4">
                            <!-- Card body -->
                            <div class="card-body">
                                <!-- Homework -->
                                <div class="row">
                                    <div class="d-lg-flex justify-content-between align-items-end col-12 mb-2">
                                        <div class="w-100 d-flex justify-content-between">
                                            <h3 class="mb-2"><a href="#" class="text-inherit editExamTitle">Edit Homework</a></h3>
                                                {{-- <button class="btn btn-outline-primary custum-btn-mobile" onclick="{{ route('assignment') }}">Back</button> --}}
                                                <a href="{{ route('admin.exam.homework') }}" class="btn btn-outline-primary custum-btn-mobile">Back</a>

                                        </div>
                                    </div>
                                    
                                    <!-- Homework title -->
                                    <div class="col-md-12 col-sm-12 col-lg-6 ">
                                        <div class="w-100">
                                            {{-- <form class="w-100 AssignmetFormData"> --}}
                                            <label class="form-label" for="homework_title">Homework Title <span class="text-danger">*</span></label>
                                            <input type="text" id="homework_title" name="homework_title" class="form-control" placeholder="Homework Title" required="" value="{{ isset($homeworkData[0]['homework_title']) ? html_entity_decode($homeworkData[0]['homework_title'], ENT_QUOTES, 'UTF-8') : '' }}" >
                                            <input type="text" id="homework_id" name="homework_id" value="{{isset($homeworkData[0]['id']) ? base64_encode($homeworkData[0]['id']) : 0}}" hidden>
                                            <small>Homework title must be between 3 to 255 characters.</small>
                                            <div class="invalid-feedback" id="homework_title_error">Please enter assignment title</div>
                                        </div>
                                    </div>

                                    

                                    {{-- <div class="col-md-12 col-sm-12 col-lg-6 ">
                                        <div class="w-100">
                                            <label class="form-label" for="section_name">Section Name <span class="text-danger">*</span></label>
                                            <input type="text"   class="form-control" disabled id="section_id" name="section_id" value="{{isset($homeworkData[0]['homework_section']['section_name']) ? ($homeworkData[0]['homework_section']['section_name']) : ''}}">
                                        </div>
                                    </div> --}}
                                    <!-- Percentage -->
                                    {{-- <div class="col-md-12 col-sm-12 col-lg-6 mt-2 mt-md-0">
                                        <div class="w-100 mt-2 mt-lg-0">
                                            <label class="form-label" for="assignment_percentage">Homework Total Percentage (%)<span class="text-danger">*</span></label>
                                            <input type="number" id="assignment_percentage" name="assignment_percentage" class="form-control" placeholder="Homework Percentage" required="" value="{{isset($homeworkData[0]['assignment_percentage']) ? $homeworkData[0]['assignment_percentage'] : ''}}">
                                            <div class="invalid-feedback" id="assignment_percentage_error">Please enter assignment total percentage</div>
                                        </div>
                                    </div> --}}
                                    
                                    <!-- Instructions -->

                                    <div class="col-md-12 mt-3">
                                        <label class="form-label">Instructions</label>
                                        <div id="homework_instruction"  placeholder="Programme Outcomes" class="form-control w-100" style="height: 200px">
                                        @php echo !empty($homeworkData[0]['instructions']) ? htmlspecialchars_decode($homeworkData[0]['instructions']) : ''  @endphp
                                    </div>
                                    <input type='text' name='homework_instruction' hidden>
                                    <div class="invalid-feedback" id="programme_outcomes_error">Please enter instructions</div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mt-3 text-start">
                                            <label class="form-label">Upload Document</label>
                                            <div class="custom-file-container mb-2">
                                                <label class="input-container">
                                                    <input type="file" class="form-control"
                                                        name="instruction_file" id="inputGroupFile04"
                                                        aria-describedby="inputGroupFileAddon04"
                                                        aria-label="Upload" accept=".pdf,.doc,.docx,.xls,.xlsx">                                                        
                                                    <span
                                                        class="input-visible">{{ isset($homeworkData[0]['instrcution_file_name']) ? $homeworkData[0]['instrcution_file_name'] : 'Choose file...' }}
                                                        <span class="browse-button">Upload</span></span>
                                                </label>
                                                <small class="mt-1">(File size should be less than 5
                                                    MB)</small>
                                                <div class="invalid-feedback" id="journal_file_error">Please
                                                    upload file.</div>
                                            </div>
                                            @if (isset($homeworkData[0]['instrcution_file_url']) &&
                                                    !empty($homeworkData[0]['instrcution_file_url']) &&
                                                    Storage::disk('local')->exists($homeworkData[0]['instrcution_file_url']))
                                                <div id="file-display"
                                                    class="file-display d-flex justify-content-between p-3 bg-light text-primary fw-bold">
                                                    <div>
                                                        <a href="{{ Storage::disk('local')->url($homeworkData[0]['instrcution_file_url']) }}"
                                                            target="_blank"><span
                                                                class="file-name">{{ $homeworkData[0]['instrcution_file_name'] }}</span></a>
                                                    </div>
                                                    <div>
                                                        <a href="javascript:void(0)"
                                                            class="me-1 text-inherit deletePdfFile"
                                                            data-file_id="{{ isset($homeworkData[0]['id']) ? base64_encode($homeworkData[0]['id']) : 0 }}"><i
                                                                class="fe fe-trash-2 fs-5"></i></a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Enable Draft Mode -->
                                  
                                </div>
                            </div>

                            {{-- question --}}
                            <div class="card-header ">
                                <div class="row justify-content-between">
                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                        <h4 class="mb-0">Questions</h4>
                                        <p class="mb-0">Homework questions categories</p>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-lg-6">
                                        <div class="mt-3 text-end">
                                            <a href="#" class="btn btn-primary addHomeworkQuestionOpen">Question <i class="fe fe-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="bg-light rounded p-2 mb-4">
                                    <div class="list-group list-group-flush border-top-0" id="QuestionList">
                                        <div id="courseOne">
                                            @if (isset($homeworkData[0]['homework_question']) && count($homeworkData[0]['homework_question']) > 0)
                                                @foreach ($homeworkData[0]['homework_question'] as $questions)
                                                    <div class="list-group-item rounded px-3 text-nowrap mb-1" id="development">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h5 class="mb-0 text-truncate">
                                                                <a href="#" class="text-inherit">
                                                                    <span class="align-middle fs-4 text-wrap-title questiontitle homeworkquestiontitle"> 
                                                                        <i class="bi bi-question-circle me-2"></i>  {!! isset($questions['question']) ? $questions['question'] : '' !!}
                                                                    </span>
                                                                </a>
                                                            </h5>                                                         
                                                            <div>
                                                                <a href="javascript:void(0);" class="me-2 text-inherit editViewHomeworkQuestion" aria-label="Edit"
                                                                    data-question_id="{{isset($questions['id']) ? base64_encode($questions['id']) : 0 }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                                                                    <i class="fe fe-edit edit-icon fs-5 " data-bs-toggle="modal" data-bs-target="#editQuestion"></i>
                                                                </a>
                                                                <a href="javascript:void(0)"
                                                                    class="me-1 text-inherit deleteHomeworkQuestion" data-question_id="{{isset($questions['id']) ? base64_encode($questions['id']) : 0 }}"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                                                                        <i class="fe fe-trash-2 fs-5"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary updateHomework">Save Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
</main>

<!-- Question Modal -->
<div class="modal fade" id="HomeworkQuestionModel" tabindex="-1" aria-labelledby="HomeworkQuestionModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- modal body -->
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="modal-title" id="HomeworkQuestionModelLabel">Add Question</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div>
                    <!-- form -->
                    <form class="needs-validation homeworkQuestions" novalidate>
                        <input type="text" id="homework_id" name="homework_id" value="{{isset($homeworkData[0]['id']) ? base64_encode($homeworkData[0]['id']) : 0}}" hidden>
                        <div class="mb-5">
                            {{-- <div class="mb-3">
                                <label class="form-label" for="question">Write Your Question<span class="text-danger">*</span></label>
                                <input type="text" id="question"  name="question" class="form-control"
                                    placeholder="Write Your Question Here">
                                <input type="text" id="question_id"  name="question_id" hidden>
                                <small>Question title must be between 3 to 255 characters.</small>
                                <div class="invalid-feedback" id="question_error">Please enter question.</div>

                            </div> --}}
                            @php $sectionMasters = DB::table('course_section_masters')
                            ->select('course_section_masters.id','course_section_masters.section_name')
                            ->leftJoin('course_managment_master','course_managment_master.section_id','course_section_masters.id')
                            ->where('course_managment_master.course_master_id', $homeworkData[0]['award_id'])
                            ->where('course_managment_master.is_deleted', 'No')
                            ->get();
                            @endphp
                            <div class="mb-3 col-12">
                                <label for="section_id" class="form-label select2">Select Section <span class="text-danger">*</span></label>
                                <select class="form-select" id="section_id" name="section_id" data-type="title">
                                    <option value="">Select Section</option>
                                    @foreach ($sectionMasters as $list)
                                    <option value="{{base64_encode($list->id)}}">{{$list->section_name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="homework_section_error">Please select section</div>
                                
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" for="question">Write Your Question</label>
                                <div id="question" placeholder="Instructions"
                                    class="form-control w-100 p-0" style="height: 150px">
                                </div>
                                <input type="hidden" id="questionData" name="questionData">
                                <input type="text" id="question_id" name="question_id" hidden>
                                <div class="invalid-feedback" id="question_error">Please enter question.</div>
                                <small>Question title must be between 3 to 1000 characters.</small>
                            </div>

                            <div class="col-md-12">
                                <div class="mt-3 text-start">
                                    <label class="form-label">Question File Upload</label>
                                    <div class="custom-file-container mb-2">
                                        <label class="input-container">
                                            <input type="file" class="form-control"
                                                name="question_file" id="inputGroupFile05" id="question_file"
                                                aria-describedby="inputGroupFileAddon05"
                                                aria-label="Upload" accept=".pdf,.doc,.docx,.xls,.xlsx">                                                        
                                            <span
                                                class="input-visible">{{ isset($homeworkData[0]['question_file_name']) ? $homeworkData[0]['question_file_name'] : 'Choose file...' }}
                                                <span class="browse-button">Upload</span></span>
                                        </label>
                                        <small class="mt-1">(File size should be less than 5
                                            MB)</small>
                                        <div class="invalid-feedback" id="question_file_error">Please
                                            upload file.</div>           
                                        <input type="text" id="question_file_name" hidden >  
                                    </div>
                                    @if (isset($homeworkData[0]['question_file_url']) &&
                                            !empty($homeworkData[0]['question_file_url']) &&
                                            Storage::disk('local')->exists($homeworkData[0]['question_file_url']))                                  
                                        <div id="file-display"
                                            class="file-display d-flex justify-content-between p-3 bg-light text-primary fw-bold">
                                            <div>
                                                <a href="{{ Storage::disk('local')->url($homeworkData[0]['question_file_url']) }}"
                                                    target="_blank"><span
                                                        class="file-name">{{ $homeworkData[0]['question_file_name'] }}</span></a>
                                            </div>                       
                                            <div>
                                                <a href="javascript:void(0)"
                                                    class="me-1 text-inherit deletePdfFile"
                                                    data-file_id="{{ isset($homeworkData[0]['id']) ? base64_encode($homeworkData[0]['id']) : 0 }}"><i
                                                        class="fe fe-trash-2 fs-5"></i></a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div id="questionFileDisplay" class="mb-3"></div>

                            <div class="mb-3">
                                <label for="mimes" class="form-label">Allowed File Types</label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="pdf" name="mimes[]" value="pdf">
                                    <label class="form-check-label" for="pdf">PDF/Excel/Word</label>
                                </div>
                                {{-- <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="mp4" name="mimes[]" value="mp4">
                                    <label class="form-check-label" for="mp4">MP4</label>
                                </div> --}}
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="write" name="mimes[]" value="write">
                                    <label class="form-check-label" for="write">Writing</label>
                                </div>
                                <div class="invalid-feedback" id="mimes_error">Please select at least one allowed file type..</div>
                            </div>
                            {{-- <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="assignment_mark">Enter Marks <span class="text-danger">*</span></label>
                                    <input type="number" id="assignment_mark" name="assignment_mark" class="form-control" placeholder="Enter Marks">
                                    <div class="invalid-feedback" id="assignment_mark_error">Please enter marks.</div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="assignment_answer_limit">Enter Answer Word Limit <span class="text-danger">*</span></label>
                                    <input type="number" id="assignment_answer_limit" name="assignment_answer_limit" class="form-control" placeholder="Enter Answer Word Limit">
                                    <div class="invalid-feedback" id="assignment_answer_limit_error">Please enter answer word limit.</div>
                                </div>
                            </div> --}}
                        </div>
                    </form>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary ms-2 addHomeworkQuestion" id="editButton">Add Question</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
     $('#HomeworkQuestionModel').on('shown.bs.modal', function () {
        $('#section_id').select2({
            dropdownParent: $('#HomeworkQuestionModel'),
            placeholder: "Select Section",
        });
    });
    </script>
@endsection