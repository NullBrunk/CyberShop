@extends("layout.base")

@section("title", "Cybershop")

@section("content")

    <script src="/assets/js/htmx.js"></script>
    
    <link href="/assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">
    <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="/assets/vendor/swiper/swiper-bundle.min.css">

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


        <section class=".section" style="background: #f5f8fd;">

            <div class="container swiper">
                <div class="slide-container">
                    <div class="card-wrapper swiper-wrapper" id="swiper-wrap">

                        @foreach($data as $d)

                            <div class="card swiper-slide">
                                <div class="image-box" onclick="window.location.href='/category/{{$d['name']}}'">
                                    <img unselectable="on" src="/assets/img/category/{{$d['name']}}.png" alt="" />
                                </div>

                                <div class="profile-details">
                                    <div class="name-job">
                                    <h3 class="name" style="margin-left: 0px; color: #000;">{{ucfirst($d['name'])}}</h3>
                                    <h4 class="job">{{$d['number']}} products</h4>
                                    </div>
                                </div>
                            </div>
                        
                        @endforeach

                    </div>
                </div>

                <div class="swiper-button-next swiper-navBtn"></div>
                <div class="swiper-button-prev swiper-navBtn"></div>
                <div class="swiper-pagination"></div>

              </div>
        </section>
        

        
        <script src="/assets/js/swiper.js"></script>


        @if(session("deletedproduct"))
            <script>
                success("{{ session('deletedproduct') }}", "Deleted")
            </script>
        @endif

        @if(session("deletedaccount"))
            <script>success("Your account has been removed permanently.", "Deleted")</script>
        @endif

@endsection
