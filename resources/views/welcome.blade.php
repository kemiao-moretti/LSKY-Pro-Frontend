@push('styles')
    <link rel="stylesheet" href="{{ asset('css/markdown-css/github-markdown-light.css') }}">
    <style>
        /* 背景基底：沿用全局令牌（白底 + 琥珀/天蓝双径向） */
        .hero-bg {
            background: var(--guest-bg);
            position: relative;
            overflow: hidden;
        }

        /* 点阵网格：中心透明、四周渐显 */
        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, var(--grid-dot) 1px, transparent 1px);
            background-size: 28px 28px;
            -webkit-mask-image: radial-gradient(ellipse 82% 74% at 50% 40%,
                transparent 0%,
                rgba(0, 0, 0, 0.25) 34%,
                rgba(0, 0, 0, 0.75) 68%,
                black 100%);
            mask-image: radial-gradient(ellipse 82% 74% at 50% 40%,
                transparent 0%,
                rgba(0, 0, 0, 0.25) 34%,
                rgba(0, 0, 0, 0.75) 68%,
                black 100%);
            pointer-events: none;
        }

        .nav-link-hover {
            transition: color 0.2s, background-color 0.2s;
        }
        .nav-link-hover:hover {
            color: var(--primary);
            background: var(--primary-soft);
        }

        .upload-section {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: var(--card-shadow-hover);
        }

        .feature-pill {
            background: var(--panel-bg);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            transition: border-color 0.2s, color 0.2s;
        }
        .feature-pill:hover {
            border-color: var(--border-strong);
            color: var(--text-primary);
        }

        .welcome-header {
            background: var(--header-bg) !important;
            border-bottom: 1px solid var(--border-color) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: var(--header-shadow) !important;
        }

        .welcome-footer {
            background: transparent !important;
            border-top: 1px solid var(--border-color) !important;
        }
    </style>
@endpush

<x-guest-layout>
    <div class="min-h-screen flex flex-col hero-bg">
        <div class="grid-pattern"></div>

        {{-- 顶部导航 --}}
        <header class="relative z-20 w-full welcome-header">
            <div class="container mx-auto px-5 sm:px-10 2xl:px-60 h-16 flex justify-between items-center">
                {{-- Logo --}}
                <a href="{{ route('/') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: var(--primary); color: var(--on-primary);">
                        <i class="fas fa-feather-alt text-sm"></i>
                    </div>
                    <span class="font-bold text-lg truncate max-w-[180px] text-slate-900">{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</span>
                </a>

                {{-- 右侧按钮 --}}
                <div class="flex items-center gap-3">
                    @includeWhen($_is_notice, 'layouts.notice')
                    @includeWhen($_group->strategies->isNotEmpty(), 'layouts.strategies')

                    @if(Auth::check())
                        @include('layouts.user-nav')
                    @else
                        <a href="{{ route('login') }}"
                            class="nav-link-hover text-slate-600 text-sm font-medium px-4 py-2 rounded-lg transition-all">
                            登录
                        </a>
                        @if(\App\Utils::config(\App\Enums\ConfigKey::IsEnableRegistration))
                        <a href="{{ route('register') }}"
                            class="text-sm font-semibold px-5 py-2 rounded-lg transition-all duration-200 hover:brightness-105"
                            style="background: var(--btn-bg); color: var(--btn-fg);">
                            注册
                        </a>
                        @endif
                    @endif
                </div>
            </div>
        </header>

        {{-- 主内容区 --}}
        <main class="relative z-10 flex-1 flex flex-col items-center justify-center px-5 py-16">
            {{-- Hero文字 --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-medium mb-6" style="background: var(--primary-soft); border: 1px solid var(--border-color); color: var(--primary);">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: var(--primary);"></span>
                    个人自用 &middot; 安全可靠的图片存储
                </div>
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background: var(--primary); color: var(--on-primary); box-shadow: var(--btn-shadow);">
                        <i class="fas fa-feather-alt text-lg"></i>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900">{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</h1>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-slate-700 mb-3">
                    上传、管理、<span style="color: var(--primary);">分享您的图片</span>
                </p>
                <p class="text-slate-500 text-sm max-w-lg mx-auto leading-relaxed">
                    本站为个人自用图床，图片安全存储于私有服务器。支持多种储存策略与相册管理，快速生成分享链接。
                </p>
            </div>

            {{-- 上传组件容器 --}}
            <div class="w-full max-w-3xl upload-section p-6 sm:p-8">
                <x-upload/>
            </div>

            {{-- 特性标签 --}}
            <div class="flex flex-wrap justify-center gap-3 mt-8">
                @foreach([['fas fa-shield-alt', '安全加密'], ['fas fa-bolt', '极速上传'], ['fas fa-share-alt', '快速分享'], ['fas fa-hdd', '多种储存'], ['fas fa-images', '相册管理']] as $feature)
                <div class="feature-pill flex items-center gap-1.5 px-4 py-2 rounded-full text-sm cursor-default">
                    <i class="{{ $feature[0] }} text-xs" style="color: var(--primary);"></i>
                    <span>{{ $feature[1] }}</span>
                </div>
                @endforeach
            </div>
        </main>

        {{-- 底部 --}}
        <footer class="relative z-10 py-4 welcome-footer">
            <p class="text-center text-slate-400 text-xs">
                初始项目:&nbsp;<a href="https://github.com/lsky-org/lsky-pro" target="_blank" rel="noreferrer" class="hover:text-slate-600 transition-colors">兰空图床</a>
                &nbsp;|&nbsp;
                UI设计:&nbsp;<a href="https://github.com/willow-god/LSKY-Pro-LiuShen" target="_blank" rel="noreferrer" class="hover:text-slate-600 transition-colors">清羽飞扬</a>
                &nbsp;|&nbsp;
                二改者:&nbsp;<a href="https://github.com/kemiao-moretti" target="_blank" rel="noreferrer" class="hover:text-slate-600 transition-colors">kemiao</a>
            </p>
            <p class="text-center text-slate-400 text-xs mt-1">
                &copy; {{ date('Y') }} {{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}. All rights reserved.
                @if(\App\Utils::config(\App\Enums\ConfigKey::IcpNo))
                &nbsp;&middot;&nbsp; <a href="https://beian.miit.gov.cn/" target="_blank" rel="noreferrer" class="hover:text-slate-600 transition-colors">{{ \App\Utils::config(\App\Enums\ConfigKey::IcpNo) }}</a>
                @endif
            </p>
        </footer>
    </div>

    @include('common.notice')

</x-guest-layout>
