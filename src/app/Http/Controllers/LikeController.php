<?php

namespace App\Http\Controllers;

use App\Models\Like;

class LikeController extends Controller
{
    public function store($product_id)
    {
        $like = Like::where('user_id', auth()->id())
            ->where('product_id', $product_id)
            ->first();

        if ($like) {
            // 既にある → 削除（解除）
            $like->delete();
        } else {
            // ない → 作成（いいね）
            Like::create([
                'user_id' => auth()->id(),
                'product_id' => $product_id,
            ]);
        }

        return back();
    }
}
