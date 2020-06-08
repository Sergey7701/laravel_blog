<?php
namespace App\Http\Controllers;

use App\Helpers\HelperStatistic;
use App\Jobs\StatisticReport as StatisticReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{

    public function showForm()
    {
        return view('/admin/statisticReport', [
            'skeleton' => HelperStatistic::$statisticFormSkeleton,
        ]);
    }

    public function makeReport(Request $request)
    {
        $statistic = HelperStatistic::$statisticFormSkeleton;
        $result    = [];
        foreach ($request->all() as $key => $val) {
            if (key_exists($key, $statistic)) {
                $result[$key] = $statistic[$key];
            }
        }
        StatisticReport::dispatch(Auth::id(), $result)->onQueue('reports')->delay(now()->addSeconds(2));
        return redirect('/admin/report');
    }
}
