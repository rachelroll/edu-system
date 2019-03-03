<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $comment = $request->get('comments', '');

        Comment::create([
            'comments' => $comment,
        ]);
    }
}
