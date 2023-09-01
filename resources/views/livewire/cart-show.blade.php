<div>
    @php
        $total_products = 0;
        $total_price = 0;
        $all_products = [];
    @endphp


    <section>

        <div class="cartshow h100">
                
            <div class="h100 h10025 d-flex flex-column w75 p-36">

                <div class="d-flex justify-content-between title-cart">
                    <h1 class="">Shopping cart</h1>
                    <p id="total-products" class="mt-3"></p>
                </div>

                <div class="titles none-sm">
                    <span class="text-center" style="width: 15%">IMAGE</span>   
                    <span class="text-center" style="width: 25%">PRODUCT</span>
                    <span class="text-center" style="width: 22%">
                        UNIT PRICE
                    </span>
                    <span class="text-end" style="width: 20%">
                        TOTAL PRICE
                    </span>
                    <span class="text-end" style="width: 16%">ACTIONS</span>

                </div>
                @foreach($_SESSION['cart'] as $c)
                
                    @php($total_products += $c["quantity"])

                    <hr class="my-4">

                    @php($current_total = $c -> product -> price * $c -> quantity)
                    @php($total_price += $current_total)
            
                    <div id="cart-elem-{{ $c -> id_product }}" class="cart-product d-flex">

                            <div class="image hidden">
                                <img src="/storage/product_img/{{ $c -> product -> product_images() -> where("is_main", "=", 1) -> first() -> img }}">        
                            </div>

                            <div class="w-80 d-flex informations">

                                <div class="d-flex name">
                                    <a class="link-product" href="/details/{{ $c -> id_product }}">{{ $c -> product -> name }}</a>
                                    <p>
                                        <i class="bi bi-x"></i><span>{{ $c -> quantity }}</span>
                                    </p>
                                    
                                </div>

                                <p class="pricess">
                                    <?php
                                        array_push($all_products, [
                                            "name" => $c -> product -> name,
                                            "id_product" => $c -> id_product,
                                            "quantity" => $c -> quantity,
                                            "price" => $current_total
                                        ]);
                                    ?>

                                    {{ $c -> product -> price  }}€
                                </p>
                                
                                <p class="pricess none-sm">
                                    {{ $current_total}} €
                                </p>
                            </div>
                            
                            <div style="width: 10%;" class="d-flex mt-4 pricess justify-content-center">
                                <button style="border-top-left-radius: 7px;border-bottom-left-radius: 7px;" class="delete button-pink" wire:click="rm({{ $c -> id_product }})">
                                    <i class="bx bx-minus"></i>
                                </button>
                                <button style="border-top-right-radius: 7px;border-bottom-right-radius: 7px;" class="delete button-blue" wire:click="plus({{ $c -> id_product }})">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                            
                    </div>
                
                @endforeach
            </div>      

            <div class="w25 summary p-36">
                <h1 class="none-sm">Summary</h1>
                <hr class="none-sm">

                <div class="none-sm">

                    <div class="d-flex justify-content-end mt-4">

                        <h3 id="total-price">
                            {{$total_price}} €
                        </h3>
                    </div>
                    
                    <div class="mt-4"  style="height: 39vh;overflow: auto;">
                        @foreach($all_products as $product)
                        <div class="mt-4" id="price-block-{{ $product["id_product"] }}">
                            <div>
                                {{ $product["name"] }} <i class="bi bi-x"></i> {{ $product["quantity"] }}
                            </div>
                            
                            <strong>
                                {{ $product["price"] }} €
                            </strong>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="div-button d-flex justify-content-center">

                    <div id="submit" data-tooltip="Total : {{$total_price}}€" class="button" style="height: 65px;">
                        <div class="button-wrapper">
                          <div class="text">PAY NOW</div>
                            <span class="cart-icon">
                                <svg class="svgIcon" viewBox="0 0 576 512"><path fill="white" d="M512 80c8.8 0 16 7.2 16 16v32H48V96c0-8.8 7.2-16 16-16H512zm16 144V416c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V224H528zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm56 304c-13.3 0-24 10.7-24 24s10.7 24 24 24h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H120zm128 0c-13.3 0-24 10.7-24 24s10.7 24 24 24H360c13.3 0 24-10.7 24-24s-10.7-24-24-24H248z"></path></svg>
                            </span>
                        </div>
                    </div>


                </div>
            </div>

        </div>
        
    </section>

    <script>
        document.getElementById("total-products").innerHTML = "{{ $total_products }}" + " products";
    </script>
</div>
