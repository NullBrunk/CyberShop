@extends("static.base")

@section("title", "Search a product")


@section("content")
    <link href="assets/css/searchbar.css" rel="stylesheet">
    <body>


        <section class="margintop d-flex align-items-center">

            <form>
                <input id="input" type="text" placeholder="Search something ..." autofocus>
                <i class="bx bx-search-alt" ></i>
            </form>

        </section>


        <section id="portfolio" class="portfolio">

            <div class="container">
                <div class="row"  id="container">
                
                </div>
            </div>
            
        </section>

        <script>

            // On recupere ce que l'utilisateur tape
            const input = document.getElementById("input");

            // On recupere le conteneur vide 
            const container = document.getElementById("container");

            // Fonction permettant d'ajouter un élement stylisé a notre conteneur vide
            function addproduct(id, image, price, classe){
                container.innerHTML += `         

                <div class="col-md-3 portfolio-item ${classe}">
                    <div class="portfolio-wrap" style="border-radius: 5px;">
                        <a href="/details/${id}">
                            <img src="/storage/product_img/${image}" class="img-fluid imgpres" alt="">
                        </a>
                        <div class="portfolio-info">   
                            <div class="portfolio-links">
                                <p class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><strong>${price} $</strong></p>
                            </div>

                        </div>
                    </div>
                </div>`
            }

            // On écoute chaque fois que l'utilisateur entre quelque chose dans l'input
            input.addEventListener('input', 

                async function(event) {
                    let content = event.target.value;

                    // On modifie le title de la page
                    document.getElementById("title").innerText = "Search  ·  " + content; 
                    
                    // On build l'url puis on fait une requete vers l'api
                    let url = "http://"+ window.location.hostname + ":8000/api/products/"+ content
                    let resp = await fetch(url);
                    
                    // Si l'input de l'utilisateur match un produit
                    if(resp.status !==  404){

                        const data = await resp.json();
                        
                        container.innerHTML = ""
                        for(let i=0; i<data.length; i++){
                            elem = data[i];
                            // On l'ajoute a notre conteneur
                            addproduct(elem.id, elem.image, elem.price, elem.class)
                        }
                    }
            });
            
        </script>
@endsection