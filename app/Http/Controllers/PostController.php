<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // \DB::listen(function ($query) {
        //     \Log::info($query->sql);
        // });
        $user = auth()->user();
        $query = Post::with(['user', 'media'])->withCount('likes')->where('published_at', '<=', now())->orWhereNull('published_at');
        // Get post from user that we follow and our own post
        // if($user) {
        //     $ids = $user->following()->pluck('users.id');
        //     $query->whereIn('user_id', $ids)->orWhere('user_id', $user->id);
        // }
        if ($user) {
            $ids = $user->following()->pluck('users.id')->toArray();

            $query->orderByRaw("
                DATE(created_at) DESC
            ")->orderByRaw("
                CASE 
                    WHEN user_id IN (" . implode(',', $ids ?: [0]) . ") THEN 0
                    ELSE 1
                END
            ")->orderByDesc('created_at');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        $post = $query->paginate(5);
        // Get all post
        // $post = Post::orderBy('created_at', 'desc')->paginate(5);

        // dump($categories);
        // dd($categories);

        return view('post.index', [
            'posts' => $post,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('post.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $data =$request->validated();

        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title']) . '-' . strtolower(Str::random(4));

        // $image = $data['image'];
        // unset($data['image']);
        // $imagePath = $image->store('post', 'public');
        // $data['image'] = $imagePath;

        $post = Post::create($data);
        $post->addMediaFromRequest('image')->toMediaCollection('post');

        return redirect()->route('dashboard')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
        return view('post.show', [
            'post' => $post,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if($post->user_id !== auth()->id()) {
            abort(403);
        }
        return view('post.edit', [
            'post' => $post,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        if($post->user_id !== auth()->id()) {
            abort(403);
        }
        $data = $request->validated();
        $post->update($data);
        if($request->hasFile('image')) {
            $post->clearMediaCollection('post');
            $post->addMediaFromRequest('image')->toMediaCollection('post');
        }
        return redirect()->route('myPosts');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if($post->user_id !== auth()->id()) {
            abort(403);
        }
        $post->delete();

        return redirect()->route('myPosts');
    }

    public function category(Category $category)
    {
        $user = auth()->user();
        $query = $category->posts()->with(['user', 'media'])->withCount('likes')->where('published_at', '<=', now())->orWhereNull('published_at');
        if ($user) {
            $ids = $user->following()->pluck('users.id')->toArray();

            $query->orderByRaw("
                DATE(created_at) DESC
            ")->orderByRaw("
                CASE 
                    WHEN user_id IN (" . implode(',', $ids ?: [0]) . ") THEN 0
                    ELSE 1
                END
            ")->orderByDesc('created_at');
        }
        else {
            $query->orderBy('created_at', 'desc');
        }
        $posts = $query->paginate(5);

        return view('post.index', [
            'posts' => $posts,
        ]);
    }

    public function myPosts()
    {
        $user = auth()->user();
        $posts = $user->posts()->with(['user', 'media'])->withCount('likes')->latest()->paginate(5);

        return view('post.index', [
            'posts' => $posts,
        ]);
    }
}
