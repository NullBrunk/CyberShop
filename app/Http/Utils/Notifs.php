<?php

use App\Http\Sql;

/**
 * Get a mail from a user id
 *
 * @param int $id  the id of the user.
 * 
 * @return array  An array with the result.
 */
function mail_from_id($id){

    $pdo = new \PDO(
        "mysql:host=localhost;dbname=" . env("DB_DATABASE"), 
        env("DB_USERNAME"), 
        env("DB_PASSWORD")
    );

    $mail = $pdo -> prepare("SELECT mail FROM users WHERE id=:id");
    $mail -> execute([ "id" => $id ]);

    return $mail -> fetch(PDO::FETCH_ASSOC);
}


/**
 * Put all the notifications in an Array with all the valuable information
 *
 * @return array  The notifications.
 */
function show(){

    // We order by id DESC to get latest notification in first
    $data = Sql::query("
        SELECT * FROM contacts
        WHERE 
            id_contacted = :id
        AND 
            readed = 0
        
        ORDER BY id DESC;
    ", [ "id" => $_SESSION["id"] ]);

    
    // We build and array with all the notifications in it
    
    $to_push = [];
    $notif_number = 0;

    foreach(Sql::query("SELECT * FROM notifs WHERE id_user = :id AND type=:type ORDER BY id DESC;", [ "id" => $_SESSION["id"], "type" => "comment" ]) as $d) {
 
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
            $mail = mail_from_id($d["id_contacted"])["mail"];
        }
        else {
            $mail = mail_from_id($d["id_contactor"])["mail"];
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