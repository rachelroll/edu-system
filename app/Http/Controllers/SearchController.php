<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){
        $key = $request->input('q');
        if($request->has('q')){
            $posts = Post::search($key)->where('is_checked', 1)->get();
        }
        return view('web.search.search', compact('posts', 'key'));
    }
}
