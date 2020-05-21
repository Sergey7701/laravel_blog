<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class StatisticController extends Controller
{

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
            'countPublishedEntries'  => \App\Entry::wherePublish(1)->count(),
            'countPublishedArticles' => \App\Entry::whereEntryableType(\App\Models\Article::class)
                ->wherePublish(1)
                ->count(),
            'countPublishedNews'     => \App\Entry::whereEntryableType(\App\News::class)
                ->wherePublish(1)
                ->count(),
            'maxLengthTextOfArticle' => \App\Models\Article::wherePublish(1)
                ->selectRaw('MAX(LENGTH(`text`)) as max_length')
                ->first()
            ->max_length,
            'maxLengthTextOfNews'    => \App\News::wherePublish(1)
                ->selectRaw('MAX(LENGTH(`text`)) as max_length')
                ->first()
            ->max_length,
        ];
    }

    private function part2()
    {
        return [
            'minLengthTextOfArticle'    => \App\Models\Article::wherePublish(1)
                ->selectRaw('MIN(LENGTH(`text`)) as min_length')
                ->first()
            ->min_length,
            'minLengthTextOfNews'       => \App\News::wherePublish(1)
                ->selectRaw('MIN(LENGTH(`text`)) as min_length')
                ->first()
            ->min_length,
            'countUsers'                => \App\User::count(),
            'mostUsersCount'            => \App\Entry::wherePublish(1)->distinct('author_id')->count(),
            'mostUserWithoutEntryCount' => \App\Models\Article::wherePublish(1)
                ->select('author_id')
                ->union(
                    \App\News::wherePublish(1)
                    ->select('author_id')
                )
                ->count(),
        ];
    }

    private function part3()
    {
        return [
            'mostUser' => \App\User::withCount(['entries' => function($q) {
                        $q->wherePublish(1);
                    }])
                ->orderByDesc('entries_count')
                ->first()
            ->name,
            'mostUserWithoutEntry' => DB::table(\App\User::withCount(['news' => function($q) {
                            $q->wherePublish(1);
                        },
                        'articles'             => function($q1) {
                            $q1->wherePublish(1);
                        }
                ]))
                ->selectRaw('name, news_count + articles_count as total_count')
                ->orderByDesc('total_count')
                ->first()
            ->name,
            'mostUserResult' => \App\User::withCount(['entries' => function ($q) {
                        $q->wherePublish(1);
                    }])
                ->orderByDesc('entries_count')
                ->first()
            ->entries_count,
            'mostUserResultWithoutEntry' => DB::table(\App\User::withCount(['news' => function ($q) {
                            $q->wherePublish(1);
                        },
                        'articles'                   => function ($q) {
                            $q->wherePublish(1);
                        }]))
                ->selectRaw('name, news_count + articles_count as total_count')
                ->orderByDesc('total_count')
                ->first()
            ->total_count,
        ];
    }

    private function part4()
    {
        return [
            'countComments'   => \App\Comment::count(),
            'entry'           => \App\Entry::withCount('comments')
                ->wherePublish(1)
                ->orderByDesc('comments_count')
                ->first(),
            'mostCommentator' => \App\User::withCount(['comments' => function($q) {
                        $q->whereHas('entry', function ($q1) {
                                $q1->wherePublish(1);
                            });
                    }])
                ->orderByDesc('comments_count')
                ->first()
            ->name,
        ];
    }

    private function part5()
    {
        return [
            'mostCommentatorCount' => \App\User::withCount(['comments' => function($q) {
                        $q->whereHas('entry', function ($q1) {
                                $q1->wherePublish(1);
                            });
                    }])
                ->orderByDesc('comments_count')
                ->first()
            ->comments_count,
            'mostCommentatorWithDraftes'      => \App\User::withCount('comments')
                ->orderByDesc('comments_count')
                ->first()
            ->name,
            'mostCommentatorWithDraftesCount' => \App\User::withCount('comments')
                ->orderByDesc('comments_count')
                ->first()
            ->comments_count,
        ];
    }

    private function part6()
    {
        return [
            'mostEditingArticle' => \App\Models\Article::wherePublish(1)
                ->withCount('versions')
                ->orderByDesc('versions_count')
                ->first(),
            'mostEditingNews'    => \App\News::wherePublish(1)
                ->withCount('versions')
                ->orderByDesc('versions_count')
                ->first(),
            'tagsCount'          => \App\Tag::count(),
            'usedTagsCount'      => DB::table(\App\Tag::withCount('articles')
                    ->withCount('news'))
                ->where('articles_count', '>', 0)
                ->orWhere('news_count', '>', 0)
                ->count(),
        ];
    }

    private function part7()
    {
        return [
            'usedTagsPublishedCount' => DB::table(\App\Tag::withCount(['articles' => function($q) {
                            $q->wherePublish(1);
                        }])
                    ->withCount(['news' => function ($q) {
                            $q->wherePublish(1);
                        }]))
                ->where('articles_count', '>', 0)
                ->orWhere('news_count', '>', 0)
                ->count(),
        ];
    }
}
