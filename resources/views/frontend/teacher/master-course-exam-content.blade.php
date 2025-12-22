@php
                        // assignment
                        $exam_assign = isset(
                        $exam['exam_course'][0][
                        'assginment_status'
                        ][0]['assginment_exam'][0],
                        )
                        ? $exam['exam_course'][0][
                        'assginment_status'
                        ][0]['assginment_exam'][0]
                        : [];

                        $assignmentCount = isset($exam['exam_course'][0]) ?
                        count($exam['exam_course'][0]['assginment_status']) : 0;

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
                        $total_mark_obtain += isset(
                        $exam['final_score_obtain'],
                        )
                        ? $exam['final_score_obtain']
                        : 0;

                        @endphp

                        {{-- assignment --}}
                        @if(!empty($exam_assign))
                        {{-- @php echo "Sdfdfds"; @endphp --}}
                        @foreach($exam['exam_course'][0]['assginment_status'] as $keys => $assignment)
                        @php
                        $exam_assign = $assignment['assginment_exam'][0];
                        // $exam_assign_second = $assignment['assginment_exam'][1];
                        @endphp
                        @if (!empty($exam_assign))
                        @php
                        $examRemarkMasterId = $assignment['id'];
                        $obtainPercentage = $assignment['final_obtain_percentage'];
                        $is_cheking_completed = $assignment['is_cheking_completed'];
                        @endphp
                        @if($keys == 0 && $attempt_exam == base64_encode("1"))
                        @if($is_cheking_completed == 2)
                        <li class="mb-2">

                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($examRemarkMasterId), 'exam_type' => base64_encode(1), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold assignmentTitleSection">
                                {{ isset($exam_assign['assignment_tittle']) ?
                                html_entity_decode($exam_assign['assignment_tittle']) : '' }}
                                ({{ isset($exam_assign['assignment_percentage']) ? $exam_assign['assignment_percentage']
                                : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($examRemarkMasterId), 'exam_type' => base64_encode(1), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold assignmentTitleSection">
                                {{ isset($exam_assign['assignment_tittle']) ?
                                html_entity_decode($exam_assign['assignment_tittle']) : '' }}
                                ({{ isset($exam_assign['assignment_percentage']) ? $exam_assign['assignment_percentage']
                                : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif

                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks Obtained:</span> <span>
                                        {{ isset($obtainPercentage) ? $obtainPercentage : 0 }}%</span>
                                </li>
                            </ul>
                            <?php

                                $totalPercentage += $obtainPercentage
                            ?>
                        </li>
                        @endif
                        @elseif($keys == 1 && $attempt_exam == base64_encode("2"))
                        @if($is_cheking_completed == 2)
                        <li class="mb-2">

                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($examRemarkMasterId), 'exam_type' => base64_encode(1), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold assignmentTitleSection">
                                {{ isset($exam_assign['assignment_tittle']) ?
                                html_entity_decode($exam_assign['assignment_tittle']) : '' }}
                                ({{ isset($exam_assign['assignment_percentage']) ? $exam_assign['assignment_percentage']
                                : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($examRemarkMasterId), 'exam_type' => base64_encode(1), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold assignmentTitleSection ">
                                {{ isset($exam_assign['assignment_tittle']) ?
                                html_entity_decode($exam_assign['assignment_tittle']) : '' }}
                                ({{ isset($exam_assign['assignment_percentage']) ? $exam_assign['assignment_percentage']
                                : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif

                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks Obtained:</span> <span>
                                        {{ isset($obtainPercentage) ? $obtainPercentage : 0 }}%</span>
                                </li>
                            </ul>
                            <?php
                                                        // $averagePercentagePerCourse = 100/$courseCount;
                                                        // $totalPercentage += ($obtainPercentage * $averagePercentagePerCourse) / 100
    
                                                        $totalPercentage += $obtainPercentage
                                                    ?>
                        </li>
                        @endif
                        @endif
                        @endif
                        @endforeach
                        @endif

                        {{-- mock --}}
                        @if (!empty($exam_mock))
                        @php
                        $firstAttemptMock = null;
                        $secondAttemptMock = null;

                        if(!empty($exam['exam_course'][0]['mock_exam_status'][0])) {
                        $firstAttemptMock = $exam['exam_course'][0]['mock_exam_status'][0];
                        }
                        if(!empty($exam['exam_course'][0]['mock_exam_status'][1])) {
                        $secondAttemptMock = $exam['exam_course'][0]['mock_exam_status'][1];
                        }

                        @endphp
                        @if($firstAttemptMock && $attempt_exam == base64_encode("1") &&
                        $firstAttemptMock['is_cheking_completed'] == 2)
                        <li class="mb-2">
                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptMock['id']), 'exam_type' => base64_encode(2), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold mockTitleSection">{{ isset($exam_mock['title']) ?
                                html_entity_decode($exam_mock['title']) : '' }}
                                ({{ isset($exam_mock['percentage']) ? $exam_mock['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptMock['id']), 'exam_type' => base64_encode(2), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold mockTitleSection">{{ isset($exam_mock['title']) ?
                                html_entity_decode($exam_mock['title']) : '' }}
                                ({{ isset($exam_mock['percentage']) ? $exam_mock['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif

                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks Obtained:</span> <span>
                                        {{ isset($firstAttemptMock['final_obtain_percentage']) ?
                                        $firstAttemptMock['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        // $averagePercentagePerCourse = 100/$courseCount;
                                        // $totalPercentage += ($obtainPercentage * $averagePercentagePerCourse) / 100

                                        $totalPercentage += $firstAttemptMock['final_obtain_percentage'];
                                    ?>
                        @elseif($secondAttemptMock && $attempt_exam == base64_encode("2") &&
                        $secondAttemptMock['is_cheking_completed'] == 2)
                        <li class="mb-2">
                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptMock['id']), 'exam_type' => base64_encode(2), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold mockTitleSection">{{ isset($exam_mock['title']) ?
                                html_entity_decode($exam_mock['title']) : '' }}
                                ({{ isset($exam_mock['percentage']) ? $exam_mock['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptMock['id']), 'exam_type' => base64_encode(2), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold mockTitleSection">{{ isset($exam_mock['title']) ?
                                html_entity_decode($exam_mock['title']) : '' }}
                                ({{ isset($exam_mock['percentage']) ? $exam_mock['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif

                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks Obtained:</span> <span>
                                        {{ isset($secondAttemptMock['final_obtain_percentage']) ?
                                        $secondAttemptMock['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        $totalPercentage += $secondAttemptMock['final_obtain_percentage'];
                                    ?>
                        @endif
                        @endif

                        {{-- vlog --}}
                        @if (!empty($exam_vlog))
                        @php
                        // $examRemarkMasterId = $exam['exam_course'][0][
                        // 'vlog_status'
                        // ][0]['id'];
                        // $obtainPercentage = $exam['exam_course'][0][
                        // 'vlog_status'
                        // ][0]['final_obtain_percentage'];
                        // $is_cheking_completed = $exam['exam_course'][0][
                        // 'vlog_status'
                        // ][0]['is_cheking_completed'];
                        $firstAttemptVlog = null;
                        $secondAttemptVlog = null;

                        if(!empty($exam['exam_course'][0]['vlog_status'][0])) {
                        $firstAttemptVlog = $exam['exam_course'][0]['vlog_status'][0];
                        }
                        if(!empty($exam['exam_course'][0]['vlog_status'][1])) {
                        $secondAttemptVlog = $exam['exam_course'][0]['vlog_status'][1];
                        }
                        @endphp
                        @if($firstAttemptVlog && $attempt_exam == base64_encode("1") &&
                        $firstAttemptVlog['is_cheking_completed'] == 2)
                        <li class="mb-2">

                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptVlog['id']), 'exam_type' => base64_encode(3), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_vlog['title']) ?
                                html_entity_decode($exam_vlog['title']) : '' }}
                                ({{ isset($exam_vlog['percentage']) ? $exam_vlog['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptVlog['id']), 'exam_type' => base64_encode(3), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_vlog['title']) ?
                                html_entity_decode($exam_vlog['title']) : '' }}
                                ({{ isset($exam_vlog['percentage']) ? $exam_vlog['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif
                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($firstAttemptVlog['final_obtain_percentage']) ?
                                        $firstAttemptVlog['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        $totalPercentage += $firstAttemptVlog['final_obtain_percentage'];
                                    ?>
                        @elseif($secondAttemptVlog && $attempt_exam == base64_encode("2") &&
                        $secondAttemptVlog['is_cheking_completed'] == 2)
                        <li class="mb-2">

                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptVlog['id']), 'exam_type' => base64_encode(3), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_vlog['title']) ?
                                html_entity_decode($exam_vlog['title']) : '' }}
                                ({{ isset($exam_vlog['percentage']) ? $exam_vlog['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/answersheet', ['exam_id' => base64_encode($secondAttemptVlog['id']), 'exam_type' => base64_encode(3), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id'])]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_vlog['title']) ?
                                html_entity_decode($exam_vlog['title']) : '' }}
                                ({{ isset($exam_vlog['percentage']) ? $exam_vlog['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif
                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($secondAttemptVlog['final_obtain_percentage']) ?
                                        $secondAttemptVlog['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        $totalPercentage += $secondAttemptVlog['final_obtain_percentage'];
                                    ?>
                        @endif
                        @endif

                        {{-- peer review --}}
                        @if (!empty($exam_peer_review))
                        @php
                        // $examRemarkMasterId = $exam['exam_course'][0][
                        // 'peer_review_status'
                        // ][0]['id'];
                        // $obtainPercentage = $exam['exam_course'][0][
                        // 'peer_review_status'
                        // ][0]['final_obtain_percentage'];
                        // $is_cheking_completed = $exam['exam_course'][0][
                        // 'peer_review_status'
                        // ][0]['is_cheking_completed'];
                        $firstAttemptPeer = null;
                        $secondAttemptPeer = null;

                        if(!empty($exam['exam_course'][0]['peer_review_status'][0])) {
                        $firstAttemptPeer = $exam['exam_course'][0]['peer_review_status'][0];
                        }
                        if(!empty($exam['exam_course'][0]['peer_review_status'][1])) {
                        $secondAttemptPeer = $exam['exam_course'][0]['peer_review_status'][1];
                        }
                        @endphp
                        @if($firstAttemptPeer && $attempt_exam == base64_encode("1") &&
                        $firstAttemptPeer['is_cheking_completed'] == 2)
                        <li class="mb-2">

                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptPeer['id']), 'exam_type' => base64_encode(4), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_peer_review['title']) ?
                                html_entity_decode($exam_peer_review['title']) : '' }}
                                ({{ isset($exam_peer_review['percentage']) ? $exam_peer_review['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptPeer['id']), 'exam_type' => base64_encode(4), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_peer_review['title']) ?
                                html_entity_decode($exam_peer_review['title']) : '' }}
                                ({{ isset($exam_peer_review['percentage']) ? $exam_peer_review['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif

                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($firstAttemptPeer['final_obtain_percentage']) ?
                                        $firstAttemptPeer['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        // $averagePercentagePerCourse = 100/$courseCount;
                                        // $totalPercentage += ($obtainPercentage * $averagePercentagePerCourse) / 100

                                        $totalPercentage += $firstAttemptPeer['final_obtain_percentage']
                                    ?>

                        @elseif($secondAttemptPeer && $attempt_exam == base64_encode("2") &&
                        $secondAttemptPeer['is_cheking_completed'] == 2)
                        <li class="mb-2">

                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptPeer['id']), 'exam_type' => base64_encode(4), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_peer_review['title']) ?
                                html_entity_decode($exam_peer_review['title']) : '' }}
                                ({{ isset($exam_peer_review['percentage']) ? $exam_peer_review['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptPeer['id']), 'exam_type' => base64_encode(4), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_peer_review['title']) ?
                                html_entity_decode($exam_peer_review['title']) : '' }}
                                ({{ isset($exam_peer_review['percentage']) ? $exam_peer_review['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif

                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($secondAttemptPeer['final_obtain_percentage']) ?
                                        $secondAttemptPeer['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        // $averagePercentagePerCourse = 100/$courseCount;
                                        // $totalPercentage += ($obtainPercentage * $averagePercentagePerCourse) / 100

                                        $totalPercentage += $secondAttemptPeer['final_obtain_percentage']
                                    ?>
                        @endif
                        @endif

                        {{-- forum leadership --}}
                        @if (!empty($exam_discord))
                        @php
                        // $examRemarkMasterId = $exam['exam_course'][0][
                        // 'discord_status'
                        // ][0]['id'];
                        // $obtainPercentage = $exam['exam_course'][0][
                        // 'discord_status'
                        // ][0]['final_obtain_percentage'];
                        // $is_cheking_completed = $exam['exam_course'][0][
                        // 'discord_status'
                        // ][0]['is_cheking_completed'];
                        $firstAttemptDiscord = null;
                        $secondAttemptDiscord = null;

                        if(!empty($exam['exam_course'][0]['discord_status'][0])) {
                        $firstAttemptDiscord = $exam['exam_course'][0]['discord_status'][0];
                        }
                        if(!empty($exam['exam_course'][0]['discord_status'][1])) {
                        $secondAttemptDiscord = $exam['exam_course'][0]['discord_status'][1];
                        }
                        @endphp
                        @if($firstAttemptDiscord && $attempt_exam == base64_encode("1") &&
                        $firstAttemptDiscord['is_cheking_completed'] == 2)
                        <li class="mb-2">
                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptDiscord['id']), 'exam_type' => base64_encode(5), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">Forum Leadership
                                ({{ isset($exam_discord['percentage']) ? $exam_discord['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptDiscord['id']), 'exam_type' => base64_encode(5), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">Forum Leadership
                                ({{ isset($exam_discord['percentage']) ? $exam_discord['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif
                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($firstAttemptDiscord['final_obtain_percentage']) ?
                                        $firstAttemptDiscord['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        // $averagePercentagePerCourse = 100/$courseCount;
                                        // $totalPercentage += ($firstAttemptDiscord['final_obtain_percentage'] * $averagePercentagePerCourse) / 100

                                        $totalPercentage += $firstAttemptDiscord['final_obtain_percentage'];
                                    ?>
                        @elseif($secondAttemptDiscord && $attempt_exam == base64_encode("2") &&
                        $secondAttemptDiscord['is_cheking_completed'] == 2)
                        <li class="mb-2">
                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptDiscord['id']), 'exam_type' => base64_encode(5), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">Forum Leadership
                                ({{ isset($exam_discord['percentage']) ? $exam_discord['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptDiscord['id']), 'exam_type' => base64_encode(5), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">Forum Leadership
                                ({{ isset($exam_discord['percentage']) ? $exam_discord['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif
                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($secondAttemptDiscord['final_obtain_percentage']) ?
                                        $secondAttemptDiscord['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        $totalPercentage += $secondAttemptDiscord['final_obtain_percentage'];
                                    ?>
                        @endif
                        @endif

                        {{-- reflective journal --}}
                        @if (!empty($exam_reflective_journal))
                        @php
                        // $examRemarkMasterId = $exam['exam_course'][0][
                        // 'reflective_journal_status'
                        // ][0]['id'];
                        // $obtainPercentage = $exam['exam_course'][0][
                        // 'reflective_journal_status'
                        // ][0]['final_obtain_percentage'];
                        // $is_cheking_completed = $exam['exam_course'][0][
                        // 'reflective_journal_status'
                        // ][0]['is_cheking_completed'];
                        $firstAttemptReflective = null;
                        $secondAttemptReflective = null;

                        if(!empty($exam['exam_course'][0]['reflective_journal_status'][0])) {
                        $firstAttemptReflective = $exam['exam_course'][0]['reflective_journal_status'][0];
                        }
                        if(!empty($exam['exam_course'][0]['reflective_journal_status'][1])) {
                        $secondAttemptReflective = $exam['exam_course'][0]['reflective_journal_status'][1];
                        }
                        @endphp
                        @if($firstAttemptReflective && $attempt_exam == base64_encode("1") &&
                        $firstAttemptReflective['is_cheking_completed'] == 2)
                        <li class="mb-2">
                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptReflective['id']), 'exam_type' => base64_encode(6), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{
                                isset($exam_reflective_journal['title']) ?
                                html_entity_decode($exam_reflective_journal['title']) : '' }}
                                ({{ isset($exam_reflective_journal['percentage']) ?
                                $exam_reflective_journal['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptReflective['id']), 'exam_type' => base64_encode(6), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{
                                isset($exam_reflective_journal['title']) ?
                                html_entity_decode($exam_reflective_journal['title']) : '' }}
                                ({{ isset($exam_reflective_journal['percentage']) ?
                                $exam_reflective_journal['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif
                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($firstAttemptReflective['final_obtain_percentage']) ?
                                        $firstAttemptReflective['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        $totalPercentage += $firstAttemptReflective['final_obtain_percentage'];
                                    ?>
                        @elseif($secondAttemptReflective && $attempt_exam == base64_encode("2") &&
                        $secondAttemptReflective['is_cheking_completed'] == 2)
                        <li class="mb-2">
                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptReflective['id']), 'exam_type' => base64_encode(6), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{
                                isset($exam_reflective_journal['title']) ?
                                html_entity_decode($exam_reflective_journal['title']) : '' }}
                                ({{ isset($exam_reflective_journal['percentage']) ?
                                $exam_reflective_journal['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptReflective['id']), 'exam_type' => base64_encode(6), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{
                                isset($exam_reflective_journal['title']) ?
                                html_entity_decode($exam_reflective_journal['title']) : '' }}
                                ({{ isset($exam_reflective_journal['percentage']) ?
                                $exam_reflective_journal['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif
                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($secondAttemptReflective['final_obtain_percentage']) ?
                                        $secondAttemptReflective['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        $totalPercentage += $secondAttemptReflective['final_obtain_percentage'];
                                    ?>
                        @endif
                        @endif

                        {{-- multiple choice --}}
                        @if (!empty($exam_mcq))
                        @php
                        // $examRemarkMasterId = $exam['exam_course'][0][
                        // 'mcq_status'
                        // ][0]['id'];
                        // $obtainPercentage = $exam['exam_course'][0][
                        // 'mcq_status'
                        // ][0]['final_obtain_percentage'];
                        // $is_cheking_completed = $exam['exam_course'][0][
                        // 'mcq_status'
                        // ][0]['is_cheking_completed'];

                        $firstAttemptMcq = null;
                        $secondAttemptMcq = null;

                        if(!empty($exam['exam_course'][0]['mcq_status'][0])) {
                        $firstAttemptMcq = $exam['exam_course'][0]['mcq_status'][0];
                        }
                        if(!empty($exam['exam_course'][0]['mcq_status'][1])) {
                        $secondAttemptMcq = $exam['exam_course'][0]['mcq_status'][1];
                        }
                        @endphp
                        @if($firstAttemptMcq && $attempt_exam == base64_encode("1") &&
                        $firstAttemptMcq['is_cheking_completed'] == 2)
                        <li class="mb-2">

                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptMcq['id']), 'exam_type' => base64_encode(7), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_mcq['title']) ?
                                html_entity_decode($exam_mcq['title']) : '' }}
                                ({{ isset($exam_mcq['percentage']) ? $exam_mcq['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptMcq['id']), 'exam_type' => base64_encode(7), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_mcq['title']) ?
                                html_entity_decode($exam_mcq['title']) : '' }}
                                ({{ isset($exam_mcq['percentage']) ? $exam_mcq['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif
                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($firstAttemptMcq['final_obtain_percentage']) ?
                                        $firstAttemptMcq['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        // $averagePercentagePerCourse = 100/$courseCount;
                                        // $totalPercentage += ($firstAttemptMcq['final_obtain_percentage'] * $averagePercentagePerCourse) / 100

                                        $totalPercentage += $firstAttemptMcq['final_obtain_percentage']
                                    ?>
                        @elseif($secondAttemptMcq && $attempt_exam == base64_encode("2") &&
                        $secondAttemptMcq['is_cheking_completed'] == 2)
                        <li class="mb-2">

                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptMcq['id']), 'exam_type' => base64_encode(7), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_mcq['title']) ?
                                html_entity_decode($exam_mcq['title']) : '' }}
                                ({{ isset($exam_mcq['percentage']) ? $exam_mcq['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptMcq['id']), 'exam_type' => base64_encode(7), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_mcq['title']) ?
                                html_entity_decode($exam_mcq['title']) : '' }}
                                ({{ isset($exam_mcq['percentage']) ? $exam_mcq['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif
                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($secondAttemptMcq['final_obtain_percentage']) ?
                                        $secondAttemptMcq['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        // $averagePercentagePerCourse = 100/$courseCount;
                                        // $totalPercentage += ($secondAttemptMcq['final_obtain_percentage'] * $averagePercentagePerCourse) / 100

                                        $totalPercentage += $secondAttemptMcq['final_obtain_percentage']
                                    ?>
                        @endif
                        @endif

                        {{-- survey --}}
                        @if (!empty($exam_survey))
                        @php
                        // $examRemarkMasterId = $exam['exam_course'][0][
                        // 'survey_status'
                        // ][0]['id'];
                        // $obtainPercentage = $exam['exam_course'][0][
                        // 'survey_status'
                        // ][0]['final_obtain_percentage'];
                        // $is_cheking_completed = $exam['exam_course'][0][
                        // 'survey_status'
                        // ][0]['is_cheking_completed'];
                        $firstAttemptSurvey = null;
                        $secondAttemptSurvey = null;

                        if(!empty($exam['exam_course'][0]['survey_status'][0])) {
                        $firstAttemptSurvey = $exam['exam_course'][0]['survey_status'][0];
                        }
                        if(!empty($exam['exam_course'][0]['survey_status'][1])) {
                        $secondAttemptSurvey = $exam['exam_course'][0]['survey_status'][1];
                        }
                        @endphp
                        @if($firstAttemptSurvey && $attempt_exam == base64_encode("1") &&
                        $firstAttemptSurvey['is_cheking_completed'] == 2)
                        <li class="mb-2">
                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptSurvey['id']), 'exam_type' => base64_encode(8), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_survey['title']) ?
                                html_entity_decode($exam_survey['title']) : '' }}
                                ({{ isset($exam_survey['percentage']) ? $exam_survey['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptSurvey['id']), 'exam_type' => base64_encode(8), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_survey['title']) ?
                                html_entity_decode($exam_survey['title']) : '' }}
                                ({{ isset($exam_survey['percentage']) ? $exam_survey['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif

                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($firstAttemptSurvey['final_obtain_percentage']) ?
                                        $firstAttemptSurvey['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
    
                                        $totalPercentage += $firstAttemptSurvey['final_obtain_percentage'];
                                    ?>
                        @elseif($secondAttemptSurvey && $attempt_exam == base64_encode("2") &&
                        $secondAttemptSurvey['is_cheking_completed'] == 2)
                        <li class="mb-2">
                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptSurvey['id']), 'exam_type' => base64_encode(8), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_survey['title']) ?
                                html_entity_decode($exam_survey['title']) : '' }}
                                ({{ isset($exam_survey['percentage']) ? $exam_survey['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptSurvey['id']), 'exam_type' => base64_encode(8), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{ isset($exam_survey['title']) ?
                                html_entity_decode($exam_survey['title']) : '' }}
                                ({{ isset($exam_survey['percentage']) ? $exam_survey['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif

                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($secondAttemptSurvey['final_obtain_percentage']) ?
                                        $secondAttemptSurvey['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php

                                        $totalPercentage += $secondAttemptSurvey['final_obtain_percentage'];
                                    ?>
                        @endif
                        @endif

                        {{-- artificial intelligence --}}
                        @if (!empty($exam_artificial_intelligence))
                        @php
                        // $examRemarkMasterId = $exam['exam_course'][0][
                        // 'artificial_intelligence_status'
                        // ][0]['id'];
                        // $obtainPercentage = $exam['exam_course'][0][
                        // 'artificial_intelligence_status'
                        // ][0]['final_obtain_percentage'];
                        // $is_cheking_completed = $exam['exam_course'][0][
                        // 'artificial_intelligence_status'
                        // ][0]['is_cheking_completed'];

                        $firstAttemptArtifical = null;
                        $secondAttemptArtifical = null;

                        if(!empty($exam['exam_course'][0]['survey_status'][0])) {
                        $firstAttemptArtifical = $exam['exam_course'][0]['survey_status'][0];
                        }
                        if(!empty($exam['exam_course'][0]['survey_status'][1])) {
                        $secondAttemptArtifical = $exam['exam_course'][0]['survey_status'][1];
                        }
                        @endphp
                        @if($firstAttemptArtifical && $attempt_exam == base64_encode("1") &&
                        $firstAttemptArtifical['is_cheking_completed'] == 2)
                        <li class="mb-2">
                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($firstAttemptArtifical['id']), 'exam_type' => base64_encode(9), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{
                                isset($exam_artificial_intelligence['title']) ?
                                html_entity_decode($exam_artificial_intelligence['title']) : '' }}
                                ({{ isset($exam_artificial_intelligence['percentage']) ?
                                $exam_artificial_intelligence['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($firstAttemptArtifical['id']), 'exam_type' => base64_encode(9), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{
                                isset($exam_artificial_intelligence['title']) ?
                                html_entity_decode($exam_artificial_intelligence['title']) : '' }}
                                ({{ isset($exam_artificial_intelligence['percentage']) ?
                                $exam_artificial_intelligence['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif
                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($firstAttemptArtifical['final_obtain_percentage']) ?
                                        $firstAttemptArtifical['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        $totalPercentage += $firstAttemptArtifical['final_obtain_percentage'];
                                    ?>
                        @elseif($secondAttemptArtifical && $attempt_exam == base64_encode("2") &&
                        $secondAttemptArtifical['is_cheking_completed'] == 2)
                        <li class="mb-2">
                            @if (Auth::user()->role === 'instructor' || Auth::user()->role === 'sub-instructor')
                            <a href="{{ url('/ementor/attempt', ['exam_id' => base64_encode($secondAttemptArtifical['id']), 'exam_type' => base64_encode(9), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{
                                isset($exam_artificial_intelligence['title']) ?
                                html_entity_decode($exam_artificial_intelligence['title']) : '' }}
                                ({{ isset($exam_artificial_intelligence['percentage']) ?
                                $exam_artificial_intelligence['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @else
                            <a href="{{ url('/admin/attempt', ['exam_id' => base64_encode($secondAttemptArtifical['id']), 'exam_type' => base64_encode(9), 'user_id' => base64_encode($exam['user_id']), 'student_course_master_id' => base64_encode($exam['student_course_master_id']),'attempt_exam'=> $attempt_exam]) }}"
                                class="fw-semibold vlogTitleSection">{{
                                isset($exam_artificial_intelligence['title']) ?
                                html_entity_decode($exam_artificial_intelligence['title']) : '' }}
                                ({{ isset($exam_artificial_intelligence['percentage']) ?
                                $exam_artificial_intelligence['percentage'] : 0 }}%)
                                <i class="bi bi-arrow-right fw-bold"></i></a>
                            @endif
                            <ul class="ps-3">
                                <li class="fs-md-6 badge text-bg-primary text-white marksObtainedTitle">
                                    <span>Total Marks
                                        Obtained:</span> <span>
                                        {{ isset($secondAttemptArtifical['final_obtain_percentage']) ?
                                        $secondAttemptArtifical['final_obtain_percentage'] : 0 }}%</span>
                                </li>
                            </ul>
                        </li>
                        <?php
                                        $totalPercentage += $secondAttemptArtifical['final_obtain_percentage'];
                                    ?>
                        @endif
                        @endif

                        {{-- eportfolio --}}
                        @if(($hasFirstAttempt) && $attempt_exam == base64_encode("1"))
                        @php

                        $exists = is_exist('exam_eportfolio', [
                        'user_id' => $ementorStudentData['studentData'][0]['user_id'],
                        'course_id' => $exam['course_id'],
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
                                        }}" class="fw-semibold portfolioTitleSection">
                                E-portfolio
                                <i class="bi bi-arrow-right fw-bold"></i>
                            </a>
                        </li>
                        @endif
                        <div class="card-footer">
                            <span class="fw-bold color-blue">Total Marks Obtained:</span> <span
                                class="text-success fw-bold"> {{ round($totalPercentage) }}%</span>
                        </div>
                        @endif
                        @if(($hasSecondAttempt) && $attempt_exam == base64_encode("2"))
                        @php

                        $exists = is_exist('exam_eportfolio', [
                        'user_id' => $ementorStudentData['studentData'][0]['user_id'],
                        'course_id' => $exam['course_id'],
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
                                        }}" class="fw-semibold portfolioTitleSection">
                                E-portfolio
                                <i class="bi bi-arrow-right fw-bold"></i>
                            </a>


                            {{-- @if (isset($exists) && is_numeric($exists) && !empty($exists) && $exists > 0)
                            <ul class="ps-3">
                                @php
                                $eportfolio = getData('exam_eportfolio', ['remark'], ['user_id' =>
                                $ementorStudentData['studentData'][0]['user_id'], 'course_id' => $exam['course_id'],
                                'student_course_master_id' => $exam['student_course_master_id']], '1', 'created_at',
                                'desc');
                                $remark = isset($eportfolio[0]->remark) ? $eportfolio[0]->remark : '';
                                @endphp
                                <li
                                    class="fs-md-6 badge text-bg-{{ $remark == '1' ? 'success' : ($remark == '0' ? 'danger' : 'warning') }} text-white marksObtainedTitle">
                                    <span>Remark:</span>
                                    <span>{{ $remark == '1' ? 'Pass' : ($remark == '0' ? 'Fail' : 'Not Checked')
                                        }}</span>
                                </li>

                            </ul>
                            @else
                            <ul class="ps-3">
                                <li class="fs-md-6 text-bg-warning text-white marksObtainedTitle">
                                    <span>Remark:</span> <span>
                                        Checked</span>
                                </li>
                            </ul>
                            @endif --}}
                        </li>
                        @endif
                        <div class="card-footer">
                            <span class="fw-bold color-blue">Total Marks Obtained:</span> <span
                                class="text-success fw-bold"> {{ round($totalPercentage) }}%</span>
                        </div>
                        @endif