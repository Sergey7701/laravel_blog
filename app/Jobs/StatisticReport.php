<?php
namespace App\Jobs;

use App\Events\StatisticReport as StatisticReportCast;
use App\Notifications\StatisticReport as StatisticMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
    public function __construct(int $userId, array $statistic, string $statisticGenerator)
    {
        $this->userId             = $userId;
        $this->statistic          = $statistic;
        $this->statisticGenerator = $statisticGenerator;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Только так, иначе сессия в конструкторе StatisticGenerator не включится и фильтр на publish не сработает
        $statisticGenerator = new $this->statisticGenerator;
        foreach ($this->statistic as $key => $val) {
            foreach ($val as $text => $func) {
                if (method_exists($statisticGenerator, $func)) {
                    $this->statistic[$key][$text] = $statisticGenerator->$func();
                } else {
                    $this->statistic[$key][$text] = '!!! Ошибка !!!';
                }
            }
        }
        event(new StatisticReportCast($this->statistic, $this->userId));
        (User::whereId($this->userId)->first())->notify(new StatisticMail($this->statistic, $this->makeCsv($this->statistic)));
    }

    private function makeCsv($result, $d = ",", $q = '"')
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
        return substr($line, 0, -1);
        ;
    }
}
