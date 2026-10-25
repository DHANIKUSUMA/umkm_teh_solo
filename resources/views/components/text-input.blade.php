@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm bg-white/80 placeholder:text-stone-400 transition duration-200']) }}>
