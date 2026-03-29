<?php

namespace App\Http\Controllers;

class TaskController
{
    public function update(Request $request, Note $note, Task $task)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'is_done' => ['sometimes', 'boolean'],
            'due_at' => ['nullable', 'date'],
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Úloha bola úspešne aktualizovaná.',
            'task' => $task,
        ], Response::HTTP_OK);
    }

//    public function update(Request $request, int $noteId, int $taskId)
//    {
//        $note = Note::find($noteId);
//        if (!$note) {
//            return response()->json([
//                'message' => 'Poznámka nenájdená.'
//            ], Response::HTTP_NOT_FOUND);
//        }
//        $task = $note->tasks()->find($taskId);
//        if (!$task) {
//            return response()->json([
//                'message' => 'Úloha nenájdená.'
//            ], Response::HTTP_NOT_FOUND);
//        }
//
//        $validated = $request->validate([
//            'title' => ['required' , 'string', 'max:255'],
//            'is_done' => ['sometimes', 'boolean'],
//            'due_at' => ['nullable', 'date'],
//        ]);
//
//        $task->update($validated);
//
//        return response()->json([
//            'message' => 'Úloha bola úspešne aktualizovaná.',
//            'task' => $task,
//        ], Response::HTTP_OK);
//    }

}
