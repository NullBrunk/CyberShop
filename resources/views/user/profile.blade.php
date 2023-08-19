@extends("layout.base")


@section("title", "Profile")

@section("content")

    <link rel="stylesheet" href="/assets/css/profile.css">

   

    <div style="margin-top: 12vh !important;"></div> {{-- Place under the navbar --}}
    
    <div class="profilepage">
        <div class="banner">
            <img src="/assets/img/banner.png">
        </div>

        <div class="informations">
            <div class="profile-name">
                <p class="m-auto">{{ ucfirst(substr($user -> mail, 0, 1)) }}</p>
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
                <a class="signal" href="/todo"><i class="bi bi-exclamation-circle"></i></a>
                <a class="chat" href="{{ route("contact.user", $user -> mail) }}"><i class="bi bi-envelope"></i></a>
                <a class="settings" href="{{ route("profile.settings") }}"><i class="bi bi-gear"></i></a>
            </div>
        </div>

        @livewire("profile-page-product", ['mail' => $user -> mail] )
    </div>

@endsection
