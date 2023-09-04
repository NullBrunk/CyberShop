<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tmp_orders;
use Stripe\StripeClient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

if(!isset($_SESSION)) session_start();

class Payment extends Controller
{
    public function create_checkout() {

        $stripe = new StripeClient(env("STRIPE_KEY"));

        $reference = Str::uuid();
        $_SESSION["reference"] = $reference;

        foreach(Cart::where("id_user", $_SESSION["id"]) -> with("product") -> get() as $p) {

            $formated_products[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $p -> product -> name,
                    ],
                    'unit_amount' => $p -> product -> price * 100,
                ],
                'quantity' => $p -> quantity,
            ];
        
            Tmp_orders::create([
                "id_buyer" => $_SESSION["id"], 
                "id_seller" => $p -> product -> id_user, 
                "id_product" => $p -> product -> id,  
                "quantity" => $p -> quantity,
                "price" => $p -> product -> price,
                "reference" => $reference,
            ]);
        }

        $checkout = $stripe -> checkout -> sessions -> create([
            "customer_email" => $_SESSION["mail"],
            "line_items" => $formated_products,
            "mode" => "payment",
            
            
            "success_url" => route("payment.success"),
            "cancel_url" => route("payment.cancel"),
        ]);

        return redirect($checkout -> url);
    }

    public function success() {
        if(!isset($_SESSION["reference"]) or empty($_SESSION["reference"])) {
            return abort(403);
        }

        $reference = $_SESSION["reference"];
        $tmp_orders = Tmp_orders::where("reference", $reference) -> get();

        foreach($tmp_orders as $tmp) {
            Order::create([
                "id_buyer" => $tmp -> id_buyer, 
                "id_seller" => $tmp -> id_seller, 
                "id_product" => $tmp -> id_product,  
                "quantity" => $tmp -> quantity,
                "price" => $tmp -> price,
                "reference" => $reference,
            ]);

            $tmp -> delete(); 
        }

        unset($_SESSION["reference"]);
        
        return view("payment.success");
    }

    public function cancel() {
        
        if(!isset($_SESSION["reference"]) or empty($_SESSION["reference"])) {
            return abort(403);
        }
        
        Tmp_orders::where("reference", $_SESSION["reference"]) -> delete();
        unset($_SESSION["reference"]);

        return view("payment.cancel");
    }
}
