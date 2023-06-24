<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>E-Commerce</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <link href="assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

        <link href="assets/css/style.css" rel="stylesheet">
        <link href="/assets/css/login.css" rel="stylesheet">

    </head>

    @include('../layout/header')

    <body style="background-color: #e1e1e1 !important;">
    
        <br>
        <section class="ftco-section" >

            <div class="container">
                <div class="row justify-content-center" style="padding-top: 2rem !important;">
                    <div class="col-md-7 col-lg-5">
                        <div class="login-wrap p-4 p-md-5" style="padding-bottom: 1rem !important;">
                            <div class="icon d-flex align-items-center justify-content-center">
                                <span class="bx bx-user" style="font-size: 45px; "></span>
                            </div>

                            <h3 class="text-center mb-4">Login</h3>
                    
                            <form method="post" class="login-form">
                                {{ csrf_field() }}

                                @if(isset($_GET['f']) or $errors->has('email') or $errors->has('pass'))
                                    <div class="alert alert-danger">
                                        Wrong username or password !
                                    </div>
                                @endif
                

                                <div class="form-group">
                                    <input type="mail" id="email" name="email" class="form-control rounded-left" placeholder="E-mail" required autofocus>
                                </div>
                                <div class="form-group d-flex">
                                    <input type="password" id="pass" name="pass" class="form-control rounded-left" placeholder="Password" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Login</button>
                                </div>

                                <div class="form-group d-md-flex">
                                    <div class="w-50">
                                    </div>

                                    <div class="w-50 text-md-right">
                                        <a style="color:#47b2e4 !important;" href="/signup">Sign-Up</a>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>

    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>

    
    <script src="assets/js/main.js"></script>

</html>