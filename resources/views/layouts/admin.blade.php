<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>{{ ($title ?? 'VitaCheck Unila') . ' · VitaCheck' }}</title>
  <meta name="color-scheme" content="light dark">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @stack('head')
  <style>
    ::-webkit-scrollbar{height:10px;width:10px}
    ::-webkit-scrollbar-thumb{background:#c7cbd1;border-radius:999px}
    .dark ::-webkit-scrollbar-thumb{background:#3a3f4a}
  </style>
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 antialiased">
  {{-- Notifikasi Global --}}
  @if(session('sukses') || session('ok') || session('error'))
    @php
      $msg = session('sukses') ?? session('ok') ?? session('error');
      $isErr = session('error');
      $color = $isErr ? 'bg-rose-600 dark:bg-rose-700' : 'bg-emerald-600 dark:bg-emerald-700';
    @endphp
    <div id="notif" class="fixed top-5 right-5 z-[60] text-white px-4 py-2 rounded-xl shadow-xl {{ $color }} animate-slidein ring-1 ring-white/10">
      {{ $msg }}
    </div>
    <script>
      setTimeout(()=>{const el=document.getElementById('notif'); if(!el) return;
        el.style.transition='opacity .4s, transform .4s';
        el.style.opacity=0; el.style.transform='translateY(-6px)';
        setTimeout(()=>el.remove(),400)
      },2000);
    </script>
    <style>
      @keyframes slidein{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
      .animate-slidein{animation:slidein .22s ease-out}
    </style>
  @endif

  <div class="min-h-screen flex">
    {{-- Sidebar --}}
    <aside id="sidebar"
      class="fixed inset-y-0 left-0 w-72 bg-white/90 backdrop-blur ring-1 ring-slate-200/80 dark:bg-slate-900/70 dark:ring-slate-800 transform -translate-x-full md:translate-x-0 md:static md:flex-shrink-0 transition-transform duration-200 z-40">
      <div class="h-16 flex items-center px-4 border-b border-slate-200 dark:border-slate-800">
        {{-- LOGO → ke admin dashboard --}}
        <a href="{{ route('admin.dasbor_admin') }}" class="inline-flex items-center gap-2 font-semibold tracking-tight">
          <span class="inline-grid place-items-center h-8 w-8 rounded-xl bg-gradient-to-br from-indigo-500 via-violet-500 to-fuchsia-500 text-white text-sm shadow-sm">V</span>
          <span class="text-slate-800 dark:text-slate-100">VitaCheck Unila</span>
        </a>
      </div>

      @php
        function navClass($isActive){
          return $isActive
            ? 'bg-gradient-to-r from-indigo-50 to-violet-50 text-indigo-700 ring-1 ring-indigo-100 dark:from-indigo-900/30 dark:to-violet-900/20 dark:text-indigo-200 dark:ring-indigo-900'
            : 'text-slate-700 hover:text-indigo-700 hover:bg-slate-50 ring-1 ring-transparent hover:ring-slate-100 dark:text-slate-300 dark:hover:text-indigo-200 dark:hover:bg-slate-800/60';
        }
      @endphp

        <div class="mt-3 mb-1 px-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Admin</div>
      <nav class="p-3 space-y-1 text-[0.94rem]">
        {{-- MENU: Admin Dashboard --}}
        <a href="{{ route('admin.dasbor_admin') }}"
           class="block px-3 py-2 rounded-xl transition {{ navClass(request()->routeIs('admin.dasbor_admin')) }}">
          <span class="inline-flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m3 12 9-8 9 8M4 10v10a1 1 0 0 0 1 1h4m6 0h4a1 1 0 0 0 1-1V10M9 21v-6h6v6"/></svg>
            Dashboard
          </span>
        </a>


        <a href="{{ route('admin.fakultas.index') }}"
           class="block px-3 py-2 rounded-xl transition {{ navClass(request()->routeIs('admin.fakultas.*')) }}">
          <span class="inline-flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
            Fakultas
          </span>
        </a>

        <a href="{{ route('admin.program-studi.index') }}"
           class="block px-3 py-2 rounded-xl transition {{ navClass(request()->routeIs('admin.program-studi.*')) }}">
          <span class="inline-flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Program Studi
          </span>
        </a>

        <a href="{{ route('admin.jenis-tes.index') }}"
           class="block px-3 py-2 rounded-xl transition {{ navClass(request()->routeIs('admin.jenis-tes.*')) }}">
          <span class="inline-flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/></svg>
            Jenis Tes
          </span>
        </a>

        <a href="{{ route('admin.slot-waktu.index') }}"
           class="block px-3 py-2 rounded-xl transition {{ navClass(request()->routeIs('admin.slot-waktu.*')) }}">
          <span class="inline-flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 0 0 2-2v-8H3v8a2 2 0 0 0 2 2z"/></svg>
            Slot Waktu
          </span>
        </a>

        <a href="{{ route('admin.pemesanans.index') }}"
           class="block px-3 py-2 rounded-xl transition {{ navClass(request()->routeIs('admin.pemesanans.*')) }}">
          <span class="inline-flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15V8a2 2 0 0 0-2-2h-3l-2-2h-4L8 6H5a2 2 0 0 0-2 2v7m2 4h14a2 2 0 0 0 2-2M3 17a2 2 0 0 0 2 2"/></svg>
            Pemesanan
          </span>
        </a>
      </nav>

      <div class="p-3 mt-auto hidden md:block">
        <div class="px-3 py-2 rounded-xl text-xs text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800/60 ring-1 ring-slate-100 dark:ring-slate-800">
          <div class="font-medium">VitaCheck v1</div>
          <div class="opacity-80">Tetap jaga privasi data pengguna.</div>
        </div>
      </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 min-w-0 md:ml-0">
      {{-- Topbar --}}
      <header class="h-16 bg-white/90 backdrop-blur ring-1 ring-slate-200 dark:bg-slate-900/70 dark:ring-slate-800 flex items-center px-3 md:px-6 justify-between sticky top-0 z-30">
        <div class="flex items-center gap-2">
          <button id="btnSidebar" class="md:hidden inline-flex items-center justify-center h-9 w-9 rounded-xl ring-1 ring-slate-200 dark:ring-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800" aria-label="Toggle Sidebar">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18"/></svg>
          </button>
          {{-- Judul topbar --}}
          <div class="font-semibold truncate">{{ $title ?? 'Admin · VitaCheck Unila' }}</div>
        </div>

        <div class="flex items-center gap-2">
          {{-- Dark mode toggle --}}
          <button id="themeToggle" class="inline-flex items-center justify-center h-9 w-9 rounded-xl ring-1 ring-slate-200 dark:ring-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800" aria-label="Toggle theme">
            <svg id="iconSun" class="h-5 w-5 hidden dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4V2m0 20v-2M4.93 4.93 3.52 3.52m16.96 16.96-1.41-1.41M4 12H2m20 0h-2M4.93 19.07 3.52 20.48m16.96-16.96-1.41 1.41M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0z"/></svg>
            <svg id="iconMoon" class="h-5 w-5 dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
          </button>

          <a href="{{ route('profile.edit') }}"
             class="hidden sm:inline-flex px-3 py-2 rounded-xl ring-1 ring-slate-200 dark:ring-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-medium">
            Profil
          </a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="px-3 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 text-white hover:from-indigo-700 hover:to-violet-700 text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-900">
              Keluar
            </button>
          </form>
        </div>
      </header>

      {{-- Content --}}
      <main class="p-3 md:p-6">
        @yield('content')
      </main>
    </div>
  </div>

  <script>
    // Persisted Dark Mode
    (function initTheme(){
      const ls = localStorage.getItem('theme');
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      if(ls === 'dark' || (!ls && prefersDark)) document.documentElement.classList.add('dark');
    })();
    document.getElementById('themeToggle')?.addEventListener('click', ()=>{
      const el = document.documentElement;
      const isDark = el.classList.toggle('dark');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });

    // Sidebar toggle (mobile)
    const btn = document.getElementById('btnSidebar');
    const sb  = document.getElementById('sidebar');
    btn?.addEventListener('click', ()=>{
      const open = !sb.classList.contains('-translate-x-full');
      if(open) sb.classList.add('-translate-x-full');
      else sb.classList.remove('-translate-x-full');
    });

    // Tutup sidebar jika klik area konten di mobile
    document.addEventListener('click', (e)=>{
      if(window.innerWidth >= 768) return;
      if(!sb.contains(e.target) && e.target !== btn && !btn.contains(e.target)){
        sb.classList.add('-translate-x-full');
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
