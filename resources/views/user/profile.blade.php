<!DOCTYPE html>
<html lang="en">
    <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Profile</title>
            
            <link href="/assets/css/fonts.css" rel="stylesheet">
            <link href="assets/css/profile.css" rel="stylesheet">

            <script src='assets/js/sweetalert2.js'></script>

            <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
            <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

            <link href="assets/css/style.css" rel="stylesheet">
            <script src="assets/js/sweetalert2.js"></script>
            <script src="/assets/js/alert.js"></script>
    </head>
    
    <body style="background-color: #37517e;" >

        @include('layout.header')


        <div style="padding-top: 350px;"></div>


        <div class="main-content">
    
            <script>
                function undisable(){
                    for(elem of ["email", "input-sub", "newpass", "renewpass", "oldpass"])
                        document.getElementById(elem).disabled = !document.getElementById(elem).disabled
                }
            </script>


            <div class="container-fluid mt--7" style="padding-right: 2.5vw !important; padding-left: 2.5vw !important;">
                
                <div class="row">
                    <div class="col-xl-8 order-xl-1" class="">
                        <div class="card bg-secondary shadow" style="border: 0px; width: 95vw;">
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0" style="width: 152%; display: flex; ">
                                            
                                            <p class="block">My account</p>
                        
                                            <button 
                                                onclick="window.location.href = '/disconnect'" 
                                                class="btn btn-primary logout">
                                                    Disconnect
                                            </button>

                                        </h3>
                                    </div>
                                    <div class="col-4 text-right">
                                    </div>
                                </div>
                            </div>
                
                            <div class="card-body" style="background-color: white;">

                                <p class="uinfo heading-small text-muted mb-4">
                                    
                                    User information  
                                    
                                    <button onclick="undisable()" class="btn-profile">
                                        Edit 
                                    
                                        <i class="bi bi-pencil-square"></i>

                                    </button>
                                </p>    


                                {{-- Gestion des messages de succès --}}
                                @if(session("done"))
                                    <script>success("{{session('done')}}")</script>
                                @endif


                                {{-- Gestion des erreurs --}}
                                @error("email")
                                    <script>salert("{{$message}}")</script>
                                @enderror

                                @error("alreadytaken")
                                    <script>salert("{{$message}}")</script>
                                @enderror

                                @error("oldpass")
                                   <script>salert("Old password cannot be empty.")</script>
                                @enderror 

                                @error("newpass")
                                    <script>salert("New password cannot be empty.")</script>
                                @enderror

                                @error("renewpass")
                                    <script>salert("The two entered passwords are not same.")</script>
                                @enderror
            
                                @error("wrong_password")
                                    <script>salert("{{$message}}")</script>
                                @enderror
                                
                                <form action="{{ route("profile.profile") }}" method="post">

                                    @csrf

                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused">
                                                
                                                    <label class="form-control-label" for="input-address">E-mail</label>
                                                    <input id="email"  name="email" class="form-control form-control-alternative" placeholder="Your e-mail address" value="{{$_SESSION['mail']}}" type="text" disabled>
                                                
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused">
                                            
                                                    <label class="form-control-label" for="input-address">Password</label>
                                                    <input id="oldpass" value="{{old("oldpass")}}" name="oldpass" class="form-control form-control-alternative" placeholder="Your current password"  type="password" disabled>
                                                        
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused">
                                                
                                                    <label class="form-control-label" for="input-address">New password</label>
                                                    <input id="newpass" name="newpass" value="{{old("newpass")}}"class="form-control form-control-alternative" placeholder="Enter a new password" type="password" disabled>
                                            
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused">

                                                    <input id="renewpass" name="renewpass" value="{{old("renewpass")}}" class="form-control form-control-alternative" placeholder="Re-enter the new password" type="password" disabled>
                                            
                                    
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <input type="submit" id="input-sub" name="submit" value="Update" class="btn btn-primary" style="background-color: #32325d;" disabled>
                                    
                                </form>

                                <hr class="my-4">

                                <h6 class="heading-small text-muted mb-4">Products that you sell</h6>
 
                                <div class="pl-lg-4">
                                    <div class="row">

                                        <div class="row portfolio-container">

                                            @if(empty($data))
                                                You are not selling any product yet, <a style="width: 20% !important;" href="{{route("product.store")}}">start here !</a>
                                            @endif
                            
                                            @foreach($data as $d)
                    
                    
                                                <div class="col-md-3 portfolio-item" style="padding-bottom: 10px;">
                                                    <div class="portfolio-wrap" style="border-radius: 5px;">
                                                        <a href="/details/{{ $d['id'] }}">
                                                            <img src="/storage/product_img/{{ $d['image'] }}" class="img-fluid imgpres" alt="">
                                                        </a>
                                                        <div class="portfolio-info">
                                                        </div>
                                                    </div>
                    
                                                </div>
                                            @endforeach
                        
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h6 class="heading-small text-muted mb-4">Delete my account</h6>
                                <div class="">
                                    Once your account is deleted, all the comments, products that you sell, history <br> of the products that you buyed/selled will be <strong>permanently deleted</strong> !
                                </div>
                                <br>
                        
                                <p onclick="boum()" class="btn btn-primary" style="border: 1px solid #af2024; background-color: #af2024;">
                                    DELETE ACCOUNT
                                </p>

                                <script>
                                    function boum(){

                                        Swal.fire({
                                            title: 'Are you sure?',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#293e61',
                                            cancelButtonColor: '#af2024',
                                            confirmButtonText: 'Yes, delete it!'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = "/profile/delete"
                                            }
                                        })
                
                                    }
                                </script>
                            </div>
                        </div>
                        <hr class="my-4">
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top: 20px"></div>
    </body>
</html>