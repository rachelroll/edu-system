<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    public function store(Request $request)
    {
        Comment::create([
            'comments' => $request->comments,

        ]);
    }
}
