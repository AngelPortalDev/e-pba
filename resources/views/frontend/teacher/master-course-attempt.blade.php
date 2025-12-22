@php
$awardCourses = collect($awardCourses)->unique(function ($item) {
return $item['student_course_master_id'] . '-' . $item['course_id'];
});
$masterCourseId = isset($examData['courseId']) ? $examData['courseId'] : 0;
$courseCount = awardCoursesCountByMasterCourseId($masterCourseId,
$examData['awardCourses'][0]['student_course_master_id']);
$attempt_exam = $examData['attempt_exam'];


@endphp
<div class="accordion accordion-flush" id="accordionExample">
        @php
        $firstKey = 0;
        $secondKey = 0;
        @endphp
    @foreach($awardCourses as $exam)
        @php
        $totalPercentage = 0;
        $averagePercentagePerCourse = 0;
        @endphp

        @php
        $completedExamCount = DB::table('exam_remark_master')->where([
        'student_course_master_id' => $examData['awardCourses'][0]['student_course_master_id'],
        'user_id' => $examData['awardCourses'][0]['user_id'],
        'course_id'=> $exam['exam_course'][0]['id'],
        'is_cheking_completed' => '2'
        ])
        ->where('exam_type', '!=', "10")
        ->pluck('exam_type')
        ->toArray();

        $counts = array_count_values($completedExamCount);

        // Filter repeated IDs, but exclude 3
        $secondAttemptIds = array_keys(array_filter($counts, function ($count, $id) {
        return $count > 1 && $id != 3; // Exclude exam ID 3
        }, ARRAY_FILTER_USE_BOTH));

        $hasSecondAttempt = count($secondAttemptIds) > 0;

        $hasFirstAttempt = !empty($completedExamCount);
        @endphp
        @if(($hasFirstAttempt) && $attempt_exam == base64_encode("1"))
        <div class="border p-3 rounded-3 mb-2" id="heading{{ $firstKey }}">
            <h3 class="mb-0 fs-4">
                <a href="#" class="d-flex align-items-center text-inherit" data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $firstKey }}" aria-expanded="{{ $firstKey == 0 ? 'true' : 'false' }}"
                    aria-controls="collapse{{ $firstKey }}">
                    <span class="me-auto">{{ isset($exam['exam_course'][0]['course_title']) ?
                        $exam['exam_course'][0]['course_title'] : '' }}
                        {{-- <span class="badge bg-success-soft ms-2">Pass</span> --}}
                    </span>
                    <span class="collapse-toggle ms-4">
                        <i class="fe fe-chevron-down"></i>
                    </span>
                </a>
            </h3>
            @php $key = $firstKey;
            $firstKey++;
            @endphp
            <div id="collapse{{ $key }}" class="collapse {{ $key == 0 ? 'show' : '' }}"
            aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionExample" style="">
            <div class="pt-2">
                <ul class="ps-3">
                        @include('frontend.teacher.master-course-exam-content', $exam)
                </ul>
            </div>
            </div>
            
        </div>
        @endif
        @if(($hasSecondAttempt) && $attempt_exam == base64_encode("2"))
        <div class="border p-3 rounded-3 mb-2" id="heading{{ $secondKey  }}">
            <h3 class="mb-0 fs-4">
                <a href="#" class="d-flex align-items-center text-inherit" data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $secondKey  }}" aria-expanded="{{ $secondKey == 0 ? 'true' : 'false' }}"
                    aria-controls="collapse{{ $secondKey  }}">
                    <span class="me-auto">{{ isset($exam['exam_course'][0]['course_title']) ?
                        $exam['exam_course'][0]['course_title'] : '' }}
                    </span>
                    <span class="collapse-toggle ms-4">
                        <i class="fe fe-chevron-down"></i>
                    </span>
                </a>
            </h3>
            @php $key = $secondKey; $secondKey++; @endphp

            <div id="collapse{{ $key }}" class="collapse {{ $key == 0 ? 'show' : '' }}"
                aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionExample" style="">
                <div class="pt-2">
                    <ul class="ps-3">
                            @include('frontend.teacher.master-course-exam-content', $exam)
                    </ul>
                </div>
            </div>
                       
        </div>
        @endif

 

    @endforeach


    <script>
  
    </script>
</div>