<!DOCTYPE html>
<html lang="en">
    <head>
        
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title id="title" >@yield("title", config("app.name"))</title>

        <meta content="" name="description">
        <meta content="" name="keywords">


        {{-- Favicon --}}
        <link rel="icon" type="image/png" href="/assets/img/favicon.png"> 


        {{-- Google font --}}
        <link href="/assets/css/fonts.css" rel="stylesheet">


        {{-- CSS --}}
        <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="/assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="/assets/css/style.css" rel="stylesheet">
        

        {{-- JS --}}
        <script src="/assets/vendor/sweetalert/sweetalert2.js"></script>
        <script src="/assets/vendor/jquery/jquery.js"></script>
        <script src="/assets/js/alert.js"></script>
        
        
        {{-- MarkeIt Up --}}
        <link rel="stylesheet" type="text/css" href="/assets/vendor/markitup/skins/simple/style.css">
        <link rel="stylesheet" type="text/css" href="/assets/vendor/markitup/sets/default/style.css">
        
        <script type="text/javascript" src="/assets/vendor/markitup/jquery.markitup.js"></script>
        <script type="text/javascript" src="/assets/vendor/markitup/sets/default/set.js"></script>

    </head>

    
    
    @include('layout.header')
        
    
    @yield("content")


        <div id="preloader"></div>
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <script src="/assets/vendor/aos/aos.js"></script>
        <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/vendor/waypoints/noframework.waypoints.js"></script>

        <script src="/assets/js/main.js"></script>

    </body>
</html>