<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = DB::table('categories')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'categories' => $categories
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        DB::table('categories')->insert([
            'name' => $request->name,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'message' => 'Kategória bola vytvorená.'
        ], Response::HTTP_CREATED);
    }

    public function show(string $id)
    {
        $category = DB::table('categories')
            ->where('id', $id)
            ->first();

        if (!$category) {
            return response()->json([
                'message' => 'Kategória nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'category' => $category
        ], Response::HTTP_OK);
    }

    public function update(Request $request, string $id)
    {
        $category = DB::table('categories')->where('id', $id)->first();

        if (!$category) {
            return response()->json([
                'message' => 'Kategória nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        DB::table('categories')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'updated_at' => now()
            ]);

        return response()->json([
            'message' => 'Kategória bola aktualizovaná.'
        ], Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $category = DB::table('categories')->where('id', $id)->first();

        if (!$category) {
            return response()->json([
                'message' => 'Kategória nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        DB::table('categories')->where('id', $id)->delete();

        return response()->json([
            'message' => 'Kategória bola odstránená.'
        ], Response::HTTP_OK);
    }

}
