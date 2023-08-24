@extends("layout.base")

@section("content")


    <link rel="stylesheet" href="/assets/vendor/swiper/swiper-bundle.min.css">
    <link href="/assets/css/searchbar.css" rel="stylesheet">
        
    <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/vendor/typed/typed.js"></script>
    <script src="/assets/vendor/htmx/htmx.js"></script>

    <body>
        <section id="hero" class="d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"  data-aos-delay="200">
                        <h1>

                            <span id="typed"></span>

                        </h1>


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
                        <img src="assets/img/hero-img.webp" class="img-fluid animated"  alt="">
                    </div>
                </div>
            </div>
        </section>


        <section class=".section" style="background: #f5f8fd;">

            <div class="container swiper" style="min-height: 38vh;">
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

                <div class="swiper-button-next swiper-navBtn" style="top: 48%"></div>
                <div class="swiper-button-prev swiper-navBtn" style="top: 48%"></div>
                <div class="swiper-pagination" style="bottom: 20px !important;"></div>

              </div>
        </section>
        
        <script>
            const typed = document.querySelector('.typed')
             new Typed('#typed', {
                strings: [ "Shop Smarter, Live Better.", ],
                loop: true,
                typeSpeed: 75,
                backSpeed: 25,
                backDelay: 2000
            });

        </script>

        
        <script src="/assets/js/swiper.js"></script>


        @if(session("deletedproduct"))
            <script>
                success("{{ session('deletedproduct') }}", "Deleted")
            </script>
        @endif

        @if(session("deletedaccount"))
            <script>success("Your account has been removed permanently.", "Deleted")</script>
            @php
                session() -> forget("deletedaccount")
            @endphp
        @endif

@endsection
