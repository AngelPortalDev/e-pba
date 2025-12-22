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
                        <h1 class="text-white mb-1 display-4 color-green">@if  (app()->getLocale() == 'es') {{__('static.awardcourse')}} @else {{__('static.Award')}} {{__('static.course')}} @endif</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="course-category-tabs-main pt-5 pb-lg-2 pb-3">
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
                $award = getData('course_master',['course_title','id','selling_price','ects','course_final_price','course_old_price','course_thumbnail_file','status','published_on','temp_count','category_id'],['category_id'=>'1', [DB::raw('award_dba',"NULL")]],'',DB::raw('IFNULL(published_on, "NULL")'),'asc');
                $order = 'asc';
                $awardSorted = $award->sort(function ($a, $b) use ($order) {
                    // Check if published_on property exists and handle cases where it's null
                    $aPublishedOn = isset($a->published_on) ? strtotime($a->published_on) : null;
                    $bPublishedOn = isset($b->published_on) ? strtotime($b->published_on) : null;

                    // Ensure nulls are always at the end for published_on
                    if ($aPublishedOn === null && $bPublishedOn === null) {
                        return $a->id <=> $b->id;
                    }
                    if ($aPublishedOn === null) {
                        return 1; // Place null last
                    }
                    if ($bPublishedOn === null) {
                        return -1; // Place null last
                    }
                    $result = $order === 'asc'
                        ? $aPublishedOn <=> $bPublishedOn
                        : $bPublishedOn <=> $aPublishedOn;
                    return $result === 0 ? $a->id <=> $b->id : $result;
                });
                // Convert the sorted collection to an array if needed
                $awardSortedArray = $awardSorted->values()->all();
                $allowedRoles = ['institute', 'superadmin', 'admin', 'instructor', 'sub-instructor'];
                $categoryAward = __('static.Award');
                @endphp

                @include('frontend.full-courses', ['courses' => $awardSortedArray,'allowedRoles'=>$allowedRoles,'Category'=>$categoryAward])

                {{-- <div class="col-md-3">
                    <div class="card card-hover">
                        <a href="#"><img
                                src="{{ asset('frontend/images/course/award-training-development.png') }}"
                                alt="course" class="card-img-top"></a>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info-soft co-category">Award</span>
                                <span class="badge bg-success-soft co-etcs">10 ECTS</span>
                            </div>
                            <h4 class="mb-2 text-truncate-line-2 course-title"><a
                                    href="#" class="text-inherit">Award in Training and
                                    Development </a></h4>

                            <div class="lh-1 mt-3">

                                <span class="fs-6">
                                    <i class="fe fe-user color-blue"></i>
                                    1200 Enrolled
                                </span>

                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer">
                            <div class="row align-items-center g-0">
                                <div class="col course-price-flex">
                                    <h5 class="mb-0 course-price">€1500</h5>
                                    <h5 class="old-price">€2300 </h5>
                                </div>

                                <div class="col-auto">
                                    <a href="#" class="text-inherit">
                                        <i
                                            class="fe fe-shopping-cart text-primary align-middle me-2"></i>
                                        <a href="#" class="buy-now">Buy Now</a>
                                    </a>
                                </div>
                            </div>

                            <div class="col-auto course-saved-btn">
                                <a href="#" class="text-reset bookmark">
                                    <i class="bi bi-suit-heart fs-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="col-md-3">
                    <div class="card card-hover">
                        <a href="#"><img
                                src="{{ asset('frontend/images/course/award-employee-and-labor-relation.png') }}"
                                alt="course" class="card-img-top"></a>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info-soft co-category">Award</span>
                                <span class="badge bg-success-soft co-etcs">4 ECTS</span>
                            </div>
                            <h4 class="mb-2 text-truncate-line-2 course-title"><a
                                    href="#" class="text-inherit">Award in Employee and
                                    Labour Relations</a></h4>

                            <div class="lh-1 mt-3">

                                <span class="fs-6">
                                    <i class="fe fe-user color-blue"></i>
                                    1200 Enrolled
                                </span>

                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer">
                            <div class="row align-items-center g-0">
                                <div class="col course-price-flex">
                                    <h5 class="mb-0 course-price">€1500</h5>
                                    <h5 class="old-price">€2300 </h5>
                                </div>

                                <div class="col-auto">
                                    <a href="#" class="text-inherit">
                                        <i
                                            class="fe fe-shopping-cart text-primary align-middle me-2"></i>
                                        <a href="#" class="buy-now">Buy Now</a>
                                    </a>
                                </div>
                            </div>

                            <div class="col-auto course-saved-btn">
                                <a href="#" class="text-reset bookmark">
                                    <i class="bi bi-suit-heart fs-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card card-hover">
                        <a href="#"><img
                                src="{{ asset('frontend/images/course/award-performance-management.png') }}"
                                alt="course" class="card-img-top"></a>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info-soft co-category">Award</span>
                                <span class="badge bg-success-soft co-etcs">7 ECTS</span>
                            </div>
                            <h4 class="mb-2 text-truncate-line-2 course-title"><a
                                    href="#" class="text-inherit">Award in Performance
                                    Management and Compensation</a></h4>

                            <div class="lh-1 mt-3">

                                <span class="fs-6">
                                    <i class="fe fe-user color-blue"></i>
                                    1200 Enrolled
                                </span>

                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer">
                            <div class="row align-items-center g-0">
                                <div class="col course-price-flex">
                                    <h5 class="mb-0 course-price">€1500</h5>
                                    <h5 class="old-price">€2300 </h5>
                                </div>

                                <div class="col-auto">
                                    <a href="#" class="text-inherit">
                                        <i
                                            class="fe fe-shopping-cart text-primary align-middle me-2"></i>
                                        <a href="#" class="buy-now">Buy Now</a>
                                    </a>
                                </div>
                            </div>

                            <div class="col-auto course-saved-btn">
                                <a href="#" class="text-reset bookmark">
                                    <i class="bi bi-suit-heart fs-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card card-hover">
                        <a href="#"><img
                                src="{{ asset('frontend/images/course/award-professional development.png') }}"
                                alt="course" class="card-img-top"></a>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info-soft co-category">Award</span>
                                <span class="badge bg-success-soft co-etcs">12 ECTS</span>
                            </div>
                            <h4 class="mb-2 text-truncate-line-2 course-title"><a
                                    href="#" class="text-inherit">Award in Professional
                                    Development</a></h4>

                            <div class="lh-1 mt-3">

                                <span class="fs-6">
                                    <i class="fe fe-user color-blue"></i>
                                    1200 Enrolled
                                </span>

                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer">
                            <div class="row align-items-center g-0">
                                <div class="col course-price-flex">
                                    <h5 class="mb-0 course-price">€1500</h5>
                                    <h5 class="old-price">€2300 </h5>
                                </div>

                                <div class="col-auto">
                                    <a href="#" class="text-inherit">
                                        <i
                                            class="fe fe-shopping-cart text-primary align-middle me-2"></i>
                                        <a href="#" class="buy-now">Buy Now</a>
                                    </a>
                                </div>
                            </div>

                            <div class="col-auto course-saved-btn">
                                <a href="#" class="text-reset bookmark">
                                    <i class="bi bi-suit-heart fs-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card card-hover">
                        <a href="#"><img
                                src="{{ asset('frontend/images/course/award-international-organisation-management.png') }}"
                                alt="course" class="card-img-top"></a>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info-soft co-category">Award</span>
                                <span class="badge bg-success-soft co-etcs">10 ECTS</span>
                            </div>
                            <h4 class="mb-2 text-truncate-line-2 course-title"><a
                                    href="#" class="text-inherit">Award in
                                    International Organisational Management and Development</a></h4>

                            <div class="lh-1 mt-3">

                                <span class="fs-6">
                                    <i class="fe fe-user color-blue"></i>
                                    1200 Enrolled
                                </span>

                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer">
                            <div class="row align-items-center g-0">
                                <div class="col course-price-flex">
                                    <h5 class="mb-0 course-price">€1500</h5>
                                    <h5 class="old-price">€2300 </h5>
                                </div>

                                <div class="col-auto">
                                    <a href="#" class="text-inherit">
                                        <i
                                            class="fe fe-shopping-cart text-primary align-middle me-2"></i>
                                        <a href="#" class="buy-now">Buy Now</a>
                                    </a>
                                </div>
                            </div>

                            <div class="col-auto course-saved-btn">
                                <a href="#" class="text-reset bookmark">
                                    <i class="bi bi-suit-heart fs-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card card-hover">
                        <a href="#"><img
                                src="{{ asset('frontend/images/course/award-global-business-strategy.png') }}"
                                alt="course" class="card-img-top"></a>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info-soft co-category">Award</span>
                                <span class="badge bg-success-soft co-etcs">10 ECTS</span>
                            </div>
                            <h4 class="mb-2 text-truncate-line-2 course-title"><a
                                    href="#" class="text-inherit">Award in Global
                                    Business Strategy (Human Resources Management)</a></h4>

                            <div class="lh-1 mt-3">

                                <span class="fs-6">
                                    <i class="fe fe-user color-blue"></i>
                                    1200 Enrolled
                                </span>

                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer">
                            <div class="row align-items-center g-0">
                                <div class="col course-price-flex">
                                    <h5 class="mb-0 course-price">€1500</h5>
                                    <h5 class="old-price">€2300 </h5>
                                </div>

                                <div class="col-auto">
                                    <a href="#" class="text-inherit">
                                        <i
                                            class="fe fe-shopping-cart text-primary align-middle me-2"></i>
                                        <a href="#" class="buy-now">Buy Now</a>
                                    </a>
                                </div>
                            </div>

                            <div class="col-auto course-saved-btn">
                                <a href="#" class="text-reset bookmark">
                                    <i class="bi bi-suit-heart fs-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="card card-hover">
                        <a href="#"><img
                                src="{{ asset('frontend/images/course/award-public-speaking.png') }}"
                                alt="course" class="card-img-top"></a>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info-soft co-category">Award</span>
                                <span class="badge bg-success-soft co-etcs">7 ECTS</span>
                            </div>
                            <h4 class="mb-2 text-truncate-line-2 course-title"><a
                                    href="#" class="text-inherit">Award in Public
                                    Speaking and Presentation Skills (Human Resources
                                    Management)</a></h4>

                            <div class="lh-1 mt-3">

                                <span class="fs-6">
                                    <i class="fe fe-user color-blue"></i>
                                    1200 Enrolled
                                </span>

                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer">
                            <div class="row align-items-center g-0">
                                <div class="col course-price-flex">
                                    <h5 class="mb-0 course-price">€1500</h5>
                                    <h5 class="old-price">€2300 </h5>
                                </div>

                                <div class="col-auto">
                                    <a href="#" class="text-inherit">
                                        <i
                                            class="fe fe-shopping-cart text-primary align-middle me-2"></i>
                                        <a href="#" class="buy-now">Buy Now</a>
                                    </a>
                                </div>
                            </div>

                            <div class="col-auto course-saved-btn">
                                <a href="#" class="text-reset bookmark">
                                    <i class="bi bi-suit-heart fs-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
     --}}



            </div>



        </div>


    </section>
</main>

@endsection
