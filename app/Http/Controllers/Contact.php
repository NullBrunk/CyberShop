<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactReq;
use Illuminate\Http\Request;

class Contact extends Controller
{
    public function show($slug = false){
        
        include_once __DIR__ . '/../../Database/config.php';

        $convs = $pdo -> prepare("
            SELECT * FROM contact
            WHERE 
                mail_contacted = :mail
            OR 
                mail_contactor = :mail
        ");

        $convs -> execute([
            "mail" => $_SESSION["mail"]
        ]);

        $exploitable_data = [];

        foreach($convs -> fetchall(\PDO::FETCH_ASSOC) as $data){
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
            if(!in_array($slug, array_keys($exploitable_data))){
                return abort(403);
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

        $send_msg = $pdo -> prepare("
            INSERT INTO 
                contact(
                    readed, mail_contactor, mail_contacted, content
                ) 
            VALUES  (
                    TRUE, :mail_contactor, :mail_contacted, :content 
                )
        ");

        $send_msg -> execute([
            "mail_contactor" => $_SESSION["mail"],
            "mail_contacted" => $mail,
            "content" => $req["content"]
        ]);

        return redirect(route("contactuser", $mail));


    }
}
