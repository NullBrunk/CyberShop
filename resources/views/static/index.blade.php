@extends("layout.base")

@section("title", "Cybershop")

@section("content")

    <script src="/assets/js/htmx.js"></script>
    
    <link href="/assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">
    <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="/assets/vendor/swiper/swiper-bundle.min.css">
    <link href="/assets/css/searchbar.css" rel="stylesheet">

    <body>
        <section id="hero" class="d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"  data-aos-delay="200">
                        <h1>E-commerce</h1>
                        <h2  style="padding-top: 2%; margin-bottom: 0px;">
                            Embrace the future of online shopping and unlock a world of innovation. Start exploring now !
                            <form class="searchbarindex" method="get" action="{{ route("product.search", "all") }}">
                                    <input name="q" type="text" placeholder="Type something ..." id="input" autofocus>
                                    
                                    <button>
                                        <i class="bx bx-search-alt"></i>
                                    </button>
                    
                                </form>
                        </h2>
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
