<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Signup</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        {{-- Favicon --}}
        <link rel="icon" type="image/png" href="/assets/img/favicon.png"> 

        {{-- Google fonts --}}
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        {{-- CSS --}}
        <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="/assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="/assets/css/login.css" rel="stylesheet">
        <link href="/assets/css/style.css" rel="stylesheet">
        @livewireStyles

        <script src="/assets/vendor/sweetalert/sweetalert2.js"></script>
        <script src="/assets/js/alert.js"></script>
        @livewireScripts

    </head>
    
    @include('layout.header')

    <body style="background-color: #e1e1e1 !important;">
	    <br>
        <section class="signupform">
            
            <div class="container">
                <div class="row justify-content-center" class="logincenter">
                    <div class="col-md-7 col-lg-5">
                        <div data-aos="zoom-in" class="login-wrap p-4 p-md-5" style="padding-bottom: 1rem !important;">
                            <div class="icon d-flex align-items-center justify-content-center">
                                <span class="bx bx-user-plus" style="font-size: 45px; "></span>
                            </div>

                            <h3 class="text-center mb-4">Sign-up</h3>
                    
                            <livewire:signup-form />

                        </div>
                    </div>
                </div>
            </div>
        </section>


        <script src="assets/vendor/aos/aos.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


        <script src="assets/js/main.js"></script>

    </body>
</html>


