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
        <h1 class="logo me-auto"> <a href="/">{{ config("app.name") }}</a> </h1>

        <nav id="navbar" class="navbar">

            <ul>

                @if($logged)

                    <script>
                        async function deleteitem(id) {


                            let idproduct = id.split("_")[1];
                            // Supprimer un élément du panier
                            url = "/cart/delete/" + idproduct;
                            let resp = await fetch(url);


                            // Supprimer l'élément de la div sans avoir a reloader la page ainsi que son hr
                            document.getElementById(id).remove();
                            document.getElementById("hr" + id).remove()


                            // On modifie le nombre affiché en haut du panier
                            const num = document.getElementById("number");


                            if(num.innerHTML == 1){
                                number = document.getElementById("number");
                                number.innerHTML = ""
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

                        <div id="cart_to_fill">

                            @foreach($_SESSION['cart'] as $c)

                                <li id="cart_{{ $c -> id }}">
                                    <p class="show_cart">

                                        <img src="/storage/product_img/{{ $c -> product -> product_images() -> where("is_main", "=", 1) -> first() -> img }}"       style="padding-left: 3%; width: 22%; display: block; user-select: none !important;">

                                        <a href="/details/{{ $c -> product -> id }}" style="display: block;overflow: hidden; width: 57%; margin:auto;">{{ $c -> product -> name }}</a>
                                        <img src="/assets/img/trash.png" onclick='deleteitem("cart_{{$c -> id}}")' class="trash-cart">
                                    </p>
                                </li>
                                <hr id="hrcart_{{ $c -> id }}">

                            @endforeach

                        </div>

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
                <li id="cart" style="list-style-type: none;" class="dropdown">

                    <a class="nav-link" href="{{ route("cart.display")}}">
                        <span id="number" class="badge bg-primary badge-number"></span>
                        <i class="bi bi-cart3"></i>
                        <span>Cart</span>

                    </a>

                    <ul class="cartn">

                        <div id="cart_to_fill">
                        </div>

                        <li>
                            <a id="price" class="button" href="{{route("cart.display")}}" style="width: 90%; display: block;">
                                Buy
                            </a>
                        </li>
                    </ul>
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

                                <span>
                                    Informatics
                                </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route("product.show", "appliances") }}">
 
                                <span>
                                    Appliances
                                </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route("product.show", "furnitures") }}">

                                <span>
                                    Furnitures
                                </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route("product.show", "vehicles") }}">
                                <span>
                                    Vehicles
                                </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route("product.show", "dresses") }}">
                                
                                <span>
                                    Dresses
                                </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route("product.show", "gaming") }}">

                                <span>
                                    Gaming
                                </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route("product.show", "other") }}">
                              
                                <span>
                                    Other
                                </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route("product.show", "food") }}">
                              
                                <span>
                                    Food
                                </span>
                        </a>
                    </li>

                    <hr class="hrproducts">

                    <li>
                        <a href="{{ route("product.show", "all") }}">
                                
                                <span>
                                    All products
                                </span>
                        </a>
                    </li>

                </ul>
            </li>


            <li style="list-style: none;">
                <a class="nav-link" href="{{ route("profile.settings") }}">
                    <i class="bi bi-sliders"></i>
                    <span>Settings</span>

                </a>
            </li>



            {{-- Login button or Logout button --}}
                @if(!$logged)
                    <li style="list-style: none;">
                        <a class="nav-link" href="{{ route("auth.login") }}">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Login</span>
                        </a>
                    </li>
                @else
                    <li style="list-style: none;">
                        <a class="nav-link" href="{{ route("logout") }}">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                @endif

                </ul>

                <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>
