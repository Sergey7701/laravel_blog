<?php
namespace App\Helpers;

use App\Comment;
use App\Entry;
use App\Models\Article;
use App\News;
use App\Tag;
use App\User;

class StatisticGenerator
{

    public function __construct()
    {
        session(['use scopePublish' => true]);
    }

    public function __destruct()
    {
        session()->forget('use scopePublish');
    }

    public function countPublishedEntries()
    {
        return Entry::count();
    }

    public function countPublishedArticles()
    {
        return Entry::whereEntryableType(Article::class)->count();
    }

    public function countPublishedNews()
    {
        return Entry::whereEntryableType(News::class)->count();
    }

    public function maxLengthTextOfArticle()
    {
        return Article::selectRaw('MAX(LENGTH(`text`)) as max_length')
                ->first()
            ->max_length;
    }

    public function maxLengthTextOfNews()
    {
        return News::selectRaw('MAX(LENGTH(`text`)) as max_length')
                ->first()
            ->max_length;
    }

    public function minLengthTextOfArticle()
    {
        return Article::selectRaw('MIN(LENGTH(`text`)) as min_length')
                ->first()
            ->min_length;
    }

    public function minLengthTextOfNews()
    {
        return News::selectRaw('MIN(LENGTH(`text`)) as min_length')
                ->first()
            ->min_length;
    }

    public function countUsers()
    {
        return User::count();
    }

    public function mostUsersCount()
    {
        return Entry::distinct('author_id')->count();
    }

    public function mostUser()
    {
        return User::withCount('entries')
                ->orderByDesc('entries_count')
                ->first()
            ->name;
    }

    public function mostUserResult()
    {
        return User::withCount('entries')
                ->orderByDesc('entries_count')
                ->first()
            ->entries_count;
    }

    public function countComments()
    {
        return Comment::count();
    }

    public function entry()
    {
        $entry = $this->mostCommentingEntry();
        return $entry->entryable->getUrlPrefix() . '/' . $entry->entryable->slug;
    }

    public function entryComments()
    {
        return ($this->mostCommentingEntry())->comments->count();
    }

    public function mostCommentator()
    {
        return User::withCount(['comments' => function($q) {
                        $q->whereHas('entry');
                    }])
                ->orderByDesc('comments_count')
                ->first()
            ->name;
    }

    public function mostCommentatorCount()
    {
        return User::withCount(['comments' => function($q) {
                        $q->whereHas('entry');
                    }])
                ->orderByDesc('comments_count')
                ->first()
            ->comments_count;
    }

    public function mostCommentatorWithDraftes()
    {
        return User::withCount('comments')
                ->orderByDesc('comments_count')
                ->first()
            ->name;
    }

    public function mostCommentatorWithDraftesCount()
    {
        return User::withCount('comments')
                ->orderByDesc('comments_count')
                ->first()
            ->comments_count;
    }

    public function mostEditingArticle()
    {
        $article = Article::has('versions')
            ->withCount('versions')
            ->orderByDesc('versions_count')
            ->first();
        return $article->getUrlPrefix() . '/' . $article->slug;
    }

    public function mostEditingNews()
    {
        $news = News::has('versions')
            ->withCount('versions')
            ->orderByDesc('versions_count')
            ->first();
        return $news->getUrlPrefix() . '/' . $news->slug;
    }

    public function tagsCount()
    {
        return Tag::count();
    }

    public function usedTagsCount()
    {
        return \DB::table(Tag::withCount('articles')
                    ->withCount('news'))
                ->where('articles_count', '>', 0)
                ->orWhere('news_count', '>', 0)
                ->count();
    }

    public function usedTagsPublishedCount()
    {
        return \DB::table(Tag::withCount(['articles' => function($q) {
                            $q->wherePublish(1);
                        }])
                    ->withCount(['news' => function ($q) {
                            $q->wherePublish(1);
                        }]))
                ->where('articles_count', '>', 0)
                ->orWhere('news_count', '>', 0)
                ->count();
    }

    private function mostCommentingEntry()
    {
        return Entry::withCount('comments')
                ->orderByDesc('comments_count')
                ->first();
    }
}
