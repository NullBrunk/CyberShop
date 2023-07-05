<?php
    
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

function show(){
    $pdo = new PDO(
        "mysql:host=localhost;dbname=" . env("DB_DATABASE"), 
        env("DB_USERNAME"), 
        env("DB_PASSWORD")
    );

    $notifs = $pdo -> prepare("
        SELECT * FROM contact
        WHERE 
            id_contacted = :id
        AND 
            readed = 0
        
        ORDER BY id DESC;
    ");
    $notifs -> execute([ "id" => $_SESSION["id"] ]);

    $to_push = [];
    $data = $notifs -> fetchall(\PDO::FETCH_ASSOC);

    foreach($data as $d){

        if($d["id_contactor"] === $_SESSION["id"]){
            $mail = mail_from_id($d["id_contacted"])["mail"];
        }
        else {
            $mail = mail_from_id($d["id_contactor"])["mail"];
        }
                    
        $to_push[$d["id"]] = 
        [  
            "icon" => "bi bi-chat-left-dots",
            "title" => "Message received",
            "content" => "From " . $mail,
            "more" => "/contact/" . $mail,
        ];
    }

    $_SESSION["notifications"] = $to_push;
    return $to_push;
}