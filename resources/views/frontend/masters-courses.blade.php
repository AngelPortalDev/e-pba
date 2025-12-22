@extends('frontend.master') @section('content')

<style>
    .sidenav.navbar .navbar-nav .sp-1 > .nav-link {
        color: #2b3990 !important;
        background-color: rgb(235 233 255);
    }
    .buy-now {
        background: none;
        border: none;
        color: blue;
        cursor: pointer;
    }
    .buy-now:focus {
        outline: none;
    }
    .buy-now:active {
        color:rgb(12, 34, 136);
    }
</style>

<main>
    <section class="py-4 py-lg-6 bg-primary">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div>
                        <h1 class="text-white mb-1 display-4 color-green"> {{__('static.Masters')}} {{__('static.course')}}</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="course-category-tabs-main pt-5 pb-3 pb-lg-2">
        <!-- row -->
        <div class="container mb-lg-8">
            <div class="row">
                <!-- col -->
                <div class="col-12">
                    {{-- <div class="mb-4">
                        <p>
                            Explore our most popular programs, get job-ready for an in-demand career.
                        </p>
                    </div> --}}
                </div>
            </div>
            <div class="row row-gap-3">

                @php
                $master =
                getData('course_master',['course_title','id','selling_price','ects','course_final_price','course_old_price','course_thumbnail_file','status','temp_count','category_id'],['category_id'=>'4'],'',DB::raw('IFNULL(published_on, "NULL")'),'desc');
                $allowedRoles = ['institute', 'superadmin', 'admin', 'instructor', 'sub-instructor'];
                $categoryMaster = __('static.Masters');
                @endphp
                @include('frontend.full-courses', ['courses' => $master,'allowedRoles'=>$allowedRoles,'Category'=>$categoryMaster])

                {{-- <div class="col-md-4 col-sm-6 col-lg-3">
                    <div class="card card-hover">
                        <a href="#"><img src="{{ asset('frontend/images/course/masters-human-resource-management.png')}}" alt="course" class="card-img-top"></a>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info-soft co-category">Masters</span>
                                <span class="badge bg-success-soft co-etcs">90 ECTS</span>
                            </div>
                            <h4 class="mb-2 text-truncate-line-2 course-title"><a href="#" class="text-inherit">Masters of Arts in Human Resource Management</a></h4> --}}

                            {{-- <div class="lh-1 mt-3">

                            <span class="fs-6">
                                <i class="fe fe-user color-blue"></i>
                                1200 Enrolled
                            </span>

                            </div> --}}
                        {{-- </div> --}}
                        <!-- Card Footer -->
                        {{-- <div class="card-footer footerResponsive">
                            <div class="row align-items-center g-0">
                                <div class="col course-price-flex">
                                    <h5 class="mb-0 course-price">€500</h5>
                                    <h5 class="old-price">€1000 </h5>
                                </div>

                                <div class="col-auto">
                                    <a href="#" class="text-inherit">
                                        <i class="fe fe-shopping-cart text-primary align-middle me-2"></i>
                                        </a><a href="#" class="buy-now">Buy Now</a>

                                </div>
                            </div>
                            <div class="col-auto course-saved-btn">
                                <a href="#" class="text-reset bookmark">
                                    <i class="bi bi-suit-heart fs-4"></i>
                                </a>
                            </div>

                        </div> --}}
                    {{-- </div> --}}
                {{-- </div> --}}
            </div>
        </div>


    </section>
</main>

@endsection
