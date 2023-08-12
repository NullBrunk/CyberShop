<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Login</title>
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

        <script src="/assets/vendor/sweetalert/sweetalert2.js"></script>
        <script src="/assets/js/alert.js"></script>

    </head>

    @include('layout.header')

    <body style="background-color: #e1e1e1 !important;" >
    
        <br>



        <section>

            <div class="container" >
                <div class="row justify-content-center logincenter">
                    <div class="col-md-7 col-lg-5">
                        <div data-aos="zoom-in" class="login-wrap p-4 p-md-5" style="padding-bottom: 1rem !important;">
                            <div class="icon d-flex align-items-center justify-content-center" >
                                <span class="bx bx-user" style="font-size: 45px; "></span>
                            </div>

                            <h3 class="text-center mb-4">Login</h3>
                    
                            <form method="post" class="login-form">
                                @csrf

                                

                                @error("verify")
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @if(session() -> has("success"))
                                    <script>
                                        success("{{ session("success") }}", "Signed Up !"); 
                                    </script>
                                @endif
                

                                <div class="form-group">
                                    @php($condition = $errors->has('email') or $errors->has('pass'))
                                    
                                    @if($condition) 
                                        <div class="alert alert-danger">
                                            Invalid mail or password !    
                                        </div>
                                    @endif

                                    <input  type="mail" id="email" name="email" class="form-control rounded-left @if($condition) is-invalid @endif" placeholder="E-mail" value="{{old("email")}}" required>    
                                
                                </div>
                                <div class="form-group d-flex">
                                    <input type="password" id="pass" name="pass" class="form-control rounded-left @if($condition) is-invalid @endif" placeholder="Password" required>
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
    
    <script src="assets/js/main.js"></script>

</html>