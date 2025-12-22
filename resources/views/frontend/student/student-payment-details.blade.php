@extends('frontend.master')
@section('content')

<style>
    .sidenav.navbar .navbar-nav .sp-7>.nav-link {
    color: #a30a1b !important;
    background-color:#ffe7ea;
    }
</style>
<main>
    <section class="pt-5 pb-5">
        <div class="container">
          @include('frontend.student.layout.student-common')

            <!-- User info -->

                <div class="col-lg-9 col-md-8 col-12">
                  <!-- Card -->
                  <div class="card mb-4">
                    <!-- Card header -->
                    <div class="card-header border-bottom-0">
                      {{-- <h3 class="mb-0">Invoices</h3>
                      <p class="mb-0">You can find all of your course invoices.</p> --}}
                       <h3 class="mb-0">{{ __('studentdashborad.payment-details') }}</h3>
                    </div>
                    <div class="accordion p-4" id="accordionExample">
                        @php $i=0; @endphp
                      @foreach($installmentData as $courseId => $installments)
                      @php $CourseData = $studentCourses->where('course_id', $courseId)->first(); @endphp
                      <div class="accordion-item">
                        <h2 class="accordion-header">
                          <button class="accordion-button {{$i != 0 ? 'collapsed' : ''}}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$i}}" aria-expanded="{{ $i == 0 ? 'true' : ''}}"  aria-controls="collapse{{$i}}">
                            <h5>Course: 
                                {{ htmlspecialchars_decode(getTranslatedCourseTitle($courseId) ?? $CourseData->course_name) }}
                                <span class="text-muted fs-6">
                                    (Total: € {{ number_format($CourseData->installment_amount * $CourseData->no_of_installment, 2) }})
                                </span>
                            </h5>   

                            </button>
                        </h2>
                        <div id="collapse{{$i}}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : ''}}" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Installment No.</th>
                                        <th>Due date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $paidSoFar = 0; $balanceRem = 0; @endphp
                                    @foreach($installments as $key => $value)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="installment-checkbox"
                                                  name="installments[{{ $courseId }}][]"
                                                  value="{{ $value['install_no'] }}"
                                                  data-course="{{ $courseId }}"
                                                  data-amount="{{ $value['amount'] }}"
                                                  data-index="{{ $key }}"
                                                  @if($value['status'] == 0) disabled
                                                  @elseif($value['install_no'] != $firstUnpaid[$courseId]) disabled @endif>
                                        </td>
                                        <td>{{ $value['install_no'] }}</td>
                                        <td>{{ $value['due_date'] }}</td>
                                        <td>€ {{$CourseData->installment_amount }}</td>
                                        <td>
                                          @if($value['status']==0) 
                                            @php $paidSoFar += $CourseData->installment_amount; @endphp
                                          Paid 
                                          @else 
                                            @php $balanceRem += $CourseData->installment_amount; @endphp
                                          Unpaid 
                                          @endif</td>
                                        <td>{{ $value['payment_date'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @php $total_full_price = $CourseData->course_old_price - ($CourseData->course_old_price - $CourseData->course_final_price) ; 
                            $totalInstallment = $CourseData->installment_amount * $CourseData->no_of_installment;
                            @endphp
                            {{-- @if($totalInstallment == $paidSoFar) --}}
                            <div class="d-flex justify-content-between align-items-center gap-2 my-2">
                                <p class="mb-0">
                                    Balance Remaining: <b>€ {{ $balanceRem }}</b>
                                    &nbsp;&nbsp; | &nbsp;&nbsp;
                                    Paid So Far: <b>€ {{ $paidSoFar }}</b>
                                </p>
                                &nbsp;&nbsp;
                            <form class="checkoutform" data-course="{{ $courseId }}">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ base64_encode($courseId) }}">
                                <input type="hidden" class="selected_installments" name="selected_installments" value="">
                                <input type="hidden" class="form-control student_course_master_id" name="student_course_master_id" value="{{base64_encode($installments[0]['student_course_master_id'])}}">
                                <input type="hidden" class="form-control overall_total" name="overall_total" value="{{base64_encode($CourseData->course_old_price)}}">
                                <input type="hidden" class="form-control overall_old_total" name="overall_old_total" value="{{base64_encode($CourseData->course_old_price -$CourseData->course_final_price)}}">
                                <input type='hidden' class="form-control overall_full_totals" name="overall_full_totals" value="{{base64_encode($total_full_price)}}">
                                <input type='hidden' class="form-control directchekout" name="directchekout" value="{{base64_encode('0')}}">
                                <input type="hidden" class="form-control payment_type_installment" name="payment_type_installment" value="InstallmentPayment">
                                <input type="hidden" class="multiple_install_no" name="multiple_install_no" value="">
                                <input type="hidden" class="multiple_amount" name="multiple_amount">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary buyCourseBtn">Pay Now <span class="payable_amount" data-course="{{ $courseId }}"></span> </button>
                                </div>
                            </form>
                            </div>
                            {{-- @endif --}}
                          </div>
                        </div>
                      </div>
                      @php $i++; @endphp
                      @endforeach

                    </div>
                  </div>
                </div>
        </div>
    </section>
</main>
<script>
//  $(document).ready(function() {
//     var $checkboxes = $('.installment-checkbox');

//     $checkboxes.on('change', function() {
//         var index = $checkboxes.index(this);

//         if ($(this).is(':checked')) {
//             // Enable the next unpaid checkbox
//             for (var i = index + 1; i < $checkboxes.length; i++) {
//                 if ($($checkboxes[i]).is(':disabled')) {
//                     $($checkboxes[i]).prop('disabled', false);
//                     break;
//                 }
//             }
//         } else {
//             // Uncheck and disable all subsequent checkboxes
//             for (var i = index + 1; i < $checkboxes.length; i++) {
//                 $($checkboxes[i]).prop('checked', false);
//                 $($checkboxes[i]).prop('disabled', true);
//             }
//         }
//     });
// });
// $(document).ready(function() {
    $('.installment-checkbox').on('change', function() {
        var $this = $(this);
        var courseId = $this.data('course');

        // Get all checkboxes for this course
        var $courseCheckboxes = $('.installment-checkbox[data-course="' + courseId + '"]');
        var index = $courseCheckboxes.index(this);

        if ($this.is(':checked')) {
            // Enable next unpaid checkbox
            for (var i = index + 1; i < $courseCheckboxes.length; i++) {
                if ($($courseCheckboxes[i]).is(':disabled')) {
                    $($courseCheckboxes[i]).prop('disabled', false);
                    break;
                }
            }
        } else {
            // Uncheck and disable all subsequent checkboxes in this course
            for (var i = index + 1; i < $courseCheckboxes.length; i++) {
                $($courseCheckboxes[i]).prop('checked', false);
                $($courseCheckboxes[i]).prop('disabled', true);
            }
        } 
        var $checked = $('.installment-checkbox[data-course="' + courseId + '"]:checked');
        var lastChecked = $(".installment-checkbox[data-course='" + courseId + "']:checked").last().val();
        var selected = $('.installment-checkbox[data-course="' + courseId + '"]:checked').map(function() { return $(this).val(); }).get();

        var totalAmount = 0;
        $checked.each(function() {
            totalAmount += parseFloat($(this).data('amount')) || 0;
            
        });

        if (lastChecked) {
            $(".selected_installments").val(lastChecked); // set in hidden input
            $('.multiple_install_no').val(selected.join(','));
            $('.multiple_amount').val(totalAmount); // total of amounts
            $('.payable_amount[data-course="' + courseId + '"]').text("€ " + totalAmount);

        } else {
            $(".selected_installments").val(""); // reset if nothing checked
            $('.multiple_install_no').val("");
            $('.multiple_amount').val(""); // total of amounts
            $('.payable_amount[data-course="' + courseId + '"]').text("");

        }
            
    });

    $('.buyCourseBtn').on('click', function() {
        var $form = $(this).closest('form');
        var courseId = $form.data('course');

        var selected = $('.installment-checkbox[data-course="' + courseId + '"]:checked')
            .map(function() { return $(this).val(); }).get();

        if (selected.length == 0) {
            swal({
                title: "",
                text: "Select a checkbox to pay.",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "", // Text for the cancel button
                        value: null, // Return null if "Ok" is clicked
                        visible: false, // Ensure the button is visible
                        className: "", // Optional: add custom class
                        closeModal: true // Close the modal on click
                    },
                    confirm: {
                        text: "Ok", // Text for the confirmation button
                    }
                },
                dangerMode: true,
            }).then((willVerified) => {
                if (willVerified) {
                    Swal.close(); // closes the alert manually             
                }
            });
            return false;
        }
        // $form.find('.selected_installments').val(selected.join(','));
        var $form = $(this).closest('form');
        var baseUrl = window.location.origin;
        $form.attr('action', baseUrl + "/checkout");
        $form.attr('method', 'POST');
        $form.submit();
    });
// });


  </script>
@endsection
