<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News;

class AdminNewsController extends NewsController
{

    public function index()
    {
        return view('admin.welcome', [
            'entries' => Entry::latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return parent::create();
    }

    public function show(News $news)
    {
        return parent::show($news);
    }

    public function edit(News $news)
    {
        return view('admin.editNews', [
            'news' => $news,
        ]);
    }

    public function update(Request $request, News $news)
    {
        $newNews = $this->updateFunction($request, $news);
        return redirect("/news/$newNews->slug");
    }

    public function destroy(News $news)
    {
        \Mail::to($news->author->email)->send(new \App\Mail\ArticleDeleted($news));
        $news->delete();
        flash('Статья успешно удалена', 'warning');
        return redirect('/');
    }
}
