<?php

namespace App\Http\Controllers\Admin;

use App\Models\Management;
use App\Models\ManagementPerson;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Statistic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController
{
    public function index()
    {
        $site_status = Management::query()->first()?->site_status;

        // Cache dashboard stats for 10 minutes to avoid repeated queries
        $stats = Cache::remember('admin_dashboard_stats', 600, function () {
            return [
                'total_posts'      => Post::count(),
                'active_posts'     => Post::where('status', 1)->count(),
                'total_views'      => PostView::count(),
                'total_users'      => User::count(),
                'today_posts'      => Post::whereDate('created_at', Carbon::today())->count(),
                'week_posts'       => Post::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
                'management_count' => ManagementPerson::count(),
                'statistics_count' => Statistic::count(),
            ];
        });

        // Weekly chart data: posts per day for the last 7 days
        $weeklyData = Cache::remember('admin_weekly_chart', 300, function () {
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $data[] = [
                    'date'  => $date->format('d.m'),
                    'label' => $date->locale('uz')->isoFormat('ddd'),
                    'count' => Post::whereDate('created_at', $date->toDateString())->count(),
                ];
            }
            return $data;
        });

        // Recent 8 posts — only select needed columns, no eager loading overhead
        $recentPosts = Post::select('id', 'title_uz', 'status', 'created_at', 'section_ids')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('home', compact('site_status', 'stats', 'weeklyData', 'recentPosts'));
    }

    public function translate(Request $request)
    {
        $data = [];
        foreach (config('app.locales') as $key_local => $value_local) {
            if ($value_local !== 'kr') {
                $translated = trs($request->data, $value_local);
                array_push($data, $translated);
            }
        }
        return response()->json(['data' => $data]);
    }

    public function translateTitle(Request $request)
    {
        $data = [];
        foreach (config('app.locales') as $key_local => $value_local) {
            if ($value_local !== 'kr' && $value_local !== 'uz') {
                $translated = trsTitle($request->data, $value_local);
                array_push($data, $translated);
            }
            if ($value_local == 'uz') {
                $translated = transliterateLatin($request->data);
                array_push($data, $translated);
            }
        }
        return response()->json(['data' => $data]);
    }
}
