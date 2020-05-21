<?php
namespace App\Http\Controllers;

use App\Entry;
use App\Events\ArticleCreated;
use App\Mail\ArticleDeleted;
use App\Mail\ArticleModified;
use App\Models\Article as ArticleModel;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Collection;
use function flash;

class ArticleController extends Controller
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
    /* Почему идёт выборка Entry, а не Article?
     * Я хочу на главной выводить новости и статьи вперемешку
     */

    public function index()
    {
        session(['admin' => false]);
        return view('welcome', [
            'entries' => Entry::with('entryable')->latest()->where('publish', true)->paginate(10),
            //'articles' => ArticleModel::with('tags')->where('publish', true)->latest()->paginate(10),
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
        $data    = $this->validate($request, [
            'header'      => 'required|between:5,100',
            'description' => 'required|max:255',
            'text'        => 'required',
            'publish'     => 'in:on'
        ]);
        $article = ArticleModel::create(array_merge($data, [
                //'publish'   => isset($data['publish']) ? 1 : null,
                'author_id' => Auth::id(),
        ]));
        event(new ArticleCreated($article));
        flash('Статья успешно создана');
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
        abort_if(!$article->id, 404);
        return view('show', [
            'article'  => $article,
            'comments' => $article->comments()->orderByDesc('created_at')->paginate(10),
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
        abort_if(\Gate::denies('update', $article), 403);
        abort_if(!$article->id, 404);
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
        abort_if(Auth()->user()->cannot('update', $article), 403);
        abort_if(!$article->id, 404);
        $article = $this->updateFunction($request, $article);
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
        abort_if(Auth()->user()->cannot('update', $article), 403);
        abort_if(!$article->id, 404);
        \Mail::to($article->author->email)->send(new ArticleDeleted($article));
        $article->delete();
        flash('Статья успешно удалена', 'warning');
        return redirect('/');
    }

    protected function tags(Request $request, ArticleModel $article)
    {
        /** @var Collection $existTags */
        $existTags   = $article->tags->keyBy('name');
        $requestTags = collect(explode(',', request('tags')))->keyBy(function($item) {
            return trim($item);
        });
        $syncIds      = $existTags->intersectByKeys($requestTags)->pluck('id')->toArray();
        $tagsToAttach = $requestTags->diffKeys($existTags);
        if (count($tagsToAttach)) {
            foreach ($tagsToAttach as $tag) {
                if (strlen($tag)) {
                    $tag       = Tag::firstOrCreate(['name' => $tag]);
                    $syncIds[] = $tag->id;
                }
            }
        }
        $article->tags()->sync($syncIds);
        return $article;
    }

    protected function updateFunction(Request $request, ArticleModel $article)
    {
        $oldTags         = $article->tags->implode('name', ',');
        $data            = $this->validate($request, [
            'header'      => 'required|between:5,100',
            'description' => 'required|max:255',
            'text'        => 'required',
            'publish'     => 'in:on'
        ]);
        $data['publish'] = isset($data['publish']) ? $data['publish'] : null;
        $this->tags($request, $article);
        $article->update(array_merge($data, [
            'newTags' => $request->tags,
            'oldTags' => $oldTags,
        ]));
        \Mail::to($article->author->email)->send(new ArticleModified($article));
        flash('Статья успешно изменена');
        return $article;
    }
}
