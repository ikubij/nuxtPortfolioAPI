<?php

namespace App\Http\Controllers;
use App\Events\NewMessage;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request){
    	$comment= array(
    		"message" => "Hello world"
    	);
    		
    	broadcast(new NewMessage($comment))->toOthers();
	    return $comment;
    }
}
