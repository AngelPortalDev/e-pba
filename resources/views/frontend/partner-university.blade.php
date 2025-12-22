@extends('frontend.master')
@section('content')

<style>
    .card:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease-in-out;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .card-img-top {
        /* height: 180px;
        object-fit: contain;
        margin-top: 10px;
        border-bottom: 0.5px solid lightgray; */
        height: 180px;
        object-fit: contain;
        margin-top: 10px;
        border-bottom: 0.5px solid lightgray;
        transition: transform 0.3s ease-in-out;
    }
    .card-body{
        padding: 20px;
        height: 130px;
    }
    .card-title{
        color: #a30a1b;
        text-align: left;
        font-size: 17px;
        line-height: 22px;
    }
    .locationtitle{
        text-align: left;
    }
    .card {
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border-radius: 8px;
    }
        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #a30a1b;
            transform: scaleY(0);
            transform-origin: top;
            transition: transform 0.2s ease-in-out;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .card:hover  {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card:hover::before {
            transform: scaleY(1);
        }
        .pagination nav > div:first-child {
            display: none;
        }
        [aria-current="page"] > span {
            background-color: #a30a1b !important; 
            color: white !important;
            font-weight: bold;
            border-color: #a30a1b !important;    
        }     
</style>

        <!-- Our Partners -->

        <section class="py-3">
            <div class="container my-lg-4">
                <div class="row justify-content-center">
                    <!-- caption -->
                    <div class="col-lg-8 col-md-12 col-12">
                        <div class="mb-8 text-center">
                            {{-- <h2 class="mb-2 display-4 fw-bold color-blue">Our Partner Colleges</h2> --}}
                            <h2 class="mb-2 display-4 fw-bold color-blue">{{__('static.partneruniversity.title')}}</h2>

                            {{-- <p class="lead mb-0">Explore our network of esteemed partner colleges, dedicated to providing top-tier education, expert insights, and transformative learning experiences.</p> --}}
                            <p class="lead mb-0">{{__('static.partneruniversity.subtitle')}}</p>

                        </div>
                    </div>
                </div>

                {{-- @php
                    $institutes = DB::table('institute_profile_master')
                        ->join('users', 'institute_profile_master.institute_id', '=', 'users.id')
                        ->where('institute_profile_master.is_approved', 1)
                        ->where('users.is_active', 'Active')
                        ->where('users.is_deleted', 'No')
                        ->select('institute_profile_master.*', 'users.name', 'users.last_name', 'users.photo')
                        ->paginate(3);

                @endphp --}}

                <!-- row -->
                <div class="row justify-content-center">
                
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            <!-- Card Template -->

                            @if(count($institutes) > 0)
                                @foreach($institutes as $institute)
                                 @php
                                        $translatedName = getTranslatedInstituteField($institute->institute_id, 'name', $institute->name);
                                        $translatedCountry = getTranslatedInstituteField($institute->institute_id, 'billing_country', $institute->billing_country);
                                    @endphp
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                        <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                                            @if (!empty($institute->photo))
                                                <img src="{{ Storage::url($institute->photo) }}" class="card-img-top" alt="{{ $institute->name }}">
                                            @elseif(!empty($institute->logo))
                                                <img src="{{ Storage::url($institute->logo) }}" class="card-img-top" alt="{{ $institute->name }}">
                                            @else
                                                <img src="{{asset('frontend/images/colleges/institute.svg')}}" class="card-img-top" alt="{{ $institute->name }}">
                                            @endif
                                            <div class="card-body text-center">
                                                <h5 class="card-title fw-semibold mb-1">
                                                    {{-- {{ $institute->name }} --}}
                                                     {{ $translatedName  }}

                                                </h5>
                                                <p class="text-muted mb-0 locationtitle"> <i class="bi bi-geo-alt-fill"></i>
                                                     {{-- {{ $institute->billing_country }} --}}
                                                        {{ $translatedCountry  }}
                                                    </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                    <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                                        <img src="{{ asset('frontend/images/colleges/Ecole.jpg') }}" class="card-img-top" alt="Ecole Conte">
                                        <div class="card-body text-center">
                                            <h5 class="card-title fw-semibold mb-1"> Ecole Conte</h5>
                                            <p class="text-muted mb-0 locationtitle"> <i class="bi bi-geo-alt-fill"></i> France</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                                    <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                                        <img src="{{ asset('frontend/images/colleges/digital.jpg') }}" class="card-img-top" alt="Business Project College">
                                        <div class="card-body text-center">
                                            <h5 class="card-title fw-semibold mb-1">Digital College</h5>
                                            <p class="text-muted mb-0 locationtitle"> <i class="bi bi-geo-alt-fill"></i> France</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if ($institutes->total() > $institutes->perPage())
                        <div class="pagination" style="align-content: center;display:contents;text-align:center">
                            <ul class="pagination mt-4 mb-2 d-flex justify-content-between">
                                {{-- Previous Page Link --}}
                                @if ($institutes->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link mx-1 rounded btn btn-secondary" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-arrow-left"></i> Prev</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link mx-1 rounded btn btn-secondary" href="{{ $institutes->previousPageUrl() }}"><i class="bi bi-arrow-left"></i> Prev</a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($institutes->hasMorePages())
                                    <li class="page-item ">
                                        <a class="page-link mx-1 rounded btn btn-primary" href="{{ $institutes->nextPageUrl() }}">Next <i class="bi bi-arrow-right"></i></a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link mx-1 rounded btn btn-primary" href="#" tabindex="-1" aria-disabled="true">Next <i class="bi bi-arrow-right"></i></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>




       
@endsection

