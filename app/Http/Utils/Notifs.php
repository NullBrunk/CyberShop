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

    $data = $contact 
        -> where("id_contacted", "=", $_SESSION["id"]) 
        -> where("readed", "=", 0)
        -> orderBy("id", "desc")
        -> get()
        -> toArray();


    
    // We build an array with all the notifications in it
    
    $to_push = [];
    $notif_number = 0;

    

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

    foreach($data as $d){

        // Cause we desplay the mail of the contactor on the notification
        if($d["id_contactor"] === $_SESSION["id"]){
            $mail = mail_from_id($d["id_contacted"])[0]["mail"];
        }
        else {
            $mail = mail_from_id($d["id_contactor"])[0]["mail"];
        }
        
        if(isset($to_push[$mail])){
            $d = explode(" m", $to_push[$mail]["title"]);
            $to_push[$mail]['title'] = (int)$d[0] + 1 . " messages received.";
        }
        else {
            $to_push[$mail] = 
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

  
    return ( [ $to_push, $notif_number ] );
}