<nav x-data="{ open: false }" @keydown.escape.window="open = false" class="bg-white/95 backdrop-blur-md border-b border-stone-200/80 shadow-sm relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 gap-4">
            <div class="flex min-w-0">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 p-1 bg-white rounded-lg shadow-sm border border-stone-200/80 group-hover:border-emerald-400/60 transition duration-200">
                            <x-application-logo class="block w-full h-full" />
                        </div>
                        <span class="hidden sm:block text-sm font-bold text-emerald-900 tracking-tight">UMKM Teh Solo</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden xl:flex xl:ms-8 items-center justify-center gap-1 min-w-0">
                    @role('admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ __('Karyawan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.produk.index')" :active="request()->routeIs('admin.produk.*')">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            {{ __('Produk') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.penjualan.index')" :active="request()->routeIs('admin.penjualan.*')">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            {{ __('Penjualan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            {{ __('Laporan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.forecast.index')" :active="request()->routeIs('admin.forecast.*')">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            {{ __('Forecast') }}
                        </x-nav-link>
                    @endrole

                    @role('karyawan')
                        <x-nav-link :href="route('karyawan.dashboard')" :active="request()->routeIs('karyawan.dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('karyawan.kasir.index')" :active="request()->routeIs('karyawan.kasir.*')">
                            {{ __('Kasir') }}
                        </x-nav-link>
                    @endrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden xl:flex xl:items-center xl:ms-4 shrink-0">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-stone-600 bg-white hover:text-emerald-800 hover:bg-emerald-50/50 focus:outline-none transition ease-in-out duration-200">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left">
                                    <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-stone-400">{{ ucfirst(Auth::user()->role) }}</div>
                                </div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center xl:hidden">
                <button
                    type="button"
                    @click="open = ! open"
                    :aria-expanded="open"
                    aria-controls="responsive-navigation"
                    :aria-label="open ? 'Tutup menu navigasi' : 'Buka menu navigasi'"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-stone-500 hover:text-emerald-700 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 transition duration-200 ease-in-out"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div id="responsive-navigation" :class="{'block': open, 'hidden': ! open}" class="hidden xl:hidden border-t border-stone-200/80">
        <div class="pt-3 pb-4">
            <p class="px-4 pb-2 text-xs font-semibold uppercase tracking-wider text-stone-400">Menu navigasi</p>
            <div class="space-y-1" @click="open = false">
                @role('admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">
                        {{ __('Karyawan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.produk.index')" :active="request()->routeIs('admin.produk.*')">
                        {{ __('Produk & Harga') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.penjualan.index')" :active="request()->routeIs('admin.penjualan.*')">
                        {{ __('Penjualan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">
                        {{ __('Laporan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.forecast.index')" :active="request()->routeIs('admin.forecast.*')">
                        {{ __('Forecast') }}
                    </x-responsive-nav-link>
                @endrole

                @role('karyawan')
                    <x-responsive-nav-link :href="route('karyawan.dashboard')" :active="request()->routeIs('karyawan.dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('karyawan.kasir.index')" :active="request()->routeIs('karyawan.kasir.*')">
                        {{ __('Kasir') }}
                    </x-responsive-nav-link>
                @endrole
            </div>

            <div class="mt-3 border-t border-stone-200 pt-4">
                <div class="px-4">
                    <div class="font-medium text-base text-emerald-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-stone-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" @click="open = false">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
