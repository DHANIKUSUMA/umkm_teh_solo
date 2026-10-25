@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold leading-5 text-emerald-950 bg-white shadow-xs border border-emerald-200/80 focus:outline-none transition-all duration-150 ease-in-out'
            : 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium leading-5 text-stone-600 hover:text-emerald-900 hover:bg-white/80 focus:outline-none focus:text-emerald-900 focus:bg-white/80 transition-all duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
