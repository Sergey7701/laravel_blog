<?php
namespace App\Http\Controllers;

//use Illuminate\Http\Request;

class TagController extends Controller
{

    public function index(\App\Tag $tag)
    {
        $articles = $tag->articles()->with('tags');
        if (!\auth()->check() || !\auth()->user()->can('manage-articles')) {
            $articles = $articles->wherePublish(1);
        }
        return view('welcome', [
            'articles' => $articles->orderByDesc('created_at')->paginate(10),
        ]);
    }
}
