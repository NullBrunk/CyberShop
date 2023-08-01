@extends("layout.base")

@section("title", "Settings")
    
@section("content")
    <body style="background-color: #282b36;" >

        @include('layout.header')


        <div style="padding-top: 108px;"></div>


        <div class="main-content">
    
            <script>
                function undisable(){
                    for(elem of ["email", "input-sub", "newpass", "renewpass", "oldpass"])
                        document.getElementById(elem).disabled = !document.getElementById(elem).disabled
                }
            </script>

            <style>
                input[type="text"], input[type="password"] {
                    background-color: #1a1b1c !important;
                    color: #afaca7 !important;
                    margin-bottom:10px;
                    border-color: rgb(55, 59, 61) !important;
                }

                input[type="text"]{
                    background: #363656;
                    color: white;
                }

                input[type="text"]:disabled {
                    background-color: #282b36 !important;
                }


                input[type="password"]:disabled {
                    background-color: #282b36 !important;
                }

                input:disabled {
                    background: #272742;
                }

                *:focus { outline: 0 !important; }

            </style>

            <div class="container-fluid mt--7" style="padding-right: 2.5vw !important; padding-left: 2.5vw !important;">
                
                <div class="row">
                    <div class="col-xl-8 order-xl-1" class="">
                        <div class="card bg-secondary shadow" style="border: 0px; width: 95vw;">
                            <div class="card-header border-0" style="background-color: #3e404b;">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0" style="width: 152%; display: flex; ">
                                            
                                            <p class="block" style="color: white; ">My account</p>
                        
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
                
                            <div class="card-body" style="background-color: #3e404b;">

                                <p class="uinfo heading-small mb-4 fg-white" style="display: flex; color: white !important; font-family: Jost; font-weight: 500;">
                                    
                                    User information  
                                    
                                    <button onclick="undisable()" class="btn-profile" style="background-color: #363656; color: white; border:#1e5ebf;">
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
                                
                                <form action="{{ route("profile.settings") }}" method="post">

                                    @csrf

                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group" style="color: white;font-family: Jost;">
                                                
                                                    <label class="form-control-label" for="input-address">E-mail</label>
                                                    <input id="email"  name="email" class="form-control form-control-alternative" placeholder="Your e-mail address" value="{{$_SESSION['mail']}}" type="text" disabled>
                                                
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused" style="color: white;font-family: Jost;">
                                            
                                                    <label class="form-control-label" for="input-address">Password</label>
                                                    <input id="oldpass" value="{{old("oldpass")}}" name="oldpass" class="form-control form-control-alternative" placeholder="Your current password"  type="password" disabled>
                                                        
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused" style="color: white;font-family: Jost;">
                                                
                                                    <label class="form-control-label" for="input-address">New password</label>
                                                    <input id="newpass" name="newpass" value="{{old("newpass")}}"class="form-control form-control-alternative" placeholder="Enter a new password" type="password" disabled>
                                            
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group focused" style="color: white;font-family: Jost;">

                                                    <input id="renewpass" name="renewpass" value="{{old("renewpass")}}" class="form-control form-control-alternative" placeholder="Re-enter the new password" type="password" disabled>
                                            
                                    
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <input type="submit" id="input-sub" name="submit" value="Update" class="btn btn-primary" style="background-color: #32325d;" disabled>
                                    
                                </form>

                            

                                <hr class="my-4" style="color: white !important;">
                                <h6 class="heading-small mb-4 fg-white" style="color: white !important; font-family: Jost; font-weight: 500;">Delete my account</h6>
                                <div style="color: white !important; font-family: Jost;">
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
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-top: 20px"></div>
@endsection