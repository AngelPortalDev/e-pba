
<div class="accordion accordion-flush" id="accordionExample">
    <div class="border p-3 rounded-3 mb-2" id="headingOne1">
        <h3 class="mb-0 fs-4">
            <a href="#"
                class="d-flex align-items-center text-inherit active"
                data-bs-toggle="collapse"
                data-bs-target="#collapseOne1" aria-expanded="true"
                aria-controls="collapseOne1">
                <span
                    class="me-auto">{{ isset($courseData['course_title']) ? $courseData['course_title'] : '' }}
                    {{-- <span class="badge bg-success-soft ms-2">Pass</span> --}}
                </span>
                <span class="collapse-toggle ms-4">
                    <i class="fe fe-chevron-down"></i>
                </span>
            </a>
        </h3>

        <div id="collapseOne1" class="collapse show"
            aria-labelledby="headingOne1"
            data-bs-parent="#accordionExample" style="">
            <div class="pt-2">
                <ul class="ps-3">
                    @foreach ($ementorStudentData['studentData'] as $key =>  $exam)
                    {{-- @php echo "<pre>"; print_r($exam['exam_course']);  --}}
                    {{-- @endphp --}}
                    {{-- @if($shouldShow) --}}
                        @php
                            $examtype = $exam['exam_type'];
                                                        
                            // assignment
                            $exam_assign = isset(
                                $exam['exam_course'][0][
                                    'assginment_status'
                                ][$key]['assginment_exam'][0],
                            )
                                ? $exam['exam_course'][0][
                                    'assginment_status'
                                ][0]['assginment_exam'][0]
                                : [];
                            // mock
                            $exam_mock = isset(
                                $exam['exam_course'][0][
                                    'mock_exam_status'
                                ][0]['mock_exam'][0],
                            )
                                ? $exam['exam_course'][0][
                                    'mock_exam_status'
                                ][0]['mock_exam'][0]
                                : [];
                            // vlog
                            $exam_vlog = isset(
                                $exam['exam_course'][0][
                                    'vlog_status'
                                ][0]['vlog_exam'][0],
                            )
                                ? $exam['exam_course'][0][
                                    'vlog_status'
                                ][0]['vlog_exam'][0]
                                : [];
                            // peer_review
                            $exam_peer_review = isset(
                                $exam['exam_course'][0][
                                    'peer_review_status'
                                ][0]['peer_review_exam'][0],
                            )
                                ? $exam['exam_course'][0][
                                    'peer_review_status'
                                ][0]['peer_review_exam'][0]
                                : [];
                            // Forum leadership
                            $exam_discord = isset(
                                $exam['exam_course'][0][
                                    'discord_status'
                                ][0]['discord_exam'][0],
                            )
                                ? $exam['exam_course'][0][
                                    'discord_status'
                                ][0]['discord_exam'][0]
                                : [];
                            // reflective journal
                            $exam_reflective_journal = isset(
                                $exam['exam_course'][0][
                                    'reflective_journal_status'
                                ][0]['reflective_journal_exam'][0],
                            )
                                ? $exam['exam_course'][0][
                                    'reflective_journal_status'
                                ][0]['reflective_journal_exam'][0]
                                : [];
                            // multiple choice
                            $exam_mcq = isset(
                                $exam['exam_course'][0][
                                    'mcq_status'
                                ][0]['mcq_exam'][0],
                            )
                                ? $exam['exam_course'][0][
                                    'mcq_status'
                                ][0]['mcq_exam'][0]
                                : [];
                            $total_mark_obtain += isset(
                                $exam['final_score_obtain'],
                            )
                                ? $exam['final_score_obtain']
                                : 0;
                            // survey
                            $exam_survey = isset(
                                $exam['exam_course'][0][
                                    'survey_status'
                                ][0]['survey_exam'][0],
                            )
                                ? $exam['exam_course'][0][
                                    'survey_status'
                                ][0]['survey_exam'][0]
                                : [];
                            // artificial intelligence
                            $exam_artificial_intelligence = isset(
                                $exam['exam_course'][0][
                                    'artificial_intelligence_status'
                                ][0]['artificial_intelligence_exam'][0],
                            )
                                ? $exam['exam_course'][0][
                                    'artificial_intelligence_status'
                                ][0]['artificial_intelligence_exam'][0]
                                : [];

                                $orderedExams = [
                                    'Assignment'               => $exam_assign,
                                    'Mock Exam'                => $exam_mock,
                                    'Vlog Exam'                => $exam_vlog,
                                    'Peer Review Exam'         => $exam_peer_review,
                                    'Discord / Forum Leadership'=> $exam_discord,
                                    'Reflective Journal Exam'  => $exam_reflective_journal,
                                    'MCQ Exam'                 => $exam_mcq,
                                    'Survey Exam'              => $exam_survey,
                                    'AI Exam'                  => $exam_artificial_intelligence,
                                ];

                                // 3) Keep only the non‐empty entries, preserving order
                                $filtered = array_filter($orderedExams, fn($data) => !empty($data));
                        @endphp
                        @foreach($filtered as $label => $data)
                        {{-- assignment --}}
                        @if($label === 'Assignment' && !empty($data))
                            @php
                                $assignmentExamCount = count($exam['exam_course'][0]['assginment_status']);
                                $exam_assign = isset(
                                    $exam['exam_course'][0][
                                        'assginment_status'
                                    ][$assignmentExamCount > 1 ? $key : 0]['assginment_exam'][0],
                                )
                                    ? $exam['exam_course'][0][
                                        'assginment_status'
                                    ][$assignmentExamCount > 1 ? $key : 0]['assginment_exam'][0]
                                    : [];

                                    $firstAttemptAssign = null;
                                    $secondAttemptAssign = null;
                                    
                                    foreach($exam['exam_course'][0]['assginment_status'] ?? [] as $key => $status) {
                                        
                                        if ($firstAttemptAssign === null) {
                                            $firstAttemptAssign = $exam['exam_course'][0]['assginment_status'][0];
                                        } elseif ($secondAttemptAssign === null) {
                                            $secondAttemptAssign = $exam['exam_course'][0]['assginment_status'][1];
                                            break; 
                                        }
                                    }
                            @endphp
                            @foreach($exam['exam_course'][0]['assginment_status'] as $key => $firstAttempt)
                             @if($firstAttemptAssign && $attempt_exam ==  base64_encode("1") && $key == 0)
                                <li class="mb-2">

                                        @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptAssign['id']), 'exam_type' => base64_encode(1), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold assignmentTitleSection ">
                                                {{ isset($exam_assign['assignment_tittle']) ? html_entity_decode($exam_assign['assignment_tittle']) : '' }} 
                                                ({{ isset($exam_assign['assignment_percentage']) ? $exam_assign['assignment_percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @else
                                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptAssign['id']), 'exam_type' => base64_encode(1), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold assignmentTitleSection ">
                                                {{ isset($exam_assign['assignment_tittle']) ? html_entity_decode($exam_assign['assignment_tittle']) : '' }} 
                                                ({{ isset($exam_assign['assignment_percentage']) ? $exam_assign['assignment_percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @endif
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($firstAttemptAssign['final_obtain_percentage']) ? $firstAttemptAssign['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                    <?php
                                        $totalPercentage += $firstAttemptAssign['final_obtain_percentage'];
                                    ?>
                                </li>
                             @elseif($secondAttemptAssign && $attempt_exam ==  base64_encode("2") && $key == 1)
                                <li class="mb-2">

                                    @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                        <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptAssign['id']), 'exam_type' => base64_encode(1), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold assignmentTitleSection">
                                            {{ isset($exam_assign['assignment_tittle']) ? html_entity_decode($exam_assign['assignment_tittle']) : '' }} 
                                            ({{ isset($exam_assign['assignment_percentage']) ? $exam_assign['assignment_percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @else
                                        <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($exam['id']), 'exam_type' => base64_encode(1), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold assignmentTitleSection">
                                            {{ isset($exam_assign['assignment_tittle']) ? html_entity_decode($exam_assign['assignment_tittle']) : '' }} 
                                            ({{ isset($exam_assign['assignment_percentage']) ? $exam_assign['assignment_percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @endif
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($secondAttemptAssign['final_obtain_percentage']) ? $secondAttemptAssign['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                    <?php
                                        $totalPercentage += $secondAttemptAssign['final_obtain_percentage'];
                                    ?>
                                </li>
                             @endif
                            @endforeach
                        
                        @endif
                        {{-- mock --}}
                        @if($label === 'Mock Exam' && !empty($data))
                            @php
                                // echo "sadsd";
                                $exam_mock = isset(
                                    $exam['exam_course'][0][
                                        'mock_exam_status'
                                    ][0]['mock_exam'][0],
                                )
                                    ? $exam['exam_course'][0][
                                        'mock_exam_status'
                                    ][0]['mock_exam'][0]
                                    : [];
                              
                                $firstAttemptMock = null;
                                $secondAttemptMock = null;
                                foreach($exam['exam_course'][0]['mock_exam_status'] ?? [] as $key => $status) {
                                    
                                    if ($firstAttemptMock === null) {
                                        $firstAttemptMock = $exam['exam_course'][0]['mock_exam_status'][0];
                                    } elseif ($secondAttemptMock === null) {
                                        $secondAttemptMock = $exam['exam_course'][0]['mock_exam_status'][1];
                                        break; 
                                    }
                                }

                            @endphp
                            @foreach($exam['exam_course'][0]['mock_exam_status'] as $key => $firstAttemptMock)
                            @if($firstAttemptMock && $attempt_exam ==  base64_encode("1") && $key == 0)

                                <li class="mb-2">
                                    @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                    <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptMock['id']), 'exam_type' => base64_encode(2), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold mockTitleSection">{{ isset($exam_mock['title']) ? html_entity_decode($exam_mock['title']) : '' }}
                                            ({{ isset($exam_mock['percentage']) ? $exam_mock['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @else
                                        <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptMock['id']), 'exam_type' => base64_encode(2), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold mockTitleSection">{{ isset($exam_mock['title']) ? html_entity_decode($exam_mock['title']) : '' }}
                                            ({{ isset($exam_mock['percentage']) ? $exam_mock['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @endif
                                
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($firstAttemptMock['final_obtain_percentage']) ? $firstAttemptMock['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                                    $totalPercentage += $firstAttemptMock['final_obtain_percentage'];
                                ?>
                            @elseif($secondAttemptMock && $attempt_exam == base64_encode("2") && $key == 1)

                                <li class="mb-2">
                                    @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                    <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptMock['id']), 'exam_type' => base64_encode(2), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold mockTitleSection">{{ isset($exam_mock['title']) ? html_entity_decode($exam_mock['title']) : '' }}
                                            ({{ isset($exam_mock['percentage']) ? $exam_mock['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @else
                                        <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptMock['id']), 'exam_type' => base64_encode(2), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold mockTitleSection">{{ isset($exam_mock['title']) ? html_entity_decode($exam_mock['title']) : '' }}
                                            ({{ isset($exam_mock['percentage']) ? $exam_mock['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @endif
                                
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($secondAttemptMock['final_obtain_percentage']) ? $secondAttemptMock['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                                    $totalPercentage += $secondAttemptMock['final_obtain_percentage'];
                                ?>
                            @endif
                            @endforeach

                        
                        @endif
                        {{-- vlog --}}
                        @if($label === 'Vlog Exam' && !empty($data))
                            @php
                                $exam_vlog = isset(
                                    $exam['exam_course'][0][
                                        'vlog_status'
                                    ][0]['vlog_exam'][0],
                                )
                                    ? $exam['exam_course'][0][
                                        'vlog_status'
                                    ][0]['vlog_exam'][0]
                                    : [];

                                $firstAttemptVlog = null;
                                $secondAttemptVlog = null;
                                
                                foreach($exam['exam_course'][0]['vlog_status'] ?? [] as $key => $status) {
                                    
                                    if ($firstAttemptVlog === null) {
                                        $firstAttemptVlog = $exam['exam_course'][0]['vlog_status'][0];
                                    } elseif ($secondAttemptVlog === null) {
                                        $secondAttemptVlog = $exam['exam_course'][0]['vlog_status'][1];
                                        break; 
                                    }
                                }
                            @endphp
                            @foreach($exam['exam_course'][0]['vlog_status'] as $key => $firstAttemptVlog)
                            @if($firstAttemptVlog && $attempt_exam ==  base64_encode("1") && $key == 0)
                                <li class="mb-2">
                                    
                                        @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptVlog['id']), 'exam_type' => base64_encode(3), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection">{{ isset($exam_vlog['title']) ? html_entity_decode($exam_vlog['title']) : '' }}
                                                ({{ isset($exam_vlog['percentage']) ? $exam_vlog['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @else
                                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptVlog['id']), 'exam_type' => base64_encode(3), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection ">{{ isset($exam_vlog['title']) ? html_entity_decode($exam_vlog['title']) : '' }}
                                                ({{ isset($exam_vlog['percentage']) ? $exam_vlog['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @endif
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($exam['final_obtain_percentage']) ? $exam['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                                $totalPercentage += $exam['final_obtain_percentage'];
                                ?>
                            @elseif($secondAttemptVlog && $attempt_exam == base64_encode("2") && $key == 1)
                                <li class="mb-2">
                                        
                                    @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                        <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptVlog['id']), 'exam_type' => base64_encode(3), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection ">{{ isset($exam_vlog['title']) ? html_entity_decode($exam_vlog['title']) : '' }}
                                            ({{ isset($exam_vlog['percentage']) ? $exam_vlog['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @else
                                        <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptVlog['id']), 'exam_type' => base64_encode(3), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection">{{ isset($exam_vlog['title']) ? html_entity_decode($exam_vlog['title']) : '' }}
                                            ({{ isset($exam_vlog['percentage']) ? $exam_vlog['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @endif
                                <ul class="ps-3">
                                    <li
                                        class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                        <span>Total Marks
                                            Obtained:</span> <span>
                                            {{ isset($exam['final_obtain_percentage']) ? $exam['final_obtain_percentage'] : 0 }}%</span>
                                    </li>
                                </ul>
                                </li>
                                <?php
                                $totalPercentage += $exam['final_obtain_percentage'];
                                ?>
                            @endif
                            @endforeach     
                        @endif
                        {{-- peer review --}}
                        @if($label === 'Peer Review Exam' && !empty($data))
                            @php
                                $exam_peer_review = isset(
                                    $exam['exam_course'][0][
                                        'peer_review_status'
                                    ][0]['peer_review_exam'][0],
                                )
                                    ? $exam['exam_course'][0][
                                        'peer_review_status'
                                    ][0]['peer_review_exam'][0]
                                    : [];

                                $firstAttemptPeer = null;
                                $secondAttemptPeer = null;
                                
                                foreach($exam['exam_course'][0]['peer_review_status'] ?? [] as $key => $status) {
                                    
                                    if ($firstAttemptPeer === null) {
                                        $firstAttemptPeer = $exam['exam_course'][0]['peer_review_status'][0];
                                    } elseif ($secondAttemptPeer === null) {
                                        $secondAttemptPeer = $exam['exam_course'][0]['peer_review_status'][1];
                                        break; 
                                    }
                                }
                            @endphp
                            @foreach($exam['exam_course'][0]['peer_review_status'] as $key => $firstAttemptPeer)
                            @if($firstAttemptPeer && $attempt_exam ==  base64_encode("1") && $key == 0)
                                <li class="mb-2">
                                        @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptPeer['id']), 'exam_type' => base64_encode(4), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection ">{{ isset($exam_peer_review['title']) ? html_entity_decode($exam_peer_review['title']) : '' }}
                                                ({{ isset($exam_peer_review['percentage']) ? $exam_peer_review['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @else
                                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptPeer['id']), 'exam_type' => base64_encode(4), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection ">{{ isset($exam_peer_review['title']) ? html_entity_decode($exam_peer_review['title']) : '' }}
                                                ({{ isset($exam_peer_review['percentage']) ? $exam_peer_review['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @endif
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($firstAttemptPeer['final_obtain_percentage']) ? $firstAttemptPeer['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                                    $totalPercentage += $firstAttemptPeer['final_obtain_percentage'];
                                ?>
                            @elseif($secondAttemptPeer && $attempt_exam == base64_encode("2") && $key == 1)
                                <li class="mb-2">
                                    @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                        <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptPeer['id']), 'exam_type' => base64_encode(4), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection">{{ isset($exam_peer_review['title']) ? html_entity_decode($exam_peer_review['title']) : '' }}
                                            ({{ isset($exam_peer_review['percentage']) ? $exam_peer_review['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @else
                                        <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptPeer['id']), 'exam_type' => base64_encode(4), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection">{{ isset($exam_peer_review['title']) ? html_entity_decode($exam_peer_review['title']) : '' }}
                                            ({{ isset($exam_peer_review['percentage']) ? $exam_peer_review['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @endif
                                <ul class="ps-3">
                                    <li
                                        class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                        <span>Total Marks
                                            Obtained:</span> <span>
                                            {{ isset($secondAttemptPeer['final_obtain_percentage']) ? $secondAttemptPeer['final_obtain_percentage'] : 0 }}%</span>
                                    </li>
                                </ul>
                                </li>
                                <?php
                                    $totalPercentage += $secondAttemptPeer['final_obtain_percentage'];
                                ?>
                            @endif
                            @endforeach
                        @endif
                        {{-- forum leadership --}}
                        @if($label === 'Discord / Forum Leadership' && !empty($data))
                            @php
                                $exam_discord = isset(
                                    $exam['exam_course'][0][
                                        'discord_status'
                                    ][0]['discord_exam'][0],
                                )
                                    ? $exam['exam_course'][0][
                                        'discord_status'
                                    ][0]['discord_exam'][0]
                                    : [];

                                $firstAttemptDiscord = null;
                                $secondAttemptDiscord = null;
                                
                                foreach($exam['exam_course'][0]['discord_status'] ?? [] as $key => $status) {
                                    
                                    if ($firstAttemptDiscord === null) {
                                        $firstAttemptDiscord = $exam['exam_course'][0]['discord_status'][0];
                                    } elseif ($secondAttemptDiscord === null) {
                                        $secondAttemptDiscord = $exam['exam_course'][0]['discord_status'][1];
                                        break; 
                                    }
                                }
                            @endphp
                            @foreach($exam['exam_course'][0]['discord_status'] as $key => $firstAttemptDiscord)
                            @if($firstAttemptDiscord && $attempt_exam ==  base64_encode("1") && $key == 0)
                                <li class="mb-2">

                                        @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptDiscord['id']), 'exam_type' => base64_encode(5), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection ">Forum Leadership
                                                ({{ isset($exam_discord['percentage']) ? $exam_discord['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @else
                                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptDiscord['id']), 'exam_type' => base64_encode(5), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection">Forum Leadership
                                                ({{ isset($exam_discord['percentage']) ? $exam_discord['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @endif
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($firstAttemptDiscord['final_obtain_percentage']) ? $firstAttemptDiscord['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                                $totalPercentage += $firstAttemptDiscord['final_obtain_percentage'];
                                ?>
                            @elseif($secondAttemptDiscord && $attempt_exam == base64_encode("2") && $key == 1)
                                <li class="mb-2">

                                    @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                        <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptDiscord['id']), 'exam_type' => base64_encode(5), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection ">Forum Leadership
                                            ({{ isset($exam_discord['percentage']) ? $exam_discord['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @else
                                        <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptDiscord['id']), 'exam_type' => base64_encode(5), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection ">Forum Leadership
                                            ({{ isset($exam_discord['percentage']) ? $exam_discord['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @endif
                                <ul class="ps-3">
                                    <li
                                        class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                        <span>Total Marks
                                            Obtained:</span> <span>
                                            {{ isset($secondAttemptDiscord['final_obtain_percentage']) ? $secondAttemptDiscord['final_obtain_percentage'] : 0 }}%</span>
                                    </li>
                                </ul>
                                </li>
                                <?php
                                $totalPercentage += $secondAttemptDiscord['final_obtain_percentage'];
                                ?>
                            @endif
                            @endforeach
                        @endif
                        {{-- reflective journal --}}
                        @if($label === 'Reflective Journal Exam' && !empty($data))
                            @php
                                $exam_reflective_journal = isset(
                                    $exam['exam_course'][0][
                                        'reflective_journal_status'
                                    ][0]['reflective_journal_exam'][0],
                                )
                                    ? $exam['exam_course'][0][
                                        'reflective_journal_status'
                                    ][0]['reflective_journal_exam'][0]
                                    : [];

                                $firstAttemptReflective = null;
                                $secondAttemptReflective = null;
                                
                                foreach($exam['exam_course'][0]['reflective_journal_status'] ?? [] as $key => $status) {
                                    
                                    if ($firstAttemptReflective === null) {
                                        $firstAttemptReflective = $exam['exam_course'][0]['reflective_journal_status'][0];
                                    } elseif ($secondAttemptReflective === null) {
                                        $secondAttemptReflective = $exam['exam_course'][0]['reflective_journal_status'][1];
                                        break; 
                                    }
                                }
                            @endphp
                            @foreach($exam['exam_course'][0]['reflective_journal_status'] as $key => $firstAttemptReflective)
                            @if($firstAttemptReflective && $attempt_exam ==  base64_encode("1") && $key == 0)
                                <li class="mb-2">
                                        @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptReflective['id']), 'exam_type' => base64_encode(6), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection ">{{ isset($exam_reflective_journal['title']) ? html_entity_decode($exam_reflective_journal['title']) : '' }}
                                                ({{ isset($exam_reflective_journal['percentage']) ? $exam_reflective_journal['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @else
                                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptReflective['id']), 'exam_type' => base64_encode(6), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection ">{{ isset($exam_reflective_journal['title']) ? html_entity_decode($exam_reflective_journal['title']) : '' }}
                                                ({{ isset($exam_reflective_journal['percentage']) ? $exam_reflective_journal['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @endif
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($firstAttemptReflective['final_obtain_percentage']) ? $firstAttemptReflective['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                                $totalPercentage += $firstAttemptReflective['final_obtain_percentage'];
                                ?>
                            @elseif($secondAttemptReflective && $attempt_exam == base64_encode("2") && $key == 1)
                                <li class="mb-2">
                                    @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                        <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptReflective['id']), 'exam_type' => base64_encode(6), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection ">{{ isset($exam_reflective_journal['title']) ? html_entity_decode($exam_reflective_journal['title']) : '' }}
                                            ({{ isset($exam_reflective_journal['percentage']) ? $exam_reflective_journal['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @else
                                        <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptReflective['id']), 'exam_type' => base64_encode(6), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection ">{{ isset($exam_reflective_journal['title']) ? html_entity_decode($exam_reflective_journal['title']) : '' }}
                                            ({{ isset($exam_reflective_journal['percentage']) ? $exam_reflective_journal['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @endif
                                <ul class="ps-3">
                                    <li
                                        class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                        <span>Total Marks
                                            Obtained:</span> <span>
                                            {{ isset($secondAttemptReflective['final_obtain_percentage']) ? $secondAttemptReflective['final_obtain_percentage'] : 0 }}%</span>
                                    </li>
                                </ul>
                                </li>
                                <?php
                                $totalPercentage += $secondAttemptReflective['final_obtain_percentage'];
                                ?>
                            @endif
                            @endforeach
                        @endif
                        {{-- multiple choice --}}
                        @if($label === 'MCQ Exam' && !empty($data))
                            @php
                                $exam_mcq = isset(
                                    $exam['exam_course'][0][
                                        'mcq_status'
                                    ][0]['mcq_exam'][0],
                                )
                                    ? $exam['exam_course'][0][
                                        'mcq_status'
                                    ][0]['mcq_exam'][0]
                                    : [];

                                    $firstAttemptMcq = null;
                                    $secondAttemptMcq = null;
                                    
                                    foreach($exam['exam_course'][0]['mcq_status'] ?? [] as $key => $status) {
                                        
                                        if ($firstAttemptMcq === null) {
                                            $firstAttemptMcq = $exam['exam_course'][0]['mcq_status'][0];
                                        } elseif ($secondAttemptMcq === null) {
                                            $secondAttemptMcq = $exam['exam_course'][0]['mcq_status'][1];
                                            break; 
                                        }
                                    }
                            @endphp
                            @foreach($exam['exam_course'][0]['mcq_status'] as $key => $firstAttemptMcq)
                            @if($firstAttemptMcq && $attempt_exam == base64_encode("1") && $key == 0)
                                <li class="mb-2">
                                        @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                            <a href="#"
                                                class="fw-semibold vlogTitleSection">{{ isset($exam_mcq['title']) ? html_entity_decode($exam_mcq['title']) : '' }}
                                                ({{ isset($exam_mcq['percentage']) ? $exam_mcq['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @else
                                            <a href="#"
                                                class="fw-semibold vlogTitleSection">{{ isset($exam_mcq['title']) ? html_entity_decode($exam_mcq['title']) : '' }}
                                                ({{ isset($exam_mcq['percentage']) ? $exam_mcq['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @endif
                                        <ul class="ps-3">
                                            <li
                                                class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                                <span>Total Marks
                                                    Obtained:</span> <span>
                                                    {{ isset($firstAttemptMcq['final_obtain_percentage']) ? $firstAttemptMcq['final_obtain_percentage'] : 0 }}%</span>
                                            </li>
                                        </ul>
                                </li>
                                <?php
                                    $totalPercentage += $firstAttemptMcq['final_obtain_percentage'];
                                ?>
                            @elseif($secondAttemptMcq && $attempt_exam == base64_encode("2") && $key == 1)
                                <li class="mb-2">
                                    @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                        <a href="#"
                                            class="fw-semibold vlogTitleSection">{{ isset($exam_mcq['title']) ? html_entity_decode($exam_mcq['title']) : '' }}
                                            ({{ isset($exam_mcq['percentage']) ? $exam_mcq['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @else
                                        <a href="#"
                                            class="fw-semibold vlogTitleSection">{{ isset($exam_mcq['title']) ? html_entity_decode($exam_mcq['title']) : '' }}
                                            ({{ isset($exam_mcq['percentage']) ? $exam_mcq['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @endif
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($secondAttemptMcq['final_obtain_percentage']) ? $secondAttemptMcq['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                                $totalPercentage += $secondAttemptMcq['final_obtain_percentage'];
                                ?>
                            @endif
                            @endforeach
                        @endif
                        {{-- survey --}}
                        @if($label === 'Survey Exam' && !empty($data))

                            @php
                                $exam_survey = isset(
                                    $exam['exam_course'][0][
                                        'survey_status'
                                    ][0]['survey_exam'][0],
                                )
                                    ? $exam['exam_course'][0][
                                        'survey_status'
                                    ][0]['survey_exam'][0]
                                    : [];
                                    $firstAttempt = null;
                                    $secondAttempt = null;
                                
                                    foreach($exam['exam_course'][0]['survey_status'] ?? [] as $key => $status) {
                                        
                                        if ($firstAttempt === null) {
                                            $firstAttempt = $exam['exam_course'][0]['survey_status'][0];
                                        } elseif ($secondAttempt === null) {
                                            $secondAttempt = $exam['exam_course'][0]['survey_status'][1];
                                            break; 
                                        }
                                    }
                            @endphp
                            
                            @foreach($exam['exam_course'][0]['survey_status'] as $key => $firstAttempt)
                            @if($firstAttempt && $attempt_exam ==  base64_encode("1"))
                            @if($key == 0)
                                <li class="mb-2">
                                        @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttempt['id']), 'exam_type' => base64_encode(8), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection">{{ isset($exam_survey['title']) ? html_entity_decode($exam_survey['title']) : '' }}
                                                ({{ isset($exam_survey['percentage']) ? $exam_survey['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @else
                                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttempt['id']), 'exam_type' => base64_encode(8), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection">{{ isset($exam_survey['title']) ? html_entity_decode($exam_survey['title']) : '' }}
                                                ({{ isset($exam_survey['percentage']) ? $exam_survey['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @endif
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($firstAttempt['final_obtain_percentage']) ? $firstAttempt['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                </li> 
                                
                                <?php
                                    $totalPercentage += $firstAttempt['final_obtain_percentage'];
                                ?>
                            @endif
                            @elseif($secondAttempt && $attempt_exam ==  base64_encode("2"))
                            @if($key == 1)
                                <li class="mb-2">
                                    @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                        <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttempt['id']), 'exam_type' => base64_encode(8), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection">{{ isset($exam_survey['title']) ? html_entity_decode($exam_survey['title']) : '' }}
                                            ({{ isset($exam_survey['percentage']) ? $exam_survey['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @else
                                        <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttempt['id']), 'exam_type' => base64_encode(8), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection">{{ isset($exam_survey['title']) ? html_entity_decode($exam_survey['title']) : '' }}
                                            ({{ isset($exam_survey['percentage']) ? $exam_survey['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @endif
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($secondAttempt['final_obtain_percentage']) ? $secondAttempt['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                            
                                </li>
                            <?php
                                $totalPercentage += $secondAttempt['final_obtain_percentage'];
                            ?>
                            @endif
                            @endif
                            @endforeach
                        
                        @endif
                        {{-- artificial intelligence --}}
                        @if($label === 'AI Exam' && !empty($data))
                            @php
                                $exam_artificial_intelligence = isset(
                                    $exam['exam_course'][0][
                                        'artificial_intelligence_status'
                                    ][0]['artificial_intelligence_exam'][0],
                                )
                                    ? $exam['exam_course'][0][
                                        'artificial_intelligence_status'
                                    ][0]['artificial_intelligence_exam'][0]
                                    : [];

                                    $firstAttemptArtifical = null;
                                    $secondAttemptArtifical = null;
                                    
                                    foreach($exam['exam_course'][0]['artificial_intelligence_status'] ?? [] as $key => $status) {
                                        
                                        if ($firstAttemptArtifical === null) {
                                            $firstAttemptArtifical = $exam['exam_course'][0]['artificial_intelligence_status'][0];
                                        } elseif ($secondAttemptArtifical === null) {
                                            $secondAttemptArtifical = $exam['exam_course'][0]['artificial_intelligence_status'][1];
                                            break; 
                                        }
                                    }
                            @endphp
                            @foreach($exam['exam_course'][0]['artificial_intelligence_status'] as $key => $firstAttemptArtifical)
                            @if($firstAttemptArtifical && $attempt_exam ==  base64_encode("1") && $key == 0)
                                <li class="mb-2">


                                        @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptArtifical['id']), 'exam_type' => base64_encode(9), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection">{{ isset($exam_artificial_intelligence['title']) ? html_entity_decode($exam_artificial_intelligence['title']) : '' }}
                                                ({{ isset($exam_artificial_intelligence['percentage']) ? $exam_artificial_intelligence['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @else
                                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptArtifical['id']), 'exam_type' => base64_encode(9), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                                class="fw-semibold vlogTitleSection">{{ isset($exam_artificial_intelligence['title']) ? html_entity_decode($exam_artificial_intelligence['title']) : '' }}
                                                ({{ isset($exam_artificial_intelligence['percentage']) ? $exam_artificial_intelligence['percentage'] : 0 }}%)
                                                <i
                                                    class="bi bi-arrow-right fw-bold"></i></a>
                                        @endif
                                    <ul class="ps-3">
                                        <li
                                            class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                            <span>Total Marks
                                                Obtained:</span> <span>
                                                {{ isset($firstAttemptArtifical['final_obtain_percentage']) ? $firstAttemptArtifical['final_obtain_percentage'] : 0 }}%</span>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                                $totalPercentage += $firstAttemptArtifical['final_obtain_percentage'];
                                ?>
                            @elseif($secondAttemptArtifical && $attempt_exam == base64_encode("2") && $key == 1)
                                <li class="mb-2">


                                    @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                                        <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptArtifical['id']), 'exam_type' => base64_encode(9), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection">{{ isset($exam_artificial_intelligence['title']) ? html_entity_decode($exam_artificial_intelligence['title']) : '' }}
                                            ({{ isset($exam_artificial_intelligence['percentage']) ? $exam_artificial_intelligence['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @else
                                        <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptArtifical['id']), 'exam_type' => base64_encode(9), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                            class="fw-semibold vlogTitleSection">{{ isset($exam_artificial_intelligence['title']) ? html_entity_decode($exam_artificial_intelligence['title']) : '' }}
                                            ({{ isset($exam_artificial_intelligence['percentage']) ? $exam_artificial_intelligence['percentage'] : 0 }}%)
                                            <i
                                                class="bi bi-arrow-right fw-bold"></i></a>
                                    @endif
                                <ul class="ps-3">
                                    <li
                                        class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                        <span>Total Marks
                                            Obtained:</span> <span>
                                            {{ isset($secondAttemptArtifical['final_obtain_percentage']) ? $secondAttemptArtifical['final_obtain_percentage'] : 0 }}%</span>
                                    </li>
                                </ul>
                                </li>
                                <?php
                                $totalPercentage += $secondAttemptArtifical['final_obtain_percentage'];
                                ?>
                            @endif
                            @endforeach
                        @endif
                        {{-- @endif --}}
                    @endforeach
                    @endforeach               

                    {{-- eportfolio --}}
                    @php
                        $exists = is_exist('exam_eportfolio', [
                            'user_id' =>
                                $ementorStudentData[
                                    'studentData'
                                ][0]['user_id'],
                            'course_id' =>
                                $ementorStudentData[
                                    'studentData'
                                ][0]['course_id'],
                            'student_course_master_id' => $exam['student_course_master_id'],
                        ]);
                    @endphp

                    @if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0)
                        <li class="mb-2">
                            
                            <a href="{{ 
                                Auth::user()->role === 'instructor' ? 
                                    url('ementor/e-portfolio-attempt', ['user_id' => base64_encode($stData['id']), 'course' => base64_encode($exam['course_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) :
                                    (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0 ?
                                    url('admin/e-portfolio-attempt', ['user_id' => base64_encode($stData['id']), 'course' => base64_encode($exam['course_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) : '#') 
                                }}" 
                                class="fw-semibold portfolioTitleSection">
                                E-portfolio
                                <i class="bi bi-arrow-right fw-bold"></i>
                            </a>
                            

                            @if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0)
                                <ul class="ps-3">
                                    @php
                                        $eportfolio = getData('exam_eportfolio', ['remark'], ['user_id' => $ementorStudentData['studentData'][0]['user_id'], 'course_id' => $exam['course_id'], 'student_course_master_id' => $exam['student_course_master_id']], '1', 'created_at', 'desc');
                                        $remark = isset($eportfolio[0]->remark) ? $eportfolio[0]->remark : '';
                                    @endphp
                                    <li class="fs-md-6 badge text-bg-{{ $remark == '1' ? 'success' : ($remark == '0' ? 'danger' : 'warning') }} text-white marksObtainedTitle">
                                        <span>Remark:</span>
                                        <span>{{ $remark == '1' ? 'Pass' : ($remark == '0' ? 'Fail' : 'Not Checked') }}</span>
                                    </li>
                                    
                                </ul>
                            @else
                                <ul class="ps-3">
                                    <li
                                        class="fs-md-6 text-bg-warning text-white marksObtainedTitle">
                                        <span>Remark:</span> <span>
                                            Checked</span>
                                    </li>
                                </ul>
                            @endif
                        </li>
                    @endif
                    <div class="card-footer">
                        <span class="fw-bold color-blue">Total Marks Obtained:</span> <span
                            class="text-success fw-bold"> {{ $totalPercentage }}%</span>
                    </div>
                </ul>
            </div>
        </div>
    </div>

</div>
