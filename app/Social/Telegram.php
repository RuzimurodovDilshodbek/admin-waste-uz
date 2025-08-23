<?php

namespace App\Social;


use App\Models\Post;
use App\Models\PostsSendAutoSocialNetwork;
use Carbon\Carbon;
use GuzzleHttp\Client;

class Telegram
{
    private $telegramApiUrl = 'https://api.telegram.org/';
    const BOT_TOKEN = '7264898715:AAG1Mw8EzdDAVnjUfpWoE_1W03C8GEwaF58';
    const PROTSESS_CHANNEL_CHAT_ID = -1002173855360; // -1002173855360 rejalangan kontent asosiy
    const APPEAL_ChANNEL_CHAT_ID = -1002231599688; // -1002173855360 rejalangan kontent asosiy
    private $client;
    private $chat_id;

    public function __construct($chat = 'post')
    {
        $this->chat_id = $chat === 'appeal' ? self::APPEAL_ChANNEL_CHAT_ID : self::PROTSESS_CHANNEL_CHAT_ID;
        $this->client = new Client([
            'base_uri' => $this->telegramApiUrl . 'bot' . self::BOT_TOKEN . '/',
        ]);
    }

    public function setWebhook() {
        $result = $this->client->post( __FUNCTION__, [
            'query' => [
                'url' => env('APP_URL') . self::BOT_TOKEN
            ]
        ]);
        echo $result->getBody() . PHP_EOL;
    }

    public function getUpdates() {
        $result = $this->client->post( __FUNCTION__);
        echo $result->getBody() . PHP_EOL;
    }

    public function sendMessage($message) {
        $this->client->post( __FUNCTION__, [
            'query' => [
                'chat_id' => $this->chat_id,
                'text' => $message,
                'parse_mode' => 'HTML'
            ]
        ]);
    }
    public function sendPost($post_id) {
        $post = Post::find($post_id);
        try {
            $media = $post->getMedia('detail_image')->last();

            $filePath = storage_path('app/public/' . $media->id. '/' . $media->file_name); // Faylni to'liq yo'li
            $fileContent = file_get_contents($filePath); // Fayl mazmunini oladi

            $this->client->post('sendPhoto', [
                'multipart' => [
                    [
                        'name'     => 'chat_id',
                        'contents' => $this->chat_id,
                    ],
                    [
                        'name'     => 'caption',
                        'contents' => "<b>$post->title_uz</b> \n\n<b>Batafsil o‘qing:👉</b> https://protsess.uz/$post->id \n\n<a href='https://protsess.uz'>Sayt</a> | <a href='https://t.me/protsess_uz'>Telegram</a> | <a href='https://www.facebook.com/profile.php?id=61564617316685&mibextid=ZbWKwL'>Facebook</a> | <a href='https://www.youtube.com/@Protsess_uz'>Youtube</a> | <a href='http://instagram.com/protsess_uz'>Instagram</a> | <a href='http://x.com/protsess_uz'>X</a> | <a href='https://t.me/protsessmedia'>Aloqa</a>",
                    ],
                    [
                        'name'     => 'parse_mode',
                        'contents' => 'HTML',
                    ],
                    [
                        'name'     => 'photo',
                        'contents' => $fileContent,
                        'filename' => $media->file_name
                    ],
                ],
            ]);
            if ($auto_send_post = PostsSendAutoSocialNetwork::query()->where('post_id',$post->id)->first()){
                $auto_send_post->update([
                    'is_send_telegram' => 1,
                    'telegram_send' => 1
                ]);
            } else {
                PostsSendAutoSocialNetwork::create([
                    'post_id' => $post->id,
                    'publish_date' => $post->publish_date,
                    'is_send_telegram' => 1,
                    'telegram_send' => 1
                ]);
            }

        } catch (\Exception $e) {
            if ($auto_send_post = PostsSendAutoSocialNetwork::query()->where('post_id',$post->id)->first()){
                $auto_send_post->update([
                    'is_send_telegram' => 0,
                    'telegram_send' => 1
                ]);
            } else {
                PostsSendAutoSocialNetwork::create([
                    'post_id' => $post->id,
                    'publish_date' => $post->publish_date,
                    'is_send_telegram' => 0,
                    'telegram_send' => 1
                ]);
            }

            return 'false';
        }

    }

    public function scheduleSendPosts() {
        $auto_posts = PostsSendAutoSocialNetwork::query()->where('telegram_send',1)->where('is_send_telegram',0)->get();
        foreach ($auto_posts as $auto_post) {
            if (Carbon::parse($auto_post->publish_date)->format('Y-m-d H:i:s') < Carbon::parse()->format('Y-m-d H:i:s')) {
                $this->sendPost($auto_post->post_id);
            }
        }
    }

    public function sendDbBackup() {
        $this->client->post( 'sendDocument', [
            'multipart' => [
                [ 'name' => 'chat_id', 'contents' => self::DB_BACKUP_CHAT_ID],
                [
                    'name' => 'document',
                    'contents' => fopen(storage_path('backups/' .date('d-m-Y').'-alosmartdb4.sql'), 'r')
                ]
            ]
        ]);
    }
}
