<?php

namespace App\Http\Controllers;

use App\Models\User;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        $posts = $user->posts()->with(['media', 'userLike'])->withCount('likes')->where(function ($query) {
            $query->where('published_at', '<=', now())
                  ->orWhereNull('published_at');
        })->latest()->paginate(5);
        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }
}
