@extends("layout.base")

@section("title", $product -> name )


@section("content")

@php($img_is_upper = count($images) > 1)
@php($mail = $product -> user -> mail)

    <body>
        <link rel="stylesheet" href="/assets/vendor/swiper/swiper-bundle.min.css">
        <script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>
        <script src="/assets/vendor/confetti/confetti.min.js"></script>

        <!-- JS -->
        <script src="/assets/vendor/confetti/confetti.min.js"></script>
        <script type="text/javascript">

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }
            
            const btn = document.querySelector('#btn');
            const canvas = document.querySelector('#confetti-canvas');
            function onButtonClick(y){
                var myConfetti = confetti.create(canvas, {
                    resize: true,
                    useWorker: true
                });
                myConfetti({
                    spread: 80,
                    particleCount: 150,
                    origin: { y: y, x: 0.88 }
                });
            }
        </script>

        <!-- CSS -->

        <script src="/assets/js/details.js"></script>

        <script>
            function deletecomm(commid){
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#293e61',
                    cancelButtonColor: '#af2024',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    // On redirige vers la page permettant de supprimer le commentaire
                    if (result.isConfirmed) {
                        fetch("/comments/delete/" + commid + "/{{ $product -> id }}", {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-Token": "{{ csrf_token() }}",
                            },
                        }).then(() => {
                            document.getElementById("comment_div_"+commid).remove()
                        });
                    }
                })
            }
        </script>


        <main id="main" >
              
            {{-- On se positionne en dessous de la navbar --}}
            <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
                <div class="container">
                    <ol></ol>
                </div>
            </section>


            
            <section id="portfolio-details" class="portfolio-details" style="padding-bottom: 0px;">
                <div class="container">
                    <div class="row gy-4 whenigrowibecomeablock">
                        <div  data-aos="fade-right" class="col-lg-8 takefull" style="width: 50%; display: flex;" >
                            <div style="margin: 0;height: 100%;width: 100%;">

                                    <div class="container swiper" style="height: 100%;width: 100%;">
                                        <div class="slide-container" style="height: 100%;">
                                            <div class="card-wrapper swiper-wrapper" id="swiper-wrap">
                        
                                                @if($img_is_upper)
                                                    @foreach($images as $img)
                                                        <div class="card swiper-slide" style="height: 75vh; display: flex; border: none;">
                                                            <div style="display: flex; height: 90%; width: 85%; margin: auto;">
                                                                <img unselectable="on" style="max-height: 100%; max-width:100%; margin: auto;" src="/storage/product_img/{{$img['img']}}" alt="" />
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else 
                                                
                                                    <div class="card" style="height: 75vh; display: flex; border: none;">
                                                        <div style="display: flex; height: 100%; width: 85%; margin: auto;">
                                                            <img unselectable="on" style="max-height: 100%; max-width:100%; margin: auto;" src="/storage/product_img/{{$images[0]['img']}}" alt="" />
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                        
                                        @if($img_is_upper)
                                            <div class="swiper-button-next swiper-navBtn" style="background: #eee;color: black !important"></div>
                                            <div class="swiper-button-prev swiper-navBtn" style="background: #eee;color: black !important"></div>
                                            <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal"></div>
                                        @endif

                                      </div>
                                {{-- Image of the product on the left --}}
                                
                            </div>
                        </div>

                        {{-- 
                            Textual description of the product
                        --}}
                        <div data-aos="fade-left" class="col-lg-4 marginlr"  style="color: white; background-color: #324769 !important; border-radius: 12px; width: 50%; height: 75vh; display: flex; flex-direction: column; ">
                            <div class="portfolio-info container" style="padding-bottom: 10px; padding-top: 30px !important;" >
                                <h2>{{$product -> name}}</h2>
                                <hr>
                            </div>

                            <div class="portfolio-info" style="position: relative;   padding-top: 0px !important;  height: 65%;">
                                <p class="descr">                     
                                    {!! $stylised_description !!}
                                </p>
                            </div>
                        
                            {{-- 
                                Si c'est l'utilisateur courant qui vend le produit,
                                on affiche un bouton d'édition du produit 
                            
                                Sinon, on affiche un bouton permettant d'ajouter
                                ce produit au panier
                            --}}


                            {{-- Pas connecté OU le vendeur n'est pas nous --}}
                            @if(!isset($_SESSION["mail"]) or (isset($_SESSION["mail"]) && $mail !== $_SESSION["mail"]))

                                <div  class="navbar formshow">
                                    <button class="addtocart" type="submit" onclick='addtocart("{{$product -> id}}"); success("The product has been added to the cart", "Added")'>BUY NOW<i  style="font-weight: bold !important;" class="bi bi-cart-plus"></i></button>
                                </div>

                            @else
                                <form class="navbar formshow" method="get" action="{{route("product.edit_form", $product -> id)}}" >  
                                    <button  class="addtocart" type="submit">EDIT PRODUCT<i style="font-weight: bold !important;" class="bi bi-cart-check"></i></button>
                                </form>
                            @endif

                        </div>

                        <br>
                        <hr>


                        <div id="info" data-aos="fade-right">

                            <h2>Product information</h2>

                            <table>
                                <tr>
                                    <th>Name</th>
                                    <td>{{$product -> name}}</td>
                                </tr>

                                <tr>
                                    <th>Seller</th>
                                    <td>
                                        {{-- 
                                            Si nous ne sommes pas le vendeur, le mail de celui ci
                                            est affiché dans une balise <a>, nous permettant
                                            (en cliquant sur le lien), d'etre redirigé vers la page pour
                                            le contacter.
                                        --}}

                                        @if(!isset($_SESSION["mail"]) or (isset($_SESSION["mail"]) && $mail !== $_SESSION["mail"])) 
                                            <a href="{{route('profile', $mail)}}">
                                                {{ $mail }}
                                            </a> 
                                        @else 
                                            {{ $mail }} 
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>Category</th>
                                    <td>{{ ucfirst($product -> class) }}</td>
                                </tr>

                                <tr>
                                    <th>Price</th>
                                    <td>{{ $product -> format_price() }}$</td>
                                </tr>

                                @if($rating)
                                    <tr>
                                        <th>Reviews</th>
                                            <td>

                                                {{ $rating['real'] }} 

                                                {!! $rating["icons"] !!}
                                            
                                                <a href="#comments"> 
                                                    {{$rating["rate"]}} 
                                                    
                                                    @if($rating["rate"] === 1)
                                                        rating
                                                    @else
                                                        ratings
                                                    @endif
                                                </a>
                                        </td>
                                    </tr>
                                @endif
                                
                            </table>

                            <p style="margin-top: 10vh;">
                            <hr>

                        </div>
                    </div>
                </div>
            </section>


            <section id="breadcrumbs" style="padding-top: 1%;" class="breadcrumbs">
                <div class="container" data-aos="fade-top-right">

                    <ol></ol>

                    
                    <h2>Comments</h2>

                    @if(!isset($_SESSION["logged"]))
                        <div class="alert alert-info">
                            Login to post a comment.
                        </div>
                    @else
                        <p class="commentlink" id="commenttext" onclick="showcomm()"><span class="amazonpolice" id="commcontent">Click here to post a comment</span> <i id="chevron" class="bx bx-chevron-right"></i></p>

                            <div id="formcomm" class="commentsbox none" >
                                
                                <form method="post" action="{{ route("comment.store", $product -> id_user) }}" style="width:100%;">
                                    
                                    @csrf
                                    <div class="title" style="height: 13vh;;">
                                        Title of your comment <abbr>*</abbr>
                                        <input name="title" type="text" value="{{old("title")}}" placeholder="Example: Nice product !" class="titlebar" maxlength="45">
                                    </div>
                                    
                                    <div class="contentcomment title" style="margin-top: 10px; height: 23vh;">
                                        Your comment <abbr>*</abbr>
                                        <textarea id="commentTextBar" placeholder="To help you write a useful comment for our cyber shop:

    - Explain to us why you chose this note?
    - What did you like best about this product?
    - Who would you recommend it to?"
                                        class="commentbar" name="comment" type="text">{{old("comment")}}</textarea>
                                    </div>

                                    <input name="id" type="hidden" value="{{ $product -> id }}">
                                    
                                    <br>
                                    <p class="title" style="margin-bottom: 0; margin-top: 60px; ">Rating <abbr>*</abbr></p>
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
                                    <br>
                                    <input class="commbutton" type="submit" value="Post comment">
                                    <br>

                                </form>
                                <p style="margin-bottom: 12vh;">
                            </div>
                        
                        @endif

                        <p style="margin-bottom: 7vh;">

                        <div id="comments">

                            @if($comments)
                                @foreach($comments as $comm)

                                <div id="comment_div_{{ $comm -> id }}">

                                    @php($mail = $comm -> user -> mail)
                                    <div id="{{ "div" . $comm -> id  }}" class="comments">          
                                        <div class="profile">
                                            
                                            <p class="profile">
                                                <i style="font-size:32px; color:#007185;" class="bi bi-person-circle"> </i>
                                                <p class="name">
            
                                                    @if(isset($_SESSION['mail']) and ($_SESSION["mail"] === $mail))
                                                        {{ $mail }}
                                                    @else
                                                        <a style="color: #007185" href="{{ route("profile", $mail) }}">{{$comm -> user-> mail}}</a>
                                                    @endif

                                                </p> 


                                                {{-- Menu to edit/delete a comment --}}
                                                @if(isset($_SESSION["mail"]) && $mail === $_SESSION["mail"])           
                                                    <p class="trash"> 
                                                        <i id="" onclick='menu("{{$comm -> id }}")' style="margin-top: 16px;" class="dots bx bx-dots-vertical-rounded"></i>     
                                                    </p>
                                                @endif 

                                            </div>
                                        </div>

                                        <div id="{{$comm -> id }}" class="none">

                                            <div style="display:flex; flex-direction: inherit; margin-bottom: 23px;">
                                                <a href="{{route("comment.update_form", $comm -> id )}}" 
                                                    id="{{$comm -> id  . 'updatebutton'}}" class="btn btn-primary menu update">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
    
    
                                                <button id="{{$comm -> id  . 'deletebutton'}}" onclick="deletecomm({{ $comm -> id }})" class="btn btn-primary menu" style="margin-left: 0px;">
                                                    <i class="bi bi-trash2-fill"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <span class="titlecomm"style="display:flex;margin-bottom: 0px;padding-bottom: 0px;">
                                            {{ $comm["title"] }}
                                            <span id="bruno{{ $comm -> id }}" class="likespan" >
                                                <p class="like">
                                                    <span id="num{{$comm -> id }}">
                                                        {{ $comm -> like() -> count() }}
                                                    </span>

                                                    <i id="icon{{$comm -> id }}" class="bi"></i>

                                                    <script>
                                                        haveiliked("{{ route('like.get', $comm -> id ) }}", "icon{{$comm -> id }}")
                                                    </script>
                                                </p>
                                            </span>
                                        </span>

                                        <script>
                                            document.getElementById("bruno{{ $comm -> id }}").addEventListener("click", (event) => {
                                                
                                                @isset($_SESSION["logged"])
                                                    heartclick("{{ route("like.toggle", $comm -> id ) }}", "icon{{$comm["id"]}}", "num{{$comm["id"]}}")
                                                    
                                                    if(!document.getElementById("icon{{$comm["id"]}}").classList.contains("bi-heart-fill")) { 

                                                        const m = (0.2 - 1.0) / (112 - 710);
                                                        const b = 1.0 - m * 710;
        
                                                        onButtonClick(m * event.clientY + b); 
                                                    }
                                                @endisset 
                                            })
                                        </script>



                                        <div class="stars">

                                            @for($i=0; $i<$comm["rating"]; $i++)
                                                <i class="bi bi-star-fill" style="color: #de7921;"></i>
                                            @endfor

                                            @for($i = $comm["rating"]; $i < 5; $i++)
                                                <i class="bi bi-star" style="color: #de7921;"></i>
                                            @endfor

                                            <span class="at">
                                                {{ $comm["writed_at"] }}
                                            </span>

                                            
                                        </div>
    

                                        <div class="comment">
                                            <?php 
                                            include_once __DIR__ . "/../../../app/Http/Utils/Style.php" ?>
                                            {!! style($comm["content"]) !!}
                                            <hr>
                                        </div>
                                    </div>                  
                                @endforeach
                            @endif

                    </div>
                </div>
            </section>
        </main>

      

        <script src="/assets/js/products.js"></script>

    {{-- Gestion des erreurs --}}

    @error("rating")
        <script>
            alert_and_scroll("You need to give a rating !")
        </script>
    @enderror

    @error("title")
        <script>
            alert_and_scroll("{{$message}}")
        </script>
    @enderror

    @error("comment")
        <script>
            alert_and_scroll("{{$message}}")
        </script>
    @enderror
                

    {{-- Gestion des messages de succès --}}

    @if(session("updated"))
        <script>
            success("{{session('updated')}}")
        </script>
    @endif

    @if(session("posted"))
        <script>
            success("{{ session('posted') }}", "Posted")
        </script>
    @endif

    @if(session("updatedcomm"))
        <script>
            success("{{ session('updatedcomm') }}", "Updated")
        </script>
    @endif

    @if(session("selled"))
        <script>
            success("{{ session('selled') }}", "Selled")
        </script>
    @endif   

@endsection


