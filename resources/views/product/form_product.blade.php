@extends("layout.base")

@section("title", $data["name"])


@section("content")

    <body>

        <script type="text/javascript">
            
            $(function() {
                $('#textarea').markItUp(mySettings);
            }) 

        </script>

            <main id="main">

                {{-- On se positionne en bas de la navbar --}}
                <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
                    <div class="container">
                        <ol></ol>
                    </div>
                </section>



                <section id="portfolio-details" class="portfolio-details">
                    
                    <form method="post" action="{{ route("product.edit", $data['id']) }}" enctype="multipart/form-data">  

                        <div class="container">
                            <div class="row gy-4">
                                <div class="col-lg-8 takefull" style="width: 50%; display: flex;" >
                                    <div class="portfolio-details-slider swiper">
                                        <div class="swiper-wrapper align-items-center">
                                                                                        
                                            <img style="width: 90% !important;" src="../../storage/product_img/{{ $data["image"] }}" alt="">
                                            <br>
                                            <br>
                                                    
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                </div>

                                <div class="col-lg-4 w-50"  style="color: white; background-color: #324769 !important; border-radius: 12px;">
                                    <div class="portfolio-info" style="padding-bottom: 10px;" >
                                        <h2>Product information</h2>
                                        <hr>
                                        <ul>

                                            <li>
                                                <strong style="display: flex">Name    : 
                                                    <input class="input-beautify" 
                                                    type="text" name="name" 
                                                    value="{{$data["name"]}}">
                                                </strong>
                                            </li>


                                            <li>
                                                <strong style="display: flex; margin-top: 8px;">Price    : 
                                                    <input 
                                                    class="input-beautify" type="text" 
                                                    name="price" value="{{$data["price"]}}">
                                                </strong>
                                            </li>
                                            

                                            <li>
                                                <strong style="display: flex;  margin-top: 8px;">Category : 
                                                    <select class="select-beautify" id="select" name="category">
                                                        <option value="filter-informatics" >Informatics</option>
                                                        <option value="filter-furnitures">Furnitures</option>
                                                        <option value="filter-appliances">Appliances</option>
                                                        <option value="filter-vehicles">Vehicles</option>
                                                        <option value="filter-dresses">Dresses</option>
                                                        <option value="filter-gaming" >Gaming</option>
                                                        <option value="filter-food" >Food</option>
                                                        <option value="filter-other" >Other</option>
                                                    </select>        
                                                </strong>

                                                <script>
                                                    // On préselectionne le bon <option>
                                                    document.getElementById("select").value = "{{ old('category') !== null ? old('category') : $data['class']}}" 
                                                </script>
                                            </li>

                                        </ul>

                                    </div>

                                    <div class="portfolio-info">
                                        <p  style="background: #fff;">
                                            <textarea name="description" id="textarea" placeholder="Description of the product" class="textarea-beautify">
{{ htmlspecialchars_decode($data["descr"])}}
                                            </textarea>      
                                        </p> 
                                    </div>
                                
                                    @csrf
    
                                    <!-- Edit -->
                                    <button class="addtocart buttonupdate" name="submit" value="update">UPDATE<i class="bi bi-check"></i></button>
                                    
                                    <!-- Delete -->
                                    <button class="deleteprod" style="margin-top:0px; margin-left: 3%; margin-bottom: 3%;"  name="submit" value="delete">DELETE <i class="bi bi-x"></i></button>

                                </div>
                            </div>
                        </div>
                    </form>
                </section>


                <script>
                    document.getElementById("textarea").style.height = "14vh" 
                </script>
            <hr>
        </main>

    {{-- Gestion des erreurs --}}

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
