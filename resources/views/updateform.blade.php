<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>{{$data["name"]}}</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <link href="../../assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <script src="../../assets/js/sweetalert2.js"></script>

        <link href="../../assets/css/style.css" rel="stylesheet">

    </head>

    <body>

        @include('../layout/header', [ "dotdotslash" => "../"])

            <main id="main">

                <!-- ======= Breadcrumbs ======= -->
                <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
                    
                    <div class="container">
                        <ol></ol>
                    </div>

                </section><!-- End Breadcrumbs -->

                <!-- ======= Portfolio Details Section ======= -->
                <section id="portfolio-details" class="portfolio-details">
                    <form method="post" action="{{ route("product.update", $data['pid']) }}" enctype="multipart/form-data">  

                        <div class="container">

                            <div class="row gy-4">
                                <div class="col-lg-8">
                                    <div class="portfolio-details-slider swiper">
                                        <div class="swiper-wrapper align-items-center">
                                            <img style="width: 60% !important;" src="../../storage/product_img/{{ $data["image"] }}" alt="">
                                            <br>
                                            <br>
                                                    
                                            @if(isset($_SESSION["done"]))
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
                            
                                            <li><strong>Name    : <input class="input-beautify" type="text"  name="name" value="{{$data["name"]}}"></strong></li>
                                            <li><strong>Price    : <input style="margin-left: 13% !important;" class="input-beautify" type="text" name="price" value="{{$data["price"]}}"></strong></li>
                                            
                                            <li>
                                                <strong>Category : 
                                                    <select class="select-beautify" id="select" name="category">
                                                        <option value="filter-laptop" >Informatics</option>
                                                        <option value="filter-dresses">Dresses</option>
                                                        <option value="filter-gaming" >Gaming</option>
                                                        <option value="filter-food" >Food</option>
                                                        <option value="filter-other" >Other</option>
                                                    </select>
                                                    
                                                </strong>
                                                <script>
                                                    document.getElementById("select").value = "{{ $data['class']}}" 
                                                </script>
                                            </li>                
                                            <br>
                                            <h3></h3>
                                        </ul>
                                    </div>
                                    <div class="portfolio-info">
                                        <p class="descr">
                                            <textarea placeholder="Description of the product" class="textarea-beautify" type="text" name="description">{{$data["descr"]}}</textarea>      
                                        </p> 
                                    </div>
                                
                                    @csrf      
                                    <button class="addtocart" style="margin-left: 6%; margin-top:0px; margin-bottom: 3%;" name="submit" value="update">UPDATE<i class="bi bi-check"></i></button>
                                    <button class="deleteprod" style="margin-top:0px; margin-left: 3%; margin-bottom: 3%;"  name="submit" value="delete">DELETE <i class="bi bi-x"></i></button>

                                </div>
                            </div>
                        </div>
                    </form>
                    
                </section>
            <hr>
        </main>


        <div id="preloader"></div>
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <script src="../../assets/vendor/aos/aos.js"></script>
        <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="../../assets/vendor/waypoints/noframework.waypoints.js"></script>

        <script src="../../assets/js/main.js"></script>

    </body>

</html>