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
                        <h1 class="text-white mb-1 display-4 color-green">{{__('static.level_4_name')}}</h1>
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
                $athelevel4 =
                    getData('course_master',['course_title','id','selling_price','ects','course_final_price','course_old_price','course_thumbnail_file','status','temp_count','category_id'],['category_id'=>'7',['status','!=','2']],'',DB::raw('IFNULL(published_on, "NULL")'),'asc');
                $allowedRoles = ['institute', 'superadmin', 'admin', 'instructor', 'sub-instructor'];
                $categoryAthe = __('footer.line_25');
                @endphp
                @include('frontend.full-courses', ['courses' => $athelevel4,'allowedRoles'=>$allowedRoles,'Category'=>$categoryAthe]);
            </div>
        </div>
    </section>
</main>

@endsection
