<?php
namespace App\Http\Controllers;

use App\Article;
use App\Entry;
use App\Events\ArticleCreated;
use App\Mail\ArticleDeleted;
use App\Models\Article as ArticleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function flash;

class AdminArticleController extends ArticleController
{

    function __construct()
    {
        $this->middleware('auth', [
            'except' => [
                'index',
                'show',
            ]
        ]);
    }

    public function index()
    {
        session(['admin' => true]);
        return view('admin.welcome', [
            'entries' => Entry::latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data    = $this->validate($request, [
            'header'      => 'required|between:5,100',
            'description' => 'required|max:255',
            'text'        => 'required',
            'publish'     => 'in:on'
        ]);
        $article = ArticleModel::create(array_merge($data, [
                'publish'   => isset($data['publish']) ? 1 : null,
                'author_id' => Auth::id(),
        ]));
        event(new ArticleCreated($article));
        flash('Статья успешно создана');
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  Article  $article
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
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(ArticleModel $article)
    {
        abort_if(!$article->id, 404);
        return view('admin.edit', [
            'article' => $article,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ArticleModel $article)
    {
        abort_if(!$article->id, 404);
        $article = $this->updateFunction($request, $article);
        return redirect("/posts/$article->slug");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(ArticleModel $article)
    {
        abort_if(!$article->id, 404);
        \Mail::to($article->author->email)->send(new ArticleDeleted($article));
        $article->delete();
        flash('Статья успешно удалена', 'warning');
        return redirect('/');
    }
}
