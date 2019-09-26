<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function getAll(Request $request, Article $article)
    {
        return $article->with('category')->get();
    }
}
