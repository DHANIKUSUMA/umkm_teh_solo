@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 w-full px-3.5 py-2.5 rounded-xl text-start text-sm font-semibold text-emerald-950 bg-emerald-100/90 border border-emerald-300/80 shadow-xs focus:outline-none transition duration-150 ease-in-out'
            : 'flex items-center gap-3 w-full px-3.5 py-2.5 rounded-xl text-start text-sm font-medium text-stone-600 hover:text-emerald-950 hover:bg-stone-100/80 border border-transparent focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
