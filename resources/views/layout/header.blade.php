@if(!isset($_SESSION))
  @php(session_start());
@endif

<header id="header" class="fixed-top " style="background-color: #293E61 !important;">

    <div class="container d-flex align-items-center">
      <h1 class="logo me-auto"><a href="/">CyberShop</a></h1>
        <nav id="navbar" class="navbar">
        
          <ul>
            <li><a class="nav-link scrollto" href="{{ route("articles") }}">Articles</a></li>            
            <li><a class="nav-link scrollto" href="{{ route("about") }}">About</a></li>
            <li><a class="nav-link scrollto" href="{{ route("contact") }}">Contact</a></li>
          
            
            @if(isset($_SESSION['logged']))
              <li ><a class="nav-link scrollto" href="{{ route("sell") }}">Sell</a></li>            
              
              <script>
                async function deleteitem(id) {
                  url = "/cart/delete/"+id
                  let resp = await fetch(url);
                  
                  let elem = document.getElementById(id)
                  elem.remove()
                };
                
              </script>

              @if(!empty($_SESSION['cart']))

                <p style="margin-right: 30px;"></p>
                  
                  @php($total = 0)

                  <li style="list-style-type: none;" class="dropdown"><a style="margin: 0px; padding: 0px;" href="#"><i style="font-size: 26px !important;" class="bi bi-cart"></i></a>
                    <ul>
                    
                      @foreach($_SESSION['cart'] as $p)
                        
                        @php($total += $p['price']) 
                        <li id="{{$p['cid']}}">
                          <p class="show_cart">
                          <img style="padding-left: 3%; width: 22%;" 
                          src="../storage/product_img/{{ $p["image"] }}">
                          
                          
                          <a href="/details/{{ $p['pid'] }}">{{ substr($p["name"], 0, 12) }}</a>
                          
                            <i onclick="deleteitem({{$p['cid']}})" class="bi bi-trash2-fill trash-cart"></i>
                          

                          <p>
                        </li>

                      @endforeach

                      <li><a id="price" class="button" href="/todo">Buy</a></li>
                    </ul>
                  </li>
              @else 
                <p style="margin-right: 10px;"></p>
              @endif


              <a href="{{ route("profile") }}" style="padding-left: 1vw;" ><i style="font-size: 26px !important;" class="bi bi-person-circle"></i></a>

            @else
              <li><a class="nav-link scrollto" href="{{ route("signup") }}">Signup</a></li>
              <li style="list-style-type: none;"><a class="getstarted scrollto" href="{{ route("login") }}">Login</a></li>  
            @endif
          </ul>

          <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
    </div>
</header>

