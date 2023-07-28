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

    <div class="container d-flex align-items-center">
      <h1 class="logo me-auto"><a href="/">Cybershop</a> </h1>
      
        <nav id="navbar" class="navbar">
          
          <ul>

            <li style="list-style: none;"><a class="nav-link" href="{{route("product.store")}}">Market</a></li>  
            <li style="list-style: none;"><a class="nav-link scrollto" href="{{ route("contact.show") }}">Chatbox</a></li>


            <li class="dropdown" style="padding-right: 8px;"><a href="#"><span>Products</span> <i class="bi bi-chevron-down"></i></a>
                <ul>                    
                    <li><a style="padding-left: 14px;" class= "nav-link" href="{{ route("product.show", "informatics") }}"><i class="bx bx-laptop"></i> <i class="bi bi-dot"></i> Informatics</a></li>
                    <li><a style="padding-left: 14px;" class= "nav-link" href="{{ route("product.show", "furnitures") }}"><i class="bx bx-door-open"></i> <i class="bi bi-dot"></i> Furnitures</a></li>
                    <li><a style="padding-left: 14px;" class= "nav-link" href="{{ route("product.show", "appliances") }}"><i class="bx bx-fridge"></i> <i class="bi bi-dot"></i> Appliances</a></li>
                    <li><a style="padding-left: 14px;" class= "nav-link" href="{{ route("product.show", "vehicles") }}"><i class="bx bx-car"></i> <i class="bi bi-dot"></i> Vehicles</a></li>
                    <li><a style="padding-left: 14px;" class= "nav-link" href="{{ route("product.show", "dresses") }}"><i class="bx bx-shopping-bag"></i> <i class="bi bi-dot"></i> Dresses</a></li>
                    <li><a style="padding-left: 14px;" class= "nav-link" href="{{ route("product.show", "gaming") }}"><i class="bx bx-joystick"></i> <i class="bi bi-dot"></i> Gaming</a></li>
                    <li><a style="padding-left: 14px;" class= "nav-link" href="{{ route("product.show", "other") }}"><i class="bx bx-package"></i> <i class="bi bi-dot"></i> Other</a></li>
                    <li><a style="padding-left: 14px;" class= "nav-link" href="{{ route("product.show", "food") }}"><i class="bx bx-bowl-rice"></i> <i class="bi bi-dot"></i> Food</a></li>

                    <hr class="hrproducts">
                    <li><a style="padding-left: 14px;" class= "nav-link" href="{{ route("product.show", "all") }}"><i class="bx bx-border-all"></i> <i class="bi bi-dot"></i> All products</a></li>
                            
                </ul>
            </li>

 
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
                                    
                <p id="padding" style="margin-right: 15px;"></p>

                <li id="cart" style="list-style-type: none;" class="dropdown ">
                    
                    <a style="margin: 0px; padding: 0px;" href="#">

                        <span class="badge bg-primary badge-number">
                            {{ $notifs_number }}
                        </span>
                        
                        <i class="bi bi-bell carti"  style="font-size: 26px !important;">
                        </i>

                    </a>

                    <ul class="cartn notif" style="overflow: scroll; max-height: 55vh; margin-left: -350%;">
                    
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

                <p id="padding" style="margin-right: 15px;"></p>

                <li id="cart" style="list-style-type: none;" class="dropdown ">
                    <a style="margin: 0px; padding: 0px;" href="#">
                        <i  style="font-size: 26px !important;" class="bi bi-bell carti">
                        </i>
                    </a>
                </li>
                @endif

                {{-- Si le tableau représentant le cart n'est pas vide --}}  
                @if(!empty($_SESSION['cart']))

                    <p id="padding" style="margin-right: 15px;"></p>
                    
                    @php($total = 0)

                    <li id="cart" style="list-style-type: none;" class="dropdown">
                        
                        <a style="margin: 0px; padding: 0px;" href="{{ route("cart.display")}}">
                            <span id="number" class="badge bg-primary badge-number">{{ sizeof($_SESSION["cart"]) }}</span>
                            <i style="font-size: 26px !important;" class="bi bi-cart carti"></i>
                        </a>

                        <ul class="cartn" style="overflow: scroll; max-height: 55vh; margin-left: -350%;">
 
                            @foreach($_SESSION['cart'] as $p)
                                
                                <li id="{{$p['cid']}}">
                                    <p class="show_cart">
                                        <img src="/storage/product_img/{{ $p["image"] }}"       style="padding-left: 3%; width: 22%;">
                                        
                                        <a href="/details/{{ $p['pid'] }}">{{ substr($p["name"], 0, 11) }}</a>
                                        <i onclick="deleteitem({{$p['cid']}})" style="cursor: pointer" class="bi bi-trash2-fill trash-cart"></i>
                                    </p>
                                </li>

                            @endforeach

                            <li>
                                <a id="price" class="button" href="{{route("cart.display")}}" style="width: 90%; display: block;">
                                    Buy
                                </a>
                            </li>
                        </ul>
                    </li>

                @else 

                    <p id="padding" style="margin-right: 30px;"></p>
                  
                    <li id="cart" style="list-style-type: none;" class="dropdown">
                
                        <a style="margin: 0px; padding: 0px;" href="#">
                            <i style="font-size: 26px !important;" class="bi bi-cart carti">
                            </i>
                        </a>
                    
                        <ul  class="cartn" style="overflow: scroll; max-height: 55vh; margin-left: -350%;"></ul>
                    </li>

                @endif

                <div id="padding" class="sep">|</div>
               
                <li id="userprofile" style="list-style-type: none;" class="dropdown">
                    <a style="margin: 0px; padding: 0px;" href="{{ route("profile.profile") }}">
                        <i style="font-size: 26px !important;" class="bi bi-person-circle">
                        </i>
                    </a>
                </li>


            @else {{-- If the user isn't logged --}}

              <li><a class="nav-link scrollto" href="{{ route("auth.signup") }}">Signup</a></li>
              <li style="list-style-type: none;"><a class="getstarted scrollto" href="{{ route("auth.login") }}">Login</a></li>  
            
            @endif
            
          </ul>

          <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>
