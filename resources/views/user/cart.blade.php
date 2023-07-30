@extends("layout.base")

@section("title", "Cart details")


@section("content")
    <body>
        <div style="display: flex; padding-top: 15vh; padding-bottom: 15vh;">

            @php($total = 0)

            <div class="showcart" style="margin: auto;">
                <div class="totalprice">
                    TOTAL <span id="totalprice" class="valuetotalprice">0</span><span style="font-size:22px; font-weight: 600;">$</span>
                </div>

                <hr style="color: white;">

                @foreach($_SESSION['cart'] as $p)
                    @php($total += $p['price'])

                    <div class="productcart">
                                
                            <div class="imgcart">
                                <img data-src="../storage/product_img/{{ $p["image"] }}">
                            </div>

                            <div class="price">
                                <strong>{{$p['price']}} $</strong>
                            </div>

                            <div class="link">
                                <a href="/details/{{ $p['pid'] }}">{{ $p["name"] }}</a>
                            </div>

                    </div>
                    
                    <hr style="color: white;">

                @endforeach

                <script>
                    document.getElementById("totalprice").innerHTML = {{(float)$total}}
                </script>

                <button onclick="window.location.href='/todo'" class="buttoncart">
                    BUY 
                </button>
                
            </div>
        </div>
            
        <script src="../assets/js/main.js"></script>
        <script>
            window.addEventListener('load', function() {
                var images = document.getElementsByTagName('img');
                for (var i = 0; i < images.length; i++) {
                    var img = images[i];
                    if (img.getAttribute('data-src')) {
                        img.setAttribute('src', img.getAttribute('data-src'));
                    }
                }
            });
        </script>
        
@endsection