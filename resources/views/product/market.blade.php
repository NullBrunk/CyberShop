@extends("static.base")

@section("title", "Sell a product")


@section("content")
    <body>

        <script>
            $(function() {        
                $('#textbar').markItUp(
                    mySettings
                );
            }) 
        </script>

            <main id="main">

                <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
                    <div class="container">
                        <ol></ol>
                        <h2></h2>
                    </div>
                </section>

                <section id="portfolio-details" class="portfolio-details" style="padding: 0px;">
                
                    <form method="post" action="{{ route("product.store") }}" enctype="multipart/form-data">  
                           
                            <div class="col-lg-4"  style="color: white; background-color: #324769 !important; border-radius: 12px; width: 50%; margin: auto;">
                                <div class="portfolio-info" style="padding-bottom: 10px;" >
                                    <h2>Sell a product</h2>
                                    <hr>
                                    <ul>
                                        <li class="li"><strong style="display: flex;">Image: <input  type="file" class="input-beautify" style="border: none; background-color: inherit;" name="product_img" {{ old("product_img") }}></strong></li>
                                        <li class="li"><strong style="display: flex; ">Name:  <input  placeholder="Brown Mushroom" class="input-beautify" type="text" name="name" value="{{old("name")}}" autofocus></strong></li>
                                        <li class="li"><strong style="display: flex; ">Price: <input  placeholder="From 0.00 to 999md " class="input-beautify" type="text" name="price" value="{{old("price")}}"></strong></li>
                                        <li class="li">
                                            <strong style="display: flex; ">Category: 

                                                <select  class="select-beautify" name="category">
                                                    <option value="filter-laptop" >Informatics</option>
                                                    <option value="filter-dresses">Dresses</option>
                                                    <option value="filter-gaming" >Gaming</option>
                                                    <option value="filter-food" >Food</option>
                                                    <option value="filter-other" >Other</option>
                                                </select>

                                            </strong>
                                        </li>                

                                    </ul>
                                </div>

                                <div class="portfolio-info">
                                    <p  style="background: #fff;">
                                        <textarea id="textbar" placeholder="Description of the product" class="textarea-beautify" type="text" name="description">{{old("description")}}</textarea>      
                                    </p> 
                                </div>
                            
                                @csrf      
                                <input class="addtocart" style="margin-left: 42%; margin-top:0px; margin-bottom: 3%;" name="submit" type="submit" value="Sell !">
                            </div>
                        </form>
                </div>
            
            </section>

            <script>
                document.getElementById("textbar").style.height = "14vh" 
            </script>
        </main><!-- End #main -->



    {{-- Gestion des erreurs --}}

    @error("imgerror")
            <script>salert("{{$message}}")</script>
    @enderror

    @error("name")
        <script>
            salert("{{$message}}")
        </script>
    @enderror

    @error("price")
        <script>
            salert("{{$message}}")
        </script>
    @enderror

    @error("description")
        <script>
            salert("{{$message}}")
        </script>
    @enderror

@endsection

