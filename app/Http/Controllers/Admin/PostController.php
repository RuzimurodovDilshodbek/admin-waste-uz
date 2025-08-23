<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\BannerPost;
use App\Models\Post;
use App\Models\PostsSendAutoSocialNetwork;
use App\Models\Section;
use App\Models\Tag;
use App\Models\Tutor;
use App\Social\ApiManager;
use App\Social\Telegram;
use Carbon\Carbon;
use Gate;
use http\Params;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use function Laravel\Prompts\select;

class PostController extends Controller
{
    use MediaUploadingTrait;

    protected $homeController;

    public function __construct()
    {
        $this->homeController = new HomeController();
    }
    public function index()
    {
        abort_if(Gate::denies('post_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $posts = Post::with(['tags', 'media','tutor'])
            ->groupBy('posts.id')
            ->orderBy("publish_date", "DESC")
            ->paginate(30);

        return view('admin.posts.index', compact('posts'));
    }

    public function archived()
    {
        abort_if(Gate::denies('post_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $posts = Post::with(['tags', 'media','tutor'])
            ->where('posts.status',2)
            ->groupBy('posts.id')
            ->orderBy("publish_date", "DESC")
            ->paginate(30);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        abort_if(Gate::denies('post_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $section_parent_ids = Section::whereIn('id',Section::pluck('parent_id'))->pluck('id');
        $sections = Section::whereNotIn('id',$section_parent_ids)->pluck('title_kr', 'id');

        $tags = Tag::pluck('title_kr', 'id');

        $tutors = Tutor::pluck('first_name_kr', 'id')->prepend(trans('global.pleaseSelect'), '');

        $catTab = 0;
        $posts = Post::query()->where('status',1)->whereNull('deleted_at')->pluck('title_kr', 'id')->prepend(trans('global.pleaseSelect'), '');
        $locales = config('app.locales');

        return view('admin.posts.create', compact('sections', 'tags', 'catTab', 'locales', 'tutors','posts'));
    }

    public function store(StorePostRequest $request)
    {
//        if ($request->image_base64) {
//            [$imageName, $imageName2] = $this->storeBase64($request->image_base64);
//        }
        $audio_name = null;
        if($request->hasFile('audio_file')) {
            $Fileaudio = $request->file('audio_file');
            $audio_name = time() . $Fileaudio->getClientOriginalName();
            $audiopath =$Fileaudio->storeAs('public/audio/' . $audio_name);
        }

//        if($request->title_kr && (!$request->title_ru || !$request->title_en || !$request->title_uz)) {
//            foreach (config('app.locales') as $key_local => $value_local) {
//                if($value_local !== 'kr' && in_array($value_local, $request->langs)) {
//                    $to_latin = transliterateLatin($request->title_kr);
//                    $request['title_' . $value_local] = trsTitle($to_latin, $value_local);
//                }
//            };
//        }
//        if($request->description_kr && (!$request->description_ru || !$request->description_en || !$request->title_uz)) {
//            foreach (config('app.locales') as $key_local => $value_local) {
//                if($value_local !== 'kr' && in_array($value_local, $request->langs)) {
//                    $to_latin = transliterateLatin($request->description_kr);
//                    $request['description_' . $value_local] = trsTitle($to_latin, $value_local);
//                }
//            };
//        }

        $post = Post::create([
            'status' => $request->status,
            'recommended' => $request->recommended,

            'title_uz' => $request->title_uz,
            'title_kr' => $request->title_kr,
            'title_ru' => $request->title_ru,
            'title_en' => $request->title_en,
            'audio_file' => $audio_name,

            'description_uz' => $request->description_uz,
            'description_kr' => $request->description_kr,
            'description_ru' => $request->description_ru,
            'description_en' => $request->description_en,


            'content_uz' => $request->content_uz,
            'content_kr' => $request->content_kr,
            'content_ru' => $request->content_ru,
            'content_en' => $request->content_en,


            'image_description_uz' => $request->image_description_uz,
            'image_description_kr' => $request->image_description_kr,
            'image_description_ru' => $request->image_description_ru,
            'image_description_en' => $request->image_description_en,

            'section_ids' => $request->section_ids,
            'youtube_link' => $request->youtube_link,
            'langs' => $request->langs,
            'tutor_id' => $request->tutor_id,
            'publish_date' => $request->publish_date ? $request->publish_date : date("d.m.Y H:i:s")
        ]);

        if ($post->youtube_link) {
            $post->saveYouTubeThumbnail();
        }

        $tags = $request->input('tags', []);
        foreach ($tags as $index => $tag) {
            if ( !is_numeric($tag)) {
                if(Tag::query()->where('title_kr','like',$tag)->first()){
                    $tag_id = Tag::query()->where('title_kr','like',$tag)->first()->id;
                    $tags[$index] = $tag_id;
                }

            }
        }
        $post->tags()->sync($tags);
        if ($request->image) {
            $file = $request->file('image');
            $path = storage_path('tmp/uploads/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($path, $fileName);

            $post->addMedia($path . $fileName)->toMediaCollection('detail_image');
        }


        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $post->id]);
        }
        if ($request->telegram_send == '1') {
            if (Carbon::parse($post->publish_date)->format('Y-m-d H:i:s') < Carbon::parse()->format('Y-m-d H:i:s')) {
                try {
                    $tg = new Telegram();
                    $tg->sendPost($post->id);
                } catch (Exception $e) {};
            } else {
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
            }
        }

//        if ($request->facebook_send == '1') {
//            if (Carbon::parse($post->publish_date)->format('Y-m-d H:i:s') < Carbon::parse()->format('Y-m-d H:i:s')) {
//                try {
//                    $tg = new ApiManager();
//
//                    $tg->fbSendPost($post->id);
//
//                } catch (Exception $e) {};
//            } else {
//                if ($auto_send_post = PostsSendAutoSocialNetwork::query()->where('post_id',$post->id)->first()){
//                    $auto_send_post->update([
//                        'is_send_facebook' => 0,
//                        'facebook_send' => 1
//                    ]);
//                } else {
//                    PostsSendAutoSocialNetwork::create([
//                        'post_id' => $post->id,
//                        'publish_date' => $post->publish_date,
//                        'is_send_facebook' => 0,
//                        'facebook_send' => 1
//                    ]);
//                }
//            }
//        }

        if ($request->twitter_send == '1') {
            if (Carbon::parse($post->publish_date)->format('Y-m-d H:i:s') < Carbon::parse()->format('Y-m-d H:i:s')){
                try {
                    $tw = new ApiManager();

                    $tw->twSendPost($post->id);

                } catch (Exception $e) {};
            } else {
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
        return redirect()->route('admin.posts.index');
    }


    public function edit(Post $post)
    {
        abort_if(Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $section_parent_ids = Section::whereIn('id',Section::pluck('parent_id'))->pluck('id');
        $sections = Section::whereNotIn('id',$section_parent_ids)->pluck('title_kr', 'id');
        $postNetwork = PostsSendAutoSocialNetwork::query()->where('post_id',$post->id)->first();


        $tutors = Tutor::pluck('first_name_kr', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tags = Tag::pluck('title_kr', 'id');

        $post->load('tags');

        $catTab = 0;

        $locales = config('app.locales');

        return view('admin.posts.edit', compact('post', 'sections', 'tags', 'tutors','catTab', 'locales', 'postNetwork' ));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
//        if ($request->image_base64) {
//            [$imageName, $imageName2] = $this->storeBase64($request->image_base64);
//        }
        $audio_name = null;
        $post->update($request->all());

        if ($postNetwork = PostsSendAutoSocialNetwork::query()->where('post_id',$post->id)->first()){
            $postNetwork->update([
                'publish_date' => $request->publish_date,
                'telegram_send' => $request->telegram_send,
                'facebook_send' => $request->facebook_send,
                'twitter_send' => $request->twitter_send
            ]);
        }

        if($request->hasFile('audio_file')) {
            $Fileaudio = $request->file('audio_file');
            $audio_name = time() . $Fileaudio->getClientOriginalName();
            $audiopath =$Fileaudio->storeAs('public/audio/' . $audio_name);
            $post->update(['audio_file' => $audio_name]);
        }

        $tags = $request->input('tags', []);
        foreach ($tags as $index => $tag) {
            if ( !is_numeric($tag)) {
                $tag_id = Tag::query()->where('title_kr','like',$tag)->first()->id;
                $tags[$index] = $tag_id;
            }
        }

        $post->tags()->sync($tags);

        if ($request->image) {
            $file = $request->file('image');
            $path = storage_path('tmp/uploads/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($path, $fileName);

            if (!$post->detail_image || $fileName !== $post->detail_image->file_name) {
                if ($post->detail_image) {
                    $post->detail_image->delete();
                }
                $post->addMedia($path . $fileName)->toMediaCollection('detail_image');
            }


        }

//        if (!empty($imageName)) {
//            if (!$post->detail_image || $imageName !== $post->detail_image->file_name) {
//                if ($post->detail_image) {
//                    $post->detail_image->delete();
//                }
//                $post->addMedia(storage_path('tmp/uploads/' . $imageName))->toMediaCollection('detail_image');
//            }
//        }

        return redirect()->route('admin.posts.index');
    }

    public function show(Post $post)
    {
        abort_if(Gate::denies('post_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $post->load('tags', 'tutor',);

        return view('admin.posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        abort_if(Gate::denies('post_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($post->recommended == '1') {

            if (!empty($bannerPost = BannerPost::query()->where('type', 'main')->where('status', 1)->where('post_id',$post->id)->first())){
                $bannerPost->delete();
            }
        }

        $post->delete();

        return back();
    }

    public function massArchiving(MassDestroyPostRequest $request)
    {
        $posts = Post::find(request('ids'));

        foreach ($posts as $post) {
            $post->update(['status' => 2]);
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function massUnArchiving(MassDestroyPostRequest $request)
    {
        $posts = Post::find(request('ids'));

        foreach ($posts as $post) {
            $post->update(['status' => 1]);
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('post_create') && Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Post();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    private function storeBase64($imageBase64)
    {
        list($type, $imageBase64) = explode(';', $imageBase64);
        list(, $imageBase64)      = explode(',', $imageBase64);
        $imageBase64 = base64_decode($imageBase64);
        $imageName= time().'.png';
        $imageName2= time().'2.png';
        $path =  storage_path('tmp/uploads/' . $imageName);
        $path2 =  storage_path('tmp/uploads/' . $imageName2);
        file_put_contents($path, $imageBase64);
        file_put_contents($path2, $imageBase64);

        return [$imageName, $imageName2];
    }
}
