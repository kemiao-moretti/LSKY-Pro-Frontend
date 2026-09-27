<div class="min-h-screen flex items-stretch">
    <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative overflow-hidden" style="background: var(--panel-dark);">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle, var(--panel-dark-dot) 1px, transparent 1px); background-size: 28px 28px;"></div>
        <div class="absolute -top-40 -left-40 w-[560px] h-[560px] rounded-full pointer-events-none" style="background: radial-gradient(circle, var(--panel-dark-glow) 0%, transparent 68%);"></div>

        <div class="relative z-10 flex flex-col justify-center px-14 xl:px-20" style="color: var(--panel-dark-text);">
            <div class="mb-10 flex items-center gap-3.5">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background: var(--primary); color: var(--on-primary);">
                    <i class="fas fa-feather-alt text-2xl"></i>
                </div>
                <div>
                    <span class="text-2xl font-extrabold tracking-tight block">{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</span>
                    <span class="text-xs tracking-widest uppercase" style="color: var(--panel-dark-muted);">Image Hosting</span>
                </div>
            </div>

            <h2 class="text-4xl xl:text-5xl font-extrabold leading-tight mb-5 tracking-tight">
                轻盈存储<br>
                <span style="color: var(--primary);">无限可能</span>
            </h2>
            <p class="text-base leading-relaxed mb-10 max-w-sm" style="color: var(--panel-dark-muted);">
                安全、快速地托管您的每一张图片，支持多种储存策略与灵活分享。
            </p>

            <div class="flex flex-col gap-3.5">
                @foreach([
                    ['fa-shield-alt', '端到端安全加密，保障数据隐私'],
                    ['fa-bolt', '极速上传，支持 WebP / GIF / RAW 等'],
                    ['fa-share-alt', 'BBCode / Markdown / 直链多格式分享'],
                    ['fa-layer-group', '多储存策略，本地 / OSS / S3 自由选择'],
                ] as $f)
                <div class="flex items-center gap-3.5">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background: var(--panel-dark-chip); border: 1px solid var(--panel-dark-dot);">
                        <i class="fas {{ $f[0] }} text-xs"></i>
                    </div>
                    <span class="text-sm" style="color: var(--panel-dark-muted);">{{ $f[1] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 xl:w-2/5 flex flex-col justify-center items-center px-8 sm:px-12 py-12 relative" style="background: var(--panel-bg-strong);">
        <div class="w-full max-w-sm relative z-10">
            <div class="flex items-center gap-2.5 mb-8 lg:hidden">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--primary); color: var(--on-primary);">
                    <i class="fas fa-feather-alt text-base"></i>
                </div>
                <div>
                    <span class="text-lg font-bold block leading-tight text-[var(--text-primary)]">{{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}</span>
                    <span class="text-xs text-[var(--text-muted)]">Image Hosting</span>
                </div>
            </div>
            {{ $slot }}
        </div>
        <p class="mt-10 text-xs text-center relative z-10 text-[var(--text-muted)]">
            初始项目:&nbsp;<a href="https://github.com/lsky-org/lsky-pro" target="_blank" rel="noreferrer" class="text-token-primary-hover transition-colors">兰空图床</a>
            &nbsp;|&nbsp;
            UI设计:&nbsp;<a href="https://github.com/willow-god/LSKY-Pro-LiuShen" target="_blank" rel="noreferrer" class="text-token-primary-hover transition-colors">清羽飞扬</a>
            &nbsp;|&nbsp;
            二改者:&nbsp;<a href="https://github.com/kemiao-moretti" target="_blank" rel="noreferrer" class="text-token-primary-hover transition-colors">kemiao</a>
        </p>
        <p class="mt-1 text-xs text-center relative z-10 text-[var(--text-muted)]">
            &copy; {{ date('Y') }} {{ \App\Utils::config(\App\Enums\ConfigKey::AppName) }}
            @if(\App\Utils::config(\App\Enums\ConfigKey\IcpNo))
            &nbsp;&middot;&nbsp; <a href="https://beian.miit.gov.cn/" target="_blank" class="text-token-primary-hover transition-colors">{{ \App\Utils::config(\App\Enums\ConfigKey\IcpNo) }}</a>
            @endif
        </p>
    </div>
</div>
