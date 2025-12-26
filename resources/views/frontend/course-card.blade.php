@if (count($courses) > 0)
@foreach ($courses as $course)
@if($course->status != '2')
<div class="item">
    <!-- Card -->
    @if($course->status == '3')
        <div class="card card-hover mb-3">
            <a
                href="{{route('get-master-course-details',['course_id'=>base64_encode($course->id)])}}"><img
                    src="{{ Storage::url($course->course_thumbnail_file) }}"
                    alt="course" class="card-img-top object-fit-cover img-fluid fade-in" loading="lazy"></a>
            <!-- Card Body -->
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-info-soft co-category">{{ $category }}</span>
                    @if(isset($course->ects) && !empty($course->ects))<span class="badge bg-success-soft co-etcs">{{isset($course->ects) ?
                        $course->ects : ''}} {{__('static.ECTS')}}</span>@endif
                </div>
                {{-- <h4 class="mb-2 text-truncate-line-2 course-title"><a
                        href="{{route('get-master-course-details',['course_id'=>base64_encode($course->id)])}}"
                        class="text-inherit">{{isset($course->course_title) ? htmlspecialchars_decode($course->course_title) : ''}}</a></h4> --}}
                    <h4 class="mb-2 text-truncate-line-2 course-title"><a
                            href="{{route('get-master-course-details',['course_id'=>base64_encode($course->id)])}}"
                            class="text-inherit">{{ htmlspecialchars_decode(getTranslatedCourseTitle($course->id) ?? $course->course_title) }}</a></h4>
                <div class="d-flex align-items-center justify-content-between mt-1 promo_code_division">
                    <span class="text-dark enroll_icon">
                        <i class="fe fe-user"></i>
                        {{-- @php $CountEnrolled = is_enrolled('',$course->id);@endphp {{$CountEnrolled}} Enrolle --}}
                        @php $CountEnrolled = $course->temp_count;@endphp {{$CountEnrolled}} {{ __('static.Enrolled') }}
                    </span>
                    @php $promoCode = getCoursePromoCode($course->id);@endphp
                    @if($promoCode)
                        <small class="promo-code font-weight-bold text-primary rounded p-1 badge_icon">
                        <span class="badge badge-success text-primary badge_icon" style="padding: 2px 4px"><span style="user-select: none">{{ __('static.promo') }}:</span> <span style="font-weight: 800">{{$promoCode}}</span></span>
                        </small>
                    @endif
                </div>
            </div>
            <!-- Card Footer -->
            <div class="card-footer" style="min-height: 65px">
                <div class="row align-items-center g-0">
                    <div class="col course-price-flex">
                        <h5 class="mb-0 course-price">€{{isset($course->course_final_price) ?
                            $course->course_final_price : 0}}</h5>
                        @if(isset($course->course_old_price) && $course->course_old_price > 0)<h5 class="old-price">€{{isset($course->course_old_price) ?
                            $course->course_old_price : 0}} </h5>@endif
                    </div>

                    <div class="col-auto">
                        @if(Auth::check() && in_array(Auth::user()->role, $allowedRoles))
                        @elseif (Auth::check() && Auth::user()->role =='user')
                            @php
                                $isPaid = is_exist('orders', ['user_id' => Auth::user()->id,'status' => '0','course_id'=> $course->id]);
                            @endphp
                            @if (isset($isPaid) && !empty($isPaid) && is_numeric($isPaid) &&  $isPaid > 0)
                                {{-- @if(!empty($doc_verified) && $doc_verified[0]->identity_is_approved == "Approved" && $doc_verified[0]->edu_is_approved == "Approved" && $doc_verified[0]->english_score >= 10 ) --}}
                                @php
                                $studentCourseMaster = getData('student_course_master',['course_expired_on','exam_attempt_remain','exam_remark'],['user_id' => Auth::user()->id, 'course_id'=> $course->id,'is_deleted'=>'No' ], "", 'created_at');

                                @endphp
                                @if(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() && $studentCourseMaster[0]->exam_attempt_remain == '1' &&  $studentCourseMaster[0]->exam_remark == '0')
                                    <a  href="{{route('master-course-panel',['course_id'=>base64_encode($course->id)])}}" class="mt-0 d-flex align-items-center justify-content-center playBtnStyle" ><i class="bi bi-play-circle btn-outline-primary me-1 fs-4" ></i> {{ __('static.play') }}</a>
                                @elseif(isset($studentCourseMaster) && !empty($studentCourseMaster[0]) && $studentCourseMaster[0]->course_expired_on > now() &&    $studentCourseMaster[0]->exam_attempt_remain == '2')
                                    <a  href="{{route('master-course-panel',['course_id'=>base64_encode($course->id)])}}" class="mt-0 d-flex align-items-center justify-content-center playBtnStyle" ><i class="bi bi-play-circle btn-outline-primary me-1 fs-4 " ></i> {{ __('static.play') }}</a>
                                @else
                                <div class="d-flex">
                                    @php
                                    $isCart = is_exist('cart', ['student_id' => Auth::user()->id,'course_id'=> $course->id,'status'=>'Active']);
                                    @endphp
                                    @if (isset($isCart) && !empty($isCart) && is_numeric($isCart) &&  $isCart > 0)
                                    <a class="text-inherit addtocart {{ buyNowDisabledClass() }}" id="addtocart" data-course-id="{{base64_encode($course->id)}}"  data-cart-id="{{base64_encode($course->id)}}" data-action="{{base64_encode('add')}}"><img src="{{ asset('frontend/images/check-out-icon.svg')}}" class="text-primary align-middle me-2" alt="add to cart" style="height: 25px; width: 25px" loading="lazy"/></a>
                                    @else
                                        <a class="text-inherit addtocart {{ buyNowDisabledClass() }}" id="addtocart" data-course-id="{{base64_encode($course->id)}}"  data-cart-id="{{base64_encode($course->id)}}" data-action="{{base64_encode('add')}}"><img src="{{ asset('frontend/images/add-to-cart-icon.svg')}}" class="text-primary align-middle me-2" alt="added to cart" style="height: 25px; width: 25px" loading="lazy"/></a>
                                    @endif
                                    <form class="checkoutform">
                                    @csrf <!-- CSRF protection -->
                                    @php $total_full_price = $course->course_old_price - ($course->course_old_price - $course->course_final_price) ; @endphp
                                    <input type='hidden' value="{{base64_encode($course->id)}}" name="course_id" id="course_id">
                                    <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($course->course_old_price)}}">
                                    <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($course->course_old_price -$course->course_final_price)}}">
                                    <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                                    <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                                    <button class="buy-now buyCourse {{ buyNowDisabledClass() }}">{{ __('static.buynow') }}</button>
                                    </form>
                                </div>
                                @endif
                            @else
                                <div class="d-flex">
                                    @php
                                    $isCart = is_exist('cart', ['student_id' => Auth::user()->id,'course_id'=> $course->id,'status'=>'Active']);
                                    @endphp
                                    @if (isset($isCart) && !empty($isCart) && is_numeric($isCart) &&  $isCart > 0)
                                    <a class="text-inherit addtocart {{ buyNowDisabledClass() }}" id="addtocart" data-course-id="{{base64_encode($course->id)}}"  data-cart-id="{{base64_encode($course->id)}}" data-action="{{base64_encode('add')}}"><img src="{{ asset('frontend/images/check-out-icon.svg')}}" class="text-primary align-middle me-2" alt="add to cart" style="height: 25px; width: 25px" loading="lazy"/></a>
                                    @else
                                        <a class="text-inherit addtocart {{ buyNowDisabledClass() }}" id="addtocart" data-course-id="{{base64_encode($course->id)}}"  data-cart-id="{{base64_encode($course->id)}}" data-action="{{base64_encode('add')}}"><img src="{{ asset('frontend/images/add-to-cart-icon.svg')}}" class="text-primary align-middle me-2" alt="added to cart" style="height: 25px; width: 25px" loading="lazy"/></i></a>
                                    @endif
                                    <form class="checkoutform">
                                    @csrf <!-- CSRF protection -->
                                    @php $total_full_price = $course->course_old_price - ($course->course_old_price - $course->course_final_price) ; @endphp
                                    <input type='hidden' value="{{base64_encode($course->id)}}" name="course_id" id="course_id">
                                    <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($course->course_old_price)}}">
                                    <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($course->course_old_price -$course->course_final_price)}}">
                                    <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                                    <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                                    <button class="buy-now buyCourse {{ buyNowDisabledClass() }}">{{ __('static.buynow') }}</button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <div class="d-flex">
                                <a class="text-inherit addtocart {{ buyNowDisabledClass() }}" id="addtocart" data-course-id="{{base64_encode($course->id)}}"  data-cart-id="{{base64_encode($course->id)}}" data-action="{{base64_encode('add')}}" data-withcart="withcart"><img src="{{ asset('frontend/images/add-to-cart-icon.svg')}}" class="text-primary align-middle me-2" style="height: 25px; width: 25px" loading="lazy"/></a>

                                <form class="checkoutform">
                                @csrf <!-- CSRF protection -->
                                @php $total_full_price = $course->course_old_price - ($course->course_old_price - $course->course_final_price) ; @endphp
                                <input type='hidden' value="{{base64_encode($course->id)}}" name="course_id" id="course_id">
                                <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($course->course_old_price)}}">
                                <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($course->course_old_price -$course->course_final_price)}}">
                                <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                                <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                                <button class="buy-now buyCourse {{ buyNowDisabledClass() }}">{{ __('static.buynow') }}</button>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
                @if(Auth::check() && in_array(Auth::user()->role, $allowedRoles))
                @else
                <div class="col-auto course-saved-btn">
                    @if (Auth::check() && Auth::user()->role =='user')
                        @php
                            $isWishlist = is_exist('wishlist', ['student_id' => Auth::user()->id,'status'=>'Active','cart_wishlist' => '0','course_id'=> $course->id]);
                        @endphp
                        @if (isset($isWishlist) && !empty($isWishlist) && is_numeric($isWishlist) &&  $isWishlist > 0)
                            @php $showicon="bi heart-icon bi-heart-fill";@endphp
                        @else
                            @php $showicon="bi bi-heart heart-icon";@endphp
                        @endif
                        <a  class="text-inherit addwishlist {{ buyNowDisabledClass() }}" aria-label="Add to Wishlist" data-course-id="{{base64_encode($course->id)}}" data-action="{{base64_encode('wishlist')}}"><i class="{{$showicon}}"></i></a>
                    @else
                        <a  class="text-inherit addwishlist {{ buyNowDisabledClass() }}" aria-label="Add to Wishlist" data-course-id="{{base64_encode($course->id)}}" data-action="{{base64_encode('wishlist')}}" data-withwishlist="withwishlist"><i class="bi bi-heart heart-icon"></i></a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    @else
        <div class="card card-hover mb-3">
            <a
            href="{{route('get-master-course-details',['course_id'=>base64_encode($course->id)])}}"><img
                src="{{ Storage::url($course->course_thumbnail_file) }}"
                alt="course" class="card-img-top object-fit-cover img-fluid fade-in" loading="lazy"></a>
            <!-- Card Body -->
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-info-soft co-category">{{ $category}}</span>
                    @if(isset($course->ects) && !empty($course->ects))<span class="badge bg-success-soft co-etcs">{{isset($course->ects) ? $course->ects : ''}}
                        {{__('static.ECTS')}}</span>@endif
                </div>
                {{-- <h4 class="mb-2 text-truncate-line-2 course-title"><a
                        href="{{route('get-master-course-details',['course_id'=>base64_encode($course->id)])}}"
                        class="text-inherit">{{isset($course->course_title) ? htmlspecialchars_decode($course->course_title) : ''}}</a>
                </h4> --}}
                <h4 class="mb-2 text-truncate-line-2 course-title"><a
                    href="{{route('get-master-course-details',['course_id'=>base64_encode($course->id)])}}"
                    class="text-inherit">{{ htmlspecialchars_decode(getTranslatedCourseTitle($course->id) ?? $course->course_title) }}</a>
            </h4>
            </div>
            <!-- Card Footer -->
            <div class="card-footer" style="min-height: 65px">
                <div class="row align-items-center g-0" style="VISIBILITY: HIDDEN;">
                    <div class="col course-price-flex">
                        <h5 class="mb-0 course-price">€{{isset($course->course_final_price) ?
                            $course->course_final_price : 0}}</h5>
                        @if(isset($course->course_old_price) && $course->course_old_price > 0)<h5 class="old-price">€{{isset($course->course_old_price) ?
                            $course->course_old_price : 0}} </h5>@endif
                    </div>

                    <div class="col-auto">
                        <a class="text-inherit"><img src="{{ asset('frontend/images/add-to-cart-icon.svg')}}" class="text-primary align-middle me-2" alt="add to cart" style="height: 25px; width: 25px" loading="lazy"/></a>
                        <a class="buy-now">{{ __('static.buynow') }}</a>
                    </div>
                </div>

                <div class="col-auto course-saved-btn"   style="VISIBILITY: HIDDEN;">
                    <a class="text-reset bookmark"><i class="bi bi-heart"></i></a>
                </div>
            </div>
        </div>
    @endif
</div>
@endif
@endforeach
@else
<img src="{{ asset('frontend/images/ComingSoon.png') }}" alt="Master" class="img-fluid coming-soon-image" loading="lazy" />
@endif