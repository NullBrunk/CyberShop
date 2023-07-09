@if(!isset($_SESSION))
  @php(session_start());
@endif

<header id="header" class="fixed-top " style="background-color: #293E61 !important;">

    <div class="container d-flex align-items-center">
      <h1 class="logo me-auto"><a href="/">Cybershop</a></h1>
        <nav id="navbar" class="navbar">
        
          <ul>
            @if(!isset($_SESSION["logged"]))
              <li><a class="nav-link scrollto" href="{{ route("root") }}">Home</a></li>            
            @endif

              <li><a class="nav-link scrollto" href="{{ route("articles") }}">Search</a></li>            
            

            @if(isset($_SESSION['logged']))
            <li ><a class="nav-link scrollto" href="{{ route("sell") }}">Market</a></li>            
            <li><a class="nav-link scrollto" href="{{ route("contact") }}">Contact</a></li>
            <li><a class="nav-link scrollto" href="{{ route("profile") }}">Profile</a></li>            

              
                <script>
                    async function deleteitem(id) {
                        // Supprimer un élément du panier dans la BDD 

                        url = "/cart/delete/"+id;
                        let resp = await fetch(url);

                        // Supprimer l'élément de la div sans avoir a relloader la page
                        const elem = document.getElementById(id);
                        elem.remove();

                        // On modifie le nombrz afficher en haut du panier
                        const num = document.getElementById("number");
                        console.log(num.innerHTML)

                        if(num.innerHTML == 1){
                          number = document.getElementById("number");
                          number.remove()
                        } 
                        else {
                          num.innerHTML = num.innerHTML - 1
                        }
                      }

                </script>



              {{-- Si le tableau représentant le cart n'est pas vide --}}  
              @if(!empty($_SESSION['cart']))

                <p id="padding" style="margin-right: 30px;"></p>
                  
                  @php($total = 0)

                  <li id="cart" style="list-style-type: none;" class="dropdown">
                    
                    <a style="margin: 0px; padding: 0px;" href="#">
                      <span id="number" class="badge bg-primary badge-number">{{ sizeof($_SESSION["cart"]) }}</span>
                      <i style="font-size: 26px !important;" class="bi bi-cart carti">
                      </i>
                    </a>
                    <ul  class="cartn" style="overflow: scroll; max-height: 55vh; margin-left: -350%;">
                    

                      @foreach($_SESSION['cart'] as $p)
                        
                        @php($total += $p['price']) 
                        <li id="{{$p['cid']}}">
                          <p class="show_cart">
                          <img style="padding-left: 3%; width: 22%;" 

                          @if(isset($dotdotslash))
                            src="../../storage/product_img/{{ $p["image"] }}">
                          @else
                            src="../storage/product_img/{{ $p["image"] }}">
                          @endif
                          
                          <a href="/details/{{ $p['pid'] }}">{{ substr($p["name"], 0, 11) }}</a>
                            <i onclick="deleteitem({{$p['cid']}})" style="cursor: pointer" class="bi bi-trash2-fill trash-cart"></i>
                          <p>
                        </li>

                      @endforeach

                      <li><a id="price" style="width: 90%; display: block;" class="button" href="{{route("cart.display")}}">Buy</a></li>
                    </ul>
                  </li>
              @else 
              <p id="padding" style="margin-right: 30px;"></p>
                  
              <li id="cart" style="list-style-type: none;" class="dropdown">
                
                <a style="margin: 0px; padding: 0px;" href="#">
                  <i style="font-size: 26px !important;" class="bi bi-cart carti">
                  </i>
                </a>
                <ul  class="cartn" style="overflow: scroll; max-height: 55vh; margin-left: -350%;">

                </ul>
              </li>
              @endif


              
<?php
    # On récupere les notifications directement depuis la base de donnée
    # grace a cette fonction 

    include_once __DIR__ . "/../../../app/Http/Utils/Notifs.php";
    $array_info = show();

    $notifs = $array_info[0];
    $notifs_number = $array_info[1];

?>


              @if(!empty($notifs))
                <p id="padding" style="margin-right: 15px;"></p>
                  
                  <li id="cart" style="list-style-type: none;" class="dropdown ">
                    
                    <a style="margin: 0px; padding: 0px;" href="#">

                      <span id="" class="badge bg-primary badge-number">{{ $notifs_number }}</span>
                      <i style="font-size: 26px !important;" class="bi bi-bell carti">
                      </i>
                    </a>
                    <ul  class="cartn notif" style="overflow: scroll; max-height: 55vh; margin-left: -350%;">
                    
                      @foreach($notifs as $n)
                        
                        <li>
                          <p class="show_cart">
                            <li class="notification-item">
                              <i style="color:#19526f; font-size: 32px ;margin: 0 20px 0 10px;" class="{{ $n['icon'] }}"></i>
                              <div>
                                <h4 style="color: rgb(68, 68, 68)">{{ $n["title"] }} </h4>
                                <p>{{ $n["content"] }} </p>
                                <a href="{{ $n["more"] }}">See more <i class="bx bx-chevrons-right" ></i></a>
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

            @else
              <li><a class="nav-link scrollto" href="{{ route("signup") }}">Signup</a></li>
              <li style="list-style-type: none;"><a class="getstarted scrollto" href="{{ route("login") }}">Login</a></li>  
            @endif
          </ul>

          <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>
