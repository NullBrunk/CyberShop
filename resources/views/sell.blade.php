<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sell a product</title>
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
        <h2></h2>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
      
      <form method="post" action="{{ route("sellProduct") }}" enctype="multipart/form-data">  
      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-8">
            <div class="portfolio-details-slider swiper">
              <div class="swiper-wrapper align-items-center">

                <input type="file" name="product_img" {{ old("product_img") }}>
                <br><br><br><br>
                  @if($errors -> has("product_img") or isset($_SESSION["error"]))
                    <div class="alert alert-danger">
                      Must be an image, and smaller than 2MO.
                    </div>

                    <?php
                      unset($_SESSION["error"]);
                    ?>
                  @elseif(isset($_SESSION["done"]))
                    <div class="alert alert-success">
                      Successfully added the product !
                    </div>

                    <?php
                      unset($_SESSION["done"]);
                    ?>     
                  @endif               
                  
                  @if($errors -> has("name"))
                    <div class="alert alert-danger">
                        The name is required and must be smaller than 45 characters !
                    </div>
                  @endif

                  @if($errors -> has("price"))
                    <div class="alert alert-danger">
                        The price is required and must be an integer !
                    </div>
                  @endif

                  @if($errors -> has("description"))
                    <div class="alert alert-danger">
                        A description is required !
                    </div>
                  @endif


              </div>
              <div class="swiper-pagination"></div>
            </div>
          </div>

          <div class="col-lg-4"  style="color: white; background-color: #324769 !important; border-radius: 12px;">
            <div class="portfolio-info" style="padding-bottom: 10px;" >
              <h2>Product information</h2>
              <hr>
              <ul>
               

                <li><strong>Name    : <input style="margin-left: 11%; font-size: 18px; padding-left: 11%; width: 70%;border: 0px solid #eeeeee; border-radius: 5px; background-color: #293e61; color: white;" placeholder="Name of the product" type="text" name="name" value="{{old("name")}}"></strong></li>
                <li><strong>Price    : <input style="margin-left: 13%; font-size: 18px; padding-left: 11%; width: 70%;border: 0px solid #eeeeee; border-radius: 5px; background-color: #293e61; color: white;" placeholder="Price of the product" type="text" name="price" value="{{old("price")}}"></strong></li>
                
                <li><strong>Category : 

                  <select style="height: 7vh; margin-left: 5%; font-size: 18px; padding-left: 18%; width: 70%;border: 0px solid #eeeeee; border-radius: 5px; background-color: #293e61; color: white;" name="category">
                    
                    <option value="filter-laptop" >Informatics</option>
                    <option value="filter-dresses">Dresses</option>
                    <option value="filter-gaming" >Gaming</option>
                    <option value="filter-health" >Health</option>
                    <option value="filter-beauty" >Beauty</option>


                </select>

                </strong></li>                
                <br>
                <h3></h3>
              </ul>
            </div>
            <div class="portfolio-info">
              <p class="descr">
                  <textarea style="background-color: #293e61; color: white; padding-left: 8px; padding-top: 8px; border-radius: 5px; border: 0; width: 100%; height: 15vh; font-size: 16px;" placeholder="Description of the product" type="text" name="description">{{old("description")}}</textarea>      
              </p> 

            </div>
            
            
              @csrf      
              <input class="addtocart" style="margin-left: 33%; margin-top:0px;" name="submit" type="submit" value="Sell !">
            </form>
          
          </div>
          

        </div>

      </div>
    </section><!-- End Portfolio Details Section -->
    <hr>


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