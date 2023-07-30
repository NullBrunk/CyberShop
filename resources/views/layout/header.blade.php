@if(!isset($_SESSION))
  @php(session_start());
@endif

@php($logged = isset($_SESSION["logged"]))


@if($logged)
    <?php
        include_once __DIR__ . "/../../../app/Http/Utils/Notifs.php";
        $array_info = show();

        $notifs = $array_info[0];
        $notifs_number = $array_info[1];
    ?>
@endif

<header id="header" class="fixed-top " style="background-color: #293E61 !important;">

    <div class="container d-flex align-items-center" style="max-width: 87vw !important;">
      <h1 class="logo me-auto"><a href="/">Cybershop</a> </h1>
      
        <nav id="navbar" class="navbar">
          
            <ul>

            @if($logged)
                                                                                   
                <script>
                    async function deleteitem(id) {

                        // Supprimer un élément du panier
                        url = "/cart/delete/"+id;
                        let resp = await fetch(url);


                        // Supprimer l'élément de la div sans avoir a reloader la page
                        const elem = document.getElementById(id);
                        elem.remove();


                        // On modifie le nombre affiché en haut du panier
                        const num = document.getElementById("number");


                        if(num.innerHTML == 1){
                          number = document.getElementById("number");
                          number.remove()
                        } 
                        else {
                          num.innerHTML = num.innerHTML - 1
                        }
                      }
                </script>

                @if(!empty($notifs))
                                    

                <li id="cart" style="list-style-type: none;" class="dropdown ">
                    
                    <a class="nav-link" href="#">
                        <span class="badge bg-primary badge-number">
                            {{ $notifs_number }}
                        </span>
                        
                        <i class="bi bi-bell"></i>
                        <span>Notifs</span>
                    </a>


                    <ul class="cartn notif">
                    
                        @foreach($notifs as $n)
                            
                            <li>
                                <p class="show_cart">
                                    <li class="notification-item">
                                        <i style="color:#19526f; font-size: 32px ;margin: 0 20px 0 10px;" class="{{ $n['icon'] }}"></i>
                                        <div>
                                            <h4 style="color: rgb(68, 68, 68)">{{ $n["title"] }} </h4>
                                            <p> {{ $n["content"] }} </p>
                                            
                                            <a href="{{ $n["more"] }}">
                                                See more 
                                                <i class="bx bx-chevrons-right"></i>
                                            </a>

                                        </div>
                                    </li>
                                <p>
                            </li>

                        @endforeach

                    </ul>
                </li>

                @else

                <a class="nav-link" href="#">
                    <i class="bi bi-bell"></i>
                    <span>Notifs</span>
                </a>


                @endif

                {{-- Si le tableau représentant le cart n'est pas vide --}}  
                @if(!empty($_SESSION['cart']))
                    
                    @php($total = 0)

                    <li id="cart" style="list-style-type: none;" class="dropdown">
                        
                        <a class="nav-link" href="{{ route("cart.display")}}">
                            <span id="number" class="badge bg-primary badge-number">{{ sizeof($_SESSION["cart"]) }}</span>
                            <i class="bi bi-cart3"></i>
                            <span>Cart</span>

                        </a>

                        <ul class="cartn">
 
                            @foreach($_SESSION['cart'] as $p)
                                
                                <li id="{{$p['cid']}}">
                                    <p class="show_cart">
                                        <img src="/storage/product_img/{{ $p["image"] }}"       style="padding-left: 3%; width: 22%; display: block;">
                                        
                                        <a href="/details/{{ $p['pid'] }}" style="display: block;overflow: hidden; width: 57%; margin:auto;">{{ $p["name"] }}</a>
                                        <img src="/assets/img/trash.png" onclick="deleteitem({{$p['cid']}})" class="trash-cart"> 
                                    </p>
                                </li>
                                <hr>

                            @endforeach

                            <li>
                                <a id="price" class="button" href="{{route("cart.display")}}" style="width: 90%; display: block;">
                                    Buy
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

            @endif
            
            @if(!$logged or empty($_SESSION['cart']))
                <li style="list-style: none;">
                    <a class="nav-link" href="{{ route("cart.display")}}">
                        <i class="bi bi-cart3"></i>
                        <span>Cart</span>
                    </a>
                </li>
            @endif


                <li style="list-style: none;">
                    <a class="nav-link" href="{{route("product.store")}}">
                        <i class="bi bi-basket3"></i>
                        <span>Market</span>
                    </a>
                </li>  
                
                <li style="list-style: none;">
                    <a class="nav-link" href="{{ route("contact.show") }}">
                        <i class="bi bi-chat"></i>
                        <span>Chatbox</span>
                    </a>
                </li>


                <li class="dropdown" style="list-style: none;">
                    <a class="nav-link" href="#">
                        <i class="bi bi-border-all"></i>
                        <span>Products</span>
                    </a>

                    <ul class="products" style="width: 11vw;">                    
                        <li>
                            <a href="{{ route("product.show", "informatics") }}">
                                <i class="bx bx-laptop showcategory"></i> 
                                <i class="bi bi-dot padding"></i> 
                                <p>
                                    Informatics
                                </p>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route("product.show", "appliances") }}">
                                <i class="bx bx-fridge showcategory"></i> 
                                <i class="bi bi-dot padding"></i> 
                                <p>
                                    Appliances
                                </p>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route("product.show", "furnitures") }}">
                                <i class="bx bx-door-open showcategory"></i> 
                                <i class="bi bi-dot padding"></i> 
                                
                                <p>
                                    Furnitures
                                </p>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route("product.show", "vehicles") }}">
                                <i class="bx bx-car showcategory"></i> 
                                <i class="bi bi-dot padding"></i> 
                                <p>
                                    Vehicles
                                </p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route("product.show", "dresses") }}">
                                <i class="bx bx-shopping-bag showcategory"></i> 
                                <i class="bi bi-dot padding"></i> 
                                <p>
                                    Dresses
                                </p>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route("product.show", "gaming") }}">
                                <i class="bx bx-joystick showcategory"></i> 
                                <i class="bi bi-dot padding"></i> 
                                <p>
                                    Gaming
                                </p>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route("product.show", "other") }}">
                                <i class="bx bx-package showcategory"></i> 
                                <i class="bi bi-dot padding"></i> 
                                <p>
                                    Other
                                </p>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route("product.show", "food") }}">
                                <i class="bx bx-bowl-rice showcategory"></i> 
                                <i class="bi bi-dot padding"></i> 
                                <p>
                                    Food
                                </p>
                            </a>
                        </li>

                        <hr class="hrproducts">

                        <li>
                            <a href="{{ route("product.show", "all") }}">
                                <i class="bx bx-border-all showcategory"></i> 
                                <i class="bi bi-dot padding"></i> 
                                <p>
                                    All products
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>

 
                <li style="list-style: none;">
                    <a class="nav-link" href="{{ route("profile.profile") }}">
                        <i class="bi bi-sliders"></i>
                        <span>Settings</span>

                    </a>
                </li>



                {{-- Login button or Logout button --}}
                @if(!$logged)
                    <li style="list-style: none;">
                        <a class="nav-link" href="{{ route("auth.login") }}">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Login</span>
                        </a>
                    </li>
                @else
                    <li style="list-style: none;">
                        <a class="nav-link" href="{{ route("disconnect") }}">
                            <i class="bi bi-box-arrow-left"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                @endif
            
            </ul>

            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>
