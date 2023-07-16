@extends("static.base")

@section("title", "Cybershop")

@include('layout.header')

@section("content")
    <body>
        <section id="hero" class="d-flex align-items-center">

        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"  data-aos-delay="200">
                    <h1>E-commerce</h1>
                    <h2>An e-Business website with Laravel & MariaDB for the backend, and Bootstrap for the front-end</h2>
                    
                </div>

                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
                    <img src="assets/img/hero-img.png" class="img-fluid animated" style="margin-left: 20%; height: 60%; margin-top: 20%;" alt="">
                </div>
            </div>
        </div>

        </section>

        <section id="portfolio" class="portfolio">
            <div class="container" data-aos="fade-up">

                <div class="row" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <ul id="portfolio-flters">
                            <li data-filter="*" class="filter-active">All</li>
                            <li data-filter=".filter-laptop">Laptops & Tablets</li>
                            <li data-filter=".filter-gaming">Gaming accessories</li>
                            <li data-filter=".filter-food">Food</li>
                            <li data-filter=".filter-dresses">Dresses</li>
                            <li data-filter=".filter-other">Other picks</li>      
                        </ul>
                    </div>
                </div>


                <div class="row portfolio-container">
                    @foreach($data as $d)


                        <div class="col-md-3 portfolio-item {{ $d['class'] }}">
                            <div class="portfolio-wrap" style="border-radius: 5px;">
                                <a href="/details/{{ $d['id'] }}">
                                    <img 

                                    data-src="/storage/product_img/{{ $d['image'] }}" 
                                    class="img-fluid imgpres" alt="">
                                </a>
                                <div class="portfolio-info">
                                
                                    <div class="portfolio-links">
                                        <p class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><strong>{{$d['price']}} $</strong></p>
                                    </div>

                                </div>
                            </div>
                        </div>

                    @endforeach

                </div>

            </div>
        </section>
@endsection


{{-- 
    Load les images uniquement lorsque la page
    a fini de se charger.
    Permet de gagner énormement de temps
--}}

<script>
    window.addEventListener('load', function() {
        var images = document.getElementsByTagName('img');
        for (var i = 0; i < images.length; i++) {
        var img = images[i];
        if (img.getAttribute('data-src')) {
            img.setAttribute('src', img.getAttribute('data-src'));
        }
        }
    });
</script>
   
@if(isset($_SESSION) && isset($_SESSION["deletedproduct"]))
    <script>
        Swal.fire(
            'Deleted !',
            'The product has been deleted successfully.',
            'success'
        ) 
    </script>

    <?php
        unset($_SESSION["deletedproduct"])
    ?>
@endif