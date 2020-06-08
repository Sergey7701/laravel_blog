<?php
namespace App\Helpers;

use App\Comment;
use App\Entry;
use App\Models\Article;
use App\News;
use App\Tag;
use App\User;

/** Массив представляет собой заготовку для формы запроса статистики по сайту
 *  Формат массива:
 *  имяЧекбоксаЗапроса => [
 *      'Название заглавного пункта запроса' => 'вызываемаяФункцияДляВыполненияЭтогоПункта',
 *      '- название подпункта запроса'       => 'вызываемаяФункцияДляВыполненияЭтогоПодпункта',
 *      '- название подпункта'               => 'вызываемаяФункцияДляВыполненияИЭтогоПодпункта',
 *  ],
 *  имяЧекбоксаЗапроса2 => [
 *  ...
 *  ]
 */
class HelperStatistic
{

    public static $statisticFormSkeleton = [
        'countPublishedEntries'      => [
            'Всего опубликованных записей: ' => 'countPublishedEntries',
            '- из них статей'                => 'countPublishedArticles',
            '- из них новостей'              => 'countPublishedNews',
        ],
        'maxLengthTextOfArticle'     => [
            'Самый длинный текст у статьи: ' => 'maxLengthTextOfArticle',
        ],
        'maxLengthTextOfNews'        => [
            'Самый длинный текст у новости: ' => 'maxLengthTextOfNews',
        ],
        'minLengthTextOfArticle'     => [
            'Самый короткий текст у статьи: ' => 'minLengthTextOfArticle',
        ],
        'minLengthTextOfNews'        => [
            'Самый короткий текст у новости: ' => 'minLengthTextOfNews',
        ],
        'countUsers'                 => [
            'Всего зарегистрированных пользователей: '   => 'countUsers',
            '- из них опубликовали хотя бы одну запись ' => 'mostUsersCount',
        ],
        'mostUser'                   => [
            'Самый пишущий автор: ' => 'mostUser',
            '- у него записей '     => 'mostUserResult',
        ],
        'countComments'              => [
            'Всего комментариев: ' => 'countComments',
        ],
        'mostCommentedEntry'         => [
            'Самая комментируемая запись: ' => 'entry',
            '- у неё комментариев: '        => 'entryComments',
        ],
        'mostCommentator'            => [
            'Самый активный комментатор, без черновиков: ' => 'mostCommentator',
            '- у него комментариев: '                      => 'mostCommentatorCount',
        ],
        'mostCommentatorWithDraftes' => [
            'Самый активный комментатор, включая черновики: ' => 'mostCommentatorWithDraftes',
            '- у него комментариев: '                         => 'mostCommentatorCountWithDraftes',
        ],
        'mostEditingArticle'         => [
            'Самая часто меняемая статья' => 'mostEditingArticle',
        ],
        'mostEditingNews'            => [
            'Самая часто меняемая новость' => 'mostEditingNews',
        ],
        'tagsCount'                  => [
            'Тегов на сайте: '                                              => 'tagsCount',
            '- из них используются в опубликованных записях и черновиках: ' => 'usedTagsCount',
            '- из них используются только в опубликованных записях: '       => 'usedTagsPublishedCount',
        ],
    ];

    public static function countPublishedEntries()
    {
        return Entry::wherePublish(1)->count();
    }

    public static function countPublishedArticles()
    {
        return Entry::whereEntryableType(Article::class)
                ->wherePublish(1)
                ->count();
    }

    public static function countPublishedNews()
    {
        return Entry::whereEntryableType(News::class)
                ->wherePublish(1)
                ->count();
    }

    public static function maxLengthTextOfArticle()
    {
        return Article::wherePublish(1)
                ->selectRaw('MAX(LENGTH(`text`)) as max_length')
                ->first()
            ->max_length;
    }

    public static function maxLengthTextOfNews()
    {
        return News::wherePublish(1)
                ->selectRaw('MAX(LENGTH(`text`)) as max_length')
                ->first()
            ->max_length;
    }

    public static function minLengthTextOfArticle()
    {
        return Article::wherePublish(1)
                ->selectRaw('MIN(LENGTH(`text`)) as min_length')
                ->first()
            ->min_length;
    }

    public static function minLengthTextOfNews()
    {
        return News::wherePublish(1)
                ->selectRaw('MIN(LENGTH(`text`)) as min_length')
                ->first()
            ->min_length;
    }

    public static function countUsers()
    {
        return User::count();
    }

    public static function mostUsersCount()
    {
        return Entry::wherePublish(1)->distinct('author_id')->count();
    }

    public static function mostUser()
    {
        return User::withCount(['entries' => function($q) {
                        $q->wherePublish(1);
                    }])
                ->orderByDesc('entries_count')
                ->first()
            ->name;
    }

    public static function mostUserResult()
    {
        return User::withCount(['entries' => function ($q) {
                        $q->wherePublish(1);
                    }])
                ->orderByDesc('entries_count')
                ->first()
            ->entries_count;
    }

    public static function countComments()
    {
        return Comment::count();
    }

    public static function entry()
    {
        return Entry::withCount('comments')
                ->wherePublish(1)
                ->orderByDesc('comments_count')
                ->first();
    }

    public static function entryComments()
    {
        return entry()->comments->count();
    }

    public static function mostCommentator()
    {
        return User::withCount(['comments' => function($q) {
                        $q->whereHas('entry', function ($q1) {
                                $q1->wherePublish(1);
                            });
                    }])
                ->orderByDesc('comments_count')
                ->first()
            ->name;
    }

    public static function mostCommentatorCount()
    {
        return User::withCount(['comments' => function($q) {
                        $q->whereHas('entry', function ($q1) {
                                $q1->wherePublish(1);
                            });
                    }])
                ->orderByDesc('comments_count')
                ->first()
            ->comments_count;
    }

    public static function mostCommentatorWithDraftes()
    {
        return User::withCount('comments')
                ->orderByDesc('comments_count')
                ->first()
            ->name;
    }

    public static function mostCommentatorWithDraftesCount()
    {
        return User::withCount('comments')
                ->orderByDesc('comments_count')
                ->first()
            ->comments_count;
    }

    public static function mostEditingArticle()
    {
        return Article::wherePublish(1)
                ->has('versions')
                ->withCount('versions')
                ->orderByDesc('versions_count')
                ->first();
    }

    public static function mostEditingNews()
    {
        return News::wherePublish(1)
                ->has('versions')
                ->withCount('versions')
                ->orderByDesc('versions_count')
                ->first();
    }

    public static function tagsCount()
    {
        return Tag::count();
    }

    public static function usedTagsCount()
    {
        return \DB::table(Tag::withCount('articles')
                    ->withCount('news'))
                ->where('articles_count', '>', 0)
                ->orWhere('news_count', '>', 0)
                ->count();
    }

    public static function usedTagsPublishedCount()
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
}
