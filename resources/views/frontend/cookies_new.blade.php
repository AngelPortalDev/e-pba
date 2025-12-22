@extends('frontend.master')
@section('content')

<main class="cookies-page">
    <div class="py-md-8 py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- caption-->
                    <h1 class="fw-bold mb-1 display-4">{!! __('cookies.line_2') !!}</h1>
                    <p class="mt-2">{!! __('cookies.line_3') !!}</p>
                      <p class="mt-2">{!! __('cookies.line_4') !!}</p>
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
                                <li class="breadcrumb-item active" aria-current="page">{!! __('cookies.line_2') !!}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="pb-8 pt-7 bg-white">
        <div class="container">
          <!-- javascript behavior vertical pills -->
          <div class="row">
            <div class="col-md-3">
              <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-Third-Party-Cookies-tab" data-bs-toggle="pill" href="#v-pills-Third-Party-Cookies" role="tab" aria-controls="v-pills-Third-Party-Cookies" aria-selected="false">{!! __('cookies.1_title') !!}</a>
                <a class="nav-link" id="v-pills-Cookies-Preference-tab" data-bs-toggle="pill" href="#v-pills-Cookies-Preference" role="tab" aria-controls="v-pills-Cookies-Preference" aria-selected="false">{!! __('cookies.2_title') !!}</a>
                <a class="nav-link" id="v-pills-Functional-Cookies-tab" data-bs-toggle="pill" href="#v-pills-Functional-Cookies" role="tab" aria-controls="v-pills-Functional-Cookies" aria-selected="false">{!! __('cookies.3_title') !!}</a>
                <a class="nav-link" id="v-pills-Social-Media-Cookies-tab" data-bs-toggle="pill" href="#v-pills-Social-Media-Cookies" role="tab" aria-controls="v-pills-Social-Media-Cookies" aria-selected="false">{!! __('cookies.4_title') !!}</a>
                <a class="nav-link" id="v-pills-Advertising-Cookies-tab" data-bs-toggle="pill" href="#v-pills-Advertising-Cookies" role="tab" aria-controls="v-pills-Advertising-Cookies" aria-selected="false">{!! __('cookies.5_title') !!}</a>
                <a class="nav-link" id="v-pills-Strictly-Necessary-Cookies-tab" data-bs-toggle="pill" href="#v-pills-Strictly-Necessary-Cookies" role="tab" aria-controls="v-pills-Strictly-Necessary-Cookies" aria-selected="false">{!! __('cookies.6_title') !!}</a>
                <a class="nav-link" id="v-pills-Performance-Cookies-tab" data-bs-toggle="pill" href="#v-pills-Performance-Cookies" role="tab" aria-controls="v-pills-Performance-Cookies" aria-selected="false">{!! __('cookies.7_title') !!}</a>
                <a class="nav-link" id="v-pills-Cookies-privacy-policy-tab" data-bs-toggle="pill" href="#v-pills-Cookies-privacy-policy" role="tab" aria-controls="v-pills-Cookies-privacy-policy" aria-selected="false"> {!! __('cookies.8_title') !!}</a>

              </div>
            </div>
            <div class="col-md-9 mt-3 mt-md-0 cookiesMobileSection">
              <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-Third-Party-Cookies" role="tabpanel" aria-labelledby="v-pills-Third-Party-Cookies">
                  <p>{!! __('cookies.line_7') !!}</p>
                  <p class="mt-2">{!! __('cookies.line_8') !!}</p>
                </div>
                <div class="tab-pane fade" id="v-pills-Cookies-Preference" role="tabpanel" aria-labelledby="v-pills-Cookies-Preference-tab">
                  <p>{!! __('cookies.line_9') !!}</p>
                  <p class="mt-2">{!! __('cookies.line_10') !!}</p>
                </div>
                <div class="tab-pane fade" id="v-pills-Functional-Cookies" role="tabpanel" aria-labelledby="v-pills-Functional-Cookies-tab">
                  <p>{!! __('cookies.line_11') !!}</p>
                  <p class="mt-2">{!! __('cookies.line_12') !!}</p>
                  <p class="mt-2">{!! __('cookies.line_13') !!}</p>
                </div>
                <div class="tab-pane fade" id="v-pills-Social-Media-Cookies" role="tabpanel" aria-labelledby="v-pills-Social-Media-Cookies-tab">
                  <p>{!! __('cookies.line_14') !!}</p>
                  <p class="mt-2">{!! __('cookies.line_15') !!}</p>
                </div>
                <div class="tab-pane fade" id="v-pills-Advertising-Cookies" role="tabpanel" aria-labelledby="v-pills-Advertising-Cookies-tab">
                  <p>{!! __('cookies.line_16') !!}</p>
                  <p class="mt-2">{!! __('cookies.line_17') !!}</p>
                </div>
                <div class="tab-pane fade" id="v-pills-Strictly-Necessary-Cookies" role="tabpanel" aria-labelledby="v-pills-Strictly-Necessary-Cookies-tab">
                  <p>{!! __('cookies.line_18') !!}</p>
                  <p class="mt-2">{!! __('cookies.line_19') !!}</p>
                </div>
                <div class="tab-pane fade" id="v-pills-Performance-Cookies" role="tabpanel" aria-labelledby="v-pills-Performance-Cookies">
                  <p>
                    {!! __('cookies.line_20') !!}</p>
                  <p class="mt-2">{!! __('cookies.line_21') !!}</p>
                </div>
                <div class="tab-pane fade" id="v-pills-Cookies-privacy-policy" role="tabpanel" aria-labelledby="v-pills-Cookies-privacy-policy">
                  <h4 class="mb-0 text-primary fw-bold">
                    {!! __('cookies.8_1title') !!}
                  </h4>
                  <p class="mt-0">{!! __('cookies.line_22') !!}</p>
                  <p class="mt-1">{!! __('cookies.line_23') !!}</p>
                  <h4 class="mt-3 mb-0 text-primary fw-bold"> {!! __('cookies.8_2title') !!}</h4>
                  <p>{!! __('cookies.line_24') !!}</p>
                  <h4 class="mt-3 mb-0 text-primary fw-bold"> {!! __('cookies.8_3title') !!}</h4>
                  <p>{!! __('cookies.line_25') !!}</p>
                  <p>{!! __('cookies.line_26') !!}</p>
                  <p>{!! __('cookies.line_27') !!}</p>
                  <p>{!! __('cookies.line_28') !!}</p>
                  <p><strong class="heading-subtitle"><u>{!! __('cookies.line_29') !!}</u></strong></p>
                  <p><ul><li>{!! __('cookies.line_31') !!}</li></ul></p>
                  <p><ul><li> {!! __('cookies.line_32') !!}</li></ul></p>
                  <p><strong class="heading-subtitle"><u>{!! __('cookies.line_34') !!}</u></strong></p>
                  <p><ul><li>{!! __('cookies.line_35') !!}</li></ul></p>
                  <p><ul><li>{!! __('cookies.line_36') !!}</li></ul></p>
                  <p><strong class="heading-subtitle"><u>{!! __('cookies.line_38') !!}</u></strong></p>
                  <p><ul><li>{!! __('cookies.line_39') !!}</p>
                  <p><ul><li>{!! __('cookies.line_40') !!}</p>
                  <h4 class="mt-3 mb-0 fw-bold">{!! __('cookies.8_4title') !!}</h4>
                  <p>{!! __('cookies.line_41') !!}</p>

                  <p>
                    <ol type="i">
                      <li>{!! __('cookies.token.line_42') !!}</li>
                    <div class="table-responsive">
                      <table>
                          <thead>
                              <tr>
                                  <th>{!! __('cookies.token.heading1') !!}</th>
                                  <th>{!! __('cookies.token.heading2') !!}</th>
                                  <th>{!! __('cookies.token.heading3') !!}</th>
                                  <th>{!! __('cookies.token.heading4') !!}</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td>{!! __('cookies.token.lines.xsrf.cookiekey') !!}</td>
                                  <td>{!! __('cookies.token.lines.xsrf.cookietype') !!}</td>
                                  <td>{!! __('cookies.token.lines.xsrf.expiration') !!}</td>
                                  <td>{!! __('cookies.token.lines.xsrf.description') !!}</td>
                              </tr>
                              <tr>
                                <td>{!! __('cookies.token.lines.csrf.cookiekey') !!}</td>
                                <td>{!! __('cookies.token.lines.csrf.cookietype') !!}</td>
                                <td>{!! __('cookies.token.lines.csrf.expiration') !!}</td>
                                <td>{!! __('cookies.token.lines.csrf.description') !!}</td>
                               </tr>
                          </tbody>
                      </table>
                    </div>
                      <li>{!! __('cookies.cookies.line_43') !!}</li>
                    <div class="table-responsive">
                      <table>
                          <thead>
                              <tr>
                                <th>{!! __('cookies.token.heading1') !!}</th>
                                <th>{!! __('cookies.token.heading2') !!}</th>
                                <th>{!! __('cookies.token.heading3') !!}</th>
                                <th>{!! __('cookies.token.heading4') !!}</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <td>{!! __('cookies.cookies.conetnt.1.cookiekey') !!}</td>
                                <td>{!! __('cookies.cookies.conetnt.1.cookietype') !!}</td>
                                <td>{!! __('cookies.cookies.conetnt.1.expiration') !!}</td>
                                <td>{!! __('cookies.cookies.conetnt.1.description') !!}</td>
                              </tr>
                              <tr>
                                <td>{!! __('cookies.cookies.conetnt.2.cookiekey') !!}</td>
                                <td>{!! __('cookies.cookies.conetnt.2.cookietype') !!}</td>
                                <td>{!! __('cookies.cookies.conetnt.2.expiration') !!}</td>
                                <td>{!! __('cookies.cookies.conetnt.2.description') !!}</td>
                              </tr>
                              <tr>
                                <td>{!! __('cookies.cookies.conetnt.3.cookiekey') !!}</td>
                                <td>{!! __('cookies.cookies.conetnt.3.cookietype') !!}</td>
                                <td>{!! __('cookies.cookies.conetnt.3.expiration') !!}</td>
                                <td>{!! __('cookies.cookies.conetnt.3.description') !!}</td>
                              </tr>
                          </tbody>
                      </table>
                    </div> 
                      <li>{!! __('cookies.targeting_cookies.line_44') !!}</li>
                    <div class="table-responsive">
                      <table>
                          <thead>
                              <tr>
                                <th>{!! __('cookies.token.heading1') !!}</th>
                                <th>{!! __('cookies.token.heading2') !!}</th>
                                <th>{!! __('cookies.token.heading3') !!}</th>
                                <th>{!! __('cookies.token.heading4') !!}</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <td>{!! __('cookies.targeting_cookies.conetnt.1.cookiekey') !!}</td>
                                <td>{!! __('cookies.targeting_cookies.conetnt.1.cookietype') !!}</td>
                                <td>{!! __('cookies.targeting_cookies.conetnt.1.expiration') !!}</td>
                                <td>{!! __('cookies.targeting_cookies.conetnt.1.description') !!}</td>
                              </tr>
                          </tbody>
                      </table>
                    </div>
                    </ol>
                  </p>
                  {{-- <p>{!!__('cookies.line_45') !!}</p> --}}
                  
                  <p><strong class="heading-subtitle">{!!__('cookies.line_47') !!}</strong></p>
                  
                  <p>{!!__('cookies.line_48') !!}</p>
                  <p>{!!__('cookies.line_49') !!}</p>
                  <p>{!!__('cookies.line_50') !!}</p>
                  <p>{!!__('cookies.line_51') !!}</p>
                  <p>{!!__('cookies.line_52') !!}</p>
                  <p>{!!__('cookies.line_53') !!}</p>
                  <ol type="a">
                  <li class="cookie_style">{!!__('cookies.line_54') !!}</li>  
                  <li class="cookie_style">{!!__('cookies.line_55') !!}</li>  
                  <li class="cookie_style">{!!__('cookies.line_56') !!}</li>  
                  <li class="cookie_style">{!!__('cookies.line_57') !!}</li>  
                  <li class="cookie_style">{!!__('cookies.line_58') !!}</li>  
                  <li class="cookie_style">{!!__('cookies.line_59') !!}</li>  
                  <li class="cookie_style">{!!__('cookies.line_60') !!}</li>  

                  </ol>
                  <p>{!!__('cookies.line_61') !!}  </p>

                 <p class="mt-1">  {!!__('cookies.line_62') !!}</a><span style="color: #000">.</span></p>
                </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</main>

@endsection