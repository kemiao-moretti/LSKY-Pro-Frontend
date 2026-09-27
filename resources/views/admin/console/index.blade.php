@section('title', '系统控制台')

<x-app-layout>
    @if(config('app.debug'))
        <p class="mt-4 p-2 rounded-md text-sm bg-red-500 text-white">
            <i class="fas fa-exclamation-triangle"></i>
            当前系统 debug 已被打开，敏感信息暴露在外，可能会被利用从而影响系统稳定性，生产环境中请务必关闭！
        </p>
    @endif
    <div class="my-6 md:my-9">
        <p class="admin-section-title">概览</p>
        <div class="relative grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            {{-- 图片数量 --}}
            <div class="stat-card surface-card rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--primary-soft);">
                    <i class="fas fa-images text-xl" style="color: var(--primary);"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500">图片数量</p>
                    <p class="font-bold text-2xl mt-0.5 text-slate-900">{{ \App\Utils::shortenNumber(\App\Models\Image::query()->count()) }}</p>
                </div>
            </div>
            {{-- 相册数量 --}}
            <div class="stat-card surface-card rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--primary-soft);">
                    <i class="fas fa-tags text-xl" style="color: var(--primary);"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500">相册数量</p>
                    <p class="font-bold text-2xl mt-0.5 text-slate-900">{{ \App\Utils::shortenNumber(\App\Models\Album::query()->count()) }}</p>
                </div>
            </div>
            {{-- 用户数量 --}}
            <div class="stat-card surface-card rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--primary-soft);">
                    <i class="fas fa-users text-xl" style="color: var(--primary);"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500">用户数量</p>
                    <p class="font-bold text-2xl mt-0.5 text-slate-900">{{ \App\Utils::shortenNumber(\App\Models\User::query()->count()) }}</p>
                </div>
            </div>
            {{-- 占用储存 --}}
            <div class="stat-card surface-card rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--primary-soft);">
                    <i class="fas fa-server text-xl" style="color: var(--primary);"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500">占用储存</p>
                    <p class="font-bold text-xl mt-0.5 leading-tight text-slate-900">{{ \App\Utils::formatSize(\App\Models\Image::query()->sum('size') * 1024) }}</p>
                </div>
            </div>

            {{-- 上传统计 4 项 --}}
            @foreach([['today','今日上传'],['yesterday','昨日上传'],['week','本周上传'],['month','本月上传']] as [$key,$label])
            <div class="stat-card surface-card flex items-center gap-4 rounded-xl p-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--primary-soft);">
                    <i class="fas fa-upload" style="color: var(--primary);"></i>
                </div>
                <div>
                    <p class="font-bold text-xl text-slate-700">{{ \App\Utils::shortenNumber($numbers[$key]) }}</p>
                    <p class="text-sm text-slate-500">{{ $label }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <p class="admin-section-title">趋势</p>
        <div class="admin-section h-80 p-4" id="chart">
            <canvas></canvas>
        </div>

        <p class="admin-section-title">系统情况</p>
        <div class="admin-section !p-0 overflow-hidden">
            <dl>
                <div class="bg-slate-50/60 px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">操作系统</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        {{ php_uname() }}
                    </dd>
                </div>
                <div class="bg-white px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">运行环境</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        {{ request()->server('SERVER_SOFTWARE') }}
                    </dd>
                </div>
                <div class="bg-slate-50/60 px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">PHP 版本</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        {{ phpversion() }}
                    </dd>
                </div>
                <div class="bg-white px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">文件上传限制</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        {{ ini_get("upload_max_filesize") }}
                    </dd>
                </div>
                <div class="bg-slate-50/60 px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">POST 数据最大限制</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        {{ ini_get('post_max_size') }}
                    </dd>
                </div>
            </dl>
        </div>

        <p class="admin-section-title">软件信息</p>
        <div class="admin-section !p-0 overflow-hidden">
            <dl>
                <div class="bg-slate-50/60 px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">软件版本</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">{{ \App\Utils::config(\App\Enums\ConfigKey::AppVersion) }}</dd>
                </div>
                <div class="bg-white px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">官方网站</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        <a target="_blank" class="text-token-primary text-token-primary-hover" href="https://www.lsky.pro">https://www.lsky.pro</a>
                    </dd>
                </div>
                <div class="bg-white px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">使用手册</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        <a target="_blank" class="text-token-primary text-token-primary-hover" href="https://docs.lsky.pro">https://docs.lsky.pro</a>
                    </dd>
                </div>
                <div class="bg-slate-50/60 px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm font-medium text-slate-500">仓库地址</dt>
                    <dd class="mt-1 text-sm text-slate-700 sm:mt-0 sm:col-span-2">
                        <a target="_blank" class="text-token-primary text-token-primary-hover" href="https://github.com/lsky-org/lsky-pro">https://github.com/lsky-org/lsky-pro</a>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/echarts/echarts.min.js') }}"></script>
        <script>
            $(function () {
                'use strict'
                let chartDom = document.getElementById('chart');
                let myChart = echarts.init(chartDom);
                let options;

                options = {
                    responsive: true,
                    title: {
                        text: '近 30 天内统计'
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        top: '10%',
                        type: 'scroll',
                        data: @json($fields)
                    },
                    grid: {
                        left: '3%',
                        right: '3%',
                        bottom: '3%',
                        containLabel: true
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            magicType: {
                                type: ["line", "bar"]
                            },
                            saveAsImage: {}
                        }
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: @json($dates)
                    },
                    yAxis: {
                        type: 'value',
                        minInterval: 1,
                    },
                    series: @json($datasets)
                };

                options && myChart.setOption(options);

                window.onresize = function() {
                    myChart.resize();
                }
            })
        </script>
    @endpush

</x-app-layout>
