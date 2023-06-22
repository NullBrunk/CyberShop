<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactReq;


function getmsgs($mail){
    
    include_once __DIR__ . '/../../Database/config.php';

    $convs = $pdo -> prepare("
            SELECT * FROM
                (
                    SELECT contact.id ,readed,content,id_contacted,users.mail as mail_contacted 
                    FROM contact 
                    INNER JOIN 
                        users 
                    ON 
                    contact.id_contacted = users.id
                ) as contacted
            INNER JOIN
                (
                    SELECT contact.id ,readed,content,id_contactor,users.mail as mail_contactor 
                    FROM contact 
                    INNER JOIN 
                        users 
                    ON 
                        contact.id_contactor = users.id
                ) as contactor
            ON contacted.id = contactor.id 
            WHERE 
                contacted.mail_contacted = :mail 
            OR 
                contactor.mail_contactor = :mail
        ");


        $convs -> execute([
            "mail" => $mail
        ]);

        return $convs -> fetchall(\PDO::FETCH_ASSOC);
}


class Contact extends Controller
{
    public function show($slug = false){
        

        $exploitable_data = [];

        foreach(getmsgs($_SESSION["mail"]) as $data){
            if($data["mail_contacted"] === $_SESSION["mail"]){

                // Mail of the other person
                $mail = $data["mail_contactor"];
                $toput = [ $data['content'], "me" => false ];
            }
            else {
                
                // Mail of the other person
                $mail = $data["mail_contacted"];
                $toput = [ $data['content'], "me" => true ];

            }

            if(isset($exploitable_data[$mail])){
                array_push($exploitable_data[$mail], $toput);
            }
            else {
                $exploitable_data[$mail] = [ $toput ];

            }
        }


        
        if($slug){

            if($slug === $_SESSION["mail"]){
                $_SESSION["contact_yourself"] = true;
                return redirect(route("contact"));  
            }


            return view("user.contact", [ "noone" => false, "user" => $slug, "data" => $exploitable_data ]);
        }   
        else {
            return view("user.contact", [ "noone" => true, "data" => $exploitable_data ]);
        }
    }

    public function send(ContactReq $req){

        include_once __DIR__ . '/../../Database/config.php';

        $url = explode("/contact/", url() -> previous());
        
        if(!isset($url[1])){
            return abort(403);
        }
        $mail = $url[1];
        
        $id = $pdo -> prepare("SELECT id FROM users WHERE mail=:mail");
        $id -> execute(["mail" => $mail]);
        $id = $id -> fetch();
        
        if(empty($id)){
            $_SESSION["contact_no_one"] = true;
            return redirect("/contact");
        }

        $send_msg = $pdo -> prepare("
        INSERT INTO 
            contact(
                readed, id_contactor, id_contacted, content
            ) 
        VALUES  (
                FALSE, :id_contactor, :id_contacted, :content 
            )
        ");
        $send_msg -> execute([
            "id_contactor" => $_SESSION["id"],
            "id_contacted" => $id['id'],
            "content" => $req["content"]
        ]);

        return redirect(route("contactuser", $mail));
        
    }

}
