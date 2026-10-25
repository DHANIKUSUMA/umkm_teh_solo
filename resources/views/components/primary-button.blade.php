<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-emerald-600 hover:to-emerald-500 focus:from-emerald-600 focus:to-emerald-500 active:from-emerald-800 active:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 shadow-md hover:shadow-lg transition-all ease-in-out duration-200']) }}>
    {{ $slot }}
</button>
