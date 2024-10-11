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

        return redirect('/')->with('success', 'Comment created successfully!');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if (Auth::user()->id != $comment->user_id) {
            return redirect()->back()->with('error', 'You do not have permission to delete this comment.');
        }
        $comment->delete();

        return redirect()->back()->with('danger', 'Comment has been deleted.');
    }
}
