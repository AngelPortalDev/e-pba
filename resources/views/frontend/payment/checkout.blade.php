@extends('frontend.master')

@section('content')

<style>

  .promo_code_hidden{

    display:none;

  }

  .promo_code_visible{

    display:block;

  }

  .loader {

   margin: auto;

   border: 20px solid #EAF0F6;

   border-radius: 50%;

   border-top: 20px solid #FF7A59;

   width: 200px;

   height: 200px;

   animation: spinner 4s linear infinite;

    }

    .save_loader-text {
            font-size: 24px;
            color: #a30a1b;
            margin-bottom: 20px;
            align-self: center;
            font-weight: bold;
        }

        .save_loader-bar {
            width: 50px;
            height: 50px;
            aspect-ratio: 1;
            border-radius: 50%;
            background:
                radial-gradient(farthest-side, #a30a1b 94%, #0000) top/8px 8px no-repeat,
                conic-gradient(#0000 30%, #a30a1b);
            -webkit-mask: radial-gradient(farthest-side, #0000 calc(100% - 8px), #000 0);
            animation: l13 1s infinite linear;
            margin-top: 10px;
            margin-left: 35px;
            margin: auto
        }

        @keyframes l13 {
            100% {
                transform: rotate(1turn);
            }
        }



    @keyframes spinner {

    0% { transform: rotate(0deg); }

    100% { transform: rotate(360deg); }

    }

  /* .direct-checkout {

    display: none;

   } */

</style>

                <section class="py-4 py-lg-6 bg-primary">

                    <div class="container">

                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-12">

                                <div>

                                    <h1 class="text-white mb-1 display-4 color-green">
                                        {{-- Checkout --}}
                                        {{ __('checkout.title') }}
                                     </h1>

                                </div>

                            </div>

                        </div>

                    </div>



                </section>



                <!-- Container fluid -->

               <!-- Container fluid -->

               <section class="container p-4 checkout-page">

                @php $course_id = ''; @endphp

                @foreach($data['CourseData'] as $course)

                @php  $course_id .= $course->id.',';
                // print_r($course);
                @endphp



                @endforeach

                <!-- row -->

                <div class="row">

                    <div class="col-xl-8 col-lg-7 order-lg-1 order-2">

                        <!-- stepper -->

                        <div id="stepperForm" class="bs-stepper">

                            <!-- card -->

                            <div class="card">

                                @if(!empty($data['MessageCheck']) && isset($data['MessageCheck']))

                                 <script>
                                    swal({
                                        title: "Already Purchased",
                                        text: "You have already purchased this award in the master",
                                        icon: "warning",
                                        position: 'top-start', // Position at the top of the page
                                        buttons: {
                                            select: {
                                                text: "Ok",
                                                value: true,
                                                className: "btn btn-primary",
                                            },
                                        },
                                        dangerMode: true,
                                    }).then((willSelect) => {
                                        if (willSelect) {
                                            return false;
                                        }
                                    });
                                    </script>

                                @endif

                                <div class="card-body">

                                    <!-- Stepper content -->

                                        <form onSubmit="return false"id="paymentprocess" novalidate>

                                            <!-- Content one -->

                                            <div>

                                                <!-- heading -->

                                                <div class="mb-5">

                                                    <h3 class="mb-1">
                                                        {{-- Billing Information --}}
                                                        {{ __('checkout.subtitle') }}
                                                    </h3>

                                                    <p class="mb-0">
                                                        {{-- Please fill all information below. --}}
                                                        {{ __('checkout.subcontent') }}
                                                    </p>

                                                </div>

                                                <!-- row -->

                                                <div class="row gx-3">

                                                    <!-- input -->



                                                    <div class="mb-3 col-md-6">

                                                        <label class="form-label" for="firstName">{{-- First Name --}} {{ __('checkout.first_name') }} <span class="text-danger">*</span></label>

                                                        <input type="text" class="form-control" placeholder="{{-- Enter First Name --}}{{ __('checkout.fname_placeholder') }}" id="first_name" name="first_name" required/>

                                                        <div class="invalid-feedback" id="first_name_error">Please enter your first name.</div>



                                                    </div>

                                                    <!-- input -->

                                                    <div class="mb-3 col-md-6">

                                                        <label class="form-label" for="lastName">{{--  Last Name--}}{{ __('checkout.last_name') }}  <span class="text-danger">*</span></label>

                                                        <input type="text" class="form-control" placeholder="{{--Enter Last Name--}}{{ __('checkout.lname_placeholder') }}" id="last_name" name="last_name" required/>

                                                        <div class="invalid-feedback" id="last_name_error">Please enter your last name.</div>



                                                    </div>



                                                    <!-- input -->

                                                    <div class="mb-3 col-12">

                                                        <label class="form-label" for="address">{{-- Address--}}{{ __('checkout.address') }}  <span class="text-danger">*</span></label>

                                                        <input type="text" class="form-control" placeholder="{{-- Enter Address --}}{{ __('checkout.address_placeholder') }}" id="address" name="address" required/>

                                                        <div class="invalid-feedback" id="address_error">Please enter your valid address.</div>



                                                    </div>

                                                    <!-- input -->

                                                    <div class="mb-3 col-12">

                                                        <label class="form-label" for="town">{{-- City --}}{{ __('checkout.city') }}  <span class="text-danger">*</span></label>

                                                        <input type="text" class="form-control" placeholder="{{--  Enter City--}}{{ __('checkout.city_placeholder') }}" id="town" name="town" required />

                                                        <div class="invalid-feedback" id="town_error">Please enter your city.</div>



                                                    </div>



                                                    <!-- select -->

                                                    <div class="mb-3 col-12">

                                                        <label class="form-label">{{-- Country --}}{{ __('checkout.country') }} <span class="text-danger">*</span></label>

                                                        <select class="form-select" id="country_id" name="country_id">

                                                            <option value="">{{-- Select Country --}}{{ __('checkout.select_country') }}</option>

                                                            @foreach (getDropDownlist('country_master',['id','country_name','currency_code']) as $mob_code)

                                                            <option value="{{$mob_code->id.'-'.$mob_code->country_name}}">{{$mob_code->country_name}}</option>

                                                            @endforeach

                                                        </select>

                                                        <div class="invalid-feedback" id="country_error">Please select your country.</div>



                                                    </div>

                                                    {{-- <input type='hidden' value="{{base64_encode($data['promoCodeDiscount'])}}" name="promo_code_discount" id="promo_code_discount"> --}}

                                                    {{-- <input type='hidden' value="{{base64_encode($data['promoCodeName'])}}" name="promo_code_name" id="promo_code_name"> --}}

                                                    <input type='hidden' value="{{base64_encode($data['overallFullTotal'])}}" name="overall_full_totals" class="overall_full_totals">

                                                    <input type='hidden' value="{{base64_encode($course_id)}}" name="course_id" id="course_id">

                                                    <input type='hidden' value="{{$data['promo_code_id']}}" name="promo_code_id" class="promo_code_id">

                                                    <input type="hidden" value="{{base64_encode($data['overalloldTotal'])}}"class="form-control overall_old_total" name="overall_old_total">

                                                    <input type="hidden" value="{{base64_encode($data['promoCodeDiscount'])}}" class="form-control promo_code_discounts" name="promo_code_discounts">

                                                    <input type="hidden" value="{{base64_encode($data['payment_type_installment'])}}" class="form-control payment_type_installment" name="payment_type_installment">
                                                    
                                                    @if($data['selected_installments'] > 0)
                                                        <input type="hidden" value="{{$data['multiple_total_amount'] ? base64_encode($data['multiple_total_amount']) : ''}}" class="form-control installment_amount" name="installment_amount">

                                                        <input type="hidden" value="{{$data['selected_installments'] ? base64_encode($data['selected_installments']) : base64_encode(1)}}" class="form-control no_of_installment" name="no_of_installment">
                                                    @else
                                                        <input type="hidden" value="{{$data['CourseData'][0]->installment_amount ? base64_encode($data['CourseData'][0]->installment_amount) : ''}}" class="form-control installment_amount" name="installment_amount">

                                                        <input type="hidden" value="{{$data['InstallPayData'] ? base64_encode($data['InstallPayData'] + 1) : base64_encode(1)}}" class="form-control no_of_installment" name="no_of_installment">
                                                    @endif

                                                    <input type='hidden' value="{{base64_encode($data['student_course_master_id'])}}"class="form-control student_course_master_id" name="student_course_master_id" >

                                                    <input type='hidden' value="{{ !empty($data['multiple_install_no']) ? base64_encode($data['multiple_install_no']) : ''}}"class="form-control multiple_install_no" name="multiple_install_no">


                                                    <!-- checkbox -->



                                                </div>



                                                <!-- Button -->
                                                @php $show = ''; @endphp

                                                <div class="d-flex justify-content-end mt-3">



                                                    <button class="btn btn-primary" {{$show}}  id="checkout-live-button">

                                                        {{-- Complete Checkout  --}}
                                                        {{ __('checkout.complete_checkout') }}
                                                        <div class="loader d-none"></div>

                                                    </button>

                                                </div>

                                            </div>



                                        </form>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-lg-4 order-lg-2 order-1 mb-4 mb-lg-0 <?php echo ($data['directchekout'] == 1) ? 'promo_code_visible' : 'promo_code_hidden'; ?>">

                        <div class="card mt-4 mt-lg-0">

                            <div class="card-body">

                                <div class="mb-4 d-flex justify-content-between align-items-center">

                                    <h4 class="mb-1">
                                        {{-- Order Summary --}}
                                        {{ __('checkout.order_summry') }}
                                    </h4>

                                    <a href="{{route('shopping-cart')}}">
                                        {{-- Edit Cart --}}
                                        {{ __('checkout.edit_cart') }}
                                    </a>

                                </div>

                                @php $total_price =0; @endphp

                                @foreach($data['CourseData'] as $course)



                                <div class="d-md-flex">

                                    <div>

                                        <img src="{{Storage::url($course->course_thumbnail_file)}}" alt="" class="img-4by3-xl rounded" />

                                    </div>

                                    <div class="ms-md-3 ">

                                        <h5 class="mb-1 text-primary-hover course-name mt-2 mt-md-0">
                                            {{-- {{htmlspecialchars_decode($course->course_title)}} --}}
                                            {{ htmlspecialchars_decode(getTranslatedCourseTitle($course->id) ?? $course->course_title) }}
                                        </h5>

                                        <h5>€{{$course->course_final_price}}
                                            @if(isset($course->course_old_price) && $course->course_old_price  > 0)<span class="old-price">€{{$course->course_old_price}}</span>@endif</h5>
                                       
                                    </div>

                                    @php $total_price += $course->course_final_price; @endphp

                                </div>

                                @if(count($data['CourseData']) > 1)

                                <hr class="my-3" />

                                @endif

                                @endforeach

                                {{-- <div class="d-md-flex">

                                    <div>

                                        <img src="{{ asset('frontend/images/course/award-recruitment-and-employee-selection.png')}}"  alt="" class="img-4by3-xl rounded" />

                                    </div>

                                    <div class="ms-md-3">

                                        <h4 class="mb-1 text-primary-hover course-name">Award in Recruitment and Employee Selection</h4>

                                        <h5>€1500 <span class="old-price">€2300 </span></h5>

                                    </div>

                                </div> --}}

                            </div>

                            <div class="card-body border-top pt-2">

                                <ul class="list-group list-group-flush mb-0">
                                      
                                        @if($data['overallTotal'] > 0)

                                        <li class="d-flex justify-content-between list-group-item px-0 text-dark fw-medium">

                                            <span>
                                                {{-- Original Prices --}}
                                                {{ __('checkout.original_price') }}:</span>

                                            <span class="text-dark fw-semibold">{{($data['overallTotal'])}}</span>

                                        </li>

                                        <li class="list-group-item px-0 d-flex justify-content-between fs-5 text-dark fw-medium">

                                            <span>
                                                {{-- Scholarship --}}
                                                {{ __('checkout.scholarship') }}
                                                :</span>



                                            <span>{{$data['overalloldTotal']}}</span>

                                        </li>
                                        @endif


                                        <li class="d-flex justify-content-between list-group-item px-0 text-dark fw-medium">

                                        <span>

                                                {{-- Discount (Promo Code): --}}
                                                {{ __('checkout.discount') }}

                                            </span>

                                            <span class="text-dark fw-semibold">{{($data['promoCodeDiscount'])}}</span>

                                        </li>



                                        <li class="d-flex justify-content-between list-group-item px-0 pb-0">

                                            <span class="fs-4 fw-semibold text-dark">
                                                {{-- Grand Total --}}
                                                {{ __('checkout.grand_total') }}
                                            </span>

                                            <span class="fw-semibold text-dark">€{{($data['overallFullTotal'])}}</span>

                                        </li>
                              
                                </ul>

                            </div>

                        </div>

                    </div>  

                    <div class="col-lg-4 order-lg-2 order-1  mb-4 mb-lg-0  <?php echo ($data['directchekout'] == 0) ? 'promo_code_visible' : 'promo_code_hidden'; ?>">
                        <!-- card -->
                            @php $CouponData = getData('coupons',['is_deleted','status'],['course_id'=>$course->course_id,'is_deleted'=>'No','status'=>'Active']); @endphp
                            @if(isset($CouponData) && $CouponData->isNotEmpty())

                            <div class="card mb-4 mt-4 mt-lg-0">


                                <!-- card body -->
                                @if($data['payment_type_installment'] == 'FullPayment')
                                <div class="card-body">


                                    <div class="">




                                            @php $coupon_name = $data['CourseData'][0]->coupon_name ;@endphp



                                        {{-- <span class="bg-green fs-5 py-1 px-2 fw-bold rounded ">Promo Code- <span class="color-blue">{{$coupon_name}}</span> </span> --}}

                                    </div>


                                  
                                    <h5 class="mb-2 mt-3">
                                        {{-- Apply promo code here --}}
                                        {{ __('checkout.apply_promo') }}
                                    </h5>

                                    <!-- row -->

                                    <div class="row g-3">

                                        <!-- col -->

                                        <div class="col">

                                            <input type="text" class="form-control promo_code_0" placeholder="{{ __("checkout.promo_code") }}" required >

                                            <div class="invalid-feedback coupon_code_error_0">Please Enter Promo Code</div>

                                            <input type='hidden' class="form-control discount_promo_0"  value="{{base64_encode($data['CourseData'][0]->coupon_discount)}}">

                                            <input type='hidden' class="form-control course_id_0"  value="{{base64_encode($data['CourseData'][0]->id)}}">

                                            <input type='hidden' class="form-control total_old_price_0" value="{{base64_encode($data['CourseData'][0]->course_final_price)}}">
                                            
                                            <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($data['CourseData'][0]->course_old_price)}}">

                                            <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($data['CourseData'][0]->course_old_price- $data['CourseData'][0]->course_final_price)}}">

                                            <input type='hidden' class="form-control direct_checkout"  value="{{base64_encode($data['directchekout'])}}">

                                          
                                        </div>

                                        <!-- col -->

                                        <div class="col-auto">

                                            <button class="btn btn-primary ApplyPromo" data-id="0" id="ApplyPromo-0">
                                                {{-- Apply --}}
                                                {{ __('checkout.apply') }}
                                            </button>

                                            <button class="btn btn-primary RemovePromo d-none" data-id="0" id="RemovePromo-0">
                                                {{-- Remove --}}
                                                {{ __('checkout.remove') }}
                                            </button>



                                        </div>

                                    </div>
                                   
                                </div>
                                @endif
                            </div>
                            @endif
                        <!-- card -->

                            <div class="card mb-1">

                                <!-- card body -->

                                <div class="card-body">

                                    <!-- text -->

                                    <h4 class="mb-3">
                                        {{-- Order Summary --}}
                                        {{ __('checkout.order_summry') }}
                                    </h4>

                                    <!-- list group -->

                                    <ul class="list-group list-group-flush">



                                        {{-- @foreach($data['CourseData'] as $course) --}}

                                            <div class="d-md-flex">

                                                <div>

                                                    <img src="{{Storage::url($data['CourseData'][0]->course_thumbnail_file)}}" alt="" class="img-4by3-xl rounded" />

                                                </div>

                                                <div class="ms-md-3 ">

                                                    <h5 class="mt-2 mt-md-0 mb-1 text-primary-hover course-name">
                                                        {{-- {{htmlspecialchars_decode($data['CourseData'][0]->course_title)}} --}}
                                                             {{ htmlspecialchars_decode(getTranslatedCourseTitle($data['CourseData'][0]->id) ?? $data['CourseData'][0]->course_title) }}

                                                    </h5>

                                                    <h5>
                                                        {{-- €{{$data['CourseData'][0]->course_final_price}} --}}

                                                        {{-- @if(isset($course->course_old_price) && $course->course_old_price  > 0)<span class="old-price">€{{$data['CourseData'][0]->course_old_price}}</span>@endif</h5> --}}
                                                    @if($data['payment_type_installment'] == 'InstallmentPayment')
                                                        <h5>€{{$data['CourseData'][0]->installment_amount * $data['CourseData'][0]->no_of_installment}}</h5>
                                                    @else
                                                        €{{$course->course_final_price}}
                                                        @if(isset($course->course_old_price) && $course->course_old_price  > 0)<span class="old-price">€{{$course->course_old_price}}</span>@endif</h5>
                                                    @endif
                                                </div>

                                            </div>



                                            <hr class="my-3" />



                                        <!-- list group item -->
                                        @if($data['payment_type_installment'] == 'FullPayment')
                                            @if($data['overallTotal'] > 0)


                                            <li class="list-group-item px-0 d-flex justify-content-between fs-5 text-dark fw-medium">

                                                <span>
                                                    {{-- Original Price  --}}
                                                    {{ __('checkout.original_price') }}
                                                    : </span>

                                                <span>{{$data['overallTotal']  ? $data['overallTotal'] : '0'}}</span>

                                            </li>

                                            <li class="list-group-item px-0 d-flex justify-content-between fs-5 text-dark fw-medium">

                                                <span>
                                                    {{-- Scholarship --}}
                                                    {{ __('checkout.scholarship') }}
                                                    :</span>



                                                <span>{{$data['overalloldTotal']  ? $data['overalloldTotal'] : '0'}}</span>

                                            </li>

                                            @endif
                                            <!-- list group item -->

                                            <li class="list-group-item px-0 d-flex justify-content-between fs-5 text-dark fw-medium">

                                                <span>

                                                    {{-- Discount (Promo Code) --}}
                                                    {{ __('checkout.discount') }}

                                                    <span class="promo_code_name"></span>

                                                    :

                                                </span>

                                                <span class="promo_code_discount">0</span>

                                            </li>                                 
                                        @endif
                                        {{-- @endforeach --}}
                                        @if($data['payment_type_installment'] == 'InstallmentPayment')
                                        @if($data['selected_installments'] > 0)
                                            <li class="d-flex justify-content-between list-group-item px-0 text-dark fw-medium">                                            
                                                <span>Each Installment Amount:</span>
                                                <span>{{ $data['CourseData'][0]->installment_amount  ? $data['CourseData'][0]->installment_amount : '0'}}</span>
                                            </li>
                                            <li class="d-flex justify-content-between list-group-item px-0 text-dark fw-medium" >                    
                                                                        
                                                <span>Paid So Far :</span>
                                                <span>{{$data['InstallPayData'] ?  $data['InstallPayData'] : '0'}}</span>
                                            </li>
                                            <li class=" d-flex justify-content-between list-group-item px-0 text-dark fw-medium" >                                          
                                                <span>No. of Installment : </span>
                                                <span>{{$data['selected_installments'] > 0 ? ($data['selected_installments']).'/'. $data['CourseData'][0]->no_of_installment : '1/5'}}</span>
                                            </li>
                                        @else
                                            <li class="d-flex justify-content-between list-group-item px-0 text-dark fw-medium">                                            
                                                <span>Each Installment Amount:</span>
                                                <span>{{ $data['CourseData'][0]->installment_amount  ? $data['CourseData'][0]->installment_amount : '0'}}</span>
                                            </li>
                                            @if($data['InstallPayData'] > 0)
                                            <li class="d-flex justify-content-between list-group-item px-0 text-dark fw-medium" >                    
                                                                        
                                                <span>Paid So Far :</span>
                                                <span>{{ $data['CourseData'][0]->installment_amount  ? ($data['CourseData'][0]->installment_amount * $data['InstallPayData'])  : ''}}</span>
                                            </li>
                                            @endif
                                            <li class=" d-flex justify-content-between list-group-item px-0 text-dark fw-medium" >                                          
                                                <span>No. of Installment : </span>
                                                <span>{{$data['InstallPayData'] > 0 ? ($data['InstallPayData'] + 1).'/'. $data['CourseData'][0]->no_of_installment : '1/'. $data['CourseData'][0]->no_of_installment }}</span>
                                            </li>
                                        @endif
                                        @endif
                                    </ul>

                                </div>

                                <!-- card footer -->

                                <div class="card-footer">

                                    <div class="px-0 d-flex justify-content-between fs-5 text-dark fw-semibold">

                                        @if($data['payment_type_installment'] == 'FullPayment')
                                        <h3 class="color-blue">
                                            {{-- Grand Total (EURO) --}}
                                            {{ __('checkout.grand_total') }}
                                        </h3>

                                        <h3 class="color-blue overall_full_total">€{{ $data['overallFullTotal']  ? $data['overallFullTotal'] : '0' }}
                                        </h3>
                                        @endif
                                        @if($data['payment_type_installment'] == 'InstallmentPayment')
                                            @php
                                                // $words = [1=>'First',2=>'Second',3=>'Third',4=>'Fourth',5=>'Fifth','6'=>'Sixth','7'=>'Seventh','8'=>'Eighth'];
                                                if($data['selected_installments'] > 0){
                                                    $installmentNo = $data['multiple_install_no'];
                                                }else{
                                                    $installmentNo = ($data['InstallPayData'] ?? 1) + 1;
                                                }
                                                // $label = $words[$installmentNo] ?? $installmentNo.'th';
                                                $label = ordinalSuffix($installmentNo);
                                            @endphp
                                            <h3 class="color-blue">
                                                {{$label}} Installment Amount
                                            </h3>

                                            <h3 class="installPaymentItemsFooter color-blue overall_full_total">
                                                @if($data['selected_installments'] > 0)
                                                    €{{ $data['multiple_total_amount']  ? $data['multiple_total_amount'] : '0' }}
                                                @else
                                                    €{{ $data['CourseData'][0]->installment_amount  ? $data['CourseData'][0]->installment_amount : '0' }}
                                                @endif
                                            </h3>
                                        @endif

                                    </div>

                                </div>

                            </div>

                    </div>

                </div>

            </section>

        </main>


    <script src="https://js.stripe.com/v3/"></script>




@endsection
