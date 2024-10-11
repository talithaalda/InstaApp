<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'required|string|max:255',
        ]);

        $imagePath = $request->file('image')->store('posts', 'public');

        $post = Post::create([
            'user_id' => Auth::id(),
            'image_path' => $imagePath,
            'caption' => $request->caption,
        ]);

        return redirect('/')->with('success', 'Post created successfully!');
    }
    public function destroy(Post $post)
    {

        if (Auth::user()->id !== $post->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this post.');
        }

        $post->delete();

        return redirect('/')->with('danger', 'Post deleted successfully.');
    }
}
