<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactReq;
use App\Http\Sql;

use App\Models\Contact;
use App\Models\User;

/**
 * Get all the contact messages of the current user
 *
 * @param int $mail     The mail of the current user
 * 
 * @return array        An array with all the messages sended to/by the
 *                      the current user
 */

function getmsgs($mail){
    
    $convs = Sql::query("
            SELECT * FROM
                (
                    SELECT contacts.id,readed,content,id_contacted,users.mail as mail_contacted 
                    FROM contacts 
                    INNER JOIN 
                        users 
                    ON 
                    contacts.id_contacted = users.id
                ) as contacted
            INNER JOIN
                (
                    SELECT contacts.id,readed,content,id_contactor,users.mail as mail_contactor,time 
                    FROM contacts 
                    INNER JOIN 
                        users 
                    ON 
                        contacts.id_contactor = users.id
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

class Contacts extends Controller {

    /**
     * Mark a contact message as readed
     *
     * @param Contact $contact      The contact model
     * @param string $id            The id of the message to mark
     * 
     * @return void   
     * 
     */

    public function mark_readed(Contact $comment, int $id){

        $comment 
        -> where("id_contacted", "=", $_SESSION["id"])
        -> where("id_contactor", "=", $id)
        -> where("readed", "=", 0)

        -> update([ 
          "readed" => 1,
        ]);
    }


    /**
     * Get all the messages of the user and show them
     *
     * @param Contact $cont    Contact model
     * @param User $user       User model
     * @param string $slug     A mail to show, or nothing
     * 
     * @return view            Une vue avec tous les messages échangés
     * 
     */

    public function show(Contact $cont, User $user, $slug = false){
        
        # The array that wi'll be passed to the vue
        $exploitable_data = [];
        $contact = [];


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
                    "time" => $time,
                    "readed" => $data["readed"]

                ];
            } 

            # Then the contactor is the other user
            else {
                $mail = $data["mail_contacted"];
                $toput = [ 
                    $data['content'], 
                    "me" => true, 
                    "id" => $data["id"],
                    "time" => $time,
                    "readed" => $data["readed"]
                ];
            }


            if(isset($exploitable_data[$mail])){
                // Push the message
                array_push($exploitable_data[$mail], $toput);
            
                // Update the time of the last sended message 
                $exploitable_data[$mail]["time"] = $data["time"];
            }
            else {

                // Push the time of the message as well as the message himself
                $exploitable_data[$mail] = [ "time" => $data["time"], $toput ];
            }
        }

        
        # Get the full array of authors
        foreach(array_keys($exploitable_data) as $name)
            array_push( $contact, [ $exploitable_data[$name]["time"], $name ] ); 
    
        # Sort it
        usort($contact, function ($date1, $date2) {
            return strtotime($date1[0]) - strtotime($date2[0]);
        });
        

        # If the user is requesting for the messages of another user
        if($slug){

            # If the user is contacting himself
            if($slug === $_SESSION["mail"]){
                $_SESSION["contact_yourself"] = true;
                return to_route("contact");  
            }

            
            # Get the mail of the requested user
            $requested_user = $user -> where("mail", "=", $slug) -> get() -> toArray();

            
            # If the requested user doses not exist
            if(empty($requested_user))
                return abort(404);


            # Mark the messages of the conversations as readed
            self::mark_readed($cont,  $requested_user[0]["id"]);


            return view("user.contact", [ "contact" => $contact, "noone" => false, "user" => $slug, "data" => $exploitable_data ]);
        }   

        else {
            return view("user.contact", [ "contact" => $contact, "noone" => true, "data" => $exploitable_data ]);
        }
    }



    /**
     * Store a message sended to a specific user
     *
     * @param ContactReq $req     The request with all the informations
     * @param Contact $contact    The contact model
     * @param User $user          The user model
     * 
     * @return redirect           Redirect to the contact page with the right slug   
     * 
     */

    public function store(ContactReq $req, Contact $contact, User $user){

        # Get the slug
        $url = explode("/contact/", url() -> previous());
        

        # If there is no slug, the user is 
        # trying to send a message to non existent user

        if(!isset($url[1])){
            return abort(403);
        }
        else {
            $mail = $url[1];
        }
        

        # Test if the user exists
        $id = $user -> where("mail", "=", $mail) -> get() -> toArray();

        
        if(empty($id)){
            $_SESSION["contact_no_one"] = true;
            return redirect("/contact");
        }
        else {
            $id = $id[0]["id"];
        }


        # Add the message

        $contact -> id_contactor = $_SESSION["id"];
        $contact -> id_contacted = $id;

        $contact -> content = $req["content"];
        
        $contact -> time = date('Y-m-d H:i:s');
        $contact -> readed = false;
        
        $contact -> save();

    
        return to_route("contactuser", $mail);
    }



    /**
     * Delete a message from a conversation if the user is allowed to
     *
     * @param Contact $contact     The message to remove threw model binding
     * 
     * @return redirect         Redirect to the previous url   
     * 
     */

    public function delete(Contact $contact){
        if($contact -> toArray()["id_contactor"] === $_SESSION["id"]){
            $contact -> delete();
        }
        else {
            return abort(403);
        }

        return redirect(url() -> previous());
    }
}
