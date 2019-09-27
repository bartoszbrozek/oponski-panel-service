<?php

namespace App\Http\Controllers;

use App\Article;
use Exception;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function getAll(Request $request, Article $article)
    {
        return $article
        ->with('category')
        ->with('user')
        ->get();
    }

    public function add(Request $request, Article $article)
    {
        try {
            $request->validate([
                "title" => 'required',
                "subtitle" => 'required',
                "content" => 'required',
            ]);

            $article->title = $request->get("title");
            $article->subtitle = $request->get("subtitle");
            $article->content = $request->get("content");
            $article->user_id = $request->user()->id;
            $article->main_img = "Main IMG";
            $article->category_id = 1;

            $article->save();

            return response()->json([
                'msg' => "Article Added",
            ], 200);
        } catch (Exception $ex) {
            return response()->json([
                'error_description' => $ex->getMessage(),
            ], 400);
        }
    }
}
