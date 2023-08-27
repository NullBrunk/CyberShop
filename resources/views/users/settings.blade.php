@extends("layout.base")

@section("title", "Settings")

@section("content")

    <link rel="stylesheet" href="/assets/css/profile.css">

    @if(session("success"))
        <script>success("Your password has been updated.")</script>
    @endif

    <div style="margin-top: 16vh !important;"></div> {{-- Place under the navbar --}}

    <div class="profilepage">
        
        <div class="products-profile mt-4 p-3 rounded">
            <h5>Change profile picture color</h5>

            
            <div class="d-flex flex-column">
                
                <p class="mt-4 text-center"><img height="100" src="{{ $user -> avatar }}" alt="Profile picture of {{ $user -> mail }}"></p>
                
                @error("color")
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror

                <form action="{{ route("settings.update_pp") }}" method="post">

                    @csrf
                    <div class="d-flex flex-row">
                        <div class="w-50 d-flex flex-column text-center">
                            <span class="avenir-900">Background :</span>
                            <input class="w-50 m-auto" type="color" name="bgcolor" value="#{{ $user -> get_color("background") }}">
                        </div>
    
                        <div class="w-50 d-flex flex-column text-center">
                            <span class="avenir-900">Frontground : </span>
                            <input class="w-50 m-auto" type="color" name="fgcolor" value="#{{ $user -> get_color("color") }}">
                        </div>
                    </div>

                    <div>
                        <button class="rounded update-button mt-3">Change</button>
                    </div>
                </form>

            </div>
        </div>

        <div class="products-profile mt-4 p-3" >
            <h5>Change password</h5>

            <form method="post" action="{{ route("settings.update") }}">

                @csrf

                <div class="form-group mt-4">
                    <p class="light">
                        Actual password
                    </p>
                    <input type="password" class="form-control @error('pass') is-invalid @enderror" name="pass" placeholder="Your actual password">
                    <div class="invalid-feedback">
                        @error("pass")
                            {{ $message }}
                        @enderror
                    </div>

                </div>

                <div class="form-row mt-4">
                    <p class="light">
                        New password
                    </p>

                    <div class="col">
                        <input type="password" class="form-control @error('newpass') is-invalid @enderror" name="newpass" placeholder="Your new password">
                        <div class="invalid-feedback">
                            @error("newpass")
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <div class="col mt-2">
                        <input type="password" class="form-control @error('renewpass') is-invalid @enderror" name="renewpass" placeholder="Re enter the new actual password">
                        <div class="invalid-feedback">
                            @error("renewpass")
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

               
                <button class="mt-3 update-button rounded">Update</button>

            </form>
        </div>

        <div class="products-profile mt-4 p-3">
            <h5>Delete your account</h5>

            <p>
                Once your account is deleted, all the comments, products that you sell, history of the products that you <br> buyed/selled, liked comments, chat messages and more will be <strong>permanently deleted</strong> !
            </p>

            <form method="post" action="{{ route("settings.delete") }}">
                @method("delete")
        
                @csrf
        
                @error("password_error")
                    <div style="color: #af2024;">
                        Invalid password.        
                    </div>
                @enderror
                <div class="d-flex">
                    <input type="password" name="password" class="form form-control mr-12 @error('password_error') is-invalid @enderror" placeholder="Your actual password">
                    <button class="delete">DELETE ACCOUNT</button>
                </div>
                
        
            </form>
        </div>
    </div>
    <p class="mt-4" ></p>

@endsection


