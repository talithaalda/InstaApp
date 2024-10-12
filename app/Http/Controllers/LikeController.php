<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike($postId)
    {
        $post = Post::findOrFail($postId);
        $like = Like::where('post_id', $postId)->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create(['post_id' => $postId, 'user_id' => Auth::id()]);
            $liked = true;
        }

        // Mengembalikan respons dalam format JSON
        return response()->json([
            'liked' => $liked,
            'likeCount' => $post->likes()->count(),
        ]);
    }
}
