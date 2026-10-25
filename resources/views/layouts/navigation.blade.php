<nav x-data="{ open: false }" @keydown.escape.window="open = false" class="sticky top-0 z-40 pt-3 px-3 sm:px-6 lg:px-8 pb-1">
    <!-- Desktop & Mobile Floating Island Capsule Navbar -->
    <div class="max-w-7xl mx-auto">
        <div class="bg-white/95 backdrop-blur-xl border border-stone-200/90 rounded-2xl shadow-md shadow-stone-200/40 px-3.5 sm:px-5 py-2.5 flex items-center justify-between gap-3 relative transition-all duration-200">
            <!-- Subtle Top Glow Accent -->
            <div class="absolute inset-x-8 top-0 h-0.5 bg-gradient-to-r from-transparent via-emerald-500/40 to-transparent pointer-events-none"></div>

            <!-- Left: Brand / Logo (Desktop) & Mobile Hamburger Trigger -->
            <div class="flex items-center gap-3">
                <!-- Mobile Hamburger Button (On Left to indicate Left Drawer) -->
                <div class="flex items-center xl:hidden">
                    <button
                        type="button"
                        @click="open = true"
                        :aria-expanded="open"
                        aria-controls="mobile-drawer"
                        aria-label="Buka menu samping"
                        class="inline-flex items-center justify-center p-2 rounded-xl text-stone-600 bg-stone-100/80 hover:text-emerald-800 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-600/30 border border-stone-200/80 transition"
                    >
                        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Logo & Brand Info -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 p-1 bg-gradient-to-br from-emerald-50 to-white rounded-xl shadow-xs border border-stone-200/90 group-hover:border-emerald-400 group-hover:shadow-emerald-500/10 transition duration-200 flex items-center justify-center">
                        <x-application-logo class="w-full h-full transform group-hover:scale-105 transition duration-200" />
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-sm font-extrabold text-emerald-950 tracking-tight leading-tight">UMKM Teh Solo</span>
                        </div>
                        <span class="hidden sm:block text-[11px] font-medium text-stone-500 leading-none mt-0.5">Sistem Kasir & Manajemen</span>
                    </div>
                </a>
            </div>

            <!-- Center: Desktop Navigation Segmented Pill Dock -->
            <div class="hidden xl:flex items-center justify-center p-1 bg-stone-100/80 rounded-2xl border border-stone-200/70 gap-1 shadow-inner">
                @role('admin')
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>{{ __('Dashboard') }}</span>
                    </x-nav-link>

                    <x-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>{{ __('Karyawan') }}</span>
                    </x-nav-link>

                    <x-nav-link :href="route('admin.produk.index')" :active="request()->routeIs('admin.produk.*')">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span>{{ __('Produk') }}</span>
                    </x-nav-link>

                    <x-nav-link :href="route('admin.penjualan.index')" :active="request()->routeIs('admin.penjualan.*')">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <span>{{ __('Penjualan') }}</span>
                    </x-nav-link>

                    <x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>{{ __('Laporan') }}</span>
                    </x-nav-link>

                    <x-nav-link :href="route('admin.forecast.index')" :active="request()->routeIs('admin.forecast.*')">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <span>{{ __('Forecast') }}</span>
                    </x-nav-link>
                @endrole

            </div>

            <!-- Right: Desktop Profile Capsule Dropdown -->
            <div class="hidden xl:flex items-center shrink-0">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-full border border-stone-200/80 bg-stone-50/80 hover:bg-emerald-50/60 hover:border-emerald-300 text-stone-700 hover:text-emerald-950 focus:outline-none transition duration-150 group">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-600 to-emerald-800 flex items-center justify-center text-white text-xs font-bold shadow-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left leading-tight">
                                <div class="text-xs font-bold text-stone-800 group-hover:text-emerald-950 truncate max-w-[120px]">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] font-semibold text-emerald-700 uppercase tracking-wider">{{ Auth::user()->role }}</div>
                            </div>
                            <svg class="w-3.5 h-3.5 text-stone-400 group-hover:text-emerald-700 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2.5 border-b border-stone-100 bg-stone-50/60">
                            <div class="text-[11px] text-stone-400 font-medium">Masuk sebagai</div>
                            <div class="text-sm font-bold text-emerald-950 truncate">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-stone-500 truncate">{{ Auth::user()->email }}</div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-stone-700 hover:text-emerald-900">
                            <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>{{ __('Profile Saya') }}</span>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-rose-600 hover:text-rose-700 hover:bg-rose-50 flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>{{ __('Keluar (Log Out)') }}</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Right: Mobile Avatar Quick Button -->
            <div class="flex items-center xl:hidden">
                <button
                    type="button"
                    @click="open = true"
                    class="flex items-center gap-1.5 p-1 rounded-full bg-stone-100 hover:bg-emerald-50 border border-stone-200/80 transition"
                    aria-label="Profil & Menu Pengguna"
                >
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-600 to-emerald-800 text-white font-bold flex items-center justify-center text-xs shadow-2xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Off-Canvas Drawer (Slides in from the LEFT) -->
    <!-- Backdrop Overlay -->
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs z-50 xl:hidden"
        aria-hidden="true"
    ></div>

    <!-- Left Off-Canvas Slide Drawer -->
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        id="mobile-drawer"
        class="fixed inset-y-0 left-0 z-50 w-72 sm:w-80 bg-white shadow-2xl flex flex-col justify-between overflow-y-auto xl:hidden border-r border-stone-200/90"
    >
        <!-- Top Drawer Header & Navigation -->
        <div>
            <!-- Header bar with logo and close button -->
            <div class="p-4 border-b border-stone-200/80 flex items-center justify-between bg-stone-50/80">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 p-1 bg-white rounded-xl shadow-xs border border-stone-200/90 flex items-center justify-center">
                        <x-application-logo class="w-full h-full" />
                    </div>
                    <div>
                        <span class="block text-sm font-extrabold text-emerald-950 tracking-tight leading-tight">UMKM Teh Solo</span>
                        <span class="block text-[11px] font-semibold text-amber-700">Tradisi Surakarta</span>
                    </div>
                </a>
                <button
                    type="button"
                    @click="open = false"
                    class="p-2 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-200/60 focus:outline-none transition"
                    aria-label="Tutup menu navigasi"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- User Mini Profile Card -->
            <div class="p-3">
                <div class="p-3 bg-gradient-to-br from-emerald-50 to-stone-50 rounded-2xl border border-emerald-200/60 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-600 to-emerald-800 text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-bold text-emerald-950 truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-stone-500 truncate">{{ Auth::user()->email }}</div>
                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-900 border border-emerald-200 uppercase tracking-wider">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Drawer Links -->
            <div class="px-3 pb-4">
                <p class="px-3 pt-2 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-stone-400">Menu Navigasi</p>
                <div class="space-y-1" @click="open = false">
                    @role('admin')
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span>{{ __('Dashboard') }}</span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>{{ __('Karyawan') }}</span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.produk.index')" :active="request()->routeIs('admin.produk.*')">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span>{{ __('Produk & Harga') }}</span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.penjualan.index')" :active="request()->routeIs('admin.penjualan.*')">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <span>{{ __('Penjualan') }}</span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <span>{{ __('Laporan') }}</span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('admin.forecast.index')" :active="request()->routeIs('admin.forecast.*')">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            <span>{{ __('Forecast') }}</span>
                        </x-responsive-nav-link>
                    @endrole

                    @role('karyawan')
                        <x-responsive-nav-link :href="route('karyawan.dashboard')" :active="request()->routeIs('karyawan.dashboard')">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span>{{ __('Dashboard') }}</span>
                        </x-responsive-nav-link>

                        <x-responsive-nav-link :href="route('karyawan.kasir.index')" :active="request()->routeIs('karyawan.kasir.*')">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span>{{ __('Kasir') }}</span>
                        </x-responsive-nav-link>
                    @endrole
                </div>
            </div>
        </div>

        <!-- Bottom Drawer Actions (Profile & Logout) -->
        <div class="p-3 border-t border-stone-200/80 bg-stone-50/70 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')" @click="open = false">
                <svg class="w-5 h-5 text-stone-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span>{{ __('Profile Saya') }}</span>
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="flex items-center gap-3 w-full px-3.5 py-2.5 rounded-xl text-start text-sm font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 border border-transparent transition duration-150"
                >
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>{{ __('Keluar (Log Out)') }}</span>
                </button>
            </form>

            <div class="pt-2 text-center">
                <p class="text-[10px] text-stone-400 font-medium">© {{ date('Y') }} UMKM Teh Solo • Otentik Tradisi</p>
            </div>
        </div>
    </div>
</nav>
