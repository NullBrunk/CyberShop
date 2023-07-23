@extends("static.base")

@section("title", "Cybershop")

@section("content")

    <script src="https://unpkg.com/htmx.org@1.9.3" integrity="sha384-lVb3Rd/Ca0AxaoZg5sACe8FJKF0tnUgR2Kd7ehUOG5GCcROv5uBIZsOqovBAcWua" crossorigin="anonymous"></script>
    
    <link href="/assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">

    <body>
        <section id="hero" class="d-flex align-items-center">

        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"  data-aos-delay="200">
                    <h1>E-commerce</h1>
                    <h2>An e-Business website with Laravel & MariaDB for the backend, and Bootstrap for the front-end</h2>
                    <a href="{{route("product.store")}}" class="sellpr"><i style="padding-right: 6px;" class="bi bi-plus-square"></i> Sell a product</a>
                </div>

                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
                    <img src="assets/img/hero-img.webp" class="img-fluid animated" style="margin-left: 20%; height: 60%; margin-top: 20%;" alt="">
                </div>
            </div>
        </div>

        </section>

        <section>

            TODO : put a caroussel with all categories, or something like this here.

        </section>

    
    @if(session("deletedproduct"))
            <script>
                success("{{ session('deletedproduct') }}", "Deleted")
            </script>
    @endif

    @if(session("deletedaccount"))
        <script>success("Your account has been removed permanently.", "Deleted")</script>
    @endif
@endsection



