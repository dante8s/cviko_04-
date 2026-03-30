<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class NoteController extends Controller
{

    public function index()
    {
        $notes = Note::query()
            ->select(['id', 'user_id', 'title', 'body', 'status', 'is_pinned', 'created_at'])
            ->with([
                'user:id,first_name,last_name',
                'categories:id,name,color',
            ])
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['notes' => $notes], Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'body'  => ['nullable', 'string'],
            'status' => ['sometimes', 'required', Rule::in(['draft', 'published', 'archived'])],
            'is_pinned' => ['sometimes', 'boolean'],
            'categories' => ['sometimes', 'array', 'max:3'],
            'categories.*' => ['integer', 'distinct', 'exists:categories,id'],
        ]);

        $note = Note::create([
            'user_id'   => $validated['user_id'],
            'title'     => $validated['title'],
            'body'      => $validated['body'] ?? null,
            'status'    => $validated['status'] ?? 'draft',
            'is_pinned' => $validated['is_pinned'] ?? false,
        ]);

        if (!empty($validated['categories'])) {
            $note->categories()->sync($validated['categories']);
        }

        return response()->json([
            'message' => 'Poznámka bola úspešne vytvorená.',
            'note' => $note->load(['user:id,first_name,last_name','categories:id,name,color']),
        ], Response::HTTP_CREATED);
    }


    public function show(string $id)
    {
        try {
            $note = Note::with([
                'user:id,first_name,last_name',
                'categories:id,name', // без color
                'tasks:id,note_id,title', // без status
                'tasks.comments:id,commentable_id,commentable_type,body,user_id,created_at',
                'comments:id,commentable_id,commentable_type,body,user_id,created_at'
            ])->find($id);

            if (!$note) {
                return response()->json(['message' => 'Poznámka nenájdená.'], 404);
            }

            return response()->json(['note' => $note], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, string $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['nullable', 'string'],
            'status' => ['sometimes', 'required', Rule::in(['draft', 'published', 'archived'])],
            'is_pinned' => ['sometimes', 'boolean'],
            'categories' => ['sometimes', 'array'],
            'categories.*' => ['integer', 'distinct', 'exists:categories,id'],
        ]);

        $note->update($validated);

        if (array_key_exists('categories', $validated)) {
            $note->categories()->sync($validated['categories']);
        }

        return response()->json([
            'message' => 'Poznámka bola aktualizovaná.',
            'note' => $note->load(['user:id,first_name,last_name','categories:id,name,color']),
        ], Response::HTTP_OK);
    }


    public function destroy(string $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        $note->delete();

        return response()->json(['message' => 'Poznámka bola úspešne odstránená.'], Response::HTTP_OK);
    }

    // ===== CUSTOM METHODS =====

    public function statsByStatus()
    {
        return response()->json([
            'stats' => Note::statsByStatus()
        ], Response::HTTP_OK);
    }

    public function archiveOldDrafts()
    {
        $affected = Note::archiveOldDrafts();

        return response()->json([
            'message' => 'Staré koncepty boli archivované.',
            'affected_rows' => $affected,
        ]);
    }

    public function userNotesWithCategories(string $userId)
    {
        return response()->json([
            'notes' => Note::userNotesWithCategories($userId)
        ], Response::HTTP_OK);
    }

    public function pin(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        $note->pin();
        return response()->json(['message' => 'Poznámka pripnutá.'], Response::HTTP_OK);
    }

    public function unpin(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        $note->unpin();
        return response()->json(['message' => 'Poznámka odopnutá.'], Response::HTTP_OK);
    }

    public function publish(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Poznámка nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        $note->publish();
        return response()->json(['message' => 'Poznámка publikovaná.'], Response::HTTP_OK);
    }

    public function archive(string $id)
    {
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['message' => 'Poznámка nenájденá.'], Response::HTTP_NOT_FOUND);
        }

        $note->archive();
        return response()->json(['message' => 'Poznámка archivovaná.'], Response::HTTP_OK);
    }
}
