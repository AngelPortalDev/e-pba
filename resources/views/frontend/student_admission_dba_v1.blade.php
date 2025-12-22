<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ascencia </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets_admissions/imgs/favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./assets/responsive.css">
    <link rel="stylesheet" href="">

<style>
    *,
body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    scroll-behavior: smooth;
    font-family: Inter, sans-serif;
}

html {
    scroll-behavior: smooth;
}

body {
    overflow-x: hidden
}

.eascencia_logo {
    width: 139px;
    height: 51px;
    position: absolute;
    top: 15%;
    left: 12%
}

.btnstyle {
    /* font-family: Raleway, Sans-serif; */
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: .5px;
    border-radius: 8px;
    padding: 8px 20px;
    top: 16px;
    left: 14px;
    line-height: 30px;
    position: absolute;
    background-color: #2b3990;
}
.btnstyle:hover{
    background-color: #191913;
}

.malta_heading_title,
.malta_sub_heading {
    /* font-family: Raleway, Sans-serif; */
    font-weight: 900;
    text-transform: uppercase
}

.ascencia_malta_logo_style {
    width: 200px;
    height: 80px;
    border-radius: 16px 0 0 16px;
    position: relative;
    border: 1px solid #fff;
    overflow: hidden
}

.navtopbar {
    background: #020101;
    height: 80px; 
    border-radius: 18px;
    box-shadow: 0px 0px 50px -20px rgba(0, 0, 0, 0.4);
}

.home_page {
    padding: 200px 0px 0px 0px;
}

.malta_heading_title {
    color: #191913;
    font-size: 36px;
    line-height: 40px
}

.malta_sub_heading {
    color: #2b3990;
    font-size: 24px;
    line-height: 30px
}

.malta_offer_title {
    color: #191913;
    /* font-family: "Open Sans", Sans-serif; */
    font-size: 19px;
    font-weight: 600;
    line-height: 30px
}

.study_benifi_list span,
.study_benifit_title {
    /* font-family: "Quattrocento Sans", Sans-serif; */
    margin-left: 5px
}

.study_benifi_list span {
    font-size: 17px;
    font-weight: 600;
}

.study_benifi_list .check-icon {
    color: #2b3990;
    font-size: 1.5rem;
    font-weight: 600 !important
}

.study_benifi_list li {
    display: flex;
    align-items: center;
    line-height: 35px;
}

.bi::before,
[class*=" bi-"]::before {
    font-weight: 700 !important
}

.study_benifit_title {
    font-size: 19px;
    font-weight: 700;
    margin-bottom: 0
}

.malta_form_column {
    display: flex;
    justify-content: end
}

.malta_lead_form {
    width: 496px;
    height: auto
}

.about_us_heading {
    color: #2b3990;
    /* font-family: Raleway, Sans-serif; */
    font-size: 16px;
    font-weight: 300;
    text-transform: uppercase;
    line-height: 28px;
    letter-spacing: 0;
    margin-top: 20rem
}

.about_us_title,
.our_mission_section_title,
.program_section_title {
    color: #191913;
    /* font-family: Raleway, Sans-serif; */
    font-size: 40px;
    font-weight: 800;
    line-height: 45px
}

.malta_description,
.program_description {
    color: #191913;
    /* font-family: "Open Sans", Sans-serif; */
    font-size: 15px;
    font-weight: 400;
    line-height: 30px
}

.apply_btn_style,
.program_heading_section {
    /* font-family: Raleway, Sans-serif; */
    text-transform: uppercase
}

.program_heading_section {
    color: #2b3990;
    font-size: 16px;
    font-weight: 300;
    line-height: 28px;
    letter-spacing: 0
}

.apply_btn_style {
    font-size: 12px;
    font-weight: 500;
    line-height: 24px;
    letter-spacing: .5px;
    background: linear-gradient(to right, #d5213f 50%, #2b3990 50%);
    background-size: 200% 100%;
    background-position: right bottom;
    transition: .4s;
    padding: 10px 20px;
    margin-top: 2rem
}

.campus_notes,
.campus_right_column {
    background-color: #191914
}

.our_program_section {
    margin-top: 0;
    margin-bottom: 0;
    padding: 120px 0 60px
}

.our_program_course_title {
    /* font-family: "Futura Bold", Sans-serif; */
    font-weight: 700;
    color: #222218
}

.campus_notes_heading,
.our_mission_section_heading {
    font-size: 16px;
    font-weight: 300;
    text-transform: uppercase;
    line-height: 28px;
    letter-spacing: 0;
    /* font-family: Raleway, Sans-serif */
}

.button_wrapper_program {
    margin-top: 40px
}

.button_wrapper_apply_now .apply_now_btn_style,
.button_wrapper_program .apply_now_btn_style {
    padding: 12px 24px;
    background-color: #2b3990 !important;
    color: #fff !important;
    text-transform: uppercase;
    font-size: 13px
}

.button_wrapper_apply_now .apply_now_btn_style:hover,
.button_wrapper_program .apply_now_btn_style:hover {
    background-color: #2b3990 !important
}

.button_wrapper_apply_now {
    margin-top: 60px !important
}

.campus_notes {
    padding: 0 20% 0 10%;
    color: #fff;
    align-content: center
}

.campus_notes_heading {
    color: #fff
}

.campus_notes_title {
    color: #fff;
    /* font-family: Raleway, Sans-serif; */
    font-size: 40px;
    font-weight: 800;
    line-height: 45px
}

.campus_description {
    color: #fff;
    /* font-family: "Open Sans", Sans-serif; */
    font-size: 14px;
    font-weight: 400;
    line-height: 30px
}

.program_description_main {
    background-color: #2b3990;
    color: #fff;
    height: 1.7rem;
    width: 1.7rem;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    font-size: 1rem
}

.program_description_icon {
    font-size: 1rem
}

.our_program_course_title {
    font-size: 1.5rem;
    margin-left: .5rem;
    line-height: 1.5
}

.mission_chev_icon_style i {
    font-size: 1.2rem
}

.our_mission_title {
    font-size: 1rem;
    line-height: 1.5
}

.my_icon {
    font-size: 1.2rem;
    background: #222218;
    height: 28px;
    width: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff
}

.my_icon:hover {
    background: #2b3990;
}

.our_mission_section {
    padding: 120px 0 60px;
    background-image: url("../frontend/images/dba/mission_background.png");
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover
}

.title_color{
    color: #2b3990;
}




/* New styling */

.bi-check-circle{
    color: #38A169;
}

.study_benifi_list span{
    color: #64748b;
}

.about-us-values{
    background-color: white !important;
}

.card-body h4{
    font-size: 22px;
}
.heading_section{
    padding-top: 30px;
}

@media only screen and (max-width: 768px) {
    .malta_heading_title {
      font-size: 32px !important;
        line-height: 40px !important;
    }
    .malta_sub_heading{
        font-size: 18px !important;
        line-height: 25px !important;
    }
    .malta_offer_title,.study_benifit_title {
        font-size: 16px !important;
    }
    .study_benifi_list span{
        font-size: 15px !important;
    }
    .about_us_title{
        font-size: 30px !important;
        line-height: 35px;
    }
 
    .home_page{
        padding: 160px 0px 0px 0px !important;
    }
    .program_section_title{
        font-size: 24px !important;
        line-height: 30px !important;
    }
    .button_wrapper{
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }
    .course-list-heading{
        margin-top: 3rem !important;
    }

 
    .campus_notes_title{
        font-size: 30px !important;
        line-height: 35px !important;
    }
    /* iframe{
        height: 1200px !important;
    } */
    
  }


   @media only screen and (max-width: 992px) {
    .our_program_course_title{
        font-size: 1rem !important;
        line-height: 1.5 !important;
    }
    .about_us_heading{
        margin-top: 0rem !important;
    }
    .about_us_mobile{
        display: none;
    }

  }

   @media only screen and (min-width: 992px) {
    .img_section_university{
        padding: 0;
    }
    .img_section_graduate{
        padding: 0;
    }
    .about_us_desktop{
        display: none;
    }
  } 

  #apply-form {
    scroll-margin-top: 100px;
  }


</style>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WVZC796Q');</script>
<!-- End Google Tag Manager -->
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WVZC796Q"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <section class="main_contaniner">
        <header>
            <div class="container mt-4 fixed-top">
                <div class="navtopbar d-flex justify-content-between">
                    <div class="logo bg-white ascencia_malta_logo_style">
                        <img src="{{ asset('assets_admissions/imgs/logo.svg') }}" alt="not found" class="img-fluid eascencia_logo bg-white" />
                    </div>
                    <div style="width: 150px; position: relative;">
                        <a href="#apply-form"> <button class="btn text-white btnstyle">Apply Now</button></a> 
                    </div>
                </div>
            </div>
        </header>
        <!-- <div class="container">
            <nav class="navbar">
                <div class="container-fluid">
                <img src="./assets/imgs/logo.svg" style="width: 160px;">
                <form class="d-flex" role="search">
                    <a href="#home"> <button class="btn btn-primary text-white ">Apply Now</button></a> 
                </form>
                </div>
            </nav>
        </div> -->
       <section class="home_page" id="home">
        <div class="container mb-4">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="heading_section" >
                        <p class="malta_heading_title"><span class="title_color">DBA </span> DOCTORATE OF <br />BUSINESS ADMINISTRATION</p>
                        <p class="malta_heading_title title_color">Upgrade yourself <br /> with the title "Dr."</p>
                        <p class="malta_sub_heading">
                            E-ASCENCIA 
                        </p>
                        <!-- <p class="malta_offer_title mt-3">50% Scholarship for Eligible Candidates</p> -->
                    </div>
                    <div class="study_benifits">
                        <ul class="list-unstyled study_benifi_list">
                            <li><i class="bi bi-check-circle"></i><span>MQF-EQF: <span>LEVEL 8</span></span></li>
                            <li><i class="bi bi-check-circle"></i><span>Hybrid program</span></li>
                            <li><i class="bi bi-check-circle"></i><span>European regonized qualification accepted globally</span></li>
                            <li><i class="bi bi-check-circle"></i><span>Up to 50% scholarship</span></li>
                        </ul>
                    </div>
                    <div class="about_us about_us_mobile">
                        <p class="about_us_heading mb-0">About us</p>
                        <p class="about_us_title">About E-Ascencia</p>
                        <p class="malta_description">Ascencia Malta is located in Floriana – right on the doorstep of Valletta. Floriana was meant to be a suburb of Valletta, but it developed to be a town on its own. A short walk separates the two towns. It isn’t easy to understand where Floriana finishes and Valletta starts, but the iconic entry to the capital is part of Floriana. Floriana sits in the very heart of the Maltese islands, with roads leading to all the bustling and major cities in Malta, so we can say that all the roads lead to Floriana – meaning that all the buses will bring you here as well.

                        </p>
                    </div>
                
                </div>
                <div class="col-lg-6 col-md-6 col-12 malta_form_column">
                    <div class="malta_lead_form" id="apply-form">
                        {{-- <iframe aria-label='Admission Open for 2025' frameborder="0" style="height:1450px;width:100%;border:none; overflow-y: hidden;" src='https://forms.zohopublic.com/info5253/form/AdmissionOpenfor2025/formperma/wLjpz1XvT3qwQsXPAbdHs_zHGsPW7lKz2OmoVmLWlR4'></iframe> --}}

                        <iframe aria-label='Admission Open for 2025 Intake!' frameborder="0" style="height:1450px;width:100%;border:none; overflow-y: hidden;;" src='https://forms.zohopublic.com/info5253/form/AdmissionOpenfor2025Intake9/formperma/-kbZ1cFHETVxGBvz7_jUTMr2SwCNYzPwMmNQs7fus40'></iframe>
                    </div>
                </div>
                <div class="about_us about_us_desktop">
                    <p class="about_us_heading mb-0">About us</p>
                    <p class="about_us_title">About E-Ascencia </p>
                    <p class="malta_description">E-Ascencia Malta is a leading online learning platform designed to deliver top-notch education to students worldwide. Our platform provides a variety of courses meticulously developed to align with international benchmarks and accommodate learners from different backgrounds.
                    </p>
                </div>
            </div>
        </div>
     <!-- Image Section Below the Main Container -->
    <section class="img_section">
        <div class="container-fluid p-0">
            <div class="bg-dark" style="height: 50px;"></div>
            <div class="row ">
                <div class="col-lg-6 col-12 img_section_university">
                    <img src="{{ asset('frontend/images/university.jpg')}}" class="img-fluid" alt="not found">
                </div>
                <div class="col-lg-6 col-12 img_section_graduate">
                    <img src="frontend/images/graduate.jpg" class="img-fluid" alt="not found">
                </div>
            </div>
        </div>
    </section>

        <!-- Our Program  -->
    <section class="our_program_section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-12">
                    <p class="program_heading_section mb-0">
                        Ascencia MALTA
                    </p>
                    <p class="program_section_title">
                        Our programmes
                    </p>
                    <p class="program_description">
                        The member schools of Ascencia are all recognised in their specialised domains and guarantee
                        that graduates will achieve insertion in world job markets.
                        The faculty comprises an academic lecturing body and the best professional players.
                        All the programs lead to a state-recognised certification.
                    </p>
                    <div class="button_wrapper_program">
                        <a href="#home" class="btn apply_now_btn_style">Apply Now</a>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <ul class="list-unstyled course-list-heading">
                        <li class="mt-4 pb-2 d-flex"><span class="program_description_main"><i
                                    class="bi bi-chevron-right program_description_icon"></i></span> <span
                                class="our_program_course_title ms-2"> English Language Courses</span></li>
                        <li class="mt-4 pb-2 d-flex"><span class="program_description_main"><i
                                    class="bi bi-chevron-right program_description_icon"></i></span> <span
                                class="our_program_course_title ms-2">UnderGraduate & Post Graduate</span></li>
                        <li class="mt-4 pb-2 d-flex"><span class="program_description_main"><i
                                    class="bi bi-chevron-right program_description_icon"></i></span> <span
                                class="our_program_course_title ms-2">Bachelor, Master, MBA</span></li>
                        <li class="mt-4 pb-2 d-flex d-inline-block"><span class="program_description_main "><i
                                    class="bi bi-chevron-right program_description_icon"></i></span> <span
                                class="our_program_course_title ms-2 d-inline-block">Arts, Science, Computer
                                Science,<br /> Economics, Business</span></li>
                    </ul>

                    <div class="companies_list mt-5">
                        <!-- First Row of Images -->
                        <div class="row">
                            <div class="col-4">
                                <img src="{{ asset('frontend/images/dba/get_qualified_new.jpg')}}" class="img-fluid">
                            </div>
                            <div class="col-4">
                                <img src="{{ asset('frontend/images/dba/elt_counsil.jpg')}}" class="img-fluid">
                            </div>
                            <div class="col-4">
                                <img src="{{ asset('frontend/images/dba/get_MQF.jpg')}}" class="img-fluid">
                            </div>
                        </div>

                        <!-- Second Row of Images -->
                        <div class="row">
                            <div class="col-4">
                                <img src="{{ asset('frontend/images/dba/eramus.jpg')}}" class="img-fluid">
                            </div>
                            <div class="col-4">
                                <img src="{{ asset('frontend/images/dba/get_malta.jpg')}}" class="img-fluid">
                            </div>
                            <div class="col-4">
                                <img src="{{ asset('frontend/images/dba/get_europian.jpg')}}" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>

       <!-- Our Mission Section -->
    <section class="our_mission_section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-12">
                    <p class="our_mission_section_heading mb-0">ASCENCIA MALTA</p>
                    <h2 class="our_mission_section_title">Our mission & values</h2>
                    <!-- <div class="button_wrapper">
                       <a href=""> <button class="btn apply_btn_style text-white">Apply Now</button></a>
                    </div> -->
                    <div class="button_wrapper_apply_now">
                        <a href="#home" class="btn apply_now_btn_style">Apply Now</a>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <li class="mt-4 pb-2 d-flex align-items-center">
                        <span class="mission_chev_icon_style">
                            <i class="bi bi-chevron-right my_icon"></i>
                        </span>
                        <span class="our_mission_title ms-3">
                            <b>Job Access</b>
                            <br />
                            Because each training course corresponds to a personal ambition,
                            all our students receive individual attention. The success of
                            each student is our priority. Our teams accompany each student
                            in the success of his project. Professional integration,
                            promoted by work-linked training, is our top priority.
                        </span>
                    </li>


                    <li class="mt-4 pb-2 d-flex align-items-center"><span class="mission_chev_icon_style"><i
                                class="bi bi-chevron-right my_icon"></i></span>
                        <span class="our_mission_title ms-3">
                            <b>Teaching Excellence</b>
                            <br />
                            The member schools of Ascencia are all recognised in their
                            specialised domains and guarantee that graduates will achieve
                            insertion in world job markets. The faculty comprises an academic
                            lecturing body and the best professional players. All the programs
                            lead to a state-recognised certification.
                        </span>
                    </li>
                    <li class="mt-4 pb-2 d-flex align-items-center"><span class="mission_chev_icon_style"><i
                                class="bi bi-chevron-right my_icon"></i></span>
                        <span class="our_mission_title ms-3">
                            <b>Job Access</b>
                            <br />
                            Because each training course corresponds to a personal ambition,
                            all our students receive individual attention. The success of
                            each student is our priority. Our teams accompany each student
                            in the success of his project. Professional integration,
                            promoted by work-linked training, is our top priority.
                        </span>
                    </li>
                </div>
            </div>
        </div>
    </section>

    </section>
    </section>

  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>