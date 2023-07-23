
<div class="row portfolio-container">
    @foreach($products as $d)


        <div class="col-md-3 portfolio-item {{ $d['class'] }}">
            <div class="portfolio-wrap" style="border-radius: 5px; flex-direction: column;">
                <a href="/details/{{ $d['id'] }}">
                    <img 

                    src="/storage/product_img/{{ $d['image'] }}" 
                    class="img-fluid imgpres" alt="">
                </a>
                <div class="portfoliodetails">
                    <strong>{{$d['price']}} $</strong>
                </div>
            </div>
        </div>

    @endforeach
</div>

@if($products -> nextPageUrl() !== null)
    <button class="paginationbutton" hx-get="{{ $products -> nextPageUrl() }}" hx-swap="outerHTML" hx-disable-element="self">
        <span class="spinner-border spinner-border-sm htmx-indicator" role="status" aria-hidden="true"></span>
        More products
    </button>
@endif
