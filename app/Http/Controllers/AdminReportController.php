<?php
namespace App\Http\Controllers;

use App\Jobs\StatisticReport as StatisticReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\StatisticGenerator;

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
            'skeleton' => $this->insertTextIntoSkeleton($this->statisticFormSkeleton),
        ]);
    }

    public function makeReport(Request $request)
    {
        $result = [];
        foreach ($request->all() as $key => $val) {
            if (key_exists($key, $this->statisticFormSkeleton)) {
                $result[$key] = $this->statisticFormSkeleton[$key];
            }
        }
        StatisticReport::dispatch(Auth::id(), $this->insertTextIntoSkeleton($result), StatisticGenerator::class)
            ->onQueue('reports')
            ->delay(now()->addSeconds(20));
        return redirect('/admin/report');
    }

    private function insertTextIntoSkeleton($skeleton)
    {
        //Вставляем текст в заготовку отчёта.
        \App::setLocale('ru');
        $formText = [];
        foreach ($skeleton as $key => $val) {
            foreach ($val as $func => $unused) {
                $formText[$key][__('statisticReport.' . $func)] = $func;
            }
        }
        return $formText;
    }

    private function fputcsv($result, $d = ",", $q = '"')
    {
        $line = "";
        foreach ($result as $list) {
            foreach ($list as $text => $func) {
                # remove any windows new lines,
                # as they interfere with the parsing at the other end
                $temp1  = str_replace("\r\n", "\n", $text);
                # if a deliminator char, a double quote char or a newline
                # are in the field, add quotes
                $field1 = (preg_match("/[$d$q\n\r]/", $temp1)) ?
                    $q . str_replace($q, $q . $q, $field1) . $q : $temp1;
                $temp2  = str_replace("\r\n", "\n", $func);
                $field2 = (preg_match("/[$d$q\n\r]/", $temp2)) ?
                    $q . str_replace($q, $q . $q, $field1) . $q : $temp2;
                $line   .= $field1 . $d . $field2 . "\r\n";
            }
        }
        # strip the last deliminator
        $line = substr($line, 0, -1);
        return $line;
    }
}
