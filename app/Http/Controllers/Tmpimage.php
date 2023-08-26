<?php

namespace App\Http\Controllers;

use App\Models\Tmp_images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

if(!isset($_SESSION)){
    session_start();
}

class Tmpimage extends Controller
{
    public function store(Request $request){

        // if the user failed to upload his image because of a validation error
        // we first clean all the files that he temporary uploaded

        if(session() -> has("error")){
            session() -> forget("error");

            foreach(Tmp_images::where("checksum", "=", md5($_SESSION["pass"] . $request ->  server("HTTP_X_CSRF_TOKEN"))) -> get() as $img){
                Storage::disk('public') -> deleteDirectory("tmp/" . $img -> folder);
                $img -> delete();
            }
        
        }

        if($request -> hasFile("mainimg") or $request -> hasFile("otherimg")){
            
            if($request -> hasFile("mainimg")){
                $val = true;
                $main_img = $request -> file("mainimg");
            }
            else if($request -> hasFile("otherimg")){
                $val = false;
                $main_img = $request -> file("otherimg");
            }

            if($main_img === null or $main_img -> getError()){
                return response()->json(["error" => "Something went wrong ..."], 422);
            }
            
            $extension = strtolower($main_img->getClientOriginalExtension());
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'svg', 'webp'])){
                return response()->json(["error" => "Wrong filetype"], 422);
            }
            
            
            $filename = $main_img -> getClientOriginalName();
            
            
            $folder = uniqid("tmp-", true);
            
            $main_img -> storeAs("tmp/" . $folder, $filename, "public");
            
            Tmp_images::create([
                "folder" => $folder,
                "file" => $filename,
                "is_main" => $val,
                "extension" => $main_img->getClientOriginalExtension(),
                "checksum"  => md5($_SESSION["pass"] . $request ->  server("HTTP_X_CSRF_TOKEN"))
            ]);
        
            return $folder;
        }

        return "";
    }


    public function delete(){
        
        $tmp = Tmp_images::where("folder", "=", request() -> getContent()) -> first();

        if($tmp){
            Storage::disk('public') -> deleteDirectory("tmp/" . $tmp -> folder);
            $tmp -> delete();
        }

        return response() -> noContent();
    }
}
