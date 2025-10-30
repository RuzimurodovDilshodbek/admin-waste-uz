<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ManagementPerson;
use App\Models\Post;
use App\Models\PostView;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /api/v1/posts

    protected $availableLanguages;

    public function __construct()
    {
        $this->availableLanguages = ['uz', 'en', 'kr', 'ru'];
        $this->columns = function($lang) {
            return [
                'id',
                'slug_'.$lang.' as get_slug',
                'title_'.$lang.' as get_title',
                'section_ids',
                'publish_date',
                'description_'.$lang.' as get_description'
            ];
        };
    }


    public function index(Request $request)
    {
        $query = Post::query();

        // agar section_id yuborilsa filterlash
        if ($request->has('section_id')) {
            $query->whereJsonContains('section_ids', $request->section_id);
        }

        $posts = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }


    public function getManagementPersons(Request $request)
    {
        $query = ManagementPerson::query()
            ->where('status', 1)
            ->whereNull('deleted_at');

        if ($request->has('type')) {
            $query->where('type',$request->type);
        }

        $management = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $management
        ]);

    }

    public function getPostId(Request $request, $id){

        $request_lang = $request->header('Accept-Language', 'uz');
        if (!in_array($request_lang, $this->availableLanguages)) {
            $request_lang = 'uz';
        }

        $columns = call_user_func($this->columns, $request_lang);

        $post = Post::query()
            ->with("tags","editor")
            ->where('posts.id',$id)
            ->where("posts.status", 1)
            ->whereNotNull('posts.title_'.$request_lang)
            ->select('id', 'slug_uz','slug_ru','slug_en','title_uz','title_ru','title_en', 'section_ids',
                'publish_date','description_uz','description_ru','description_en','content_uz','content_ru','content_en',
                'tutor_id','image_description_uz','image_description_ru','image_description_en')
            ->first();

        if ($post) {
            $post->makeHidden(['card_image', 'media', 'section_ids']);
            $ip = request()->ip();
            $postView = PostView::query()->where('post_id', $post->id)->where('ip', $ip)->orderBy("created_at", "DESC")->first();

            if (!empty($postView)){
                if (Carbon::parse($postView->created_at)->diffInMinutes(now())  > 1){
                    PostView::query()->create([
                        'ip' => $ip,
                        'post_id' => $post->id
                    ]);
                }
            } else {
                PostView::query()->create([
                    'ip' => $ip,
                    'post_id' => $post->id
                ]);
            }
        } else {
            return response()->errorJson('post not found', 404);
        }

        $resent_posts = Post::query()
            ->where('section_ids',$post->section_ids)
            ->orderBy("created_at", "DESC")->limit(6)
            ->get();

        $this->processPosts($resent_posts, $request_lang);



        $result = [
            'post' => $post,
            'resent_posts' => $resent_posts,
        ];

        if ($result['resent_posts']->isEmpty()) {
            return response()->errorJson('post not found', 404);
        } else {
            return response()->successJson(['data' => $result]);
        }
    }

    private function processPosts(&$posts, $request_lang)
    {
        foreach ($posts as $post) {
            $post['url'] = localized_url("get-post/{$post->id}");
            $post['photo'] = $post->detail_image?->url;
            $post['section_name'] = $post->section->{'title_'.$request_lang};
            $post['section_slug'] = $post->section->{'slug_'.$request_lang};
        }
        $posts->makeHidden(['detail_image', 'card_image', 'media', 'section_ids', 'section']);
    }
}
