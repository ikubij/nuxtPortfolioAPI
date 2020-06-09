<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts=Post::all();

        return response()->json([
            "posts" => $posts,

        ], 200);

    }

    public function new(Request $request)
    {
        // $loggedUser = Auth::User();
        // $manager=Manager::where('user_id', $loggedUser->id)->first();

        $post=new Post();
        $post->title=$request->title;
        $post->summary=$request->summary;
        $post->save();

        return response()->json([
            'success'=>true,
            'data' => $this->index()
        ]);
    }

    public function deletePost(Request $request, $id){
        $post = Post::where('id',$id)->delete();

        return response()->json([
            'success'=>true,
            'data' => $this->index()
        ]);
    }
}
