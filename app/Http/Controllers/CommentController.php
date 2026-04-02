<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;

class CommentController extends Controller
{
    public function comment(Post $post)
    {
        $data = request()->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);
        $post->comment()->create([
            'user_id' => auth()->id(),
            'parent_id' => $data['parent_id'] ?? null,
            'comment' => $data['comment'],
        ]);

        return redirect(url()->previous() . '#comments');
    }
}
