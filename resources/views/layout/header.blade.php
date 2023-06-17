@if(!isset($_SESSION))
  @php(session_start());
@endif

<header id="header" class="fixed-top " style="background-color: #293E61 !important;">
    <div class="container d-flex align-items-center">

      <h1 class="logo me-auto"><a href="/">CyberShop</a></h1>

      <nav id="navbar" class="navbar">
      
      
        <ul>
          @if(isset($_SESSION['logged']))
            <li><a class="nav-link scrollto" href="{{ route("sell") }}">Sell</a></li>            
          @endif

          <li><a class="nav-link scrollto" href="{{ route("articles") }}">Articles</a></li>            
          <li><a class="nav-link scrollto" href="{{ route("about") }}">About</a></li>
          <li><a class="nav-link scrollto" href="{{ route("contact") }}">Contact</a></li>
        
          
          @if(isset($_SESSION['logged']))
            <script>

            async function deleteitem(id, price, total) {
                url = "/cart/delete/"+id
                let resp = await fetch(url);

                let elem = document.getElementById(id)
                elem.remove()

              
            };

            </script>

          @if(!empty($_SESSION['cart']))

            @php($total = 0)
            @php($i = 0)

            <li style="list-style-type: none;" class="dropdown"><a href="#"><i style="font-size: 26px !important;" class="bi bi-cart"></i></a>
              <ul>
                

                @foreach($_SESSION['cart'] as $p)
                  
                  @php($total += $p['price']) 
                  
                  <li id="{{$i}}">
                    <p class="show_cart">
                    <img style="padding-left: 3%; width: 22%;" 
                     src="../storage/product_img/{{ $p["image"] }}">
                    
                    
                    <a href="/details/{{$p['id']}}">{{ substr($p["name"], 0, 12) }}</a>
                    
                      <i onclick="deleteitem({{$i}}, {{$p['price']}}, {{$total}})" class="bx bx-trash-alt trash-cart"></i>
                    

                    <p>
                  </li>
                  @php($i++)
                @endforeach

                <li><a id="price" class="button" href="/">Buy</a></li>
              </ul>
            </li>
          @endif

          <li style="list-style-type: none;" class="dropdown"><a href="#"><i style="font-size: 32px !important;" class="bx bx-user-circle"></i></a>
                  <ul>
                    <li><a href="{{ route("profile") }}">Profile</a></li>
                    <li><a href="{{ route("disconnect") }}">Disconnect</a></li>
                  </ul>
          </li>
        @else
            <li><a class="nav-link scrollto" href="{{ route("signup") }}">Signup</a></li>
            <li style="list-style-type: none;"><a class="getstarted scrollto" href="{{ route("login") }}">Login</a></li>  
            @endif
        </ul>

        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>

    </div>
  </header>

