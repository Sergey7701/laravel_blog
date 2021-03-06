<?php
namespace App\Http\Controllers;

use App\Events\ArticleCreated;
use App\Mail\ArticleModified;
use App\News;
use App\Tag;
use App\Traits\FlushCacheIfNeeded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function flash;

class NewsController extends Controller
{
 use FlushCacheIfNeeded; 
    function __construct()
    {
        $this->middleware('permission:manage-articles', [
            'except' => [
                'index',
                'show',
            ]
        ]);
    }

    public function index()
    {
        return redirect('/');
    }

    public function create()
    {
        return view('createNews', [
            'title' => 'Новая новость',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'header'  => 'required|between:5,100',
            'text'    => 'required',
            'publish' => 'in:on'
        ]);
        $news = News::create(array_merge($data, [
                'author_id' => Auth::id(),
        ]));
        event(new ArticleCreated($news));
        flash('Новость успешно создана');
        return redirect('/');
    }

    public function show(News $news)
    {
        return view('showNews', [
            'news'     => $news,
            'comments' => $news->comments()->orderByDesc('created_at')->paginate(10),
        ]);
    }

    public function edit(News $news)
    {
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, News $news)
    {
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        return redirect('/');
    }

    protected function tags(Request $request, News $news)
    {
        /** @var Collection $existTags */
        $existTags   = $news->tags->keyBy('name');
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
        $news->tags()->sync($syncIds);
        return $news;
    }

    protected function updateFunction(Request $request, News $news)
    {
        $oldTags         = $news->tags->implode('name', ', ');
        $data            = $this->validate($request, [
            'header'  => 'required|between:5,100',
            'text'    => 'required',
            'publish' => 'in:on'
        ]);
        $data['publish'] = isset($data['publish']) ? $data['publish'] : null;
        $this->tags($request, $news);
        $news->update(array_merge($data, [
            'newTags' => $request->tags,
            'oldTags' => $oldTags,
        ]));
        \Mail::to($news->author->email)->send(new ArticleModified($news));
        flash('Новость успешно изменена');
        return $news;
    }
}
