<div wire:init="show_products">
    
    <div class="products-profile">
        @isset($products)
        <div>
            <div class="row portfolio-container" style="padding: 15px;">
                <h5>Products in sale</h5>
                @foreach($products as $product)
                    <div class="col-md-2 portfolio-item {{ $product['class'] }} " style="@if($limit === false) margin-bottom: 40px; @endif margin-top: 10px;">
                        <div class="portfolio-wrap" style="flex-direction: column; height: 29vh !important;">
                            <a style="text-align: center;" href="/details/{{ $product['id'] }}">
                                <img src="/storage/product_img/{{ $product -> product_images() -> where("is_main", true) -> first() -> img }}" class="imgpres" style="user-select: none !important; height: 19vh !important;">
                            </a>
                        </div>
            
                        <div class="products">
            
                            <div class="categ">
                                {{ ucfirst($product["class"]) }}
                            </div>
            
                            <div class="title">
                                <a href="{{route("details", $product['id'])}}">{{ $product["name"] }}</a>
                            </div>
                        
                            <div class="pricepr">                 
                                {{ $product -> format_price() }} <span>$</span>
                                           
                            </div>
            
                        </div>
                    </div>
                    @endforeach

                    <div style="text-align: center; margin-top: 10px;">
                        @if($product_number > 6)
                            @if($limit === true)
                                <button wire:click="toggle_limit" class="more"><i class="bi bi-arrow-down-short"></i></button>
                            @else
                                <button wire:click="toggle_limit" class="more"><i class="bi bi-arrow-up-short"></i></button>
                            @endif
                        @endif
                    </div>

                </div>
            </div>
                
        @else
            Loading ...
        @endisset
    </div>            

    <div class="mt-4">

    </div>
</div>
