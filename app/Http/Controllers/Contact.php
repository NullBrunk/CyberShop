<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactReq;
use App\Http\Sql;


/**
 * Get all the contact messages of the current user
 *
 * @param int $mail  The mail of the current user
 * 
 * @return array    An array with all the messages sended to/by the
 *                  the current user
 */

function getmsgs($mail){
    
    $convs = Sql::query("
            SELECT * FROM
                (
                    SELECT contact.id,readed,content,id_contacted,users.mail as mail_contacted 
                    FROM contact 
                    INNER JOIN 
                        users 
                    ON 
                    contact.id_contacted = users.id
                ) as contacted
            INNER JOIN
                (
                    SELECT contact.id,readed,content,id_contactor,users.mail as mail_contactor,time 
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

            ORDER BY contacted.id 
        ", [
            "mail" => $mail
        ]);

        return $convs;
}


class Contact extends Controller {

    /**
     * Convert an id to a mail address
     *
     * @param string $mail  The mail of the user
     * 
     * @return array    An array with the id of the user or nothing
     *                  if the user does not exists.
     */

    public function id2mail($mail){

        $id = Sql::query(
            "SELECT id FROM users WHERE mail=:mail",
            [ "mail" => $mail ]
        );

        return $id;
    }



    /**
     * Mark a contact message as readed
     *
     * @param string $id  The id of the message to mark
     * 
     * @return void   
     * 
     */

    public function mark_readed($id){

        Sql::query("
            UPDATE contact 
            SET 
                readed = 1 
            WHERE 
                id_contacted = :me 
            AND
                id_contactor = :he
            AND 
                readed = 0
        ", [
            "me" => $_SESSION["id"],
            "he" => $id,
        ]);

    }



    /**
     * Get all the messages of the user and show them
     *
     * @param string $slug  A mail to show, or nothing
     * 
     * @return view     Une vue avec tous les messages échangé   
     * 
     */

    public function show($slug = false){
        
        # The array that wi'll be passed to the vue
        $exploitable_data = [];

        foreach(getmsgs($_SESSION["mail"]) as $data){

            # Create time at hand 
            $time = explode("-", explode(" ", $data["time"])[0])[2] . " " . strtolower(date('F', mktime(0, 0, 0, explode("-", $data["time"])[1], 10))) . ", " . implode(":", array_slice(explode(":", explode(" ", $data["time"])[1]), 0, 2));

            # Then the contactor is the current user
            if($data["mail_contacted"] === $_SESSION["mail"]){

                                
                $mail = $data["mail_contactor"];
                $toput = [ 
                    $data['content'], 
                    "me" => false, 
                    "id" => $data["id"],
                    "time" => $time
                ];
            } 

            # Then the contactor is the other user
            else {
                $mail = $data["mail_contacted"];
                $toput = [ 
                    $data['content'], 
                    "me" => true, 
                    "id" => $data["id"],
                    "time" => $time
                ];
            }


            if(isset($exploitable_data[$mail])){
                array_push($exploitable_data[$mail], $toput);
            }
            else {
                $exploitable_data[$mail] = [ $toput ];
            }
        }

        
        # If the user is requesting for the messages of another user
        if($slug){

            # If the user is contacting himself
            if($slug === $_SESSION["mail"]){
                $_SESSION["contact_yourself"] = true;
                return redirect(route("contact"));  
            }

            # We get the mail of the reqiested user
            $id = Contact::id2mail($slug);

            # If the requested user doses not exist
            if(empty($id))
                return abort(404);
            else 
                $id = $id[0]["id"];

            # Mark the messages of the conversations as readed
            Contact::mark_readed($id);

            return view("user.contact", [ "noone" => false, "user" => $slug, "data" => $exploitable_data ]);

        }   

        else {
            return view("user.contact", [ "noone" => true, "data" => $exploitable_data ]);
        }
    }



    /**
     * Store a message sended to a specific user
     *
     * @param ContactReq $req  The request with all the informations
     * 
     * @return redirect     Redirect to the contact page with the right slug   
     * 
     */

    public function store(ContactReq $req){

        # Get the slug
        $url = explode("/contact/", url() -> previous());
        
        # If there is no slug, the user is trying to send a message to
        # no one

        if(!isset($url[1])){
            return abort(403);
        }
        else {
            $mail = $url[1];
        }
        
        # Test if the user exists
        $id = Contact::id2mail($mail);
        if(empty($id)){
            $_SESSION["contact_no_one"] = true;
            return redirect("/contact");
        }

        # Add the message
        Sql::query("
            INSERT INTO 
                contact(
                    readed, id_contactor, id_contacted, content, time
                ) 
            VALUES (
                    FALSE, :id_contactor, :id_contacted, :content, :time
                )
        ", [
            "id_contactor" => $_SESSION["id"],
            "id_contacted" => $id[0]['id'],
            "content" => $req["content"],
            "time" => date('Y-m-d H:i:s')
        ]);

        return redirect(route("contactuser", $mail));
    }



    /**
     * Delete a message from a conversation if the user is allowed to
     *
     * @param int $slug  The id of the message to remove
     * 
     * @return redirect     Redirect to the previous url   
     * 
     */

    public function delete($slug){

        Sql::query("
            DELETE FROM contact 
            WHERE 
                id=:slug 
            AND 
                id_contactor=:user
        ", [
            "slug" => $slug,
            "user" => $_SESSION["id"]
        ]);

        return redirect(url() -> previous());
    }
}
