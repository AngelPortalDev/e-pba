<!-- Header import -->
@extends('admin.layouts.main')
 @section('content')



    <!-- Container fluid -->
    <section class="container-fluid p-4">
        <div class="row justify-content-between ">
            <!-- Page Header -->
            <div class="col-lg-4 col-12">
                <div class=" pb-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="mb-2 mb-lg-0">
                        <h1 class="mb-1 h2 fw-bold">
                           Course Category
                            <span class="fs-5 counts"></span>
                        </h1>
                        <!-- Breadcrumb  -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{route('dashboard')}}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Course Category</a></li>
                                <!-- <li class="breadcrumb-item active" aria-current="page">All Admin</li> -->
                            </ol>
                        </nav>
                    </div>
                    <div class="nav btn-group" role="tablist">


                    </div>
                </div>
            </div>
            <!-- <form class="d-flex align-items-center col-12 col-lg-3"> -->
                <div class="col-lg-8 col-12 text-end pt-2 mb-0 mb-sm-3">
                    <div class="d-sm-flex justify-content-sm-end">
                          <!-- Button With Icon -->
                          <div class="d-grid d-sm-block ms-2 d-md-0 mt-2 mt-md-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                                Create <i class="fe fe-plus ms-1"></i>
                            </button>
                          </div>
                          {{--<div class="d-grid d-sm-block ms-2 d-md-0 mt-2 mt-md-0">
                            <button type="button" class="btn btn-outline-primary deleteEntries">
                                Delete <i class="fe fe-trash ms-1"></i>
                            </button>
                          </div>
                           <div class="d-grid d-sm-block ms-2 d-md-0 mt-2 mt-md-0">
                            <button type="button" class="btn btn-outline-primary ">
                                Import <i class="fe fe-download ms-1"></i>
                            </button>
                          </div>
                          <div class="d-grid d-sm-block ms-2 d-md-0 mt-2 mt-md-0">
                            <button type="button" class="btn btn-outline-primary ">
                                Export <i class="fe fe-upload ms-1"></i>
                            </button>
                          </div> --}}
                    </div>
                </div>


        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 mt-3 mt-md-0">
                <!-- Card -->
                <div class="card rounded-3">
                    <!-- Card Header -->
                    <div class="p-4 row">
                        <div class="card-header p-0 col-12 col-md-7 col-lg-7">
                            <ul class="nav nav-lb-tab border-bottom-0" id="tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    {{-- <a class="nav-link active" onclick="sectionList('')" >All</a> --}}
                                    <a class="nav-link active section-tab" data-cat="Yes" data-bs-toggle="pill" href="#all-sections" role="tab"  aria-selected="true">All</a>

                                </li>
                                {{-- <li class="nav-item" role="presentation"> --}}
                                    {{-- <a class="nav-link"  onclick="sectionList('Yes')" >Active</a> --}}
                                    {{-- <a class="nav-link section-tab" data-cat="Yes" data-bs-toggle="pill" href="#all-sections" role="tab"  aria-selected="true">Active</a>

                                </li> --}}
                                {{-- <li class="nav-item" role="presentation"> --}}
                                    {{-- <a class="nav-link" onclick="sectionList('No')"  >Inactive</a> --}}
                                    {{-- <a class="nav-link section-tab" data-cat="No" data-bs-toggle="pill" href="#all-sections" role="tab"  aria-selected="true">Inactive</a> --}}
                                {{-- </li> --}}

                                <li class="nav-item" role="presentation">
                                    {{-- <a class="nav-link"  onclick="sectionList('deleted')" >Deleted</a> --}}
                                    {{-- <a class="nav-link section-tab" data-cat="deleted" data-bs-toggle="pill" href="#all-sections" role="tab"  aria-selected="true">Deleted</a> --}}

                                </li>
                            </ul>
                        </div>


                        <!-- Form -->


                        <div class="d-flex align-items-center col-12 col-md-5 col-lg-5 justify-content-end border-bottom">
                            <div class="row justify-content-end">
                                <form class="d-flex align-items-center col-lg-8 col-md-12 col-sm-12 mt-2 mt-md-0 mb-2 mb-md-0 w-100">
                                    <span class="position-absolute ps-3 search-icon">
                                        <i class="fe fe-search"></i>
                                    </span>
                                    <input type="search" class="form-control ps-6 searchSection" id="searchInput" placeholder="Search Here">
                                </form>


                                <!-- input -->
                                {{-- <div class="col-lg-6 col-md-12 col-sm-12 mt-2 mt-lg-0 mb-2 mb-md-0">
                                    <!-- form select -->
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected="">Filter</option>
                                        <option value="Newest">Newest</option>
                                        <option value="Price: High-Low">Active</option>
                                        <option value="Price: Low-High">Inactive</option>
                                        <option value="Price: Low-High">Delected</option>
                                        <option value="Price: Low-High">Award</option>
                                        <option value="Price: Low-High">Certificate</option>
                                        <option value="Price: Low-High">Diploma</option>
                                        <option value="Price: Low-High">Masters</option>
                                    </select>
                                </div> --}}
                            </div>
                        </div>
                    </div>



                    <div>
                        <div class="tab-content" id="tabContent">
                            <!-- All Students Tab  -->
                            <div class="tab-pane fade active show" id="all-students" role="tabpanel" aria-labelledby="all-students-tab">
                                <div class="table-responsive">
                                    <!-- Table -->
                                    <!-- <table class="table mb-0 text-nowrap table-centered table-hover table-with-checkbox table-centered table-hover"> -->
                                        <table class="table mb-0 text-nowrap table-hover table-centered table-with-checkbox table-centered table-hover section_list w-100">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="checkAll">
                                                            <label class="form-check-label" for="checkAll"></label>
                                                        </div>
                                                    </th>
                                                    <th>Sr. No.</th>
                                                    <th>Category Name</th>
                                                    {{-- <th>Assigned Course Name</th> --}}
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card Footer -->
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>


    </section>
</main>


<!-- Create Admin Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Create New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row needs-validation"  id="sectionForm" novalidate>
                    <input type="hidden" name="category_id" id="category_id">

                    <div class="mb-2 col-12">
                        <label for="SectionTitle" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="section_title" id="section_title" placeholder="Category Name" required>
                        <small>Category Name must be between 5 to 250 characters.</small>
                        <div class="invalid-feedback" id="section_title_error">Please enter category name</div>
                    </div>

                    <div class="col-12 d-flex justify-content-end pt-2">
                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="addCategory">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>


$(document).ready(function () {
    sectionList();
    handleSearchInput('searchInput', sectionList);
});
$('#checkAll').click(function (e) {
    $('.section_list tbody :checkbox').prop('checked', $(this).is(':checked'));
    e.stopImmediatePropagation();
});
function sectionList(action) {
    $("#processingLoader").fadeIn();
    $(".dataTables_filter").css("display", "none");
    var sectionTable = $(".section_list").DataTable();
    var baseUrl = window.location.origin + "/";
    $.ajax({
        url: baseUrl + "admin/course-category-get-data/" + action,
        method: "GET",
        success: function (data) {
            $("#processingLoader").fadeOut();
            $('.section_list').DataTable().clear().destroy();
            $(".counts").html("(" + data.length + ")");
            $(".section_list").DataTable({
                data: data,
                columns: [
                    {
                        data: "id",
                        render: function (data, type, full, meta) {
                            var CourseId = btoa(data);
                            var isChecked = full.checked ? "checked" : "";
                            var section_type = btoa("section");

                            return (
                                '<form class="actionData"><input type="hidden" class="form-check-input action" name="action" value="'+section_type+'"><input type="checkbox" data-delete_id="'+CourseId+'" class="form-check-input checkbox sub_chk " name="id[]" value="' +
                                CourseId +
                                '" ' +
                                isChecked +
                                "></form>"
                            );
                        },
                        width:"0%"
                    },
                    {
                        data: null,
                        render: function (data, type, full, row) {
                            i = row.row + 1;
                            return i;
                        },
                         width:"10%"
                    },
                    {
                        data: null,
                        render: function (data,row) {
                            var name = '';
                            var action = btoa("edit");
                            var CategoryID = btoa(data.id);
                            if(data.category_name){
                                name = data.category_name;
                            }
                            return (
                               "<div class='d-flex align-items-center'>" +
                                "<div></div>" +
                                "<div class='text-wrap-title'>" +
                                "<h4 class='mb-1 text-primary-hover'>" +
                                '<a href="#" class="editSection" data-id="'+CategoryID+'" data-name="'+name+'" data-bs-toggle="tooltip" title="Edit">'+ name +'</a>'+
                                // "<a href='" + editUrl + "' class='text-inherit'>" + name + "</a>" +
                                "</h4>" +
                                "</div>" +
                                "</div>"
                            );
                        },
                        width:"45%"
                    },

                    {
                        data: null,
                        render: function (row) {
                            var status = row.is_deleted;
                            if (status === "No") {
                                return "<span class='badge text-success bg-light-success'>Active</span>";
                            }
                            if (status === "Yes") {
                                return '<span class="badge text-danger bg-light-danger">Inactive</span>';
                            }
                            if(status == ''){
                                return '';
                            }

                        },
                        width:"20%"
                    },

                    {
                        data: null,
                        render: function (row) {
                            var CategoryID = btoa(row.id);
                            var action = btoa("edit");
                            var status = row.is_deleted === "Yes" ? false : true;


                            return (
                                '<div class="hstack gap-3"><a href="#" class="editSection" data-id="'+CategoryID+'" data-name="'+row.category_name+'" data-bs-toggle="tooltip" title="Edit"><i class="fe fe-edit"></i></a>'+
                                    '<a href="#" class="deleteCourseCategory" data-id="'+CategoryID+'"><i class="fe fe-trash"></i></a>'
                            );
                        },
                        width:"20%"
                    },
                ],
            });
        },
        error: function (xhr, status, error) {
            $("#processingLoader").fadeOut();
            console.error(xhr);
        },
    });

    $(".section-tab").on("click", function (event) {
        event.preventDefault();
        sectionList($(this).data("cat"));
    });
}

$('.searchSection').on('keyup', function() {
    var table = $('.section_list').DataTable();
    var searchTerm = $(this).val();
    table.search(searchTerm).draw();
});

$(document).on("click", ".editSection", function (e) {
    e.preventDefault();

    var id = $(this).data("id");
    var name = $(this).data("name");

    $("#category_id").val(id);
    $("#section_title").val(name);
    $("#taskModalLabel").text("Edit Category");
    $("#addCategory").text("Update");
    $("#addSectionModal").modal("show");
});

$('#addSectionModal').on('hidden.bs.modal', function () {
    $("#sectionForm")[0].reset();
    $(".errors").remove();
    $("#section_id").val("");
    $("#taskModalLabel").text("Create New Category");
    $("#addCategory").text("Add");
});
</script>
@endsection
