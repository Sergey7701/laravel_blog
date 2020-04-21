<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\NewsletterAboutNewArticles as NewsLetter;

class NewsletterAboutNewArticles extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:NewsletterAboutNewArticles {interval}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Рассылка уведомлений пользователям о новых статьях';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $articles  = \App\Models\Article::where('created_at', '>', (new Carbon)->subDays($this->argument('interval')))->get();
        \App\User::all()->map(function($user) {
            $user->notify(new NewsLetter($articles));
        });
    }
}
