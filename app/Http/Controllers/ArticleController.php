<?php
namespace App\Http\Controllers;

use App\Models\Article as ArticleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function index()
    {
        return view('welcome', [
            'articles' => ArticleModel::with('tags')->wherePublish(!null)->latest()->paginate(10),
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
        event(new \App\Events\ArticleCreated($article));
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
        abort_if(\Gate::denies('update', $article), 403);
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
        $data = $this->validate($request, [
            'header'      => 'required|between:5,100',
            'description' => 'required|max:255',
            'text'        => 'required',
            'publish'     => 'in:on'
        ]);
        $article->update(array_merge($data, [
            'publish' => isset($data['publish']) ? 1 : null,
        ]));
        $this->tags($request, $article);
        \Mail::to($article->author->email)->send(new \App\Mail\ArticleModified($article));
        flash('Статья успешно изменена');
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
        \Mail::to($article->author->email)->send(new \App\Mail\ArticleDeleted($article));
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
//        $tagsToDetach = $existTags->diffKeys($requestTags);
        foreach ($tagsToAttach as $tag) {
            $tag       = \App\Tag::firstOrCreate(['name' => $tag]);
//            $article->tags()->attach($tag);
            $syncIds[] = $tag->id;
        }
//        foreach ($tagsToDetach as $tag) {
//            $article->tags()->detach($tag);
//        }
        //dd($syncIds);
        $article->tags()->sync($syncIds);
    }
}
