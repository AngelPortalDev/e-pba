@extends('frontend.master')
@section('content')

<main>
    <div class="py-md-8 py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- caption-->
                    <h1 class="fw-bold mb-1 display-4">{{__('privacy.title')}}</h1>
                    <p class="fw-bold mt-2">{{__('privacy.effective_date')}}</p>
                    {{-- <p class="fw-bold mt-2">Last updated: 06/06/2024</p> --}}
                    <p class="mt-2">{!! __('privacy.intro', ['webiste_url' => route('/'),]) !!}</p>
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
                                <li class="breadcrumb-item active" aria-current="page">{{__('privacy.breadcrumb_title')}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="pb-8 pt-3 bg-white">
        <div class="container">
          <div class="row ">
            <div class=" col-12">

              <h3 class="fw-bold mb-0 heading-title">1. {!! __('privacy.sections.who_we_are.title') !!}</h3>
              <p>{!! __('privacy.sections.who_we_are.content') !!}</p>
              <p>{!! __('privacy.sections.who_we_are.portal_description') !!}</p>
              <p>{!! __('privacy.sections.who_we_are.tou_reference') !!}</p>
              <h3 class="fw-bold mb-0 heading-title">2. {!! __('privacy.sections.policy_coverage.title') !!}</h3>
              <p>{!! __('privacy.sections.policy_coverage.content') !!}</p>
              <p>{!! __('privacy.sections.policy_coverage.clarification') !!}</p>
              <p>{!! __('privacy.sections.policy_coverage.definitions') !!}</p>
              <h3 class="fw-bold mb-0 heading-title">3. {!! __('privacy.sections.data_types.title') !!}</h3>
              <p>{!! __('privacy.sections.data_types.intro') !!}</p>
              <div class="table-responsive">
                <table border="1" style="border-collapse: collapse; width: 100%;">
                  <thead>
                    <tr>
                      <th style="padding: 8px; text-align: left;">{{ __('privacy.sections.data_types.categories.heading.title') }}</th>
                      <th style="padding: 8px; text-align: left;">{{ __('privacy.sections.data_types.categories.heading.description') }}</th>
                      <th style="padding: 8px; text-align: left;">{{ __('privacy.sections.data_types.categories.heading.source') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.profile.title') !!}</td>
                      <td style="padding: 8px;">
                        {!! __('privacy.sections.data_types.categories.profile.description') !!}                    </td>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.profile.source') !!}</td>
                    </tr>
                    <tr>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.interaction.title') !!}</td>
                      <td style="padding: 8px;">
                        {!! __('privacy.sections.data_types.categories.interaction.description') !!}                    </td>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.interaction.source') !!}</td>
                    </tr>
                    <tr>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.learning_information.title') !!}
                      </td>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.learning_information.description') !!}
                      </td>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.learning_information.source') !!}</td>
                    </tr>
                    <tr>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.shared_content.title') !!}
                      </td>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.shared_content.description') !!}
                      </td>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.shared_content.source') !!}</td>
                    </tr>
                    <tr>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.financial_information.title') !!}
                      </td>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.financial_information.description') !!}
                      </td>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.financial_information.source') !!}</td>
                    </tr>
                    <tr>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.usage_information.title') !!}
                      </td>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.usage_information.description') !!}
                      </td>
                      <td style="padding: 8px;">{!! __('privacy.sections.data_types.categories.usage_information.source') !!}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <h3 class="fw-bold mb-0 heading-title">4.{!! __('privacy.sections.data_usage.title') !!}
              </h3>
              <p>{!! __('privacy.sections.data_usage.primary_objective') !!}
              </p>
              <p>{!! __('privacy.sections.data_usage.legal_basis') !!}</p>
              <div class="table-responsive">
                <table border="1" style="border-collapse: collapse; width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding: 8px; text-align: left;">{{ __('privacy.sections.data_usage.purposes.heading.title') }}</th>
                            <th style="padding: 8px; text-align: left;">{{ __('privacy.sections.data_usage.purposes.heading.type') }}</th>
                            <th style="padding: 8px; text-align: left;">{{ __('privacy.sections.data_usage.purposes.heading.basis') }} </th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td style="padding: 8px;"><u>{!! __('privacy.sections.data_usage.purposes.onboarding.title') !!}
                            </u><p></p><p>{!! __('privacy.sections.data_usage.purposes.onboarding.description') !!}
                            </p></td>
                            <td style="padding: 8px;">{!! __('privacy.sections.data_usage.purposes.onboarding.data_types') !!} </td>
                            <td style="padding: 8px;">{!! __('privacy.sections.data_usage.purposes.onboarding.basis') !!}</td>
                        </tr>
                        <tr>
                          <td style="padding: 8px;"><u>{!! __('privacy.sections.data_usage.purposes.deliver_courses.title') !!}
                          </u><p></p><p>{!! __('privacy.sections.data_usage.purposes.deliver_courses.description') !!}
                          </p></td>
                          <td style="padding: 8px;">{!! __('privacy.sections.data_usage.purposes.deliver_courses.data_types') !!} </td>
                          <td style="padding: 8px;">{!! __('privacy.sections.data_usage.purposes.deliver_courses.basis') !!}</td>
                      </tr>
                        <tr>
                            <td style="padding: 8px;"><u>{!! __('privacy.sections.data_usage.purposes.assessments.title') !!}</u><p></p><p>{!! __('privacy.sections.data_usage.purposes.assessments.description') !!}</p></td>
                            <td style="padding: 8px;">{!! __('privacy.sections.data_usage.purposes.assessments.data_types') !!}</td>
                            <td style="padding: 8px;"><p>{!! __('privacy.sections.data_usage.purposes.assessments.basis') !!}</p></td>
                        </tr>
                        <tr>
                            <td style="padding: 8px;"><u>{!! __('privacy.sections.data_usage.purposes.our_portal.title') !!}</u><p></p><p>{!! __('privacy.sections.data_usage.purposes.our_portal.description') !!}</p></td>
                            <td style="padding: 8px;"> {!! __('privacy.sections.data_usage.purposes.our_portal.data_types') !!}</td>
                            <td style="padding: 8px;"><p>{!! __('privacy.sections.data_usage.purposes.our_portal.basis') !!} </p></td>
                        </tr>
                        <tr>
                          <td style="padding: 8px;">
                            <u>{!! __('privacy.sections.data_usage.purposes.applicable_laws.title') !!}</u><p></p>
                            <p>{!! __('privacy.sections.data_usage.purposes.applicable_laws.description') !!}</p>
                          </td>
                          <td style="padding: 8px;">
                            {!! __('privacy.sections.data_usage.purposes.applicable_laws.data_types') !!}
                          </td>
                          <td style="padding: 8px;">
                            {!! __('privacy.sections.data_usage.purposes.applicable_laws.basis') !!}
                          </td>
                        </tr>
                        <tr>
                          <td style="padding: 8px;">
                            <u>{!! __('privacy.sections.data_usage.purposes.payments_fees.title') !!}</u><p></p><p>{!! __('privacy.sections.data_usage.purposes.payments_fees.description') !!}</p>
                          </td>
                          <td style="padding: 8px;">
                            {!! __('privacy.sections.data_usage.purposes.payments_fees.data_types') !!}
                          </td>
                          <td style="padding: 8px;">
                            {!! __('privacy.sections.data_usage.purposes.payments_fees.basis') !!}
                          </td>
                        </tr>
                        <tr>
                          <td style="padding: 8px;">
                            <u>{!! __('privacy.sections.data_usage.purposes.database_marketing.title') !!}</u><p></p>
                            <p>{!! __('privacy.sections.data_usage.purposes.database_marketing.description') !!}</p>
                          </td>
                          <td style="padding: 8px;">
                            {!! __('privacy.sections.data_usage.purposes.database_marketing.data_types') !!}
                          </td>
                          <td style="padding: 8px;">
                            <p>{!! __('privacy.sections.data_usage.purposes.database_marketing.title') !!}</p>
                          </td>
                        </tr>
                        <tr>
                          <td style="padding: 8px;">
                            <u>{!! __('privacy.sections.data_usage.purposes.analytics.title') !!}</u><p></p>
                            <p>{!! __('privacy.sections.data_usage.purposes.analytics.description') !!}</p>
                          </td>
                          <td style="padding: 8px;">
                            {!! __('privacy.sections.data_usage.purposes.analytics.data_types') !!}
                          </td>
                          <td style="padding: 8px;">
                            {!! __('privacy.sections.data_usage.purposes.analytics.basis') !!}
                          </td>
                        </tr>
                        <tr>
                          <td style="padding: 8px;">
                            <u>{!! __('privacy.sections.data_usage.purposes.our_interests.title') !!}</u><p></p>
                            <p>{!! __('privacy.sections.data_usage.purposes.our_interests.description') !!}</p>
                          </td>
                          <td style="padding: 8px;">
                            {!! __('privacy.sections.data_usage.purposes.our_interests.data_types') !!}
                          </td>
                          <td style="padding: 8px;">
                            {!! __('privacy.sections.data_usage.purposes.our_interests.basis') !!}
                          </td>
                        </tr>
                        <tr>
                          <td style="padding: 8px;">
                            <u>{!! __('privacy.sections.data_usage.purposes.business_transactions.title') !!}</u><p></p>
                            <p>{!! __('privacy.sections.data_usage.purposes.business_transactions.description') !!}</p>
                          </td>
                          <td style="padding: 8px;">
                            {!! __('privacy.sections.data_usage.purposes.business_transactions.data_types') !!}
                          </td>
                          <td style="padding: 8px;">
                            <p>{!! __('privacy.sections.data_usage.purposes.business_transactions.basis') !!}</p>
                          </td>
                        </tr>
                    </tbody>
                </table>
              </div>
                <p><u>{!! __('privacy.sections.data_usage.title_purpose') !!}</u></p>
                <p>{!! __('privacy.sections.data_usage.change_of_purpose') !!}</p>
                <h3 class="fw-bold mb-0 heading-title">5. {!! __('privacy.section.5') !!}</h3>
                <p>{!! __('privacy.5.content') !!}<p>
                <h3 class="fw-bold mb-0 heading-title">6. {!! __('privacy.6.title') !!}</h3>
                <p>{!! __('privacy.6.content') !!}</p>
                <h3 class="fw-bold mb-0 heading-title">7. {!! __('privacy.7.title') !!}</h3>
                {!! __('privacy.7.content') !!}
                <h3 class="fw-bold mb-0 heading-title">8. {!! __('privacy.8.title') !!} </h3>
                <p>{!! __('privacy.8.content') !!}</p>
                <h3 class="fw-bold mb-0 heading-title">9. {!! __('privacy.sections.data_sharing.title') !!}</h3>
                <p> {!! __('privacy.sections.data_sharing.content') !!}</p>
                <p> {!! __('privacy.sections.data_sharing.conetnt1') !!}</p>
                <ol type="a">
                   <li> {!! __('privacy.sections.data_sharing.parties.lecturers') !!}</li>
                   <li> {!! __('privacy.sections.data_sharing.parties.anti_cheating') !!}</li>
                   <li> {!! __('privacy.sections.data_sharing.parties.thirdparty') !!}</li>
                   <li> {!! __('privacy.sections.data_sharing.parties.d') !!}</li>
                   <li> {!! __('privacy.sections.data_sharing.parties.e') !!}</li>
                   <li> {!! __('privacy.sections.data_sharing.parties.f') !!}</li>
                   <li> {!! __('privacy.sections.data_sharing.parties.g') !!}</li>
                </ol>
                <p>{!! __('privacy.sections.data_sharing.wemay') !!}</p>
                <p>{!! __('privacy.sections.data_sharing.safeguards') !!}<p>
                <h3 class="fw-bold mb-0 heading-title">10. {!! __('privacy.sections.data_transfers.title') !!}</h3>
                <p>{!! __('privacy.sections.data_transfers.content') !!} {!! __('privacy.sections.data_transfers.india_example') !!}</p>
                <p>{!! __('privacy.sections.data_transfers.safeguards') !!}<p>
                <ol type="a">
                <li>{!! __('privacy.sections.data_transfers.safeguard_methods1') !!}</li>
                <li>{!! __('privacy.sections.data_transfers.safeguard_methods2') !!}</li>
                </ol>
                <h3 class="fw-bold mb-0 heading-title">11.  {!! __('privacy.sections.rights.title') !!}  </h3>
                <p>{!! __('privacy.sections.rights.intro') !!}<p>
                <ol type="a">
                    <li>{!! __('privacy.sections.rights.rights_list.access') !!}</li>
                    <li>{!! __('privacy.sections.rights.rights_list.rectification') !!}</li>
                    <li>{!! __('privacy.sections.rights.rights_list.c') !!}</li>
                    <li>{!! __('privacy.sections.rights.rights_list.d') !!}</li>
                    <li>{!! __('privacy.sections.rights.rights_list.e') !!}</li>
                    <li>{!! __('privacy.sections.rights.rights_list.f') !!}</li>
                </ol>
                <li>{!! __('privacy.sections.rights.list1') !!}</li>
                <li>{!! __('privacy.sections.rights.withdrawal') !!}</li>
                <li>{!! __('privacy.sections.rights.contact') !!}</li>
                <p></p>
                <h3 class="fw-bold mb-0 heading-title text-primary">12. {!! __('privacy.12.title') !!} </h3>
               <p>{!! __('privacy.12.1') !!}</p>
               <p>{!! __('privacy.12.2') !!}</p>
                <p> {!! __('privacy.12.3') !!}</p>
                  <p> {!! __('privacy.12.4') !!}</p>

                <h3 class="fw-bold mb-0 heading-title text-primary">{!! __('privacy.13.title') !!}</h3>
              <p>{!! __('privacy.13.1') !!}</p>
              <p>{!! __('privacy.13.2') !!}</p>

                <h3 class="fw-bold mb-0 heading-title text-primary">{!! __('privacy.14.title') !!}</h3>
                <p>{!! __('privacy.14.1') !!}</p>
                <p>{!! __('privacy.14.1.1') !!}</p>

                <h3 class="fw-bold mb-0 heading-title text-primary">{!! __('privacy.15.title') !!}</h3>
                <p>{!! __('privacy.15.1') !!}</p>
                <p>{!! __('privacy.15.2') !!}</p>

                <h3 class="fw-bold mb-0 heading-title text-primary">{!! __('privacy.16.title') !!}</h3>
                <p>{!! __('privacy.16.1') !!}</p>
                <p>{!! __('privacy.16.2') !!}</p>
                <p><p>{!! __('privacy.idpc.address') !!}</p></p>
                <p>
                    Telephone - <a href="tel:+35623287100">(+356) 2328 7100</a>
                </p>
                <p>{!!__('contact.email')!!} - <a href="mailto:idpc.info@idpc.org.mt">{!!__('privacy.idpc.email')!!}</a></p>
                <p>{!! __('privacy.version')!!}</p>
                <p>{!! __('privacy.date')!!}</p>
                <p><u>{!! __('privacy.sections.changes.title')!!} </u>- {!! __('privacy.sections.changes.content')!!}</p>
            </div>
            </div>
          </div>
        </div>
      </section>
</main>

@endsection
