<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Images;
use App\Models\User; 
use Validator;
use File;

class ImageController extends Controller
{
    function addImage(Request $request) {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'description' => 'required|string'
        ]);
        if($validate->fails()){
            return response()->json([
                "status" => "error",
                "results" => "Some fields are empty"
            ], 400);
        }
        if ($request->hasFile('url')) {
             // Only allow .jpg, .jpeg and .png file types.
            $validate_img = Validator::make($request->all(), [
                'url' => 'mimes:jpeg,jpg,png'
            ]);
            if($validate->fails()){
                return response()->json([
                    "status" => "error",
                    "results" => "Invalid file type."
                ], 400);
            }
            $user = User::find($request->user_id);
            if(!$user){
                return response()->json([
                    "status" => "error",
                    "results" => "Invalid User"
                ], 401);
            }
            // Save the file locally in the storage/public/ folder under a new folder named /product
            $request->url->store('images', 'public');

            // Store the record, using the new file hashname which will be it's new filename identity.
            $image = new Images;
            $image->user_id = $request->user_id;
            $image->url = $request->url->hashName();
            $image->description = $request->description;
            if($image->save()){
                return response()->json([
                    'status' => 'success',
                    'results' => 'Post Added',
                    'url' => $image
                ], 200);
            };
        }
        else{
            return response()->json([
                "status" => "error",
                "results" => "Missing an image"
            ], 400);
        }
    }
    function deleteImage(Request $request){
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'image_id' => 'required|string',
        ]);
        if($validate->fails()){
            return response()->json([
                "status" => "error",
                "results" => "Some fields are empty"
            ], 400);
        }
        // Check if image exist
        $image = Images::find($request->image_id);
        if(!$image){
            return response()->json([
                "status" => "error",
                "results" => "Post does not exist"
            ], 400);
        }
        // Check if user is allowed to delete it
        $image_allow = Images::where("user_id", $request->user_id)
                    ->where("id", $request->image_id)
                    ->get();
        
        if(count($image_allow) == 0){
            return response()->json([
            "status" => "error",
            "results" => "This user is not allowed to delete this image"
            ], 401);
        }
        if(Storage::delete("app/public/posts/".$image_allow[0]->url)){
            if($image_allow[0]->delete()){
                return response()->json([
                    'status' => 'success',
                    'results' => 'Post Deleted',
                    'image' => $image_allow
                ], 200);
            };
        }
    }
    function getImages(){
        $images = Images::all();
        if(count($images) == 0){
            return response()->json([
                "status" => "success",
                "results" => "No Posts yet"
            ], 200);
        }
        return response()->json([
            "status" => "success",
            "results" => $images
        ], 200);
    }
    function getImagesUser($user_id){
    
        $image = Images::where("user_id", $user_id)
                        ->get();
        if(!$image){
            return response()->json([
                "status" => "error",
                "results" => "Image does not exist"
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'results' => 'images',
            'images' => $image,
        ], 200);   

    }
}
