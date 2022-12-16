<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Validator;

class CommentController extends Controller
{
    function addComment(Request $request){
        $validate = Validator::make($request->all(), [
            'image_id' => 'required|integer',
            'comment' => 'required|string'
        ]);
        if($validate->fails()){
            return response()->json([
                "status" => "error",
                "results" => "Some fields are empty"
            ]);
        }
        $user = Auth::user();
        $user_id = $user->id;
        //Check if post exists
        $image = Post::find($request->image_id);
        if(!$image){
            return response()->json([
                "status" => "error",
                "results" => "Invalid Post"
            ]);
        }
        // Adding new Comment
        $comment = new Comment;
        $comment->user_id = $user_id;
        $comment->image_id = $request->image_id;
        $comment->comment = $request->comment;
        if($comment->save()){
            return response()->json([
                'status' => 'success',
                'results' => 'Comment Added',
                'comment' => $comment
            ], 200);
        }
    }

    function deleteComment($comment_id){
        //Check if comment exist
        $user = Auth::user();
        $user_id = $user->id;
        $comment = Comment::find($comment_id);
        if(!$comment){
            return response()->json([
                "status" => "error",
                "results" => "Comment does not exist"
            ]);
        }
        if($user_id != $comment->user_id){
            return response()->json([
                "status" => "error",
                "results" => "User Not allowed to delete this comment"
            ]);
        }
        //Delete Comment
        if($comment->delete()){
            return response()->json([
                'status' => 'success',
                'results' => 'Comment Deleted',
                'like' => $comment
            ], 200);
        }
    }
}
