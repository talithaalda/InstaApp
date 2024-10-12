<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate(['comment' => 'required|string|max:255']);

        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user' => [
                    'username' => Auth::user()->username
                ],
                'comment' => $comment->comment,
                'is_owner' => Auth::id() == $comment->user_id  // Informasi apakah user adalah pemilik komentar
            ]
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if (Auth::user()->id != $comment->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to delete this comment.');
        }
        $comment->delete();

        return response()->json(['success' => 'Comment deleted successfully.']);
    }
}
