<?php

namespace App\Http\Controllers;

use App\Models\User;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        $posts = $user->posts()->with(['user', 'media'])->withCount('likes')->where('published_at', '<=', now())->orWhereNull('published_at')->latest()->paginate(5);
        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }
}
