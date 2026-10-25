<div {{ $attributes->merge(['class' => 'inline-flex items-center justify-center']) }}>
    <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-md">
        <!-- Cup Base / Saucer (Warm Tea Brown) -->
        <ellipse cx="50" cy="85" rx="36" ry="6" fill="#78350F" opacity="0.85" />
        
        <!-- Tea Cup Body (Gradient Forest Green to Emerald) -->
        <defs>
            <linearGradient id="teaCupGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#065F46" />
                <stop offset="100%" stop-color="#047857" />
            </linearGradient>
            <linearGradient id="teaLiquidGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#B45309" />
                <stop offset="100%" stop-color="#78350F" />
            </linearGradient>
            <linearGradient id="teaLeafGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#10B981" />
                <stop offset="100%" stop-color="#059669" />
            </linearGradient>
        </defs>

        <!-- Cup Handle -->
        <path d="M68 50 C82 50, 82 72, 65 72" stroke="#047857" stroke-width="6" stroke-linecap="round" fill="none" />

        <!-- Cup Outer Shape -->
        <path d="M22 42 H78 L71 75 C70 80, 64 83, 58 83 H42 C36 83, 30 80, 29 75 Z" fill="url(#teaCupGrad)" />
        
        <!-- Cup Inner Tea Liquid Oval -->
        <ellipse cx="50" cy="43" rx="27" ry="6.5" fill="url(#teaLiquidGrad)" />

        <!-- Tea Leaves floating / emerging with steam -->
        <!-- Center Leaf -->
        <path d="M50 35 C42 22, 45 10, 50 4 C55 10, 58 22, 50 35 Z" fill="url(#teaLeafGrad)" />
        <path d="M50 4 Q49 20 50 35" stroke="#064E3B" stroke-width="1.2" stroke-linecap="round" />

        <!-- Left Leaf -->
        <path d="M42 32 C33 24, 30 15, 33 9 C40 12, 45 22, 42 32 Z" fill="#34D399" />
        <path d="M33 9 Q38 20 42 32" stroke="#065F46" stroke-width="1" stroke-linecap="round" />

        <!-- Steam Curves (Warm amber tea aroma) -->
        <path d="M62 30 C66 22, 60 16, 65 10" stroke="#F59E0B" stroke-width="2.2" stroke-linecap="round" stroke-dasharray="1 2" opacity="0.8" />
        <path d="M28 32 C25 25, 29 19, 26 13" stroke="#F59E0B" stroke-width="1.8" stroke-linecap="round" stroke-dasharray="1 2" opacity="0.6" />
    </svg>
</div>
