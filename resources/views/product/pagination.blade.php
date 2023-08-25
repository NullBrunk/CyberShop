<div class="row portfolio-container">
    @foreach($products as $d)

        <div class="col-md-3 portfolio-item {{ $d['class'] }} ">
            <div class="portfolio-wrap" style="flex-direction: column;">
                <a style="text-align: center;" href="/details/{{ $d['id'] }}">
                    <img src="/storage/product_img/{{ $d['img'] }}" class="imgpres" style="user-select: none !important;">
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
                    {{ $d -> format_price() }} <span>$</span>

                    <p id="stars-{{$d['id']}}" class="pr_stars"></p>

                    <script>
                        showrating(location.protocol + "//" + window.location.hostname + ":8000/api/rating/{{ $d['id'] }}", {{$d['id']}});
                    </script>                                      
                </div>

            </div>
        </div>

    @endforeach
</div>

@php($query_string = "")

@isset($search)
    @php($query_string .= "&q=" . $search)
@endisset

@isset($max_price) 
    @php($query_string .= "&mp=" . $max_price)
@endisset


@if($products -> nextPageUrl() !== null)

<button class="buttonpag" hx-get="{{ $products -> nextPageUrl() . $query_string }}" hx-swap="outerHTML" hx-trigger="revealed">
        <span class="paginationbutton">
            <span class="spinner-border spinner-border-sm htmx-indicator" role="status" aria-hidden="true"></span>
        </span>
    </button>

@endif
