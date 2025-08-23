<?php

namespace App\Console\Commands;

use App\Social\Telegram;
use Illuminate\Console\Command;
use Goutte\Client;

class TelegramCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tg:sendPost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        dd('kelli');
        $t = new Telegram();
        $t->sendPost(1);
//        $method = $this->argument('method');
        $post_id = $this->argument('post_id');
//
//        dd($t->$method($post_id ?? 99));

    }
}
