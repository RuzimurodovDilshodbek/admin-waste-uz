<?php

namespace App\Social;


use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\Post;
use App\Models\PostsSendAutoSocialNetwork;
use App\Social\Facebook\Exceptions\FacebookSDKException;
use App\Social\Facebook\Facebook;
use App\Social\Codebird\Codebird;
use App\Social\Facebook\Exceptions\FacebookResponseException;
use Carbon\Carbon;

class ApiManager
{
    public $fb_conf;
    public $tw_conf;

    public function __construct()
    {

        $this->tw_conf = array(
            'CONSUMER_KEY'       => 'Iawv3zoJnsMi7TM6zvm3L3nyy', // https://apps.twitter.com/app/29298176/keys  //R2F5OWRyeWVqclI4OWtTTmFzNE86MTpjaQ
            'CONSUMER_SECRET'    => 'MYJiZDEh95cKA4ZRBV0RBAgIRmGYOR2unbhQtlxOR96F85mG25',  //wz4GIhiJTV2YCyjVnmkaANervSIEh6FhAntagmv7AjQVUVAs0S
            'ACCESS_TOKEN'  => '1829172239247519748-KTdJM9dsyK4JKoti2sGhwCluXlz84s',
            'TOKEN_SECRET'  => 'fzgqbasasoDnUsVsrEBdLVwnrrbtzBMuj2HQwnp8qkYUu',
            'BEARER_TOKEN' => 'AAAAAAAAAAAAAAAAAAAAAAAOvwEAAAAAyWNfMf19vM33MtJrkAFgFGDT5Kg%3DivVQESRF5suLoHhkg09Lp6eda57dBKTRmH67pDpNueluYOx6pa'
        );


    }

    public function fbSendPost($post_id = null)
    {

        if (!$post_id) {
            return false;
        }
        $post = Post::find($post_id);

        $fb = new Facebook([
            'app_id' => $this->fb_conf['API_ID'],
            'app_secret' => $this->fb_conf['API_SECRET'],
            'default_graph_version' => $this->fb_conf['API_VER'],
        ]);

        $linkData['message'] = $post->title_kr;
        $linkData['message'] .= "\n\n" . strip_tags($post->description_kr);
        $linkData['link'] = 'https://protsess.uz/'.$post->id;

        $path = '/feed';

        $pageAccessToken = $this->fb_conf['ACCESS_TOKEN'];

        try {
            $response = $fb->post('/'.$this->fb_conf['PAGE_ID'].$path, $linkData, $pageAccessToken);
            $result =  $response->getGraphNode();

            if ($auto_send_post = PostsSendAutoSocialNetwork::query()->where('post_id',$post->id)->first()){
                $auto_send_post->update([
                    'is_send_facebook' => 1,
                    'facebook_send' => 1
                ]);
            } else {
                PostsSendAutoSocialNetwork::create([
                    'post_id' => $post->id,
                    'publish_date' => $post->publish_date,
                    'is_send_facebook' => 1,
                    'facebook_send' => 1
                ]);
            }
            return  $result;
        }
        catch(FacebookResponseException $e) {
            $e_msg = 'Graph returned an error: '.$e->getMessage();
            $this->log_errors('facebook', $e_msg);
            if ($auto_send_post = PostsSendAutoSocialNetwork::query()->where('post_id',$post->id)->first()){
                $auto_send_post->update([
                    'is_send_facebook' => 0,
                    'facebook_send' => 1
                ]);
            } else {
                PostsSendAutoSocialNetwork::create([
                    'post_id' => $post->id,
                    'publish_date' => $post->publish_date,
                    'is_send_facebook' => 0,
                    'facebook_send' => 1
                ]);
            }
            return FALSE;
        }
        catch(FacebookSDKException $e) {
            $e_msg = 'Facebook SDK returned an error: '.$e->getMessage();
            $this->log_errors('facebook', $e_msg);
            if ($auto_send_post = PostsSendAutoSocialNetwork::query()->where('post_id',$post->id)->first()){
                $auto_send_post->update([
                    'is_send_facebook' => 0,
                    'facebook_send' => 1
                ]);
            } else {
                PostsSendAutoSocialNetwork::create([
                    'post_id' => $post->id,
                    'publish_date' => $post->publish_date,
                    'is_send_facebook' => 0,
                    'facebook_send' => 1
                ]);
            }
            return FALSE;
        }
    }

    public function scheduleFacebookSendPosts() {
        return false;
//        $auto_posts = PostsSendAutoSocialNetwork::query()->where('facebook_send',1)->where('is_send_facebook',0)->get();
//
//        foreach ($auto_posts as $auto_post) {
//            if (Carbon::parse($auto_post->publish_date)->format('Y-m-d H:i:s') < Carbon::parse()->format('Y-m-d H:i:s')) {
//                $this->fbSendPost($auto_post->post_id);
//            }
//        }
    }

    public function twSendPost($post_id = null) {

        if (!$post_id) {
            return false;
        }
        $post = Post::find($post_id);
        $message = $post->title_uz;
        $message .= "\n\n" . 'https://protsess.uz/'.$post->id;
        try {
            $connection = new TwitterOAuth($this->tw_conf['CONSUMER_KEY'], $this->tw_conf['CONSUMER_SECRET'], $this->tw_conf['ACCESS_TOKEN'],$this->tw_conf['TOKEN_SECRET']);

            $connection->post("tweets", ["text" => $message]);

            if ($auto_send_post = PostsSendAutoSocialNetwork::query()->where('post_id',$post->id)->first()){
                $auto_send_post->update([
                    'is_send_twitter' => 1,
                    'twitter_send' => 1
                ]);
            } else {
                PostsSendAutoSocialNetwork::create([
                    'post_id' => $post->id,
                    'publish_date' => $post->publish_date,
                    'is_send_twitter' => 1,
                    'twitter_send' => 1
                ]);
            }
        }
        catch(\Exception $e){

            if ($auto_send_post = PostsSendAutoSocialNetwork::query()->where('post_id',$post->id)->first()){
                $auto_send_post->update([
                    'is_send_twitter' => 0,
                    'twitter_send' => 1
                ]);
            } else {
                PostsSendAutoSocialNetwork::create([
                    'post_id' => $post->id,
                    'publish_date' => $post->publish_date,
                    'is_send_twitter' => 0,
                    'twitter_send' => 1
                ]);
            }
        }
    }

    public function scheduleTwitterSendPosts() {
        $auto_posts = PostsSendAutoSocialNetwork::query()->where('twitter_send',1)->where('is_send_twitter',0)->get();

        foreach ($auto_posts as $auto_post) {
            if (Carbon::parse($auto_post->publish_date)->format('Y-m-d H:i:s') < Carbon::parse()->format('Y-m-d H:i:s')) {
                $this->twSendPost($auto_post->post_id);
            }
        }
    }

    public function log_errors($api, $error)
    {
        $curdate = date('d.m.Y h:i:s');
        $myFile = 'API-LOGS/api_error_log.txt';
        $fh = fopen($myFile) or die("can't open file");
        fwrite($fh, $curdate.' - '.$api.': '.$error."\n");
        fclose($fh);
    }
}
