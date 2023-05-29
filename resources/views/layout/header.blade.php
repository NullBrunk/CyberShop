@php(session_start())
<header id="header" class="fixed-top " style="background-color: #293E61 !important;">
    <div class="container d-flex align-items-center">

      <h1 class="logo me-auto"><a href="/">CyberShop</a></h1>

      <nav id="navbar" class="navbar">
      
      
        <ul>
            <li><a class="nav-link scrollto" href="{{ route("about") }}">About</a></li>
            <li><a class="nav-link scrollto" href="{{ route("contact") }}">Contact</a></li>
        
        @if(isset($_SESSION['logged']))
          <li style="list-style-type: none;" class="dropdown"><a href="#"><i style="font-size: 32px !important;" class="bx bx-user-circle"></i></a>
                  <ul>
                    <li><a href="#">Profile</a></li>
                    <li><a href="#">Settings</a></li>
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