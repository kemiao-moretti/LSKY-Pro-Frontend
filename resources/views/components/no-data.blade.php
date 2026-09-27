@props(['message' => '暂无数据'])

<div class="flex flex-col justify-center items-center py-16 space-y-3">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: var(--primary-soft);">
        <i class="fas fa-inbox text-3xl" style="color: var(--primary);"></i>
    </div>
    <p class="text-slate-400 text-sm">{{ $message }}</p>
</div>
