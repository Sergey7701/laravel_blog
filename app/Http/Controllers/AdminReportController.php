<?php
namespace App\Http\Controllers;

use App\Helpers\HelperStatistic;
use App\Jobs\StatisticReport as StatisticReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{

    /** Массив представляет собой заготовку для формы запроса статистики по сайту
     *  Формат массива:
     *  имяЧекбоксаЗапроса => [
     *          'вызываемаяФункцияДляВыполненияЭтогоПункта' => [],
     *          'вызываемаяФункцияДляВыполненияЭтогоПодпункта' => [],
     *          'вызываемаяФункцияДляВыполненияИЭтогоПодпункта' => [],
     *  ],
     *  имяЧекбоксаЗапроса2 => [
     *  ...
     *  ];
     *  Названия подпунктов хранятся в lang/ru/statisticReport.php
     *  Для их получения использовать \App::setLocale('ru');
     */
    
    protected $statisticFormSkeleton = [
        'countPublishedEntries'      => [
            'countPublishedEntries'  => [],
            'countPublishedArticles' => [],
            'countPublishedNews'     => [],
        ],
        'maxLengthTextOfArticle'     => [
            'maxLengthTextOfArticle' => [],
        ],
        'maxLengthTextOfNews'        => [
            'maxLengthTextOfNews' => [],
        ],
        'minLengthTextOfArticle'     => [
            'minLengthTextOfArticle' => [],
        ],
        'minLengthTextOfNews'        => [
            'minLengthTextOfNews' => [],
        ],
        'countUsers'                 => [
            'countUsers'     => [],
            'mostUsersCount' => [],
        ],
        'mostUser'                   => [
            'mostUser'       => [],
            'mostUserResult' => [],
        ],
        'countComments'              => [
            'countComments' => [],
        ],
        'mostCommentedEntry'         => [
            'entry'         => [],
            'entryComments' => [],
        ],
        'mostCommentator'            => [
            'mostCommentator'      => [],
            'mostCommentatorCount' => [],
        ],
        'mostCommentatorWithDraftes' => [
            'mostCommentatorWithDraftes'      => [],
            'mostCommentatorWithDraftesCount' => [],
        ],
        'mostEditingArticle'         => [
            'mostEditingArticle' => [],
        ],
        'mostEditingNews'            => [
            'mostEditingNews' => [],
        ],
        'tagsCount'                  => [
            'tagsCount'              => [],
            'usedTagsCount'          => [],
            'usedTagsPublishedCount' => [],
        ],
    ];

    public function showForm()
    {
        return view('/admin/statisticReport', [
            'skeleton' => $this->statisticFormSkeleton,
        ]);
    }

    public function makeReport(Request $request)
    {
        $result    = [];
        foreach ($request->all() as $key => $val) {
            if (key_exists($key, $this->statisticFormSkeleton)) {
                $result[$key] = $this->statisticFormSkeleton[$key];
            }
        }
        StatisticReport::dispatch(Auth::id(), $result)
            ->onQueue('reports')
            ->delay(now()->addSeconds(20));
        return redirect('/admin/report');
    }
}
