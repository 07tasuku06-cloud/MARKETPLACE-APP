<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, $product_id)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $product_id,
            'comment' => $request->comment,
        ]);

        return back();
    }
}
