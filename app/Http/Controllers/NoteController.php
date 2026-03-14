<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = DB::table('notes')
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();
        return response()->json(['notes' => $notes], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::table('notes')->insert([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'body' => $request->body,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Poznámka bola úspešne vytvorená.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $note = DB::table('notes')
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->first();

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'note' => $note
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $note = DB::table('notes')->where('id', $id)->first();

        if (!$note) {
            return response()->json([
                'message' => 'Poznámka nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        DB::table('notes')->where('id', $id)->update([
            'title' => $request->title,
            'body' => $request->body,
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Poznámka bola úspešne aktualizovaná.'
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id) // toto je soft delete
    {
        $note = DB::table('notes')
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->first();

        if (!$note) {
            return response()->json(['message' => 'Poznámka nenájdená.'], Response::HTTP_NOT_FOUND);
        }

        DB::table('notes')->where('id', $id)->update([
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);

//        DB::table('notes')->where('id', $id)->delete();

        return response()->json(['message' => 'Poznámka bola úspešne odstránená.'], Response::HTTP_OK);
    }

    public function notesByCategory(string $id)
    {
        $notes = DB::table('notes')
            ->join('note_category', 'notes.id', '=', 'note_category.note_id')
            ->where('note_category.category_id', $id)
            ->whereNull('notes.deleted_at')
            ->select('notes.*')
            ->get();

        return response()->json([
            'notes' => $notes
        ], Response::HTTP_OK);
    }


}
