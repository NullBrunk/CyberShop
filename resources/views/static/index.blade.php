@extends("layout.base")

@section("title", "Cybershop")

@section("content")

    <script src="/assets/js/htmx.js"></script>
    
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

        <section style="background: #f3f5fa;">
            <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
            <link rel="stylesheet" href="/assets/vendor/swiper/swiper-bundle.min.css">



            <section id="testimonials" class="testimonials section-bg">
                <div class="container">
          
          
                  <div class="testimonials-slider swiper">
                        <div class="swiper-wrapper" id="swiper-wrap"></div>
                        
                        <script>
                            const container = document.getElementById("swiper-wrap");
                        
                            ["all", "informatics", "gaming", "dresses", "food", "furnitures", "vehicles", "appliances", "other"].forEach(element => {
                                container.innerHTML += `
                                <div class="swiper-slide" >
                                    <div class="testimonial-wrap">
                                        <div class="testimonial-item">
                                        
                                            <p onclick="window.location.href='/category/${element}'"" style="font-style: initial !important; cursor: pointer;">
                                                <img style="height: 17vh;" src="/assets/img/category/${element}.png"></img><br><br>
                                                <strong>${element.charAt(0).toUpperCase() + element.slice(1)}</strong>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                                `
                            });

                        </script>
                 </div>
          
                <div class="swiper-pagination"></div>
              </section><!-- End Testimonials Section -->
          
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



