<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class LikeController extends Controller
{
    public function like(Post $post)
    {
        $existingLike = auth()->user()->hasLiked($post);

        if ($existingLike) {
            $post->likes()->where('user_id', auth()->id())->delete();
            return response()->json(['count' => $post->likes->count()]);
        } else {
            $post->likes()->create([
                'user_id' => auth()->id(),
            ]);
        }

        return response()->json(['count' => $post->likes->count()]);

    }
}
