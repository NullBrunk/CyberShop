<?php

use App\Models\Contact;
use App\Models\Notif;
use App\Models\User;


/**
 * Get a mail from a user id
 *
 * @param int $id  the id of the user.
 * 
 * @return array  An array with the result.
 */
function mail_from_id($id){
    $user = new User();
    return $user -> select("mail") -> where("id", "=", $id) -> get() -> toArray();
}


/**
 * Put all the notifications in an Array with all the valuable information
 *
 * @return array  The notifications.
 */
function show(){

    # We order by id DESC to get latest notification in first
    $contact = new Contact();
    $notif = new Notif();


    // We build an array with all the notifications in it
    $to_push = [];
    $notif_number = 0;


    # We get all the contact notifications

    $data = $contact 
        -> where("id_contacted", "=", $_SESSION["id"]) 
        -> where("readed", "=", 0)
        -> orderBy("id", "desc")
        -> get()
        -> toArray();

    foreach($data as $d){

        if($d["id_contactor"] === $_SESSION["id"]){
            $id_to_use = $d["id_contacted"];
        }
        else {
            $id_to_use = $d["id_contactor"];
        }
        
        if(isset($to_push[$id_to_use])){
            $d = explode(" m", $to_push[$id_to_use]["title"]);
            $to_push[$id_to_use]['title'] = (int)$d[0] + 1 . " messages received.";
        }
        else {

            # Get the mail
            $mail = mail_from_id($id_to_use)[0]["mail"];

            $to_push[$id_to_use] = 
            [  
                "icon" => "bx bx-chat",
                "title" => "1 message received.",
                "content" => "From " . $mail . ".",
                "more" => "/contact/" . $mail,
                "type" => "message"
            ];
        }
        $notif_number++;
        
    }
    
 
    # We get all the notifs from the notif table

    foreach($notif -> where("id_user", "=", $_SESSION["id"]) -> where("type", "=", "comment") -> orderBy("id", "desc") -> get() -> toArray() as $d) {
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