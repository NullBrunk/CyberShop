@if(!isset($_SESSION))
    @php(session_start());
@endif

@php($logged = isset($_SESSION["logged"]))
<header id="header" class="fixed-top " style="background-color: #293E61 !important;">

    <script src="/assets/js/cart_header.js"></script>

    <div class="container d-flex align-items-center" style="max-width: 87vw !important;">
        <h1 class="logo me-auto"> <a href="/">{{ config("app.name") }}</a> </h1>

        <nav id="navbar" class="navbar">

            <ul>

                @if($logged)

                    {{-- Using the PUSHER websocket --}}
                    <x-pusher-websocket />
                    
                    <livewire:header />

                    <script>
                        
                    </script>


                    {{-- Si le tableau représentant le cart n'est pas vide --}}
                    @if(!empty($_SESSION['cart']) && Route::currentRouteName() !== "cart.display")

                        <?php
                            $total = 0;
                            $v = 0;

                            foreach($_SESSION['cart'] as $q){
                                $v += $q["quantity"];
                            }
                        ?>

                        <li id="cart" style="list-style-type: none;" class="dropdown">

                            <a class="nav-link" href="{{ route("cart.display")}}">
                                <span id="number" class="badge bg-primary badge-number">{{ $v }}</span>
                                <i class="bi bi-cart3"></i>
                                <span>Cart</span>
                            </a>

                            <ul style="width: 290px; max-height: 70vh; overflow: scroll;">

                                <div id="">

                                    <div id="cart_to_fill">

                                        @foreach($_SESSION['cart'] as $c)
                                        
                                        <li id="cart_{{ $c -> id_product }}">
                                            <div class="show_cart">

                                                <img src="/storage/product_img/{{ $c -> product -> product_images() -> where("is_main", "=", 1) -> first() -> img }}" style="padding-left: 3%; width: 22%; display: block; user-select: none !important;">
                                                
                                                <div class="d-flex flex-column cartelem" style="width: 57%; overflow: hidden;">
                                                    <a href="/details/{{ $c -> id_product }}" style="width: 94%; padding: 6px 0px 0px 20px; overflow: hidden;">{{ $c -> product -> name }}</a>
                                                    <div>
                                                        <i class="bi bi-x"></i><span>{{ $c -> quantity }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-row m-auto">
                                                    <button  class="cartbutton button-blue rounded" onclick='addtocart("{{$c -> id_product}}")'>+</button>
                                                    <button style="margin-right: 4px;" class="cartbutton button-red rounded" onclick='deleteitem("cart_{{$c -> id_product}}")'>-</button>
                                                </div>
                                            </div>
                                        </li>
                                        <hr id="hrcart_{{ $c -> id_product }}">

                                        @endforeach

                                    </div>
                                    <a href="/cart/show" class="btn btn-primary button-blue cart-buy" style="width: 90%;margin-left: 5%; font-weight: 900; margin-bottom: 10px;"> BUY </a>                                  
                                </div>
                            </ul>
                            
                        </li>
                    @endif

                @else
                    <a class="nav-link" href="#">
                        <i class="bi bi-bell"></i>
                        <span>Notifs</span>
                    </a>
                @endif

            @if(!$logged or empty($_SESSION['cart']))
            <li id="cart" style="list-style-type: none;" class="dropdown">

                <a class="nav-link" href="{{ route("cart.display")}}">
                    <span id="number" class="badge bg-primary badge-number"></span>
                    <i class="bi bi-cart3"></i>
                    <span>Cart</span>
                </a>

                <ul style="width: 250px">

                    <div id="">

                        <div id="cart_to_fill">

                    
                        </div>
                        <a href="/cart/show" class="btn btn-primary button-blue cart-buy" style="width: 90%;margin-left: 5%; font-weight: 900;"> BUY </a>                                  
                    </div>
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

                <ul class="products" style="width: 150px;">
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
                <a class="nav-link" @if(isset($_SESSION["mail"])) href="{{ route("profile", $_SESSION["mail"]) }}" @else href="/login" @endif>
                    <i class="bi bi-sliders"></i>
                    <span>Profile</span>

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
                        <a class="nav-link" style="cursor: pointer;" onclick="logout()">
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



