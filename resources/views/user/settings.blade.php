@extends("layout.base")

@section("title", "Settings")


@section("content")
           
<link rel="stylesheet" href="/assets/css/settings.css">
<div style="margin-top: 12vh !important;"></div>

<div class="main">
    <form action="{{ route("settings.delete") }}">
        @method("delete")

        @csrf

        Password : <input type="password" name="password">

        <button class="delete">DELETE ACCOUNT</button>
    </form>
</div>

@endsection