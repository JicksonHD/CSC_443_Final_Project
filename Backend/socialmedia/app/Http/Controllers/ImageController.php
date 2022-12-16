<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\User; 
use Validator;
use File;

class ImageController extends Controller
{
    function addPost(Request $request) {
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
        if ($request->hasFile('image')) {
             // Only allow .jpg, .jpeg and .png file types.
            $validate_img = Validator::make($request->all(), [
                'image' => 'mimes:jpeg,jpg,png'
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
            $request->image->store('images', 'public');

            // Store the record, using the new file hashname which will be it's new filename identity.
            $image = new Image;
            $image->user_id = $request->user_id;
            $image->url = $request->image->hashName();
            $image->description = $request->description;
            if($image->save()){
                return response()->json([
                    'status' => 'success',
                    'results' => 'Post Added',
                    'post' => $image
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
}
