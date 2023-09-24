<?php

namespace App\Http\Controllers;

use App\Models\User;

class Notifications extends Controller
{
    public static function get_array_notifications(){
        
   
        $notif_number = 0;
        $to_push = [];
        
        $user = User::find($_SESSION["id"]);
        
        foreach($user -> notifications -> toArray() as $notif){

            if("App\Notifications\ReceivedMessageNotification" === $notif["type"]){

                if( isset($to_push[$notif["data"][0]["link"] ])){
                    $number = explode(" m", $to_push[$notif["data"][0]["link"]]["title"])[0];
                    $to_push[$notif["data"][0]["link"]]["title"] = (int)$number + 1 . " messages received.";
                } else {
                    $to_push[$notif["data"][0]["link"]] = [              
                        "icon" => "bx bx-chat",
                        "title" => "1 message received.",
                        "content" => $notif["data"][0]["content"],
                        "more" => $notif["data"][0]["link"],
                    ];
                }
                
                $notif_number++;

            }
            else if("App\Notifications\CommentedProductNotification" === $notif["type"]) {            
                array_push($to_push, [  
                    "icon" => "bx bx-detail",
                    "title" => "Commented product.",
                    "content" => $notif["data"][0]["content"],
                    "more" => $notif["data"][0]["link"],
                ]);
        
                $notif_number++;
            }

        }

        return [
            $to_push, $notif_number
        ];

    }
}
