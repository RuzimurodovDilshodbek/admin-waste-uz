<?php

namespace App\Http\Controllers\Api;

use App\Models\BannerPost;
use App\Models\ExchangeRate;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Quotation;
use App\Models\Section;
use App\Models\Tag;
use App\Models\Tutor;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Social\Telegram;
use Carbon\Carbon;
use http\Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    protected $availableLanguages;
    protected $columns;

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
                'youtube_link',
                'views_count',
                'description_'.$lang.' as get_description'
            ];
        };
    }

    public function getNewsHome(Request $request)
    {
        $request_lang = $request->header('Accept-Language', 'uz');
        if (!in_array($request_lang, $this->availableLanguages)) {
            $request_lang = 'uz';
        }

        $columns = call_user_func($this->columns, $request_lang);

        $usedIds = [];

        $posts_saylov = Post::query()->where('section_ids', 8)->whereNotNull('title_' . $request_lang)->whereNotIn('id', $usedIds)
            ->orderBy("id", "DESC")->select($columns)->limit(4)->get();

        $usedIds = array_merge($usedIds, $posts_saylov->pluck('id')->toArray());

        $recommendedPosts = Post::query()
            ->where('recommended', 1)
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("created_at", "DESC")
            ->select($columns)
            ->limit(9)
            ->get();
        $usedIds = array_merge($usedIds, $recommendedPosts->pluck('id')->toArray());

        $newsResent = Post::query()
            ->whereIn('section_ids', [1,9,10])
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("id", "DESC")
            ->select($columns)
            ->limit(6)
            ->get();

        $usedIds = array_merge($usedIds, $newsResent->pluck('id')->toArray());

        $analysisPosts = Post::query()
            ->where('section_ids', 6)
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("id", "DESC")
            ->select($columns)
            ->limit(6)
            ->get();

        $usedIds = array_merge($usedIds, $analysisPosts->pluck('id')->toArray());


        $mediaPosts = Post::query()
            ->where('section_ids', 14)
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("id", "DESC")
            ->select($columns)
            ->limit(6)
            ->get();

        $usedIds = array_merge($usedIds, $mediaPosts->pluck('id')->toArray());


        $sportPosts = Post::query()
            ->where('section_ids', 5)
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("id", "DESC")
            ->select($columns)
            ->limit(4)
            ->get();

        $usedIds = array_merge($usedIds, $sportPosts->pluck('id')->toArray());

        $politicsPosts = Post::query()
            ->where('section_ids', 2)
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("id", "DESC")
            ->select($columns)
            ->limit(4)
            ->get();

        $usedIds = array_merge($usedIds, $politicsPosts->pluck('id')->toArray());


        $economyPosts = Post::query()
            ->where('section_ids', 4)
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("id", "DESC")
            ->select($columns)
            ->limit(4)
            ->get();

        $usedIds = array_merge($usedIds, $economyPosts->pluck('id')->toArray());


        $societyPosts = Post::query()
            ->where('section_ids', 3)
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("id", "DESC")
            ->select($columns)
            ->limit(4)
            ->get();

        $usedIds = array_merge($usedIds, $societyPosts->pluck('id')->toArray());

        $culturePosts = Post::query()
            ->where('section_ids', 11)
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("id", "DESC")
            ->select($columns)
            ->limit(4)
            ->get();

        $usedIds = array_merge($usedIds, $culturePosts->pluck('id')->toArray());

        $technologyPosts = Post::query()
            ->where('section_ids', 12)
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("id", "DESC")
            ->select($columns)
            ->limit(4)
            ->get();

        $usedIds = array_merge($usedIds, $technologyPosts->pluck('id')->toArray());


        // Hamma ro'yxatlar uchun umumiy funktsiya
        $this->processPosts($societyPosts, $request_lang);
        $this->processPosts($economyPosts, $request_lang);
        $this->processPosts($politicsPosts, $request_lang);
        $this->processPosts($sportPosts, $request_lang);
        $this->processPosts($analysisPosts, $request_lang);
        $this->processPosts($posts_saylov, $request_lang);
        $this->processPosts($recommendedPosts, $request_lang);
        $this->processPosts($newsResent, $request_lang);
        $this->processPosts($culturePosts, $request_lang);
        $this->processPosts($technologyPosts, $request_lang);
        $this->processPosts($mediaPosts, $request_lang);



        $mostReadPosts = Post::query()
            ->whereNotNull('title_' . $request_lang)
            ->whereNotIn('id', $usedIds)
            ->orderBy("views_count", "DESC")
            ->select($columns)
            ->limit(4)
            ->get();

        $this->processPosts($mostReadPosts, $request_lang);

        $result = [
            'postsSaylov' => $posts_saylov,
            'recommendedPosts' => $recommendedPosts,
            'newsResent' => $newsResent,
            'mostReadPosts' => $mostReadPosts,
            'analysisPosts' => $analysisPosts,
            'sportPosts' => $sportPosts,
            'politicsPosts' => $politicsPosts,
            'economyPosts' => $economyPosts,
            'societyPosts' => $societyPosts,
            'culturePosts' => $culturePosts,
            'technologyPosts' => $technologyPosts,
            'mediaPosts' => $mediaPosts


        ];

        if ($result['postsSaylov']->isEmpty() &&
            $result['recommendedPosts']->isEmpty() &&
            $result['newsResent']->isEmpty() &&
            $result['mostReadPosts']->isEmpty()) {
            return response()->errorJson('post not found', 404);
        }

        return response()->successJson(['data' => $result]);
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
            ->select('id', 'slug_'.$request_lang.' as get_slug','title_'.$request_lang.' as get_title', 'section_ids',
                'publish_date', 'views_count','youtube_link','description_'.$request_lang.' as get_description','content_'.$request_lang.' as get_content','tutor_id','image_description_'.$request_lang.' as get_image_description',

                )
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

        $most_read_posts = Post::query()
            ->where('section_ids',$post->section_ids)
            ->orderBy("views_count", "DESC")->limit(4)
            ->select($columns)
            ->get();

        $resent_posts = Post::query()
            ->where('section_ids',$post->section_ids)
            ->orderBy("created_at", "DESC")->limit(6)
            ->select($columns)
            ->get();

        $this->processPosts($most_read_posts, $request_lang);
        $this->processPosts($resent_posts, $request_lang);

        $post->update([
            'views_count' => 1 + $post->views_count
        ]);

        $postClone = clone $post;
        if (isset($post->youtube_link)) {

            $postClone->youtube_link = 'https://www.youtube.com/embed/' . getYouTubeVideoId($post->youtube_link);
        }

        $result = [
            'post' => $postClone,
            'mostReadPosts' => $most_read_posts,
            'resent_posts' => $resent_posts,
        ];

        if ($result['mostReadPosts']->isEmpty() &&
            $result['resent_posts']->isEmpty()) {
            return response()->errorJson('post not found', 404);
        } else {
            return response()->successJson(['data' => $result]);
        }
    }

    public function getCategoryId(Request $request, $id) {
        $request_lang = $request->header('Accept-Language', 'uz');
        if (!in_array($request_lang, $this->availableLanguages)) {
            $request_lang = 'uz';
        }

        $columns = call_user_func($this->columns, $request_lang);


        $categorySectionIds = Section::query()->where('id', $id)->orWhere('parent_id',$id)->pluck('id');


        $categoryPosts = Post::query()->whereIn('section_ids',$categorySectionIds)
            ->whereNotNull('title_'.$request_lang)
            ->orderBy("publish_date", "DESC")
            ->select($columns)
            ->paginate(8);

        $this->processPosts($categoryPosts, $request_lang);

        $mostReadPosts = Post::query()
            ->whereIn('section_ids',$categorySectionIds)
            ->whereNotNull('title_'.$request_lang)
            ->orderBy("views_count", "DESC")->limit(4)
            ->select($columns)
            ->get();

        $this->processPosts($mostReadPosts, $request_lang);

        if ($categoryPosts->isEmpty()) {
            return response()->errorJson('Post not found', 404);
        }

        $result = [
            'categoryPosts' => $categoryPosts,
            'mostReadPosts' => $mostReadPosts,
        ];

        return response()->successJson(['data' => $result]);

    }

    public function getSearch(Request $request)
    {
        $search = request()->search;

        $request_lang = $request->header('Accept-Language', 'uz');
        if (!in_array($request_lang, $this->availableLanguages)) {
            $request_lang = 'uz';
        }

        $columns = call_user_func($this->columns, $request_lang);

        $posts = Post::query()
            ->where("status", 1)
            ->whereNotNull('title_'.$request_lang)
            ->where("content_uz", 'like', '%'.$search.'%')
            ->Orwhere("content_kr", 'like', '%'.$search.'%')
            ->Orwhere("content_ru", 'like', '%'.$search.'%')
            ->Orwhere("content_en", 'like', '%'.$search.'%')
            ->Orwhere("title_uz", 'like', '%'.$search.'%')
            ->Orwhere("title_ru", 'like', '%'.$search.'%')
            ->Orwhere("title_kr", 'like', '%'.$search.'%')
            ->Orwhere("title_en", 'like', '%'.$search.'%')
            ->orderBy("publish_date", "DESC")
            ->select($columns)
            ->paginate(10);

        $this->processPosts($posts, $request_lang);

        $resent_posts = Post::query()
            ->orderBy("created_at", "DESC")->limit(6)
            ->select($columns)
            ->get();

        $this->processPosts($resent_posts, $request_lang);

        if ($posts->isEmpty()) {
            return response()->errorJson('Post not found', 404);
        }
        $result = [
            'posts' => $posts,
            'resent_posts' => $resent_posts,
        ];

        return response()->successJson(['data' => $result]);
    }

    public function getTags(Request $request, $id){

        $request_lang = $request->header('Accept-Language', 'uz');
        if (!in_array($request_lang, $this->availableLanguages)) {
            $request_lang = 'uz';
        }

        $tag = Tag::query()->where("id", $id)->select('title_'.$request_lang.' as get_title','id')->first();

        if (empty($tag)) {
            return response()->errorJson('Tag not found', 404);
        }

        $columns = call_user_func($this->columns, $request_lang);


        $posts = Post::query()
            ->leftJoin('post_tag', 'posts.id', '=', 'post_tag.post_id')
            ->leftJoin('tags', 'tags.id', '=', 'post_tag.tag_id')
            ->where("post_tag.tag_id", $tag->id)
 	        ->whereNotNull('posts.title_'.$request_lang)
            ->where("posts.status", 1)
            ->orderBy("posts.created_at", "DESC")
            ->select('posts.id',
                'posts.slug_'.$request_lang.' as get_slug',
                'posts.title_'.$request_lang.' as get_title',
                'posts.section_ids',
                'posts.publish_date',
                'posts.views_count',
                'posts.description_'.$request_lang.' as get_description')
            ->paginate(10);

        foreach ($posts as $post) {
            $post['url'] = localized_url("get-post/{$post->id}");
            $post['photo'] = $post->detail_image ?->url;
            $post['section_name'] = $post->section->{'title_' . $request_lang};
            $post['section_slug'] = $post->section->{'slug_' . $request_lang};
        }

        $posts->makeHidden(['detail_image', 'card_image', 'media', 'section_ids', 'section']);

        $mostReadPosts = Post::query()
            ->whereNotNull('title_'.$request_lang)
            ->orderBy("views_count", "DESC")->limit(4)
            ->select($columns)
            ->get();

        $this->processPosts($mostReadPosts, $request_lang);


        if ($posts->isEmpty()) {
            return response()->errorJson('Post not found', 404);
        }
        $result = [
            'posts' => $posts,
            'mostReadPosts' => $mostReadPosts,
            'tag' => $tag,
        ];

        return response()->successJson(['data' => $result]);
    }

    public function getVideoPage(Request $request) {
        $request_lang = $request->header('Accept-Language', 'uz');
        if (!in_array($request_lang, $this->availableLanguages)) {
            $request_lang = 'uz';
        }

        $columns = call_user_func($this->columns, $request_lang);

        $videoList = Post::query()
            ->whereNotNull('youtube_link')
            ->whereNotNull('title_' . $request_lang)
            ->orderBy("created_at", "DESC")
            ->select($columns)
            ->limit(9)
            ->get();
        $this->processPosts($videoList, $request_lang);

        $result = [
            'posts' => $videoList
        ];

        return response()->successJson(['data' => $result]);
    }
    public function getPhotoPage(Request $request) {
        $request_lang = $request->header('Accept-Language', 'uz');
        if (!in_array($request_lang, $this->availableLanguages)) {
            $request_lang = 'uz';
        }

        $columns = call_user_func($this->columns, $request_lang);

        $photoList = Post::query()
            ->where('section_ids',13)
            ->whereNotNull('title_' . $request_lang)
            ->orderBy("created_at", "DESC")
            ->select($columns)
            ->limit(9)
            ->get();
        $this->processPosts($photoList, $request_lang);

        $result = [
            'posts' => $photoList
        ];

        return response()->successJson(['data' => $result]);
    }

    public function getMostUsedTags(Request $request)
    {
        $request_lang = $request->header('Accept-Language', 'uz');
        if (!in_array($request_lang, $this->availableLanguages)) {
            $request_lang = 'uz';
        }
        $limit = $request->input('limit', 10); // Ko'rsatiladigan taglar sonini belgilash, default 10 ta.
        $sectionIds = $request->input('section_ids'); // section_id ni olish

        if ($sectionIds) {
            $tags = Tag::query()
                ->select('tags.id', 'tags.title_'.$request_lang, DB::raw('COUNT(post_tag.tag_id) as usage_count'))
                ->join('post_tag', 'tags.id', '=', 'post_tag.tag_id')
                ->join('posts', 'posts.id', '=', 'post_tag.post_id')
                ->when($sectionIds, function ($query) use ($sectionIds) {
                    return $query->where('posts.section_ids', $sectionIds); // section_ids JSON maydoni bo'yicha filtr
                })
                ->groupBy('tags.id', 'tags.title_'.$request_lang)
                ->orderBy('usage_count', 'DESC')
                ->limit($limit)
                ->get();
        } else {
            $tags = Tag::query()
                ->select('tags.id','tags.title_'.$request_lang, DB::raw('COUNT(post_tag.tag_id) as usage_count'))
                ->join('post_tag', 'tags.id', '=', 'post_tag.tag_id')
                ->groupBy('tags.id')
                ->orderBy('usage_count', 'DESC')
                ->limit($limit)
                ->get();
        }


        if ($tags->isEmpty()) {
            return response()->errorJson('No tags found for this section', 404);
        }

        return response()->successJson(['data' => $tags]);
    }

// Hamma post ro'yxatlari uchun umumiy funktsiya
    private function processPosts(&$posts, $request_lang)
    {
        foreach ($posts as $post) {
        $post['url'] = localized_url("get-post/{$post->id}");
        $post['photo'] = $post->detail_image?->url;
        $post['section_name'] = $post->section->{'title_'.$request_lang};
        $post['section_slug'] = $post->section->{'slug_'.$request_lang};
        if ($post->youtube_link) {
            $post['youtube_url'] = 'https://www.youtube.com/embed/' . getYouTubeVideoId($post->youtube_link);
        }
    }
        $posts->makeHidden(['detail_image', 'card_image', 'media', 'section_ids', 'section']);
    }

    public function sendAppeal(Request $request) {

        $validator = $this->sendAppealToValidate($request->all());
        if (!$validator->fails()) {

            try {
                $telegram = new Telegram('appeal');

                $text = "<b>Ism familiyasi: </b>" .$request->name. " \n <b>Telefon raqami: </b>". $request->phone. " \n <b>Murojaat matni :</b> ". $request->content;

                $telegram->sendMessage($text);
                return response()->successJson('Send to appeal');
            } catch (Exception $e) {
                return response()->errorJson('Serverning ichki xatoligi', 400, $e->getMessage() );
            }

        } else {
            $errors = $validator->failed();
            return response()->errorJson('Fill in the fields', 400, $errors);

        }

    }

    public function sendAppealToValidate($array, $status = null)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:7','max:15'],
            'content' => ['required','string', 'max:1024']
        ];

        $validator = Validator::make($array, $rules);

        return $validator;
    }



    public function getExchangeRate() {
        $exchangeRate = ExchangeRate::query()->orderBy("id", "DESC")->select('USD','EUR','RUB')->first();

        return response()->successJson(['data' => $exchangeRate]);
    }

    public function getEditorPosts(Request $request, $id){
        $request_lang = $request->header('Accept-Language', 'uz');
        if (!in_array($request_lang, $this->availableLanguages)) {
            $request_lang = 'uz';
        }

        $columns = call_user_func($this->columns, $request_lang);

        $editor = Tutor::query()->where("id", $id)->select('first_name_'.$request_lang.' as get_first_name','last_name_'.$request_lang.' as get_last_name','about_'.$request_lang.' as get_about','id')->first();

        if (empty($editor)) {
            return response()->errorJson('Editor not found', 404);
        }

        $editorPosts = Post::query()->where('tutor_id',$editor->id);


        $editor['count_posts'] = $editorPosts->count();
        $editor['all_ready_posts'] = $editorPosts->sum('views_count');
        $editor['main_photo'] = $editor->photo?->url;
        $editor->makeHidden(['photo','media']);


        $mostReadPosts = $editorPosts
            ->whereNotNull('title_'.$request_lang)
            ->orderBy("views_count", "DESC")->limit(16)
            ->select($columns)
            ->get();

        $this->processPosts($mostReadPosts, $request_lang);


        $result = [
            'editor' => $editor,
            'mostReadPosts' => $mostReadPosts,
        ];
        return response()->successJson(['data' => $result]);

    }
}
