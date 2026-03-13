@extends('layouts.admin')

@section('styles')
<style>
    /* Chart.js loader */
    .chart-wrap { position: relative; height: 260px; }
</style>
@endsection

@section('content')
<div class="content">

    {{-- ===== GREETING BANNER ===== --}}
    <div class="dashboard-greeting">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="greeting-badge">
                    <i class="fas fa-seedling"></i>
                    Chiqindilarni boshqarish tizimi
                </div>
                <h1 class="greeting-title mt-3">
                    Xush kelibsiz, {{ auth()->user()->name ?? 'Admin' }}! 👋
                </h1>
                <p class="greeting-subtitle">
                    Bugun saytingiz qanday ishlayotganini kuzating va kontent boshqaruv amallarini bajaring.
                </p>
                <div class="quick-actions">
                    @can('post_create')
                        <a href="{{ route('admin.posts.create') }}" class="quick-action-btn primary">
                            <i class="fas fa-plus"></i>
                            Yangi maqola
                        </a>
                    @endcan
                    @can('section_access')
                        <a href="{{ route('admin.postGetSectionId', ['id' => 1]) }}" class="quick-action-btn">
                            <i class="fas fa-newspaper"></i>
                            Yangiliklar
                        </a>
                        <a href="{{ route('admin.postGetSectionId', ['id' => 2]) }}" class="quick-action-btn">
                            <i class="fas fa-bullhorn"></i>
                            E'lonlar
                        </a>
                    @endcan
                </div>
            </div>
            <div class="col-md-4 d-none d-md-flex justify-content-end align-items-center">
                <div style="text-align:right; opacity:0.15; font-size:120px; line-height:1;">
                    ♻
                </div>
            </div>
        </div>

        <div class="greeting-time">
            <i class="fas fa-clock"></i>
            <span id="current-datetime"></span>
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="row" style="margin-bottom: 4px;">

        {{-- Total posts --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-4">
            <div class="stat-card stat-card-green">
                <div class="stat-card-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-card-label">Jami maqolalar</div>
                <div class="stat-card-value">{{ number_format($stats['total_posts']) }}</div>
                <span class="stat-card-change up">
                    <i class="fas fa-arrow-up" style="font-size:9px;"></i>
                    Faol: {{ $stats['active_posts'] }}
                </span>
                <div class="stat-card-bg">📄</div>
            </div>
        </div>

        {{-- Today's posts --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-4">
            <div class="stat-card stat-card-blue">
                <div class="stat-card-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-card-label">Bugun</div>
                <div class="stat-card-value">{{ $stats['today_posts'] }}</div>
                <span class="stat-card-change neutral">
                    Haftalik: {{ $stats['week_posts'] }}
                </span>
                <div class="stat-card-bg">📅</div>
            </div>
        </div>

        {{-- Total views --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-4">
            <div class="stat-card stat-card-purple">
                <div class="stat-card-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-card-label">Jami ko'rishlar</div>
                <div class="stat-card-value">{{ number_format($stats['total_views']) }}</div>
                <span class="stat-card-change neutral">
                    <i class="fas fa-chart-line" style="font-size:9px;"></i>
                    Analytics
                </span>
                <div class="stat-card-bg">👁</div>
            </div>
        </div>

        {{-- Users --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-4">
            <div class="stat-card stat-card-cyan">
                <div class="stat-card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-card-label">Foydalanuvchilar</div>
                <div class="stat-card-value">{{ $stats['total_users'] }}</div>
                <span class="stat-card-change neutral">Admin tizimi</span>
                <div class="stat-card-bg">👥</div>
            </div>
        </div>

        {{-- Management --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-4">
            <div class="stat-card stat-card-orange">
                <div class="stat-card-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-card-label">Boshqaruv xodimlari</div>
                <div class="stat-card-value">{{ $stats['management_count'] }}</div>
                <span class="stat-card-change neutral">Xodimlar</span>
                <div class="stat-card-bg">👔</div>
            </div>
        </div>

        {{-- Statistics entries --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 mb-4">
            <div class="stat-card stat-card-yellow">
                <div class="stat-card-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-card-label">Statistika yozuvlari</div>
                <div class="stat-card-value">{{ $stats['statistics_count'] }}</div>
                <span class="stat-card-change neutral">Ma'lumotlar</span>
                <div class="stat-card-bg">📊</div>
            </div>
        </div>

    </div>

    {{-- ===== CHART + RECENT POSTS ===== --}}
    <div class="row">

        {{-- Weekly chart --}}
        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-area mr-2" style="color:#16a34a;"></i>
                        So'nggi 7 kun maqolalari
                    </h3>
                    <small style="color:#94a3b8; font-size:12px;">Kunlik nashr statistikasi</small>
                </div>
                <div class="card-body">
                    <div class="chart-wrap">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick info panel --}}
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2" style="color:#0891b2;"></i>
                        Tezkor ma'lumot
                    </h3>
                </div>
                <div class="card-body" style="padding: 12px 20px !important;">

                    {{-- Content distribution --}}
                    <div style="margin-bottom: 20px;">
                        <div style="font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px;">
                            Kontent taqsimoti
                        </div>

                        @php
                            $total = max($stats['total_posts'], 1);
                            $activePercent = round(($stats['active_posts'] / $total) * 100);
                        @endphp

                        <div style="margin-bottom: 14px;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                <span style="font-size:13px; font-weight:600; color:#475569;">Faol maqolalar</span>
                                <span style="font-size:13px; font-weight:700; color:#16a34a;">{{ $stats['active_posts'] }}</span>
                            </div>
                            <div style="height:6px; background:#f1f5f9; border-radius:10px; overflow:hidden;">
                                <div style="height:100%; width:{{ $activePercent }}%; background:linear-gradient(90deg, #16a34a, #22c55e); border-radius:10px; transition:width 1s ease;"></div>
                            </div>
                        </div>

                        @php
                            $inactiveCount = $stats['total_posts'] - $stats['active_posts'];
                            $inactivePercent = round(($inactiveCount / $total) * 100);
                        @endphp
                        <div style="margin-bottom: 14px;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                <span style="font-size:13px; font-weight:600; color:#475569;">Nofaol maqolalar</span>
                                <span style="font-size:13px; font-weight:700; color:#94a3b8;">{{ $inactiveCount }}</span>
                            </div>
                            <div style="height:6px; background:#f1f5f9; border-radius:10px; overflow:hidden;">
                                <div style="height:100%; width:{{ $inactivePercent }}%; background:#e2e8f0; border-radius:10px;"></div>
                            </div>
                        </div>
                    </div>

                    <div style="height:1px; background:#f1f5f9; margin: 8px 0 16px;"></div>

                    {{-- Quick links --}}
                    <div style="font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:12px;">
                        Tezkor havolalar
                    </div>

                    <div style="display:flex; flex-direction:column; gap:6px;">
                        @can('section_access')
                        <a href="{{ route('admin.postGetSectionId', ['id' => 1]) }}"
                           style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:#f8fafc; border-radius:10px; text-decoration:none; transition:all 0.2s;"
                           onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='#f8fafc'">
                            <span style="display:flex; align-items:center; gap:10px; font-size:13px; font-weight:600; color:#475569;">
                                <span style="width:30px; height:30px; background:#dcfce7; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-newspaper" style="color:#16a34a; font-size:13px;"></i>
                                </span>
                                Yangiliklar
                            </span>
                            <i class="fas fa-arrow-right" style="color:#cbd5e1; font-size:11px;"></i>
                        </a>
                        <a href="{{ route('admin.postGetSectionId', ['id' => 2]) }}"
                           style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:#f8fafc; border-radius:10px; text-decoration:none; transition:all 0.2s;"
                           onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='#f8fafc'">
                            <span style="display:flex; align-items:center; gap:10px; font-size:13px; font-weight:600; color:#475569;">
                                <span style="width:30px; height:30px; background:#e0f2fe; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-bullhorn" style="color:#0284c7; font-size:13px;"></i>
                                </span>
                                E'lonlar
                            </span>
                            <i class="fas fa-arrow-right" style="color:#cbd5e1; font-size:11px;"></i>
                        </a>
                        <a href="{{ route('admin.postGetSectionId', ['id' => 3]) }}"
                           style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:#f8fafc; border-radius:10px; text-decoration:none; transition:all 0.2s;"
                           onmouseover="this.style.background='#fff7ed'" onmouseout="this.style.background='#f8fafc'">
                            <span style="display:flex; align-items:center; gap:10px; font-size:13px; font-weight:600; color:#475569;">
                                <span style="width:30px; height:30px; background:#fff7ed; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-video" style="color:#ea580c; font-size:13px;"></i>
                                </span>
                                Kuzatuv kameralari
                            </span>
                            <i class="fas fa-arrow-right" style="color:#cbd5e1; font-size:11px;"></i>
                        </a>
                        @endcan

                        @can('statistic_access')
                        <a href="{{ route('admin.statistics.index') }}"
                           style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:#f8fafc; border-radius:10px; text-decoration:none; transition:all 0.2s;"
                           onmouseover="this.style.background='#faf5ff'" onmouseout="this.style.background='#f8fafc'">
                            <span style="display:flex; align-items:center; gap:10px; font-size:13px; font-weight:600; color:#475569;">
                                <span style="width:30px; height:30px; background:#f3e8ff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-chart-bar" style="color:#9333ea; font-size:13px;"></i>
                                </span>
                                Statistikalar
                            </span>
                            <i class="fas fa-arrow-right" style="color:#cbd5e1; font-size:11px;"></i>
                        </a>
                        @endcan
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ===== RECENT POSTS TABLE ===== --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-2" style="color:#16a34a;"></i>
                        So'nggi maqolalar
                    </h3>
                    <div>
                        @can('section_access')
                            <a href="{{ route('admin.postGetSectionId', ['id' => 1]) }}"
                               class="btn btn-sm"
                               style="background:#f0fdf4; color:#16a34a; border:1px solid #dcfce7; font-size:12px; padding:5px 12px; border-radius:8px;">
                                Barchasini ko'rish
                                <i class="fas fa-arrow-right ml-1" style="font-size:10px;"></i>
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body" style="padding: 0 !important;">
                    <div class="table-responsive">
                        <table class="recent-posts-table w-100">
                            <thead>
                                <tr>
                                    <th style="width:40px;">#</th>
                                    <th>Sarlavha</th>
                                    <th style="width:120px;">Holati</th>
                                    <th style="width:80px;">—</th>
                                    <th style="width:140px;">Sana</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPosts as $index => $post)
                                    <tr>
                                        <td style="color:#94a3b8; font-weight:600; font-size:12px;">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="post-title-cell">
                                            <span title="{{ $post->title_uz }}">
                                                {{ $post->title_uz }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($post->status == 1)
                                                <span class="badge-status active">Faol</span>
                                            @else
                                                <span class="badge-status inactive">Nofaol</span>
                                            @endif
                                        </td>
                                        <td style="color:#94a3b8; font-size:12px;">
                                            —
                                        </td>
                                        <td style="color:#94a3b8; font-size:12px; white-space:nowrap;">
                                            <i class="fas fa-calendar" style="font-size:10px; margin-right:4px;"></i>
                                            {{ \Carbon\Carbon::parse($post->created_at)->format('d.m.Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align:center; padding:40px; color:#94a3b8;">
                                            <div style="font-size:36px; margin-bottom:12px;">📭</div>
                                            <div style="font-size:14px; font-weight:600;">Hozircha maqolalar yo'q</div>
                                            <div style="font-size:12px; margin-top:4px;">Birinchi maqolangizni yarating</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Session status --}}
    @if(session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ===== Live clock =====
function updateClock() {
    var now = new Date();
    var days = ['Yakshanba', 'Dushanba', 'Seshanba', 'Chorshanba', 'Payshanba', 'Juma', 'Shanba'];
    var months = ['Yanvar', 'Fevral', 'Mart', 'Aprel', 'May', 'Iyun', 'Iyul', 'Avgust', 'Sentabr', 'Oktabr', 'Noyabr', 'Dekabr'];
    var h = String(now.getHours()).padStart(2, '0');
    var m = String(now.getMinutes()).padStart(2, '0');
    var s = String(now.getSeconds()).padStart(2, '0');
    var day = days[now.getDay()];
    var date = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
    document.getElementById('current-datetime').textContent = day + ', ' + date + ' · ' + h + ':' + m + ':' + s;
}
updateClock();
setInterval(updateClock, 1000);

// ===== Weekly Chart =====
var weeklyLabels = {!! json_encode(array_column($weeklyData, 'date')) !!};
var weeklyCounts = {!! json_encode(array_column($weeklyData, 'count')) !!};

var ctx = document.getElementById('weeklyChart').getContext('2d');

// Gradient fill
var gradient = ctx.createLinearGradient(0, 0, 0, 260);
gradient.addColorStop(0, 'rgba(22, 163, 74, 0.25)');
gradient.addColorStop(1, 'rgba(22, 163, 74, 0.01)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: weeklyLabels,
        datasets: [{
            label: "Maqolalar soni",
            data: weeklyCounts,
            borderColor: '#16a34a',
            backgroundColor: gradient,
            borderWidth: 2.5,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#16a34a',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a',
                titleColor: '#94a3b8',
                bodyColor: '#ffffff',
                borderColor: '#1e293b',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 10,
                callbacks: {
                    title: function(items) { return items[0].label + ' kuni'; },
                    label: function(item) { return item.raw + ' ta maqola'; }
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 12, weight: '600', family: 'Inter' }
                }
            },
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9', lineWidth: 1 },
                border: { display: false, dash: [4, 4] },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 11, family: 'Inter' },
                    precision: 0,
                    maxTicksLimit: 6
                }
            }
        },
        interaction: { intersect: false, mode: 'index' }
    }
});
</script>
@endsection
