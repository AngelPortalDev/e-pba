@extends('frontend.master')
@section('content')

<style>
    .course-details-play-icon {
    height: 45px;
    width: 45px;
    background-color: #a30a1b;
    border-radius: 50%;
    color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    font-size: 24px;
}
    .course-details-play-icon::before {
    color: #ffffff;
    font-size: 14px;
    display: flex
;
    justify-content: center;
    align-items: center;
    vertical-align: middle;
    margin: 0 auto;
    margin-top: 0px;
    border-radius: 50%;
    margin-left: 10px;
    font-size: 24px;
    }

    .video-container {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
</style>

<main>
    <div class="py-md-8 py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- caption-->
                    <h1 class="fw-bold mb-1 display-4">{!! __('faq.line_2') !!}</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- container  -->
    <div class="pt-3 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div>
                        <!-- breadcrumb  -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">{{__('terms.breadcrumb_home')}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{!! __('faq.line_4') !!}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="py-8 bg-white">
        <div class="container">
            {{-- <div class="row">
                <div class="col-12">
                    <div class="mb-4">
                        <h2 class="mb-0 fw-semibold">{!! __('faq.line_5') !!}</h2>
                    </div>
                    <div class="accordion accordion-flush" id="accordionExample">
                        <div class="border p-3 rounded-3 mb-2" id="headingOne">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit active collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <span class="me-auto">{!! __('faq.line_6') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                <div class="pt-2">
                                    <p>{!! __('faq.ans6_1') !!}</p>
                                    <p>{!! __('faq.ans6_2') !!}</p>
                                    <p>{!! __('faq.ans6_3') !!}</p>
                                    <p>{!! __('faq.ans6_4') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Card  -->
                        <!-- Card header  -->
                        <div class="border p-3 rounded-3 mb-2" id="headingTwo">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <span class="me-auto">{!! __('faq.line_7') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="pt-3">
                                    <p>{!! __('faq.ans7_1') !!}</p>
                                    <p>{!! __('faq.ans7_2') !!}</p>
                                    <p>{!! __('faq.ans7_3') !!}</p>
                                    <p>{!! __('faq.ans7_4') !!}</p>
                                    <p>{!! __('faq.ans7_5') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Card  -->
                        <!-- Card header  -->
                        <div class="border p-3 rounded-3 mb-2" id="headingThree">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit active collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <span class="me-auto">{!! __('faq.line_8') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>

                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="pt-3">
                                    <p>{!! __('faq.ans8_1') !!}</p>
                                    <p>{!! __('faq.ans8_2') !!}</p>
                                    <p>{!! __('faq.ans8_3') !!}</p>
                                    <p>{!! __('faq.ans8_4') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Card  -->
                        <!-- Card header  -->
                        <div class="border p-3 rounded-3 mb-2" id="headingFour">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit active collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    <span class="me-auto">{!! __('faq.line_9') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>

                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                <div class="pt-3">
                                    <p>{!! __('faq.ans9_1') !!}</p>
                                    <p>{!! __('faq.ans9_2') !!}</p>
                                    <p>{!! __('faq.ans9_3') !!}</p>
                                  
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-12">
                    <div class="mb-4 mt-6">
                        <h2 class="mb-0 fw-semibold">{!! __('faq.line_10') !!}</h2>
                    </div>
                    <!-- accordion  -->
                    <div class="accordion accordion-flush" id="accordionExample2">
                        <div class="border p-3 rounded-3 mb-2" id="headingEight">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                    <span class="me-auto">{!! __('faq.line_11') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-bs-parent="#accordionExample2">
                                <div class="pt-2">
                                    <p>{!! __('faq.line_12') !!}</p>
                                    <p class="mt-2">{!! __('faq.line_13') !!}</p>
                                    <ul>
                                        
                                        <li>{!! __('faq.line_14') !!}</li>
                                        <li>{!! __('faq.line_15') !!}</li>
                                        <li>{!! __('faq.line_16') !!}</li>
                                        <li>{!! __('faq.line_17') !!}</li>
                                        <li>{!! __('faq.line_18') !!}</li>
                                    </ul>
                                    <p>{!! __('faq.line_19') !!}</p>
                                    <ul>
                                        <li>{!! __('faq.line_20') !!}</li>
                                        <li>{!! __('faq.line_21') !!}</li>
                                        <li>{!! __('faq.line_22') !!}</li>
                                    </ul>
                                    <p>{!! __('faq.line_23') !!}</p>
                                    <ul>
                                        <li>{!! __('faq.line_24') !!}</li>
                                        <li>{!! __('faq.line_25') !!}</li>
                                        <li>{!! __('faq.line_26') !!}</li>
                                    </ul>
                                    <p>{!! __('faq.line_27') !!}</p>
                                    <ul>
                                        <li>{!! __('faq.line_28') !!}</li>
                                        <li>{!! __('faq.line_29') !!}</li>
                                        <li>{!! __('faq.line_30') !!}</li>
                                    </ul>
                                    <p>{!! __('faq.line_31') !!}</p>
                                    <ul>
                                        <li>{!! __('faq.line_32') !!}</li>
                                        <li>{!! __('faq.line_33') !!}</li>
                                        <li>{!! __('faq.line_34') !!}</li>
                                        <li>{!! __('faq.line_35') !!}</li>


                                    </ul>
                                    <p>{!! __('faq.line_36') !!}</p>
                                    <ul>
                                        <li>{!! __('faq.line_37') !!}</li>
                                        <li>{!! __('faq.line_38') !!}</li>
                                        <li>{!! __('faq.line_39') !!}</li>
                                    </ul>
                                    <p>{!! __('faq.line_40') !!}</p>
                                    <ul>
                                        <li>{!! __('faq.line_41') !!}</li>
                                        <li>{!! __('faq.line_42') !!}</li>
                                        <li>{!! __('faq.line_43') !!}</li>
                                    </ul>
                                    <p>{!! __('faq.line_44') !!}</p>
                                    <ul>
                                        <li>{!! __('faq.line_45') !!}</li>
                                        <li>{!! __('faq.line_46') !!}</li>
                                   
                                    </ul>
                                    <p>{!! __('faq.line_47') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Card  -->
                        <!-- Card header  -->
                        <div class="border p-3 rounded-3 mb-2" id="headingNine">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                    <span class="me-auto">{!! __('faq.line_48') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-bs-parent="#accordionExample2">
                                <div class="pt-3">
                                    <p class="mb-2">{!! __('faq.line_49') !!}</p>
                                    <ol>
                                        <li>{!! __('faq.line_50') !!}</li>
                                        <li>{!! __('faq.line_51') !!}</li>
                                        <li>{!! __('faq.line_52') !!}</li>
                                        <li>{!! __('faq.line_53') !!}</li>
                                        <li>{!! __('faq.line_54') !!}</li>
                                        <li>{!! __('faq.line_55') !!}</li>    
                                        <li>{!! __('faq.line_56') !!}</li>
                                        <li>{!! __('faq.line_57') !!}</li>    
                                    </ol>
                                    <p>{!! __('faq.line_58') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Card  -->
                        <!-- Card header  -->
                        <div class="border p-3 rounded-3 mb-2" id="headingTen">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit active" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                    <span class="me-auto">{!! __('faq.line_59') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-bs-parent="#accordionExample2">
                                <div class="pt-3">
                                    <p>{!! __('faq.line_60') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Card  -->
                        <!-- Card header  -->
                        <div class="border p-3 rounded-3 mb-2" id="headingEleven">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit active" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                                    <span class="me-auto">{!! __('faq.line_61') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-bs-parent="#accordionExample2">
                                <div class="pt-3">
                                    <p>{!! __('faq.line_62') !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="border p-3 rounded-3 mb-2" id="headingSixteen">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit active" data-bs-toggle="collapse" data-bs-target="#collapseSixteen" aria-expanded="false" aria-controls="collapseSixteen">
                                    <span class="me-auto">{!! __('faq.line_63') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseSixteen" class="collapse" aria-labelledby="headingSixteen" data-bs-parent="#accordionExample2">
                                <div class="pt-3">
                                    <p> {!! __('faq.line_64') !!}</p>
                                    <p> {!! __('faq.line_66') !!}</p>
                                    <p> {!! __('faq.line_68') !!}</p>

                                    <p class="mt-2">{!! __('faq.line_70') !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="border p-3 rounded-3 mb-2" id="headingSeventeen">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit active" data-bs-toggle="collapse" data-bs-target="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
                                    <span class="me-auto">{!! __('faq.line_71') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseSeventeen" class="collapse" aria-labelledby="headingSeventeen" data-bs-parent="#accordionExample2">
                                <div class="pt-3">
                                    <p>{!! __('faq.line_72') !!}</p>

                                </div>
                            </div>
                        </div>
                        <div class="border p-3 rounded-3 mb-2" id="headingEighteen">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit active" data-bs-toggle="collapse" data-bs-target="#collapseEighteen" aria-expanded="false" aria-controls="collapseEighteen">
                                    <span class="me-auto">{!! __('faq.line_73') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseEighteen" class="collapse" aria-labelledby="headingEighteen" data-bs-parent="#accordionExample2">
                                <div class="pt-3">
                                    <p class="mb-2">{!! __('faq.line_74') !!}</p>
                                    <p class="fw-bold mb-2">{!! __('faq.line_75') !!}</p>

                                            <p>{!! __('faq.line_76') !!}</p>
                                            <ul>
                                                <li>{!! __('faq.line_77') !!}</li>
                                                <li>{!! __('faq.line_78') !!}</li>
                                            </ul>

                                            <p>{!! __('faq.line_79') !!}</p>
                                            <ul>
                                                <li>{!! __('faq.line_80') !!}</li>
                                                <li>{!! __('faq.line_81') !!}</li>
                                            </ul>

                                            <p>{!! __('faq.line_82') !!}</p>
                                            <ul>
                                                <li>{!! __('faq.line_83') !!}</li>
                                                <li>{!! __('faq.line_84') !!}</li>
                                            </ul>

                                            <p>{!! __('faq.line_85') !!}</p>
                                            <p>{!! __('faq.line_86') !!}</p>

                                        <p class="mt-2">{!! __('faq.line_87') !!}</p>
                                         <p class="mt-2">{!! __('faq.line_88') !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="mb-4 mt-6">
                        <h2 class="mb-0 fw-semibold">{!! __('faq.line_90') !!}</h2>
                    </div>
                    <div class="accordion accordion-flush" id="accordionExample3">
                        <div class="border p-3 rounded-3 mb-2" id="headingTwelve">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit" data-bs-toggle="collapse" data-bs-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                                    <span class="me-auto">{!! __('faq.line_91') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-bs-parent="#accordionExample3">
                                <div class="pt-2">
                                    <p class="mb-2">{!! __('faq.line_92') !!}</p>
                                    <ol>
                                    <li>{!! __('faq.line_93') !!}</li>   
                                    <li>{!! __('faq.line_94') !!}</li> 
                                    <li>{!! __('faq.line_95') !!}</li> 
                                    <li>{!! __('faq.line_96') !!}</li> 
                                    <li>{!! __('faq.line_97') !!}</li> 
                                    <li>{!! __('faq.line_98') !!}</li> 
                                    <li>{!! __('faq.line_99') !!}</li> 
                                    

                                    </ol>
                                    <p>{!! __('faq.line_100') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Card  -->
                        <!-- Card header  -->
                        <div class="border p-3 rounded-3 mb-2" id="headingThirteen">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit" data-bs-toggle="collapse" data-bs-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                                    <span class="me-auto">{!! __('faq.line_101') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseThirteen" class="collapse" aria-labelledby="headingThirteen" data-bs-parent="#accordionExample3">
                                <div class="pt-3">
                                    <p class="mb-2">{!! __('faq.line_102') !!}</p>
                                    <ol>
                                        <li> {!! __('faq.line_103') !!}</li>
                                        <li> {!! __('faq.line_104') !!}</li>
                                        <li> {!! __('faq.line_105') !!}</li>
                                        <li> {!! __('faq.line_106') !!}</li>
                                        <li> {!! __('faq.line_107') !!}</li>
                                        <li> {!! __('faq.line_108') !!}</li>
                                        <li> {!! __('faq.line_109') !!}</li>
                                        <li> {!! __('faq.line_110') !!}</li>

                                    </ol>
                                    <p>{!! __('faq.line_111') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Card  -->
                        <!-- Card header  -->
                        <div class="border p-3 rounded-3 mb-2" id="headingFourteen">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit active" data-bs-toggle="collapse" data-bs-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                                    <span class="me-auto">{!! __('faq.line_112') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseFourteen" class="collapse" aria-labelledby="headingFourteen" data-bs-parent="#accordionExample3">
                                <div class="pt-3">
                                    <p>{!! __('faq.line_113') !!}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Card  -->
                        <!-- Card header  -->
                        <div class="border p-3 rounded-3 mb-2" id="headingFifteen">
                            <h3 class="mb-0 fs-4">
                                <a href="#" class="d-flex align-items-center text-inherit active" data-bs-toggle="collapse" data-bs-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">
                                    <span class="me-auto">{!! __('faq.line_114') !!}</span>
                                    <span class="collapse-toggle ms-4">
                                        <i class="fe fe-chevron-down"></i>
                                    </span>
                                </a>
                            </h3>
                            <div id="collapseFifteen" class="collapse" aria-labelledb{!! __('faq.line_102') !!}y="headingFifteen" data-bs-parent="#accordionExample3">
                                <div class="pt-3">
                                    <p class="mb-2">{!! __('faq.line_115') !!}</p>
                                    <p class="fw-bold mb-2">{!! __('faq.line_116') !!}</p>
                                    <p class="mb-2">{!! __('faq.line_117') !!}</p>
                                    <p class="mb-2">{!! __('faq.line_118') !!}</p>
                                    <p>{!! __('faq.line_119') !!}
                                    </p>                                        
                                </div>
                            </div>
                        </div>
                         <!-- Card  -->
                        <!-- Card header  -->
                        {{-- <div class="border p-3 rounded-3 mb-2" id="headingNinteen">
                            <h3 class="mb-2 fs-4">
                              <a href="#" class="d-flex align-items-center text-inherit active" data-bs-toggle="collapse" data-bs-target="#collapseNinteen" aria-expanded="true" aria-controls="collapseNinteen">
                                <span class="me-auto">{!! __('faq.line_120') !!}</span>
                                <span class="collapse-toggle ms-4">
                                  <i class="fe fe-chevron-down"></i>
                                </span>
                              </a>
                            </h3>
                          
                            <div id="collapseNinteen" class="collapse show" aria-labelledby="headingNinteen" data-bs-parent="#accordionExample3">
                              <?php 
                                $libraryId = '253882';
                                $pullZone = 'https://vz-8beca12f-70b.b-cdn.net/';
                                $videoUrl = "https://iframe.mediadelivery.net/embed/$libraryId/82c2a69d-1242-4fd7-94b6-9eaf2401f92f";
                              ?>
                              <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="video-container open-video-modal" data-video-url="https://iframe.mediadelivery.net/embed/253882/82c2a69d-1242-4fd7-94b6-9eaf2401f92f?autoplay=true">
                                        <i class="bi bi-play-fill btn-outline-primary fs-2 course-details-play-icon"></i>
                                        <img src="{{ asset('frontend/images/DBA-Thumbnail.jpg')}}" class="img-fluid"/>
                                    </div>
                                  </div>
                              </div>
                              
                            </div>
                        </div> --}}
                          
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content bg-white">
        <div class="modal-body p-0 position-relative">
            <i class="bi bi-x fs-2 text-white couser-detail-modal-close-button" data-bs-dismiss="modal" aria-label="Close"></i>
          <div class="ratio ratio-16x9" style="width:100%">
            <iframe id="videoFrame" src="" allowfullscreen allow="autoplay" width="100%"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const videoContainers = document.querySelectorAll('.open-video-modal');
    const modal = new bootstrap.Modal(document.getElementById('videoModal'));
    const videoFrame = document.getElementById('videoFrame');

    videoContainers.forEach(container => {
      container.addEventListener('click', function () {
        const videoUrl = this.getAttribute('data-video-url');
        videoFrame.src = videoUrl;
        modal.show();
      });
    });

    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
      videoFrame.src = "";
    });
  });

</script>

@endsection