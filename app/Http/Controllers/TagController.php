<?php
namespace App\Http\Controllers;

//use Illuminate\Http\Request;

class TagController extends Controller
{

    public function index(\App\Tag $tag)
    {
        return view('welcome', [
            'articles' => $tag->articles()->with('tags')->wherePublish(1)->orderByDesc('created_at')->paginate(10),
        ]);
    }
}
