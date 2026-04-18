<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Note;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function indexNote(Note $note)
    {
        $this->authorize('view', $note);

        return response()->json([
            'comments' => $note->comments
        ]);
    }

    public function storeNote(Request $request, Note $note)
    {
        $this->authorize('create', [Comment::class, $note]);

        $validated = $request->validate([
            'body' => ['required', 'string']
        ]);

        $comment = $note->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body']
        ]);

        return response()->json($comment);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $comment->update($validated);

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment
        ]);
    }
}
