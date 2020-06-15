<?php
namespace App\Http\Controllers;

use App\Comment;
use App\Entry;
use App\Models\Article;
use App\News;
use App\Tag;
use App\User;
use DB;

class StatisticController extends Controller
{

    public function __construct()
    {
        config(['custom.use_globalScopePublish' => true]);
    }

    public function __destruct()
    {
        config(['custom.use_globalScopePublish' => false]);
    }

    public function index()
    {
        return view('statistic', array_merge(
                $this->part1(),
                $this->part2(),
                $this->part3(),
                $this->part4(),
                $this->part5(),
                $this->part6(),
                $this->part7(),
            )
        );
    }

    private function part1()
    {
        return [
            'countPublishedEntries'  => Entry::count(),
            'countPublishedArticles' => Entry::whereEntryableType(Article::class)
                ->count(),
            'countPublishedNews'     => Entry::whereEntryableType(News::class)
                ->count(),
            'maxLengthTextOfArticle' => Article::selectRaw('MAX(LENGTH(`text`)) as max_length')
                ->first()
            ->max_length,
            'maxLengthTextOfNews'    => News::selectRaw('MAX(LENGTH(`text`)) as max_length')
                ->first()
            ->max_length,
        ];
    }

    private function part2()
    {
        return [
            'minLengthTextOfArticle'    => Article::selectRaw('MIN(LENGTH(`text`)) as min_length')
                ->first()
            ->min_length,
            'minLengthTextOfNews'       => News::selectRaw('MIN(LENGTH(`text`)) as min_length')
                ->first()
            ->min_length,
            'countUsers'                => User::count(),
            'mostUsersCount'            => Entry::distinct('author_id')->count(),
            'mostUserWithoutEntryCount' => Article::select('author_id')
                ->union(
                    News::select('author_id')
                )
                ->count(),
        ];
    }

    private function part3()
    {
        return [
            'mostUser'                   => User::withCount('entries')
                ->orderByDesc('entries_count')
                ->first()
            ->name,
            'mostUserWithoutEntry'       => DB::table(User::withCount('news', 'articles'))
                ->selectRaw('name, news_count + articles_count as total_count')
                ->orderByDesc('total_count')
                ->first()
            ->name,
            'mostUserResult'             => User::withCount('entries')
                ->orderByDesc('entries_count')
                ->first()
            ->entries_count,
            'mostUserResultWithoutEntry' => DB::table(User::withCount('news', 'articles'))
                ->selectRaw('name, news_count + articles_count as total_count')
                ->orderByDesc('total_count')
                ->first()
            ->total_count,
        ];
    }

    private function part4()
    {
        return [
            'countComments'   => Comment::count(),
            'entry'           => Entry::withCount('comments')
                ->orderByDesc('comments_count')
                ->first(),
            'mostCommentator' => User::withCount(['comments' => function($q) {
                        $q->whereHas('entry');
                    }])
                ->orderByDesc('comments_count')
                ->first()
            ->name,
        ];
    }

    private function part5()
    {
        return [
            'mostCommentatorCount' => User::withCount(['comments' => function($q) {
                        $q->whereHas('entry');
                    }])
                ->orderByDesc('comments_count')
                ->first()
            ->comments_count,
            'mostCommentatorWithDraftes'      => User::has('comments')
                ->withCount('comments')
                ->orderByDesc('comments_count')
                ->first()
            ->name,
            'mostCommentatorWithDraftesCount' => User::has('comments')
                ->withCount('comments')
                ->orderByDesc('comments_count')
                ->first()
            ->comments_count,
        ];
    }

    private function part6()
    {
        return [
            'mostEditingArticle' => Article::withCount('versions')
                ->orderByDesc('versions_count')
                ->first(),
            'mostEditingNews'    => News::withCount('versions')
                ->orderByDesc('versions_count')
                ->first(),
            'tagsCount'          => Tag::count(),
            'usedTagsCount'      => DB::table(Tag::withCount(['articles' => function($q) {
                            $q->withoutGlobalScope('publish');
                        }])
                    ->withCount(['news' => function ($q) {
                            $q->withoutGlobalScope('publish');
                        }]))
                ->where('articles_count', '>', 0)
                ->orWhere('news_count', '>', 0)
                ->count(),
        ];
    }

    private function part7()
    {
        return [
            'usedTagsPublishedCount' => DB::table(Tag::withCount('articles')
                    ->withCount('news'))
                ->where('articles_count', '>', 0)
                ->orWhere('news_count', '>', 0)
                ->count(),
        ];
    }
}
