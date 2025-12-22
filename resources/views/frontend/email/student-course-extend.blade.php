<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ascencia: Extends Course Validation</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Tahoma !important;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            /* text-align: center; */
        }

        .container {
            max-width: 700px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
            border: 1px solid #ddd;
        }


        .logo-1 {
            width: auto;
            height: 40px;
        }

        p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .button {
            display: inline-block;
            padding: 7px 10px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .footer {
            margin-top: 10px;
            color: #777;
        }

        .social-logo {
            width: 26px;
        }
        .text-color{
            color: #212529 !important
        }
        @media only screen and (max-width: 600px) {
            .subject1{
                font-size: 1.20rem !important;
            }
            .refundAmount{
                width: 70% !important;
            }
}
    </style>
</head>

<body>
    <div class="container" style="font-family: Tahoma;">
        <div class="row align-items-center justify-content-center">
            <div style="text-align: center; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 20px;">
                <img src="https://www.eascencia.mt/frontend/images/brand/logo/logo_final.png" alt="E-Ascencia Logo" class="logo-1" style="height: 40px; width: auto;">
            </div>

            <div style="text-align: center;">
                <p style="font-weight: 700;font-size: 22px; " class="my-2 text-color subject1">Good News!<br/> <span style="color: #2b3990;"> We’ve Extended Your Course Access by 3 Months </span>  </p>
            </div>

            <div style="text-align: center;">

                <img class="p-3 refundAmount" src="{{ asset('frontend/images/email/good-news.png') }}"
                    alt="E-Ascencia" style="width:55%; padding:10px">
            </div>

            <div class="col-md-12 ps-md-4 my-md-3 border-md-start mt-5">

                <p>Dear #Name#, </p>
                <p>I hope you’re doing well!</p>

                <p>We noticed that you purchased <strong>#coursename#</strong> but haven’t had a chance to complete it yet. We understand that life can get busy, so we have great news for you — <strong>we’ve extended your course access by an additional #months# months at no extra cost!</strong></p>
                <p>This means you now have more time to continue your learning journey and complete your course at your own pace.</p>
                <p>Here’s how you can get back on track:</p>

                 <ul>
                    <li><p class="mb-0"> <strong>Log in to your account:</strong>  <a href="#Link#" target="_blank">Login</a></p></li>
                    <li><p class="mb-0"><strong>Resume your course:</strong> from where you left off.</p></li>
                    <li><p class="mb-0">Feel free to reach out if you need any support along the way.</p></li>
                  </ul>
                  <p>We’re excited to see you finish the course and gain all the valuable skills and knowledge it offers.</p>

                  <p>If you have any questions or concerns, please don’t hesitate to contact us at <strong><a class="text-decoration-none text-dark" href="mailto:support@eascencia.mt">support@eascencia.mt</a></strong>
                    We’re here to help and want to ensure everything is clear for you.</p>

                 <p class="mb-3"> Best regards, <br>
                    <strong>The E-Ascencia Team</strong>
                </p>

                <a href="https://www.facebook.com/people/E-Ascencia-Malta/61559646792486/" target="_blank"><img class="social-logo mb-2 "
                    src="https://www.eascencia.mt/frontend/images/social/social-media-01.png" alt="social logo"></a>
                <a href="https://www.instagram.com/eascencia/" target="_blank"><img class="social-logo mb-2 "
                        src="https://www.eascencia.mt/frontend/images/social/social-media-02.png" alt="social logo"></a>

                <div class="footer" style="margin-top: 10px !important">
                    <p class="text-color">
                        If you no longer wish to receive emails, <a href="#unsubscribeRoute#" target="_blank" class="text-decoration-none">click here to unsubscribe</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>

</body>

</html>
