@extends('frontend.master')
@section('content')

<style>
    .sidenav.navbar .navbar-nav .e-men-5 > .nav-link {
    background-color: var(--gk-gray-200);
}
.dataTables_filter{
    display: none;
}
</style>

<main>
    <section class="pt-5 pb-5">
        <div class="container">
            
            <!-- Top Menubar -->
            @include('frontend.institute.layout.institute-common')

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
                                <h3 class="mb-0">Teachers</h3>
                                {{-- <span>Meet teachers taking your course.</span> --}}
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
                                                <div class="col-lg-12 col-md-7 col-12 mb-lg-0 mb-2">
                                                    <input type="search" class="form-control" id="searchInput" placeholder="Search by name">
                                                </div>
                                                {{-- <div class="col-lg-3 col-md-5 col-12">
                                                    <select class="form-select">
                                                        <option value="">Date Created</option>
                                                        <option value="Newest">Newest</option>
                                                        <option value="High Earned">Award</option>
                                                        <option value="High Earned">Certificate</option>
                                                        <option value="High Earned">Diploma</option>
                                                        <option value="High Earned">Masters</option>
                                                    </select>
                                                </div> --}}
                                            </form>
                                        </div>
                                    </div>
                                  
                                </div>
                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-centered text-nowrap instituteTeacherList">
                                         <thead class="table-light">
                                            <tr>
                                                <th>Sr No.</th>
                                                <th>Teacher Name</th>
                                                <th>Email </th>
                                                <th>Designation</th>
                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        {{-- <tbody>
                                            <tr>
                                                <td>
                                                    <a href="student-details" class="d-flex align-items-center">
                                                        <img src="{{ asset('frontend/images/avatar/avatar-3.jpg')}}" alt="" class="rounded-circle avatar-md me-2" >
                                                        <h5 class="mb-0 color-blue">Guy Hawkins</h5>
                                                    </a>
                                                </td>
                                                <td>Award in Recruitment and Employee Selection
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2"><span>80%</span></div>
                                                        <div class="progress w-100" style="height: 6px">
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>3/12/2020</td>
                                                <td><span class="badge bg-success">Pass</span></td>

 
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="student-details" class="d-flex align-items-center">
                                                        <img src="{{ asset('frontend/images/avatar/avatar-2.jpg')}}" alt="" class="rounded-circle avatar-md me-2" >
                                                        <h5 class="mb-0 color-blue">Dianna Smiley</h5>
                                                    </a>
                                                </td>
                                                <td>Award in Recruitment and Employee Selection
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2"><span>76%</span></div>
                                                        <div class="progress w-100" style="height: 6px">
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 76%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>3/12/2020</td>
                                                 <td><span class="badge bg-danger">Fail</span></td>

 
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="student-details" class="d-flex align-items-center">
                                                        <img src="{{ asset('frontend/images/avatar/avatar-5.jpg')}}" alt="" class="rounded-circle avatar-md me-2" >
                                                        <h5 class="mb-0 color-blue">Guy Hawkins</h5>
                                                    </a>
                                                </td>
                                                <td>Award in Recruitment and Employee Selection
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2"><span>11%</span></div>
                                                        <div class="progress w-100" style="height: 6px">
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 11%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>3/12/2020</td>
                                                 <td><span class="badge bg-success">Pass</span></td>

 
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="student-details" class="d-flex align-items-center">
                                                        <img src="{{ asset('frontend/images/avatar/avatar-6.jpg')}}" alt="" class="rounded-circle avatar-md me-2" >
                                                        <h5 class="mb-0 color-blue">Guy Hawkins</h5>
                                                    </a>
                                                </td>
                                                <td>Award in Recruitment and Employee Selection
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2"><span>45%</span></div>
                                                        <div class="progress w-100" style="height: 6px">
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 45%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>3/12/2020</td>
                                                 <td><span class="badge bg-success">Pass</span></td>

 
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a href="student-details" class="d-flex align-items-center">
                                                        <img src="{{ asset('frontend/images/avatar/avatar-7.jpg')}}" alt="" class="rounded-circle avatar-md me-2" >
                                                        <h5 class="mb-0 color-blue">Guy Hawkins</h5>
                                                    </a>
                                                </td>
                                                <td>Award in Recruitment and Employee Selection
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2"><span>60%</span></div>
                                                        <div class="progress w-100" style="height: 6px">
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 60%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>3/12/2020</td>
                                                 <td><span class="badge bg-warning">Pending</span></td>

 
                                            </tr>

                                        </tbody> --}}
                                    </table>
                                </div>
                                {{-- <div class="pt-2 pb-4">
                                    <!-- Pagination -->
                                    <nav>
                                        <ul class="pagination justify-content-center mb-0">
                                            <li class="page-item disabled">
                                                <a class="page-link mx-1 rounded" href="#">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" >
                                                    </svg>
                                                </a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link mx-1 rounded" href="#">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link mx-1 rounded" href="#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link mx-1 rounded" href="#">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link mx-1 rounded" href="#">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path>
                                                    </svg>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div> --}}
                            </div>
                </div>
            {{-- </div> --}}
        </div>
    </section>
</main>
<script>
    $('#searchInput').on('keyup', function() {
        var table = $('.instituteTeacherList').DataTable();
        var searchTerm = $(this).val();
        table.search(searchTerm).draw();
    });
    $(document).ready(function () {
        assignedCourseList();
        handleSearchInput('searchInput', assignedCourseList);
    });

    function assignedCourseList(){
        $(".dataTables_filter").css('display','none');
        var baseUrl = window.location.origin;  
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        var userRole = "{{ Auth::user()->role }}";
        
        let url;
        // if (userRole === 'institute') {
        url = baseUrl + "/institute/get-institute-teachers/0";
       
        $.ajax({
            url: url,
            method: 'GET',
            success: function (data) {
                $('.instituteTeacherList').DataTable().destroy();
                // $(".loader").addClass("d-none");
                $(".instituteTeacherList").DataTable({
                data: data, // Pass
    
                    columns: [
                        {
                            data: null,
                            render: function (data, type, full, row) {
                                i = row.row + 1;
                                return i;
                            },
                        },
                        {
                            data: null,
                            render: function (data, type, full, row) {
                                // return row.name+' '+row.last_name;
                                
                                
                                if(data.lactrure_name != ''){
                                    if(data.lactrure_name != ''){
    
                                        var fname = data.lactrure_name;
                                        var status = data.status;
                                        if(status == '0'){
                                            status_but = '<span class="badge-dot bg-success me-1 d-inline-block align-middle"></span>';
                                        }else{
                                            status_but = '<span class="badge-dot bg-danger me-1 d-inline-block align-middle"></span>';
                                        }
                                    }else{
                                        var fname = '';
                                        var status_but ='';
    
                                    }  
                                    var img = data.image ? baseUrl + '/storage/' + data.image : baseUrl + '/storage/teacherDocs/teacher-profile-photo.jpg';

                                    return (
                                        `<a href="#" class="d-flex align-items-center">
                                            <img src="`+img+`" alt="" class="rounded-circle avatar-md me-2">
                                            <h5 class="mb-0 color-blue">`+fname+` `+status_but+`</h5>
                                        </a>`

                                    );
                                }else{
                                    return '';
                                }
                            },
                        },
                        {
                            data: null,
                            render: function(row) {
                                if(row.email != ''){
                                    if(row.email != ''){
                                        var email = row.email;
                                    }else{
                                        var email = '';
                                    }                                    
                                    return email;
                                }else{
                                    return '';
                                }

                            },
                            width: '30%',
                        },
                        {
                            data: null,
                            render: function(row) {
                                if(row.designation != ''){
                                    if(row.designation != ''){
                                        var designation = row.designation;
                                    }else{
                                        var designation = '';
                                    }                                    
                                    return designation;
                                }else{
                                    return '';
                                }


                            },
                            width: '30%',
                        },
                        {
                            data: null,
                            render: function(row) {
                                                            
                                if(row.id != ''){

                                    var adminId = row.id != '' ? row.id : ''; 
                                    var editUrl ="institute-teacher-view/"+btoa(adminId);
 
                                    var Action = '<div class="hstack gap-3"><a href="' + editUrl + '" data-bs-toggle="tooltip" data-placement="top" title="Edit"><i class="fe fe-edit"></i></a><a href="#" data-bs-toggle="tooltip" data-placement="top" title="Delete" class="statusTeacherActive" data-status="'+btoa('delete')+'" data-teacher_id="'+btoa(row.id)+'"><i class="fe fe-trash"></i></a><span class="dropdown dropstart"><a class="btn-icon btn btn-ghost btn-sm rounded-circle" href="#" role="button" data-bs-toggle="dropdown" data-bs-offset="-20,20" aria-expanded="false"><i class="fe fe-more-vertical"></i></a> <span class="dropdown-menu"><span class="dropdown-header">Settings</span>';

                                    if(row.status == '1'){ 
                                        Action += '<a class="dropdown-item statusTeacherActive" href="#" data-status="'+btoa('teacher_status_active')+'" data-teacher_id="'+btoa(adminId)+'" >  <span class="badge-dot bg-success me-1 d-inline-block align-middle"></span>Active</a>'; 
                                    }
                                    if(row.status == '0'){ 
                                        Action += '<a class="dropdown-item statusTeacherActive" href="#" data-status="'+btoa('teacher_status_inactive')+'" data-teacher_id="'+btoa(adminId)+'"><span class="badge-dot bg-danger me-1 d-inline-block align-middle"></span>Inactive </a>';
                                    }
                                }                                   
                                return Action;
                               


                            },
                            width: '30%',
                        },
                        
                       
                    ],            
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
</script>

@endsection