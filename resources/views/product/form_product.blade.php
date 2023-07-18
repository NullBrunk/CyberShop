@extends("static.base")

@section("title", $data["name"])


@section("content")
    <body>

        @include('layout.header')

            <main id="main">

                {{-- On se positionne en bas de la navbar --}}
                <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
                    <div class="container">
                        <ol></ol>
                    </div>
                </section>



                <section id="portfolio-details" class="portfolio-details">
                    
                    <form method="post" action="{{ route("product.edit", $data['pid']) }}" enctype="multipart/form-data">  

                        <div class="container">
                            <div class="row gy-4">
                                <div class="col-lg-8 takefull" style="width: 50%; display: flex;" >
                                    <div class="portfolio-details-slider swiper">
                                        <div class="swiper-wrapper align-items-center">
                                            
                                            {{-- Pour l'instant l'image n'est pas modifiable --}}
                                            
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
                            
                                            {{-- On préremplit tous les champs --}}

                                            <li>
                                                <strong>Name    : 
                                                    <input class="input-beautify" 
                                                    type="text" name="name" 
                                                    value="{{$data["name"]}}">
                                                </strong>
                                            </li>

                                            <li>
                                                <strong>Price    : 
                                                    <input style="margin-left: 12% !important;" 
                                                    class="input-beautify" type="text" 
                                                    name="price" value="{{$data["price"]}}">
                                                </strong>
                                            </li>
                                            
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
                                                    // On préselectionne le bon <option>
                                                    document.getElementById("select").value = "{{ $data['class']}}" 
                                                </script>

                                            </li>

                                            <br>
                                            <h3></h3>
                                        
                                        </ul>
                                    </div>

                                    <div class="portfolio-info">
                                        <p class="descr">
                                            <textarea 
                                                placeholder="Description of the product" 
                                                id="textarea" class="textarea-beautify" 
                                                type="text" name="description"
                                            >
{{$data["descr"]}}
                                            </textarea>      
                                        </p> 
                                    </div>
                                
                                    @csrf
                                    
                                    {{-- 
                                        Depuis ce formulaire on peut soit editer 
                                        le produit soit arreter de le vendre en le 
                                        supprimant
                                    --}}

                                    <button class="addtocart buttonupdate" name="submit" value="update">UPDATE<i class="bi bi-check"></i></button>
                                    <button class="deleteprod" style="margin-top:0px; margin-left: 3%; margin-bottom: 3%;"  name="submit" value="delete">DELETE <i class="bi bi-x"></i></button>

                                </div>
                            </div>
                        </div>
                    </form>
                    
                </section>
            <hr>
        </main>

@endsection

{{-- Gestion des erreurs --}}

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
