@extends('frontend.master')
@section('content')

<style>
    .sidenav.navbar .navbar-nav .e-men-13 > .nav-link {
        color: #a30a1b !important;
        background-color: #ffe7ea;
}
.dataTables_filter{
    display: none;;
    }

.table-centered td, .table-centered th:last-child {
    vertical-align: baseline !important;
}
.table-centered td:nth-child(2){
    vertical-align: middle !important;
}
.table-centered td:nth-child(1){
    vertical-align: middle !important;
}

</style>

<main>
    <section class="pt-5 pb-5">
        <div class="container">
            
            <!-- Top Menubar -->
            {{-- @include('frontend.teacher.layout.e-mentor-common') --}}
            
            @if (Auth::user()->role === 'instructor')
                @include('frontend.teacher.layout.e-mentor-common')
            @elseif (Auth::user()->role === 'sub-instructor')
                @include('frontend.sub-ementor.layout.sub-e-mentor-common')
            @endif

            <!-- Content -->

            {{-- <div class="row mt-0 mt-md-4"> --}}

                {{-- Left menubar  --}}
                
                {{-- @include('frontend.teacher.layout.e-mentor-left-menu') --}}

                <div class="col-lg-9 col-md-8 col-12">
                    <!-- Card -->
                    <div class="card mb-4">
                        <!-- Card body -->
                        <div class="p-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-0">Withdrawn Students</h3>
                                <span>Meet students taking your course.</span>
                            </div>

                        </div>
                    </div>
                    <!-- Tab content -->

                        <!-- Tab pane -->
                            <div class="card">
                                <div class="card-header border-bottom-0">
                                    <div>
                                        <div>
                                            <form class="row gx-3">
                                                <div class="col-lg-9 col-md-7 col-12 mb-lg-0 mb-2">
                                                    <input type="search" class="form-control searchStudent" placeholder="Search by name" id="searchInput">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                  
                                </div>
                                <div class="card rounded-3">
                                    <!-- Card Header -->
                                    <div class="p-4 row">
                                        <div class="card-header p-0 col-12 col-md-12 col-lg-12">
                                            <ul class="nav nav-lb-tab border-bottom-0" id="tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active section-student-tab" data-cat="all" data-bs-toggle="pill" href="#all-student-tab" role="tab"  aria-selected="true">All</a>
                                                </li>
                                                {{-- <li class="nav-item" role="presentation">
                                                    <a class="nav-link section-student-tab" data-cat="athe" data-bs-toggle="pill" href="#all-student-tab" role="tab" aria-selected="false" tabindex="-1">ATHE</a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Table -->
                                <div class="tab-content" id="tabContent">
                                    <!-- Tab -->
        
                                    <!-- All Students Tab  -->
                                <div class="tab-pane fade active show" role="tabpanel" aria-labelledby="all-courses-tab">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-centered text-nowrap studentListRemark w-100">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Name</th>
                                                    <th>Course</th>
                                                    {{-- <th>Enrolled</th>
                                                    <th>Doc Status</th>
                                                    <th>Exam</th> --}}
                                                    {{-- @if (Auth::user()->role !== 'sub-instructor')
                                                        <th>Sub Ementor</th>
                                                    @endif --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                </div>
                                </div>
                                
                            </div>
                </div>
            {{-- </div> --}}
        </div>
    </section>
</main>

<script>
    $('.searchStudent').on('keyup', function() {
        var table = $('.studentListRemark').DataTable();
        var searchTerm = $(this).val();
        table.search(searchTerm).draw();
    });
    $(document).ready(function () {
        assignedCourseList('all');
    });
    $(".section-student-tab").on("click", function () {
        assignedCourseList($(this).data("cat"));
    });

    function assignedCourseList(action) {
        $(".dataTables_filter").css('display', 'none');
        var baseUrl = window.location.origin;
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var userRole = "{{ Auth::user()->role }}";
        $("#processingLoader").fadeIn();
        let url;
        if (userRole === 'instructor') {
            url = baseUrl + "/ementor/get-purchased-students-list/"+"withdraw";
        } else if (userRole === 'sub-instructor') {
            url = baseUrl + "/sub-ementor/get-all-students-list/";
        } else if (userRole === 'admin' || userRole === 'superadmin') {
            url = baseUrl + "/admin/get-all-students-list/";
        }
        if ($.fn.DataTable.isDataTable('.studentListRemark')) {
            $('.studentListRemark').DataTable().destroy();
        }
        $.ajax({
            url: url,
            method: 'GET',
            success: function(data) {
                $("#processingLoader").fadeOut();        
                const subEmentorsData = data.subEmentors;
                

                // Define the base columns
                let columns = [
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            i = row.row + 1;
                            return i;
                        },
                        width:'10%'
                    },
                    {
                        data: null,
                        render: function(data, type, full, row) {
                            
                            if (userRole === 'sub-instructor') {
                                data = data['user_data']
                            }
                            
                            if (data.name) {
                                var fname = data.name || '';
                                var last_name = data.last_name || '';
                                var profileUrl = baseUrl + '/ementor/students-edit/' + btoa(data.id);
                                var img = data.photo ? baseUrl + '/storage/' + data.photo : baseUrl + '/storage/studentDocs/student-profile-photo.png';
                                return (
                                    `<div class="d-flex align-items-center"><p class="d-flex align-items-center"><img src="` + img + `" alt="" class="rounded-circle avatar-md me-2">
                                    <h5 class="mb-0 color-blue" style="margin-top:-10px;" ><a href=`+profileUrl+` style="color: #a30a1b ! important;">` + fname + ` ` + last_name + `</a></h5></p></div>`
                                );
                            } else {
                                return '';
                            }
                        },
                        width:'10%'
                    },
                    {
                        data: null,
                        render: function(row) {
                            var courseTitles = [];
                            if (row.allPaidCourses && row.allPaidCourses.length > 0) {
                                row.allPaidCourses.forEach(function(course, index) {
                                    
                                    if (userRole === 'sub-instructor') {
                                        var userId = btoa(course.userId);
                                    }else{
                                        var userId = btoa(row.id);
                                    }

                                    
                                    var courseId = btoa(course.course_id);
                                    var scmId = btoa(course.scmId);

                                    if (userRole === 'instructor') {
                                        courseUrl = `/ementor/e-mentor-students-exam-details/${userId}/${courseId}/${scmId}`;
                                    } else if (userRole === 'sub-instructor') {
                                        courseUrl = `/sub-ementor/sub-e-mentor-students-exam-details/${userId}/${courseId}/${scmId}`;
                                    }
                                    courseTitles.push(`${index + 1}. <a href="${courseUrl}">${course.course_title}</a><hr style="color:#fff">`);
                                });
                            }
                            return courseTitles.join('');
                        },
                        width: '30%'
                    },
                ];

                
                // Initialize DataTable
                $(".studentListRemark").DataTable({
                    data: data.studentData,
                    columns: columns,
                    drawCallback: function(settings) {
                        // Check if there's data for the sub-mentor column
                        $(".submentor-select").each(function() {
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }






    $('#searchInput').on('input', function() {
        var table = $('.studentListRemark').DataTable();
        var searchTerm = $(this).val();
        table.search(searchTerm).draw();
    });

    $(document).on('change', '.submentor-select', function () {
        const userId = $(this).data('user-id');
        const courseId = $(this).data('course-id');
        const scmId = $(this).data('scm-id');
        const subEmentorId = $(this).val();
        
        const subEmentorName = $(this).find('option:selected').text();
        

        if (subEmentorId) {
            // swal({
            //     title: "Are you sure?",
            //     text: `Assign ${subEmentorName} as sub-mentor? This cannot be undone.`,
            //     icon: "warning",
            //     buttons: true,
            //     dangerMode: true,
            // }).then((willAssign) => {
            //     if (willAssign) {

            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            const modalData = {
                title: "Are you sure?",
                message: `Assign ${subEmentorName} as sub-mentor? This cannot be undone.`,
                icon: warningIconPath,
            }
            showModal(modalData, true);
            $("#modalCancel").on("click", function () {
                $("#customModal").hide();
            });
            $("#modalOk").on("click", function () {
                $("#customModal").hide();
                $("#processingLoader").fadeIn();
                $.ajax({
                    url: '/ementor/assign-sub-ementor',
                    method: 'POST',
                    data: {
                        studentId: userId,
                        courseId: btoa(courseId),
                        scmId: btoa(scmId),
                        subEmentorId: btoa(subEmentorId),
                    },
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    success: function(response) {
                        // swal("Success!", "Sub-mentor assigned.", "success");
                        $("#processingLoader").fadeOut();
                        const modalData = {
                            title: "Success!",
                            message: `Sub-mentor assigned.`,
                            icon: successIconPath,
                        }
                        showModal(modalData);
                    },
                    error: function(error) {
                        // swal("Error!", "There was an issue assigning the sub-mentor. Please try again.", "error");
                        const modalData = {
                            title: "Error!",
                            message: `There was an issue assigning the sub-mentor. Please try again.`,
                            icon: errorIconPath,
                        }
                        showModal(modalData);
                    }
                });
            });

        }
    });

</script>
@endsection