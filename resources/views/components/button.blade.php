@props(['disabled' => false])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-1.5 py-2 px-5 text-sm font-semibold rounded-lg transition-all duration-200 hover:brightness-105 focus:outline-none focus:ring-2 focus:ring-amber-500/40 disabled:opacity-60 disabled:cursor-not-allowed']) }} {{ $disabled ? 'disabled' : '' }} style="background: var(--btn-bg); color: var(--btn-fg); box-shadow: var(--btn-shadow);">
    {{ $slot }}
</button>
