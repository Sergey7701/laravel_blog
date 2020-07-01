<?php
namespace App\Http\Controllers;

use App\Comment;
use App\Entry;
use App\Models\Article;
use App\News;
use App\Tag;
use App\User;
use DB;
use Illuminate\Support\Facades\Cache;
use App\Helpers\StatisticGenerator;

class StatisticController extends Controller
{

    protected $statisticItems = [
        'countPublishedEntries',
        'countPublishedArticles',
        'countPublishedNews',
        'maxLengthTextOfArticle',
        'maxLengthTextOfNews',
        'minLengthTextOfArticle',
        'minLengthTextOfNews',
        'countUsers',
        'mostUsersCount',
        'mostUser',
        'mostUserResult',
        'countComments',
        'entry',
        'entryComments',
        'mostCommentator',
        'mostCommentatorCount',
        'mostCommentatorWithDraftes',
        'mostCommentatorWithDraftesCount',
        'mostEditingArticle',
        'mostEditingNews',
        'tagsCount',
        'usedTagsCount',
        'usedTagsPublishedCount',
    ];

    public function index()
    {
        $statistic = [];
        if (Cache::has('PleaseClearCache')) {
            Cache::tags('statistic')->flush();
        }
        foreach ($this->statisticItems as $item) {
            $statistic[$item] = Cache::tags('statistic')->remember($item, 60 * 60, function () use ($item) {
                return (new StatisticGenerator)->$item();
            });
        }
        return view('statistic', $statistic);
    }
}
