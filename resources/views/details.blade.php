<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{$data["name"]}}</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Arsha
  * Updated: Mar 10 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  @include('../layout/header')

  
  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
      <div class="container">

        <ol></ol>
        <h2>{{$data["name"]}}</h2>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-8">
            <div class="portfolio-details-slider swiper">
              <div class="swiper-wrapper align-items-center">


                  <img style="width: 60% !important;" src="../storage/product_img/{{ $data["image"] }}" alt="">

              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>

          <div class="col-lg-4"  style="color: white; background-color: #324769 !important; border-radius: 12px;">
            <div class="portfolio-info" style="padding-bottom: 10px;" >
              <h2>Product information</h2>
              <hr>
              <ul>

                <li><strong>Category</strong>: {{ ucfirst(explode('-', $data["class"])[1]) }}</li>
                <li><strong>Seller</strong>: {{ $data['mail'] }}</li>
                <li><strong>Price</strong>: {{ $data['price'] }}$</li>
                <br>
                <h3></h3>
              </ul>
            </div>
            <div class="portfolio-info">
              <p class="descr">
                {{ $data['descr'] }}
              </p> 

            </div>
            @if(isset($_SESSION["mail"]) && $data['mail'] !== $_SESSION["mail"])
            <form class="navbar" method="post" action="{{route("addCart")}}">  
              @csrf      
              <input class="addtocart" type="submit" value="Add to cart">
              <input type="hidden"  name="id" value="{{$data['pid']}}">
            </form>
            
            @else
              <form class="navbar" method="post" action="{{route("deleteProduct", $data['pid'])}}">  
                @csrf      
                <input type="hidden" name="_method" value="DELETE">
                <input class="addtocart" type="submit" value="Delete product">
            </form>
            @endif
          
          </div>
          

        </div>

      </div>
    </section><!-- End Portfolio Details Section -->
    <hr>

    <section id="breadcrumbs" style="padding-top: 1%;" class="breadcrumbs">
      <div class="container">

        <ol></ol>
        <h2>Comments</h2>

        @if($errors->has('comment') or $errors->has('id'))
            <div class="alert alert-danger">
                An error has occured !
            </div>
        @endif

        
        @if($errors -> has('rating'))
          <div class="alert alert-danger">
            You need to give a rating !
          </div>
        @endif

        @if(isset($_SESSION['done']))
              <div class="alert alert-success">
                  Your comment has been posted
              </div>

              <?php
                unset($_SESSION['done'])
              ?> 
        @endif

        <div style="display: flex">
        <form method="post" action="{{ route("addComment") }}" style="width:100%;">
          @csrf
          <textarea placeholder="Type something ..." class="commentbar" name="comment" type="text">{{old("comment")}}</textarea>
          <input name="id" type="hidden" value="{{$data['pid']}}">
          <br>
          
          <div class="rating">
            <input type="radio" id="star5" name="rating" value="5">
            <label for="star5"></label>
            <input type="radio" id="star4" name="rating" value="4">
            <label for="star4"></label>
            <input type="radio" id="star3" name="rating" value="3">
            <label for="star3"></label>
            <input type="radio" id="star2" name="rating" value="2">
            <label for="star2"></label>
            <input type="radio" id="star1" name="rating" value="1">
            <label for="star1"></label>
          </div>

          <input style="border: 2px solid #cccccc !important; border-radius: 4px; padding: 4px 4px; margin-left:80%; color: #444444; background-color: #cccccc;" class="" type="submit" value="Post comment">
          <br>

        </form>
      </div>
      <br><br><br><br>

        <hr class="margin-bottom:40px;">
        <div id="comments">
        </div>

        <script>
          async function getComm() {
            let id = {{$data['pid']}};
            let resp = await fetch(window.location.href+"/../../api/comments/"+id);
    
            if(resp.status !==  404){
              const data = await resp.json();
              array = await data;

              commentDiv = document.getElementById("comments")

              array.forEach(e => {
                
                rat = e.rating
                stars = "";
                for ( let i = 0; i < rat; i++ )
                    stars += '<i class="bi bi-star-fill" style="color:#ffa41c; "></i>';
                
                for ( let i = rat; i < 5; i++ )
                    stars += '<i class="bi bi-star" style="color: #de7921;"></i>';
                


                commentDiv.innerHTML += `
                <div id="${e.id}" class="comments">
                
                
                <div class="profile">
                  <i style="font-size:32px; color:#a8b6b7;" class="bi bi-person-circle"> </i>
                  <p class="name">
                    ${e.mail}
                  </p> 
                </div>
                <div class=stars>
                  ${stars}
                </div>

                <div class="at">
                ${e.writed_at}
                </div>
                <div class="comment">
                ${e.content}
                <hr>
                </div>
                
                </div>
                `
              });
            }
            else {
              return false;
            }
          };

          getComm()


        </script>
      </div>
    </section><!-- End Breadcrumbs -->

  </main><!-- End #main -->


  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/aos/aos.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/glightbox/js/glightbox.js"></script>
  <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>

</html>