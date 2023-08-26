@extends("layout.base")

{{-- In case the user is me, title is "my profile". Else the title is "profile of wathever@user.com" --}}
@section("title", $user -> mail === session("mail") ? "My profile" : "Profile of " . $user -> mail )

@section("content")

    <link rel="stylesheet" href="/assets/css/profile.css">

   

    <div style="margin-top: 12vh !important;"></div> {{-- Place under the navbar --}}
    
    <div class="profilepage">
        <div class="banner">
            <img src="/assets/img/banner.png">
        </div>

        <div class="absolute informations">
            <div class="profile-name">
                <img src="https://ui-avatars.com/api/?background=random&size=300&rounded=true&length=1&name={{ $user -> mail }}" alt="">
            </div>
            <div class="profile-info">
                <p class="mail">
                    {{ strtoupper($user -> mail) }}
                </p>
                <p class="joined" id="joined">
                    Joined the {{ $user -> format_date() }}
                </p>
            </div>    
            <div class="buttons">
                
                
                @if(session("mail") !== $user -> mail) 
                    <a class="signal" href="/todo"><i class="bi bi-exclamation-circle"></i></a>
                    <a class="chat" href="{{ route("contact.user", $user -> mail) }}"><i class="bi bi-envelope"></i></a>
                @else
                    <a class="chat" href="{{ route("settings.show") }}"><i class="bi bi-gear"></i></a>
                @endif

            </div>
        </div>

        <div class="cards">
            <div class="comments relative">
                <div class="commentbar-top">
                    <h5 style="padding: 10px 0px 0px 10px;">Latests comments</h5>
                    <hr style="color: black; margin: 0px;">
                </div>
                @livewire("profile-page-comments", [ "mail" => $user -> mail ] )
            </div>

                @livewire("profile-page-informations", [ "mail" => $user -> mail ])
        </div>
        @livewire("profile-page-product", ["mail" => $user -> mail] )
    </div>

@endsection
