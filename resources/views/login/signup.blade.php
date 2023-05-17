<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>E-Commerce</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Arsha
  * Updated: Mar 10 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  @extends('../layout/header')

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html><!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>WebChat</title>
  <meta content="" name="description">

  <meta content="" name="keywords">


  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/assets/css/login.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">


</head>

<body style="background-color: #e1e1e1 !important;">
	<br>
        <section class="ftco-section" >
      
            <div class="container">
                <div class="row justify-content-center" style="padding-top: 2rem !important;">
                    <div class="col-md-7 col-lg-5">
                        <div class="login-wrap p-4 p-md-5" style="padding-bottom: 1rem !important;">
                            <div class="icon d-flex align-items-center justify-content-center">
                                <span class="bx bx-user-plus" style="font-size: 45px; "></span>
                            </div>

                            <h3 class="text-center mb-4">Sign-up</h3>
                    
                    
                            <form method="post" class="login-form">
                            
                                {{ csrf_field() }}

                                @if ($errors->has('email'))
                                    <div class="alert alert-danger">
                                        You must enter a valid mail adress
                                    </div>
                                @endif
                                <div class="form-group">
                                    <input type="mail" id="email" name="email" class="form-control rounded-left" placeholder="E-mail" required>
                                </div>


                                @if ($errors->has('pass'))
                                    <div class="alert alert-danger">
                                        Password is required !
                                    </div>
                                @endif
                                <div class="form-group d-flex">
                                    <input type="password" id="pass" name="pass" class="form-control rounded-left" placeholder="Password" required>
                                </div>

                                
                                @if ($errors->has('repass'))
                                    <div class="alert alert-danger">
                                        Password are not same !
                                    </div>
                                @endif
                                <div class="form-group d-flex">
                                    <input type="password" id="pass" name="repass" class="form-control rounded-left" placeholder="Re Password" required>
                                </div>
                    
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Login</button>
                                </div>

              
                                <div class="form-group d-md-flex">
                    
                                    <div class="w-50">

                                    
                                    </div>
                                    <div class="w-50 text-md-right">
                                        <a style="color:#47b2e4 !important;" href="/login">Login</a>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

	</body>
</html>


