<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article as ArticleModel;

class Article extends Controller
{
    public function __construct()
    {
   
    }
    public function list()
    {
        return view('welcome', [
            'articles' => ArticleModel::wherePublish(!null)->orderByDesc('created_at')->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        $data = $this->validate($request, [
            'header'      => 'required|between:5,100',
            'description' => 'required|max:255',
            'text'        => 'required',
            'publish'     => 'in:on'
        ]);
        ArticleModel::create([
            'header'      => $data['header'],
            'description' => $data['description'],
            'text'        => $data['text'],
            'publish'     => isset($data['publish']) ? 1 : null,
        ]);
        return redirect('/');
    }
}
