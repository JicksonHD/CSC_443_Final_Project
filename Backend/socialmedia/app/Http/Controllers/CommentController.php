<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Images;
use App\Models\User;
use App\Models\Comments;
use Validator;

class CommentController extends Controller
{
    function addComment(Request $request){
        $validate = Validator::make($request->all(), [
            'image_id' => 'required|integer',
            'content' => 'required|string',
            'user_id' => 'required|integer'
        ]);
        if($validate->fails()){
            return response()->json([
                "status" => "error",
                "results" => "Some fields are empty"
            ]);
        }

        $user_id = $request->user_id;
        //Check if post exists
        $image = Images::find($request->image_id);
        if(!$image){
            return response()->json([
                "status" => "error",
                "results" => "Invalid Post"
            ]);
        }
        // Adding new Comment
        $comment = new Comments;
        $comment->user_id = $user_id;
        $comment->image_id = $request->image_id;
        $comment->content = $request->content;
        if($comment->save()){
            return response()->json([
                'status' => 'success',
                'results' => 'Comment Added',
                'content' => $comment
            ], 200);
        }
    }

    function deleteComment($comment_id){
        //Check if comment exist
        $user = Auth::user();
        $user_id = $user->id;
        $comment = Comments::find($comment_id);
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
    function getComments($image_id){
        //Check if post exist
        $image = Images::find($image_id);
        if(!$image){
            return response()->json([
                "status" => "error",
                "results" => "Post does not exist"
            ], 404);
        }
        //Getting username with content of each comment
        $comments = DB::table('users')
            ->join('comments', 'users.id', '=', 'comments.user_id')
            ->where('comments.image_id', '=', $image_id)
            ->select('users.first_name', 'comments.content')
            ->get();

        if(count($comments) == 0){
            return response()->json([
                'status' => 'failure',
                'results' => 'No Comments',
                'total' => 0
            ]);
        }
        return response()->json([
            'status' => 'success',
            'results' => 'comments',
            'content' => $comments,
            'total' => count($comments)
        ], 200);   
    }
}
