@extends('frontend.master')
@section('content')

<main>
    <div class="py-md-8 py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- caption-->
                    <h1 class="fw-bold mb-1 display-4 ">{{__('terms.title')}}</h1>
                    <h5>{{__('terms.subtitle')}}</h5>
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
                                <li class="breadcrumb-item active" aria-current="page">{{__('terms.breadcrumb_title')}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="pb-8 pt-3 bg-white">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-12">
              <!-- date -->
              <div class="py-3 mb-5 border-bottom">
                <h3>
                  <span class="">{{__('terms.version')}}</span>
                </h3>
                <h3>
                  <span class="">{{__('terms.effective_date')}}</span>
                </h3>
              </div>
              
              <p>{{__('terms.intro')}}</p>
              <p>{{__('terms.intro_2')}}{{__('terms.intro_3')}}{{__('terms.intro_4')}}{{__('terms.intro_6')}}</p>
              <p>{{__('terms.intro_6')}}</p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">1. {{__('terms.section_1_title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.1_i')}}</li>
                  <ol type="a">
                    <li>{{__('terms.1_a')}}</li>
                    <li>{{__('terms.1_b')}}{{__('terms.1_b_2')}}</li>
                    <li>{{__('terms.1_c')}}</li>
                    <li>{{__('terms.1_d')}}</li>
                  </ol>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">2.	{{__('terms.section_2_title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.2_i')}}</li>
                  <li>{{__('terms.2_ii')}}</li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">3.	{{__('terms.section_3_title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.3_i')}}</li>
                  <li>{{__('terms.3_ii')}}</li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">4.	{{__('terms.section_4_title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.4.i')}}</li>
                  <li>{{__('terms.4.ii')}}</li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">5.	{{__('terms.section_5_title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.5.i')}}</li>
                  <li>{{__('terms.5.ii')}}</li>
                  <li>{{__('terms.5.iii')}}</li>
                  <li>{{__('terms.5.iv')}}</li>
                  <li>{{__('terms.5.v')}}</li>
                  
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">6.	{{__('terms.section_6_title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.6.i')}}</li>
                  <li>{{__('terms.6.ii')}}</li><li>{{__('terms.6.iii')}}</li><li>{{__('terms.6.iv')}}</li><li>{{__('terms.6.v')}}</li>
                  <ol type="a">
                    <li>{{__('terms.6.v.a')}}</li> 
                    <li>{{__('terms.6.v.b')}}</li> 
                    <ol type="a">
                      <li>{{__('terms.6.v.b.a')}}</li> 
                      <li>{{__('terms.6.v.b.b')}}</li> 
                      <li>{{__('terms.6.v.b.c')}}</li> 
                      <li>{{__('terms.6.v.b.d')}}</li> 
                      <li>{{__('terms.6.v.b.e')}}</li> 

                    </ol>
                    <li>{{__('terms.6.v.b.c')}}</li>
                     
                        
                      <li>{{__('terms.6.v.b.d')}}</li>
                      <li>{{__('terms.6.v.b.e')}}</li>

                  </ol>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">7.	{{__('terms.section_7_title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.7.i')}}</li>
                  <li>{{__('terms.7.ii')}}</li>
                  <li>{{__('terms.7.iii')}}</li>
                  <li>{{__('terms.7.iv')}}</li>
                  <li>{{__('terms.7.v')}}</li>
                  <li>{{__('terms.7.vi')}}</li>
                  <li>{{__('terms.7.vii')}}</li>
                  <li>{{__('terms.7.viii')}}</li>


                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">8.	{{__('terms.section_8_title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.8.i')}}</li>
                  <li>{{__('terms.8.ii')}}</li>
                  <li>{{__('terms.8.iii')}}</li>
                  <li>{{__('terms.8.iv')}}</li>
                  <li>{{__('terms.8.v')}}</li>
                  <li>{{__('terms.8.vi')}}</li>
                  <li>{{__('terms.8.vii')}}</li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">9.	{{__('terms.section_9_title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.9.i')}}</li>
                  <li>{{__('terms.9.ii')}}</li>
                  <li>{{__('terms.9.iii')}}</li>
                  <li>{{__('terms.9.iv')}}</li>
                  </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">10.	{{__('terms.10.title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.10.i')}}</li>

                  <ol type="a">
                    <li>{{__('terms.10.i.a')}}</li>
                    <li>{{__('terms.10.i.b')}}</li>
                    <li>{{__('terms.10.i.c')}}</li>
                    <li>{{__('terms.10.i.d')}}</li>
                    <li>{{__('terms.10.i.e')}}</li>
                    <li>{{__('terms.10.i.f')}}</li>
                    <li>{{__('terms.10.i.g')}}</li>

                  </ol>
                  <li>{{__('terms.10.ii')}}</li>
                  <li>{{__('terms.10.iii')}}</li>
                  <li>{{__('terms.10.iv')}}</li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">11. {{__('terms.11.title')}}</h3>
              <p>
                <ol type="i">
                  <p></p>
                  <p><u>{{__('terms.11.p')}}</u></p>
                  <li>{{__('terms.11.i')}}</li>
                  <li>{{__('terms.11.ii')}}</li>
                  <li>{{__('terms.11.iii')}}</li>
                  <li>{{__('terms.11.iv')}}</li>
                
                  <p></p>
                  <p><u>{{__('terms.11.p1')}}</u></p>
                  <li>{{__('terms.11.v')}}</li>
                  <li>{{__('terms.11.vi')}}</li>
                  <li>{{__('terms.11.vii')}}</li>
                  <li>{{__('terms.11.viii')}}</li>
                  <li>{{__('terms.11.ix')}}</li>
              
                  <p></p>
                  <p><u>{{__('terms.11.p2')}}</u></p>
                  <li>{{__('terms.11.x')}}</li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">12. {{__('terms.12.title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.12.i')}}</li>
                  <li>{{__('terms.12.ii')}}</li>
                  <li>{{__('terms.12.iii')}}</li>
                  <li>{{__('terms.12.iv')}}</li>
                  <li>{{__('terms.12.v')}}</li>
                  <li>{{__('terms.12.vi')}}</li>
                  <li>{{__('terms.12.vii')}}</li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">13. {{__('terms.13.title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.13.i')}}</li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">14.	{{__('terms.14.title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.14.i')}}</li>
                  <li>{{__('terms.14.ii')}}</li>
                  <li>{{__('terms.14.iii')}}</li>
                  <li>{{__('terms.14.iv')}}</li>
                  <li>{{__('terms.14.v')}} </li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">15.	{{__('terms.15.title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.15.i')}}</li>
                  <li>{{__('terms.15.ii')}}</li>
                  <li>{{__('terms.15.iii')}}</li>
                  <li>{{__('terms.15.iv')}}</li>
                  <li>{{__('terms.15.v')}} </li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">16.	{{__('terms.16.title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.16.i')}}</li>
                  <li>{{__('terms.16.ii')}}</li>
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">17.	{{__('terms.17.title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.17.i')}}</li>  
                  <li>{{__('terms.17.ii')}}</li>  
                  <li>{{__('terms.17.iii')}}</li>  

                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">18.	{{__('terms.18.title')}}</h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.18.i')}}</li>  
                  <li>{{__('terms.18.ii')}}</li>  
                  <li>{{__('terms.18.iii')}}</li>   

                  <li>{{__('terms.18.iv')}}</li>   
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">19.	{{__('terms.19.title')}} </h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.19.i')}}</li>   
                  <li>{{__('terms.19.ii')}}</li>   

                  
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">20.	{{__('terms.20.title')}} </h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.20.i')}}</li>   
                </ol>
              </p>
              <h3 class="mt-3 fw-bold mb-0 heading-title">21.	{{__('terms.21.title')}} </h3>
              <p>
                <ol type="i">
                  <li>{{__('terms.21.i')}}</li>   
                  <li>{{__('terms.21.ii')}}</li>   
                  <li>{{__('terms.21.iii')}}</li>   

                
                </ol>
              </p>
            </div>
          </div>
        </div>
      </section>
</main>

@endsection