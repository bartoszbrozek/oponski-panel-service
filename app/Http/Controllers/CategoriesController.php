<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use Exception;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function getAll(Request $request, Category $category)
    {
        return $category
            ->get();
    }

    public function add(Request $request, Category $article)
    {
        try {
            $request->validate([
                "name" => 'required',
            ]);

            $article->name = $request->get("name");

            $article->save();

            return response()->json([
                'msg' => "Category Added",
            ], 200);
        } catch (Exception $ex) {
            return response()->json([
                'error_description' => $ex->getMessage(),
            ], 400);
        }
    }
}
