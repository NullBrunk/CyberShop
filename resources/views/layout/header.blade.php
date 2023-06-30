@if(!isset($_SESSION))
  @php(session_start());
@endif

<header id="header" class="fixed-top " style="background-color: #293E61 !important;">

    <div class="container d-flex align-items-center">
      <h1 class="logo me-auto"><a href="/">Cybershop</a></h1>
        <nav id="navbar" class="navbar">
        
          <ul>
            <li><a class="nav-link scrollto" href="{{ route("root") }}">Home</a></li>            
            <li><a class="nav-link scrollto" href="{{ route("articles") }}">Search</a></li>            
            
            
            @if(isset($_SESSION['logged']))
            <li><a class="nav-link scrollto" href="{{ route("contact") }}">Contact</a></li>

                <li ><a class="nav-link scrollto" href="{{ route("sell") }}">Sell</a></li>            
              
                <script>
                    async function deleteitem(id) {

                        url = "/cart/delete/"+id;
                        let resp = await fetch(url);

                        const elem = document.getElementById(id);
                        elem.remove();

                        const num = document.getElementById("number");
                        console.log(num.innerHTML)

                        if(num.innerHTML == 1){
                            const cart = document.getElementById("cart");
                            const p = document.getElementById("padding");
                            
                            cart.remove()
                            p.style.marginRight = "10px";
                        }
                        else {
                            num.innerHTML = num.innerHTML - 1
                        }

                    };

                </script>

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
                          src="../storage/product_img/{{ $p["image"] }}">
                          
                          
                          <a href="/details/{{ $p['pid'] }}">{{ explode(' ', $p["name"])[0] . ' ...' }}</a>
                          
                            <i onclick="deleteitem({{$p['cid']}})" style="cursor: pointer" class="bi bi-trash2-fill trash-cart"></i>
                            

                          <p>
                        </li>

                      @endforeach

                      <li><a id="price" class="button" href="{{route("cart.display")}}">Buy</a></li>
                    </ul>
                  </li>
              @else 
                <p style="margin-right: 10px;"></p>
              @endif


              <a href="{{ route("profile") }}" class="pdlp" ><i style="font-size: 26px !important;" class="bi bi-person-circle"></i></a>

            @else
              <li><a class="nav-link scrollto" href="{{ route("signup") }}">Signup</a></li>
              <li style="list-style-type: none;"><a class="getstarted scrollto" href="{{ route("login") }}">Login</a></li>  
            @endif
          </ul>

          <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>

