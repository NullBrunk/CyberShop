@extends("static.base")

@section("title", ucfirst($name))

@section("content")

    <script src="https://unpkg.com/htmx.org@1.9.3" integrity="sha384-lVb3Rd/Ca0AxaoZg5sACe8FJKF0tnUgR2Kd7ehUOG5GCcROv5uBIZsOqovBAcWua" crossorigin="anonymous"></script>
    <link href="/assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">
    <link href="/assets/css/searchbar.css" rel="stylesheet">
    

    <body>

        <div style="padding-top:6%;" class=" d-flex align-items-center">
        
                <form>
                    <input id="input" type="text" placeholder="Search something ..." autofocus>
                    <i class="bx bx-search-alt" ></i>
                </form>
    
            </div>
    
    
            <div id="portfolio" class="portfolio">
    
                <div class="container">
                    <div class="row"  id="container">
                    </div>
                </div>
                
            </div>
    
            <script>
    
                // On recupere ce que l'utilisateur tape
                const input = document.getElementById("input");
    
                // On recupere le conteneur vide 
                const container = document.getElementById("container");

                // produits
                const portfolio = document.getElementById("baseproduct");

    
                // Fonction permettant d'ajouter un élement stylisé a notre conteneur vide
                function addproduct(id, image, price, classe, name){
                    
           
    
                    container.innerHTML += `         
    
                    <div class="col-md-3 portfolio-item ${classe}">
                        <div class="portfolio-wrap" style="flex-direction: column;">
                            <a href="/details/${id}">
                                <img src="/storage/product_img/${image}" class="img-fluid imgpres" alt="">
                            </a>
                        </div>

                        <div class="products">

                            <div class="categ">
                                ${ classe.split('-')[1].charAt(0).toUpperCase() + classe.split('-')[1].slice(1) }
                            </div>
                            <div class="title">
                                <a href="/details/${id}">${name}</a>
                            </div>
                        
                            <div class="pricepr">
                                ${price} <span>$</span>
                            </div>
                        </div>
                    </div>
                        `;

                }
    
                // On écoute chaque fois que l'utilisateur entre quelque chose dans l'input
                input.addEventListener('input', 
    
                    async function(event) {

                        // supprimer les produits deja affiché                 

                        let content = event.target.value;
                        
                        // On build l'url puis on fait une requete vers l'api
                        let url = "http://"+ window.location.hostname + ":8000/api/products/" + "{{$name}}" + "/" + content
                        let resp = await fetch(url);
                        if(typeof baseproduct !== 'undefined'){
                            baseproduct.remove()
                        }

                        // Si l'input de l'utilisateur match un produit
                        if(resp.status !==  404){
    
                            const data = await resp.json();
                            
                            container.innerHTML = ""
                            for(let i=0; i<data.length; i++){
                                elem = data[i];
                                // On l'ajoute a notre conteneur
                                addproduct(elem.id, elem.image, elem.price, elem.class, elem.name)
                            }

                            

                        }
                });
                
            </script>


        </div>


        <section id="baseproduct" class="portfolio" >

        
        
                

            <div class="container" data-aos="fade-up">

            


                <div  class="row portfolio-container">
                    @foreach($products as $d)


                        <div class="col-md-3 portfolio-item {{ $d['class'] }}">
                            <div class="portfolio-wrap" style="flex-direction: column;">
                                <a href="/details/{{ $d['id'] }}">
                                    <img 

                                    data-src="/storage/product_img/{{ $d['image'] }}" 
                                    class="img-fluid imgpres" alt="">
                                </a>
                                
                            </div>
                            <div class="products">


                                <div class="categ">
                                    {{ ucfirst(explode('-', $d["class"])[1]) }}
                                </div>
                                <div class="title">
                                    <a href="{{route("details", $d['id'])}}">{{ $d["name"] }}</a>
                                </div>
                               
                                <div class="pricepr">
                                    {{$d['price']}} <span>$</span>
                                </div>

                            </div>
                        </div>

                    @endforeach

                </div>

                @if($products -> nextPageUrl() !== null)
                    <button class="buttonpag" hx-get="{{ $products -> nextPageUrl() }}" hx-swap="outerHTML" hx-trigger="revealed">
                        <span class="paginationbutton">
                            <span class="spinner-border spinner-border-sm htmx-indicator" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                @endif
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

