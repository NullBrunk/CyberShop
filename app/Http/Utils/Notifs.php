<?php

use App\Models\Notif;


/**
 * Put all the notifications in an Array with all the valuable information
 *
 * @return array  The notifications.
 */

function show() : array
{
    $notif_number = 0;
    $to_push = [];


    # We get all the contact notifications

    foreach(Notif::where("id_user", "=", $_SESSION["id"]) -> where("type", "=", "chatbox") -> orderBy("id", "desc") -> get() -> toArray() as $d){
       
        if(isset($to_push[$d["moreinfo"]])){
            $number = explode(" m", $to_push[$d["moreinfo"]]["title"])[0];
            $d["name"] = (int)$number + 1 . " messages received.";
        }

        $to_push[$d["moreinfo"]] = [              
            "icon" => $d['icon'],
            "title" => $d["name"],
            "content" => $d["content"],
            "more" => $d["link"],
            "type" => "comment",
        ];
        
        $notif_number++;
    }

    
    # We get all the commented product notifs

    foreach(Notif::where("id_user", "=", $_SESSION["id"]) -> where("type", "=", "comment") -> orderBy("id", "desc") -> get() -> toArray() as $d) {
        $to_push["o" . $d["id"]] = 
        [  
            "icon" => $d['icon'],
            "title" => $d["name"],
            "content" => $d["content"],
            "more" => $d["link"],
            "type" => "comment",
        ];

        $notif_number++;
    }


    return ( [ $to_push, $notif_number ] );

}