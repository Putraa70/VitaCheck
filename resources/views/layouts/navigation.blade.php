<nav x-data="{ open: false }"
     class="sticky top-0 z-40 border-b border-blue-800/30 backdrop-blur-md"
     style="background: linear-gradient(135deg, rgba(30,64,175,.95) 0%, rgba(37,99,235,.95) 50%, rgba(20,184,166,.95) 100%);">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <x-application-logo class="block h-8 w-auto text-white" />
                    <span class="text-white font-bold text-lg tracking-wide">VitaCheck</span>
                </a>

                <div class="hidden sm:flex sm:items-center sm:gap-6">
                    {{-- <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white/80 hover:text-emerald-200">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')" class="text-white/80 hover:text-emerald-200">
                        {{ __('Profil') }}
                    </x-nav-link> --}}

                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-emerald-300 hover:text-white font-semibold">
                            {{ __('Admin') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md text-white/90 hover:text-white hover:bg-white/10 transition">
                            <div class="truncate max-w-[10rem]">{{ Auth::user()->name }}</div>
                            <svg class="h-4 w-4 text-white/90" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white/90 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-blue-900/90 pb-3" style="font-family: 'Inter', sans-serif;">
    <div class="pt-2 pb-3 space-y-1 px-4">
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
            {{ __('Profil') }}
        </x-responsive-nav-link>
        @if(auth()->check() && auth()->user()->role === 'admin')
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-emerald-200">
                {{ __('Admin') }}
            </x-responsive-nav-link>
        @endif
    </div>

    <div class="pt-4 pb-1 border-t border-blue-700">
        <div class="px-4">
            <div class="font-medium text-base text-white truncate">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-white/70">{{ Auth::user()->email }}</div>
        </div>

        <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profil') }}
            </x-responsive-nav-link>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Keluar') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</div>