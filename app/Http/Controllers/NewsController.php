<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:manage-articles', [
            'except' => [
                'index',
                'show',
            ]
        ]);
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
                //'publish'   => isset($data['publish']) ? 1 : null,
                'author_id' => Auth::id(),
        ]));
        event(new \App\Events\ArticleCreated($news));
        flash('Новость успешно создана');
        return redirect('/');
    }

    public function show(News $news)
    {
        return view('showNews', [
            'news' => $news,
        ]);
    }

    public function edit(News $news)
    {
        
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        
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
        Version::create([
            'article_id' => $article->id,
            'editor_id'  => (int) Auth::id(),
            'header'     => $article->header,
//            'description' => $article->description,
            'text'       => $article->text,
            'publish'    => $article->publish,
            'tags'       => $article->tags->implode('name', ', '),
        ]);
    }

    protected function updateFunction(Request $request, News $news)
    {
        $oldArticle      = clone $news;
        $oldTags         = $oldArticle->tags->implode('name', ', ');
        $data            = $this->validate($request, [
            'header'  => 'required|between:5,100',
            'text'    => 'required',
            'publish' => 'in:on'
        ]);
        $data['publish'] = isset($data['publish']) ? $data['publish'] : null;
        $news->update($data);
        $this->tags($request, $news);
        // $this->createVersion($oldArticle, $oldTags);
        \Mail::to($news->author->email)->send(new \App\Mail\ArticleModified($news));
        flash('Новость успешно изменена');
        return $news;
    }

    protected function createVersion(News $news, $oldTags)
    {
        Version::create([
            'article_id'  => $news->id,
            'editor_id'   => (int) Auth::id(),
            'header'      => $news->header,
            'description' => $news->description,
            'text'        => $news->text,
            'publish'     => $news->publish,
            'tags'        => $oldTags,
        ]);
    }
}
