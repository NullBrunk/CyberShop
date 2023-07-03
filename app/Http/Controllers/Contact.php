<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactReq;
use Illuminate\Support\Facades\Http;

function getmsgs($mail){
    
    $pdo = config("app.pdo");

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

            ORDER BY contacted.id DESC
        ");


        $convs -> execute([
            "mail" => $mail
        ]);

        return $convs -> fetchall(\PDO::FETCH_ASSOC);
}


class Contact extends Controller {


    public function id2mail($mail){

        $pdo = config("app.pdo");

        $id = $pdo -> prepare("SELECT id FROM users WHERE mail=:mail");
        $id -> execute([ "mail" => $mail ]);

        return $id -> fetch(\PDO::FETCH_ASSOC);
    }

    public function mark_readed($id){

        $pdo = config("app.pdo");


        $mark = $pdo -> prepare("
            UPDATE contact 
            SET 
                readed = 1 
            WHERE 
                id_contacted = :me 
            AND
                id_contactor = :he
            AND 
                readed = 0
        ");

        $mark -> execute([
            "me" => $_SESSION["id"],
            "he" => $id,
        ]);
    }


    public function show($slug = false){
        
        $exploitable_data = [];

        foreach(getmsgs($_SESSION["mail"]) as $data){

            # Then the contactor is the current user
            if($data["mail_contacted"] === $_SESSION["mail"]){

                $mail = $data["mail_contactor"];
                $toput = [ $data['content'], "me" => false, "id" => $data["id"]];
            } 

            # Then the contactor is the other user
            else {

                $mail = $data["mail_contacted"];
                $toput = [ $data['content'], "me" => true, "id" => $data["id"] ];
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

            $id = Contact::id2mail($slug);

            if(!$id)
                return abort(404);
            else 
                $id = $id["id"];

            Contact::mark_readed($id);

            return view("user.contact", [ "noone" => false, "user" => $slug, "data" => $exploitable_data ]);

        }   

        else {
            return view("user.contact", [ "noone" => true, "data" => $exploitable_data ]);
        }
    }


    public function send(ContactReq $req){

        $pdo = config("app.pdo");

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
            VALUES (
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


    public function delete($slug){

        $pdo = config("app.pdo");

        $del = $pdo -> prepare("
            DELETE FROM contact 
            WHERE 
                id=:slug 
            AND 
                id_contactor=:user
        ");
        $del -> execute([
            "slug" => $slug,
            "user" => $_SESSION["id"]
        ]);

        return redirect(url() -> previous());
    }
}
