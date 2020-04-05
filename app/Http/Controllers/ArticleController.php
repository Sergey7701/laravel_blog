<?php
namespace App\Http\Controllers;

use App\Models\Article as ArticleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{

    public function index()
    {
        return view('welcome', [
            'articles' => ArticleModel::wherePublish(!null)->orderByDesc('created_at')->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('createArticle', [
            'title' => 'Новая статья',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'header'      => 'required|between:5,100',
            'description' => 'required|max:255',
            'text'        => 'required',
            'publish'     => 'in:on'
        ]);
        ArticleModel::create(array_merge($data, [
            'publish'   => isset($data['publish']) ? 1 : null,
            'author_id' => Auth::id(),
        ]));
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(ArticleModel $article)
    {
        return view('show', [
            'article' => $article,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(ArticleModel $article)
    {
        return view('edit', [
            'article' => $article,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ArticleModel $article)
    {
        $this->guard($article->author_id);
        $data = $this->validate($request, [
            'header'      => 'required|between:5,100',
            'description' => 'required|max:255',
            'text'        => 'required',
            'publish'     => 'in:on'
        ]);
        $article->update(array_merge($data, [
            'publish' => isset($data['publish']) ? 1 : null,
        ]));
        return redirect("/posts/$article->slug");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArticleModel $article)
    {
        $this->guard($article->author_id);
        $article->delete();
        return redirect('/');
    }

    private function guard($author_id)
    {
        if ($author_id === Auth::id()) {
            return abort(403);
        }
    }
}
