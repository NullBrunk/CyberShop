<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Cart details</title>
        <meta content="" name="description">
        <meta content="" name="keywords">


        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="../assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">


        <link href="../assets/css/style.css" rel="stylesheet">

    </head>
    <body>
    <div style="display: flex; padding-top: 15vh; padding-bottom: 15vh;">

    @include('layout/header')


            @php($total = 0)
        <div class="showcart" style="margin: auto;">
            <div class="totalprice">
                TOTAL <span id="totalprice" class="valuetotalprice">0</span><span style="font-size:22px; font-weight: 600;">$</span>
            </div>
            <hr style="color: white;">
            @foreach($_SESSION['cart'] as $p)
                @php($total += $p['price'])

            
                <div class="productcart">
                            
                        <div class="imgcart">
                            <img data-src="../storage/product_img/{{ $p["image"] }}">
                        </div>

                        <div class="price">
                            <strong>{{$p['price']}} $</strong>
                        </div>

                        <div class="link">
                            <a href="/details/{{ $p['pid'] }}">{{ $p["name"] }}</a>
                        </div>

                </div>
                <hr style="color: white;">

            @endforeach
            <script>
                document.getElementById("totalprice").innerHTML = {{(float)$total}}
            </script>


            <button onclick="window.location.href='/todo'" class="buttoncart">BUY <i style="font-weight: bold !important;" class="bi bi-cart-check"></i> </button>
            </div>
        </div>
        
        <div id="preloader"></div>
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
        
        <script src="../assets/vendor/aos/aos.js"></script>
        <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>
                <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

        
        <script src="../assets/js/main.js"></script>
        <script>window.addEventListener('load', function() {
            var images = document.getElementsByTagName('img');
            for (var i = 0; i < images.length; i++) {
              var img = images[i];
              if (img.getAttribute('data-src')) {
                img.setAttribute('src', img.getAttribute('data-src'));
              }
            }
          }
          );</script>
    </body>
   

    </html>