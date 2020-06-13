<?php
namespace App\Jobs;

use App\Events\StatisticReport as StatisticReportCast;
use App\Helpers\StatisticGenerator;
use App\Notifications\StatisticReport as StatisticMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;;

class StatisticReport implements ShouldQueue
{

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $userId;
    protected $statistic;
    protected $statisticGenerator;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $userId, array $statistic, $statisticGenerator)
    {
        $this->userId = $userId;
        $this->statisticGenerator = $statisticGenerator;
        //Вставляем текст в заготовку отчёта.
        \App::setLocale('ru');
        foreach ($statistic as $key => $val) {
            foreach ($val as $func => $unused) {
                $this->statistic[$key][ __('statisticReport.' . $func)] = $func;
            }
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->statistic as $key => $val) {
            foreach ($val as $text => $func) {
                if (method_exists($this->statisticGenerator, $func)) {
                    $this->statistic[$key][$text] = $this->statisticGenerator->$func();
                } else {
                    $this->statistic[$key][$text] = '!!! Ошибка !!!';
                }
            }
        }
        event(new StatisticReportCast($this->statistic, $this->userId));
        (User::whereId($this->userId)->first())->notify(new StatisticMail($this->statistic));
    }
}
