@extends("static.base")

@section("title", "Sell a product")


@section("content")
    <body>

        @include('layout.header')
            <main id="main">

                <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
                    <div class="container">
                        <ol></ol>
                        <h2></h2>
                    </div>
                </section>

                <section id="portfolio-details" class="portfolio-details">
                
                    <form method="post" action="{{ route("product.store") }}" enctype="multipart/form-data">  
                        <div class="container">
                            <div class="row gy-4">
                                <div class="col-lg-8">
                                    <div class="portfolio-details-slider swiper">
                                        <div class="swiper-wrapper align-items-center">                
                                            <input type="file" name="product_img" {{ old("product_img") }}>       
                                            <br><br><br><br>
                                        </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>

                            <div class="col-lg-4"  style="color: white; background-color: #324769 !important; border-radius: 12px;">
                                <div class="portfolio-info" style="padding-bottom: 10px;" >
                                    <h2>Product information</h2>
                                    <hr>
                                    <ul>

                                        <li><strong>Name    : <input placeholder="Brown Mushroom" class="input-beautify" type="text" name="name" value="{{old("name")}}" autofocus></strong></li>
                                        <li><strong>Price    : <input placeholder="From 0.00 to 999md " style="margin-left: 13% !important;" class="input-beautify" type="text" name="price" value="{{old("price")}}"></strong></li>
                                        <li>
                                            <strong>Category : 

                                                <select class="select-beautify" name="category">
                                                    <option value="filter-laptop" >Informatics</option>
                                                    <option value="filter-dresses">Dresses</option>
                                                    <option value="filter-gaming" >Gaming</option>
                                                    <option value="filter-food" >Food</option>
                                                    <option value="filter-other" >Other</option>
                                                </select>

                                            </strong>
                                        </li>                
                                        <br>

                                        <h3></h3>

                                    </ul>
                                </div>

                                <div class="portfolio-info">
                                    <p class="descr">
                                        <textarea placeholder="Description of the product" class="textarea-beautify" type="text" name="description">{{old("description")}}</textarea>      
                                    </p> 
                                </div>
                            
                                @csrf      
                                <input class="addtocart" style="margin-left: 33%; margin-top:0px; margin-bottom: 3%;" name="submit" type="submit" value="Sell !">
                            </div>
                        </form>
                    </div>
                </div>
            
            </section><!-- End Portfolio Details Section -->
            <hr>
        </main><!-- End #main -->



    {{-- Gestion des erreurs --}}

    @error("imgerror")
            <script>alert("{{$message}}")</script>
    @enderror

    @error("name")
        <script>
            alert("{{$message}}")
        </script>
    @enderror

    @error("price")
        <script>
            alert("{{$message}}")
        </script>
    @enderror

    @error("description")
        <script>
            alert("{{$message}}")
        </script>
    @enderror

@endsection

