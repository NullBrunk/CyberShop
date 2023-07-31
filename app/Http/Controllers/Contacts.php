<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests\ContactReq;
use Illuminate\Http\Request;

use App\Models\Contact;
use App\Models\User;


/**
 * Get all the contact messages of the current user using PDO
 *
 * @param int $mail     The mail of the current user
 * 
 * @return array        An array with all the messages sended to/by the
 *                      the current user
 */

function getmsgs($mail){

    $pdo = new \PDO("mysql:host=localhost;dbname=" . env("DB_DATABASE"), env("DB_USERNAME"), env("DB_PASSWORD"));

    $sql = $pdo -> prepare("
        SELECT * FROM
            (
                SELECT contacts.id,readed,type,content,id_contacted,users.mail as mail_contacted 
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
    ");
    $sql -> execute([
        "mail" => $mail
    ]);

    return $sql -> fetchall(\PDO::FETCH_ASSOC);
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
        
        include_once __DIR__ . "/../Utils/Style.php";

        # The array that will be passed to the view

        $exploitable_data = [];
        $contact = [];


        foreach(getmsgs($_SESSION["mail"]) as $data){

            # Then the contactor is the other user
            if($data["mail_contacted"] === $_SESSION["mail"]){       
                $mail = $data["mail_contactor"];
                
                if($data["readed"] == 0){
                    $exploitable_data[$mail]["unread"] = true;
                } 

                $toput = [ 
                    style($data['content']), 
                    "type" => $data["type"],
                    "me" => false, 
                    "id" => $data["id"],
                    "time" => $data["time"],
                    "readed" => $data["readed"]

                ];
            } 

            # Then the contactor is us
            else {
                $mail = $data["mail_contacted"];
                $toput = [ 
                    style($data['content']),
                    "type" => $data["type"], 
                    "me" => true, 
                    "id" => $data["id"],
                    "time" => $data["time"],
                    "readed" => $data["readed"]
                ];
            }


            if(isset($exploitable_data[$mail])){
                // Push the message
                array_push($exploitable_data[$mail], $toput);
            
                // Update the time of the latest message 
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
        

        # If the user is requesting for a specific conversation with another user
        if($slug){

            # If the user is contacting himself
            if($slug === $_SESSION["mail"]){
                return to_route("contact.show") -> withErrors(["contact_yourself" => "You cant contact yourself"]);  
            }

            
            # Get the mail of the requested user
            $requested_user = $user -> where("mail", "=", $slug) -> get() -> toArray();

            
            # If the requested user does not exist
            if(empty($requested_user))
                return abort(404);


            # Mark all wthe messages of the conversation as readed
            self::mark_readed($cont,  $requested_user[0]["id"]);


            return view("user.contact", [ "contact" => $contact, "user" => $slug, "data" => $exploitable_data ]);
        }   

        else {
            return view("user.contact", [ "contact" => $contact, "data" => $exploitable_data ]);
        }
    }



    /**
     * Store a message sended to a specific user
     *
     * @param Request $request      The request with all the informations
     * @param Contact $contact         The contact model
     * @param User $user               The user model
     * 
     * @return redirect                Redirect to the contact page with the right slug   
     * 
     */

    public function store(Request $request, Contact $contact, User $user){

        # Get the slug
        $url = explode("/contact/", url() -> previous());
        

        # If there is no slug, the user is 
        # trying to send a message to ... no one

        if(!isset($url[1])){
            return abort(403);
        }
        else {
            $mail = $url[1];
        }
        

        # Test if the user exists
        $id = $user -> where("mail", "=", $mail) -> get() -> toArray();

        
        if(empty($id)){
            return redirect("/contact") -> withErrors(["contact_no_one" => "You cant contact this user !"]);
        }
        else {
            $id = $id[0]["id"];
        }

        # The users want to send an image 
        if(isset($request["img"])){
            $req = $request -> validate([
                "img" => "required|image|max:2000"
            ]);
            
            $img = $req["img"];
            
            if($img !== null && !$img -> getError()){
                
                # Store the image 
                $img_path = $req["img"] -> store("contact_img", "public");            
                
                
                # Add the img to the contact messages
                $contact -> id_contactor = $_SESSION["id"];
                $contact -> id_contacted = $id;
        
                $contact -> content = $img_path;
                
                $contact -> type = "img";
                $contact -> time = date('Y-m-d H:i:s');
                $contact -> readed = false;
                
                $contact -> save();
            
            }
        }
        else {
            
            # Else try to store basic text content

            $req = $request -> validate([
                'content' => 'required',
            ]);
    
            # Add the message
    
            $contact -> id_contactor = $_SESSION["id"];
            $contact -> id_contacted = $id;
    
            $contact -> content = htmlspecialchars($req["content"]);
            
            $contact -> time = date('Y-m-d H:i:s');
            $contact -> readed = false;
            
            $contact -> save();

        }

    
        return to_route("contact.user", $mail);
    }



    /**
     * Delete a message from a conversation if the user is allowed to
     *
     * @param Contact $contact     The message to remove threw model binding
     * 
     * @return redirect            Redirect to the previous url   
     * 
     */

    public function delete(Contact $contact){
        
        $data = $contact -> toArray();
        
        if($data["id_contactor"] === $_SESSION["id"]){
            
            if($data["type"] === "img"){
                Storage::disk("public") -> delete($data['content']);
            }

            $contact -> delete();
        }
        else {
            return abort(403);
        }

        return redirect(url() -> previous());
    }



    /**
     * Show the view of the contact form to update a message or a 403 page if user
     * is not allowed to edit this contact message.
     *
     * @param Request $request
     * @param Contact $contact     The message threw model binding
     * 
     * @return view | redirect           
     * 
     */
    
    public function show_form(Request $request, Contact $contact){

        $contact_message = $contact -> toArray();

        if($contact_message["id_contactor"] === $_SESSION["id"]){ // and $contact_message["type"] === "text" and $request -> server("HTTP_HX_REQUEST") === "true"){
            return view("user.form_contact", [ "message" => $contact_message]);
        }
        else {
            return abort(403);
        }
    }



    /**
     * Update the message if the user is allowed to, else return 403 
     *
     * @param ContactReq $request       The request with all the valuable information
     * @param Contact $contact          The message to update threw model binding
     * 
     * @return view | redirect           
     * 
     */

    public function edit(ContactReq $request, Contact $contact){
        
        if($contact["id_contactor"] === $_SESSION["id"] and $contact["type"] === "text"){

            $contact -> content = htmlspecialchars($request["content"]);
            $contact -> save();

            return redirect(url() -> previous() . "#msg" . $contact["id"]);
        }
        else {
            return abort(403);
        }
    }
}
