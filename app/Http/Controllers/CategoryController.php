<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{

    public function index()
    {
        return response()->json([
            'categories' => Category::latest()
        ], Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return response()->json([
            'message' => 'Kategória bola vytvorená.',
            'category' => $category
        ], Response::HTTP_CREATED);
    }

    public function show(string $id)
    {
        $category = Category::find($id);

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
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Kategória nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        $category->update([
            'name' => $request->name,
            'color' => $request->color,
        ]);

        return response()->json([
            'message' => 'Kategória bola aktualizovaná.',
            'category' => $category
        ], Response::HTTP_OK);
    }


    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Kategória nenájdená.'
            ], Response::HTTP_NOT_FOUND);
        }

        $category->delete();

        return response()->json([
            'message' => 'Kategória bola odstránená.'
        ], Response::HTTP_OK);
    }
}
