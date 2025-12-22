
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
    /* background-color: #f1f5f9; */
    font-family: Inter, sans-serif;
}

html {
    scroll-behavior: smooth;
}

body {
    overflow-x: hidden
}



.thankyouText{
    font-size: 40px;
    font-weight: 900;
    text-transform: uppercase;
    line-height: 60px;
    color: #191913;
    margin-top: 10rem;
    text-align: center;
}


.subText{
    font-size: 29px;
    font-weight: 900;
    text-transform: uppercase;
    line-height: 40px;
    text-align: center;
    color: #2b3990;

}

.wish{
    text-align: center;
}


@media only screen and (max-width: 992px) {
    .thankyouText{
        font-size: 28px;
        margin-top: 5rem;
    }
    .subText{
        font-size: 22px;
    }
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
        {{-- <img src="{{ asset('frontend/images/bg-thankyou.jpg')}}" alt="not found" class="img-fluid eascencia_logo bg-white" /> --}}
        <div style="text-align: center;margin-top: 4rem">
            <img src="https://www.eascencia.mt/frontend/images/brand/logo/logo_final.png" alt="E-Ascencia Logo" class="logo-1" style="height: 50px; width: auto;">
        </div>
        <p class="thankyouText">Thank You</p>
        <p class="subText">Your data have been Submitted!</p>
        <p class="wish">We are eager to give you best education and style!</p>
    
        
    </section>

  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>

</html>