@extends("layout.base")

@section("title", ucfirst($name))

@section("content")

    <script src="/assets/js/htmx.js"></script>
    <link href="/assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">
    <link href="/assets/css/searchbar.css" rel="stylesheet">
    

    <body>

        <script>
            async function showrating(url, id) {
                
                let resp = await fetch(url);
                const data = await resp.json();

                if(data.length !== 0){
                    document.getElementById("stars-" + id).innerHTML = data.icons;
                }
                else {
                    return false;
                }

            }
        </script>

        <div style="padding-top:5%;"class=" d-flex align-items-center">
            <form  method="get" action="{{ route("product.search", $name) }}">
                <input name="q" type="text" placeholder="Search something ..." value="{{ isset($search) ? $search : ""}}" id="input" autofocus>
                
                <button style="width: 3%;border-radius: 4px;border: 0;">
                    <i class="bx bx-search-alt"></i>
                </button>

            </form>
        </div>
    
        @if(isset($search))
            <div class="results_for">
                {{ $number }} @if($number === 0) result @else results @endif for <span>"{{ $search }}"</span> 
            </div>
        @endif

        <div id="portfolio" class="portfolio">
            <div class="container">
                <div class="row" id="container">
                </div>
            </div>
        </div>
    

        <section id="baseproduct" class="portfolio" style="padding: 50px 0px;">

            <div class="container" data-aos="fade-up">
                <div  class="row portfolio-container">

                    @foreach($products as $d)

                        <div class="col-md-3 portfolio-item {{ $d['class'] }}">
                            <div class="portfolio-wrap" style="flex-direction: column;">
                                <a href="/details/{{ $d['id'] }}">
                                    <img 

                                    data-src="/storage/product_img/{{ $d['img'] }}" 
                                    class="img-fluid imgpres" alt="">
                                </a> 
                            </div>

                            <div class="products">
                                <div class="categ">
                                    {{ ucfirst($d["class"]) }}
                                </div>

                                <div class="title">
                                    <a href="{{route("details", $d['id'])}}">{{ $d["name"] }}</a>
                                </div>
                               
                                <div class="pricepr">
                                    
                                    {{$d['price']}} <span>$</span>

                                    @if(isset($notpaginated))
                                        
                                        <p class="pr_stars" id="stars-{{$d['id']}}">{!! $rating[$d["id"]] !!}</p>
                                    
                                    @else

                                        <p class="pr_stars" id="stars-{{$d['id']}}"></p>

                                        <script>
                                            showrating(location.protocol + "//" + window.location.hostname + ":8000/api/rating/{{ $d['id'] }}", {{$d['id']}});
                                        </script>   

                                    @endif   
                                      
                                </div>

                            </div>
                        </div>

                    @endforeach

                </div>

                @if($products -> nextPageUrl() !== null && isset($search))

                    <button class="buttonpag" hx-get="{{ $products -> nextPageUrl() . "&q=" . $search }}" hx-swap="outerHTML" hx-trigger="revealed">
                        <span class="paginationbutton">
                            <span class="spinner-border spinner-border-sm htmx-indicator" role="status" aria-hidden="true"></span>
                        </span>
                    </button>

                @elseif($products -> nextPageUrl() !== null)

                    <button class="buttonpag" hx-get="{{ $products -> nextPageUrl() }}" hx-swap="outerHTML" hx-trigger="revealed">
                        <span class="paginationbutton">
                            <span class="spinner-border spinner-border-sm htmx-indicator" role="status" aria-hidden="true"></span>
                        </span>
                    </button>

                @endif

            </div>
        </section>

@endsection


{{-- Load products image after body fully loaded --}}
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

