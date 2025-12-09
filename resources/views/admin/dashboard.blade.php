@extends('layouts.admin', ['title' => 'Dashboard Admin'])

@section('content')
{{-- BACKDROP HALUS UNTUK AREA KONTEN (opsional, bisa dihapus kalau body sudah punya) --}}
<div class="relative -mt-2 mb-6">
  <div class="absolute inset-0 -z-10 rounded-3xl bg-gradient-to-br from-indigo-500/15 via-fuchsia-400/15 to-emerald-400/15 blur-2xl"></div>
</div>

{{-- HEADER --}}
<div class="mb-6">
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div>
      <h2 class="text-2xl font-bold tracking-tight text-slate-800 dark:text-slate-100">
        Dashboard
      </h2>
      <p class="text-sm text-slate-500 dark:text-slate-400">
        Ringkasan operasional VitaCheck Unila
      </p>
    </div>
  </div>
</div>

{{-- TOOLBAR (GLASS) --}}
<div class="rounded-2xl border border-white/20 bg-white/20 dark:bg-slate-900/30 backdrop-blur-xl shadow-xl ring-1 ring-black/5 p-4 mb-6">
  <div class="flex flex-wrap items-center gap-2">
    <a href="{{ route('admin.slot-waktu.create') }}"
       class="inline-flex items-center px-4 py-2 rounded-xl text-white bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 shadow-sm">
      + Buat Slot
    </a>
    <a href="{{ route('admin.jenis-tes.create') }}"
       class="inline-flex items-center px-4 py-2 rounded-xl text-white bg-gradient-to-r from-fuchsia-500 to-rose-500 hover:from-fuchsia-600 hover:to-rose-600 shadow-sm">
      + Tambah Jenis Tes
    </a>
    <span class="h-6 w-px bg-white/25"></span>
    <a href="{{ route('admin.fakultas.index') }}"
       class="inline-flex items-center px-4 py-2 rounded-xl border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
      Kelola Fakultas
    </a>
    <a href="{{ route('admin.program-studi.index') }}"
       class="inline-flex items-center px-4 py-2 rounded-xl border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
      Kelola Prodi
    </a>
    <a href="{{ route('admin.jenis-tes.index') }}"
       class="inline-flex items-center px-4 py-2 rounded-xl border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
      Kelola Jenis Tes
    </a>
    <a href="{{ route('admin.slot-waktu.index') }}"
       class="inline-flex items-center px-4 py-2 rounded-xl border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
      Kelola Slot
    </a>
  </div>
</div>

{{-- KPI CARDS (GLASS + ACCENT) --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  {{-- Total Pemesanan --}}
  <div class="relative overflow-hidden rounded-2xl border border-white/20 bg-white/15 dark:bg-slate-900/25 backdrop-blur-2xl shadow-xl p-5 ring-1 ring-black/5">
    <div class="absolute -top-8 -right-6 h-24 w-24 rounded-full bg-indigo-500/30 blur-2xl"></div>
    <div class="absolute -bottom-10 -left-8 h-24 w-24 rounded-full bg-violet-500/25 blur-2xl"></div>
    <p class="text-sm text-slate-700/90 dark:text-slate-200/90">Total Pemesanan</p>
    <div class="mt-1 flex items-baseline gap-2">
      <span class="text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ number_format($totalPemesanan ?? 0) }}</span>
      <a href="{{ route('admin.pemesanans.index') }}" class="text-xs underline underline-offset-4 text-indigo-700 dark:text-indigo-300">Lihat</a>
    </div>
  </div>

  {{-- Pengguna --}}
  <div class="relative overflow-hidden rounded-2xl border border-white/20 bg-white/15 dark:bg-slate-900/25 backdrop-blur-2xl shadow-xl p-5 ring-1 ring-black/5">
    <div class="absolute -top-8 -right-8 h-24 w-24 rounded-full bg-emerald-400/30 blur-2xl"></div>
    <div class="absolute -bottom-10 -left-8 h-24 w-24 rounded-full bg-teal-400/25 blur-2xl"></div>
    <p class="text-sm text-slate-700/90 dark:text-slate-200/90">Pengguna Terdaftar</p>
    <span class="mt-1 text-3xl font-semibold block text-slate-900 dark:text-slate-100">{{ number_format($totalPengguna ?? 0) }}</span>
  </div>

  {{-- Jenis Tes --}}
  <div class="relative overflow-hidden rounded-2xl border border-white/20 bg-white/15 dark:bg-slate-900/25 backdrop-blur-2xl shadow-xl p-5 ring-1 ring-black/5">
    <div class="absolute -top-8 -right-8 h-24 w-24 rounded-full bg-amber-400/30 blur-2xl"></div>
    <div class="absolute -bottom-10 -left-8 h-24 w-24 rounded-full bg-orange-400/25 blur-2xl"></div>
    <p class="text-sm text-slate-700/90 dark:text-slate-200/90">Jenis Tes</p>
    <div class="mt-1 flex items-baseline gap-2">
      <span class="text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ number_format($totalJenisTes ?? 0) }}</span>
      <a href="{{ route('admin.jenis-tes.index') }}" class="text-xs underline underline-offset-4 text-amber-700 dark:text-amber-300">Kelola</a>
    </div>
  </div>

  {{-- Slot Aktif --}}
  <div class="relative overflow-hidden rounded-2xl border border-white/20 bg-white/15 dark:bg-slate-900/25 backdrop-blur-2xl shadow-xl p-5 ring-1 ring-black/5">
    <div class="absolute -top-8 -right-8 h-24 w-24 rounded-full bg-fuchsia-400/30 blur-2xl"></div>
    <div class="absolute -bottom-10 -left-8 h-24 w-24 rounded-full bg-rose-400/25 blur-2xl"></div>
    <p class="text-sm text-slate-700/90 dark:text-slate-200/90">Slot Aktif</p>
    <div class="mt-1 flex items-baseline gap-2">
      <span class="text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ number_format($totalSlotAktif ?? 0) }}</span>
      <a href="{{ route('admin.slot-waktu.index') }}" class="text-xs underline underline-offset-4 text-fuchsia-700 dark:text-fuchsia-300">Kelola</a>
    </div>
  </div>
</div>

@php
  $statusStyles = [
    'menunggu'      => 'bg-amber-100/40 text-amber-800 ring-amber-300/50 dark:bg-amber-500/15 dark:text-amber-200 dark:ring-amber-400/20',
    'terkonfirmasi' => 'bg-sky-100/40 text-sky-800 ring-sky-300/50 dark:bg-sky-500/15 dark:text-sky-200 dark:ring-sky-400/20',
    'check_in'      => 'bg-indigo-100/40 text-indigo-800 ring-indigo-300/50 dark:bg-indigo-500/15 dark:text-indigo-200 dark:ring-indigo-400/20',
    'selesai'       => 'bg-emerald-100/40 text-emerald-800 ring-emerald-300/50 dark:bg-emerald-500/15 dark:text-emerald-200 dark:ring-emerald-400/20',
    'dibatalkan'    => 'bg-rose-100/40 text-rose-800 ring-rose-300/50 dark:bg-rose-500/15 dark:text-rose-200 dark:ring-rose-400/20',
  ];
  $toStatus = fn($s) => route('admin.pemesanans.index', ['status'=>$s]);
@endphp

{{-- SUMMARY STATUS (GLASS) --}}
<div class="grid md:grid-cols-5 gap-4 mb-6">
  @foreach (['menunggu','terkonfirmasi','check_in','selesai','dibatalkan'] as $st)
    <a href="{{ $toStatus($st) }}"
       class="rounded-2xl border border-white/20 bg-white/15 dark:bg-slate-900/30 backdrop-blur-xl shadow-xl ring-1 ring-black/5 p-4 block hover:bg-white/25 dark:hover:bg-white/10 transition">
      <p class="text-xs text-slate-600 dark:text-slate-300 mb-1 capitalize">{{ str_replace('_',' ',$st) }}</p>
      <div class="text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $kpi[$st] ?? 0 }}</div>
    </a>
  @endforeach
</div>

{{-- GRID UTAMA --}}
<div class="grid xl:grid-cols-3 gap-6">

  {{-- Tren 8 Minggu (GLASS) --}}
  <div class="xl:col-span-2 rounded-2xl border border-white/20 bg-white/15 dark:bg-slate-900/30 backdrop-blur-2xl shadow-xl ring-1 ring-black/5 p-6">
    <div class="flex items-center justify-between mb-3">
      <h3 class="font-semibold text-slate-800 dark:text-slate-100">Tren Pemesanan (8 Minggu)</h3>
      <button id="btnSaveTrend" class="inline-flex items-center px-3 py-1.5 rounded-lg border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
        Unduh PNG
      </button>
    </div>
    <canvas id="trendChart" height="120"></canvas>
  </div>

  {{-- Distribusi Jenis Tes (GLASS) --}}
  <div class="rounded-2xl border border-white/20 bg-white/15 dark:bg-slate-900/30 backdrop-blur-2xl shadow-xl ring-1 ring-black/5 p-6">
    <div class="flex items-center justify-between mb-3">
      <h3 class="font-semibold text-slate-800 dark:text-slate-100">Distribusi Jenis Tes</h3>
      <button id="btnSaveJenis" class="inline-flex items-center px-3 py-1.5 rounded-lg border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
        Unduh PNG
      </button>
    </div>
    <canvas id="jenisChart" height="120"></canvas>
    <ul class="mt-4 text-sm text-slate-700 dark:text-slate-300 space-y-1">
      @foreach(($byJenis ?? collect()) as $row)
        <li class="flex items-center justify-between">
          <span>{{ $row['label'] }}</span>
          <span class="font-medium">{{ $row['total'] }}</span>
        </li>
      @endforeach
    </ul>
  </div>

  {{-- Pemesanan Terbaru (GLASS) --}}
  <div class="xl:col-span-2 rounded-2xl border border-white/20 bg-white/15 dark:bg-slate-900/30 backdrop-blur-2xl shadow-xl ring-1 ring-black/5 p-6">
    <div class="flex items-center justify-between mb-3">
      <h3 class="font-semibold text-slate-800 dark:text-slate-100">Pemesanan Terbaru</h3>
      <div class="flex items-center gap-2">
        <a href="{{ route('admin.pemesanans.index') }}"
           class="inline-flex items-center px-3 py-1.5 rounded-lg border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
          Lihat semua
        </a>
        <button id="btnCopyAll"
                class="inline-flex items-center px-3 py-1.5 rounded-lg border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
          Salin semua kode
        </button>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-white/30 dark:bg-white/10 text-slate-800 dark:text-slate-200">
          <tr>
            <th class="px-3 py-2 text-left">Kode</th>
            <th class="px-3 py-2 text-left">Mahasiswa</th>
            <th class="px-3 py-2 text-left">Jenis Tes</th>
            <th class="px-3 py-2 text-left">Tanggal</th>
            <th class="px-3 py-2 text-left">Status</th>
            <th class="px-3 py-2 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/20 dark:divide-white/10">
          @forelse(($recent ?? collect()) as $p)
            @php
              $cls = $statusStyles[$p->status] ?? 'bg-white/40 text-slate-800 ring-white/50 dark:bg-white/10 dark:text-slate-200 dark:ring-white/10';
            @endphp
            <tr class="text-slate-800 dark:text-slate-100">
              <td class="px-3 py-2 font-medium">
                <span class="mr-2">{{ $p->kode }}</span>
                <button class="copy-kode text-xs text-indigo-700 dark:text-indigo-300 underline underline-offset-4" data-kode="{{ $p->kode }}">Salin</button>
              </td>
              <td class="px-3 py-2">{{ $p->user->name ?? '-' }}</td>
              <td class="px-3 py-2">{{ $p->jenisTes->nama ?? '-' }}</td>
              <td class="px-3 py-2">{{ optional($p->slotWaktu->tanggal)->format('d M Y') ?? '-' }}</td>
              <td class="px-3 py-2">
                <span class="inline-flex items-center rounded-full px-2.5 py-1 ring-1 text-xs {{ $cls }}">
                  {{ ucfirst(str_replace('_',' ',$p->status)) }}
                </span>
              </td>
              <td class="px-3 py-2">
                <a href="{{ route('admin.pemesanans.show',$p) }}"
                   class="inline-flex items-center px-3 py-1.5 rounded-lg border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
                  Detail
                </a>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="px-3 py-6 text-center text-slate-600 dark:text-slate-300">Belum ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Slot Mendatang (GLASS) --}}
  <div class="rounded-2xl border border-white/20 bg-white/15 dark:bg-slate-900/30 backdrop-blur-2xl shadow-xl ring-1 ring-black/5 p-6">
    <div class="flex items-center justify-between mb-3">
      <h3 class="font-semibold text-slate-800 dark:text-slate-100">Jadwal Slot Mendatang</h3>
      <a href="{{ route('admin.slot-waktu.index') }}"
         class="inline-flex items-center px-3 py-1.5 rounded-lg border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
        Kelola
      </a>
    </div>
    <ul class="space-y-3">
      @forelse(($upcomingSlots ?? collect()) as $s)
        <li class="flex items-center justify-between text-slate-800 dark:text-slate-100">
          <div>
            <p class="font-medium">{{ $s->tanggal->translatedFormat('l, d M Y') }}</p>
            @if(isset($s->kuota,$s->terpesan))
              <p class="text-xs text-slate-600 dark:text-slate-300">Sisa kuota: {{ max($s->kuota - $s->terpesan, 0) }}</p>
            @endif
          </div>
          <div class="flex gap-2">
           <a href="{{ route('admin.slot-waktu.show',$s) }}"
               class="inline-flex items-center px-3 py-1.5 rounded-lg border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
              Lihat
            </a>
            <a href="{{ route('admin.slot-waktu.edit',$s) }}"
               class="inline-flex items-center px-3 py-1.5 rounded-lg border border-white/25 bg-white/10 hover:bg-white/20 dark:bg-white/5 dark:hover:bg-white/10 backdrop-blur">
              Ubah
            </a>
          </div>
        </li>
      @empty
        <li class="text-sm text-slate-700 dark:text-slate-300">Belum ada slot mendatang.</li>
      @endforelse
    </ul>
  </div>

</div>{{-- /grid utama --}}

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Save canvas helper
  const saveCanvas=(id,fn)=>{
    const a=document.createElement('a');
    a.download=fn; a.href=document.getElementById(id).toDataURL('image/png'); a.click();
  };
  document.getElementById('btnSaveTrend')?.addEventListener('click',()=>saveCanvas('trendChart','tren-pemesanan.png'));
  document.getElementById('btnSaveJenis')?.addEventListener('click',()=>saveCanvas('jenisChart','distribusi-jenis-tes.png'));

  // Theme-aware colors for charts
  function chartColors() {
    const dark = document.documentElement.classList.contains('dark');
    return {
      text: dark ? 'rgba(226,232,240,0.85)' : 'rgba(30,41,59,0.85)',
      grid: dark ? 'rgba(148,163,184,0.18)' : 'rgba(100,116,139,0.16)',
      line: 'rgba(79,70,229,1)',
      fill: 'rgba(79,70,229,.14)'
    };
  }

  function makeTrendChart(){
    const c = chartColors();
    const trendCtx=document.getElementById('trendChart').getContext('2d');
    return new Chart(trendCtx,{
      type:'line',
      data:{
        labels:@json($labels ?? []),
        datasets:[{
          label:'Pemesanan',
          data:@json($trendSeries ?? []),
          tension:.35, fill:true, borderWidth:2,
          borderColor:c.line, backgroundColor:c.fill, pointRadius:3
        }]
      },
      options:{
        responsive:true,
        plugins:{legend:{display:false}},
        scales:{
          x:{ticks:{color:c.text}, grid:{color:c.grid}},
          y:{beginAtZero:true, ticks:{precision:0, color:c.text}, grid:{color:c.grid}}
        }
      }
    });
  }

  function makeJenisChart(){
    const c = chartColors();
    const jenisCtx=document.getElementById('jenisChart').getContext('2d');
    return new Chart(jenisCtx,{
      type:'doughnut',
      data:{
        labels:@json(collect($byJenis ?? [])->pluck('label')),
        datasets:[{
          data:@json(collect($byJenis ?? [])->pluck('total')),
          borderWidth:0,
          backgroundColor:[
            'rgba(79,70,229,.9)','rgba(14,165,233,.9)','rgba(249,115,22,.9)',
            'rgba(6,182,212,.9)','rgba(236,72,153,.9)','rgba(34,197,94,.9)'
          ]
        }]
      },
      options:{
        plugins:{legend:{position:'bottom', labels:{color:c.text}}},
        cutout:'60%'
      }
    });
  }

  // init charts
  let trendChart = makeTrendChart();
  let jenisChart = makeJenisChart();

  // re-render charts when theme toggles (jika ada tombol toggle di layout)
  const themeToggle = document.getElementById('themeToggle');
  themeToggle?.addEventListener('click', ()=>{
    setTimeout(()=>{ // tunggu class 'dark' ter-apply
      trendChart.destroy(); jenisChart.destroy();
      trendChart = makeTrendChart(); jenisChart = makeJenisChart();
    }, 0);
  });

  // copy helpers
  document.querySelectorAll('.copy-kode').forEach(b=>{
    b.addEventListener('click',()=>{
      navigator.clipboard.writeText(b.dataset.kode);
      b.textContent='Tersalin ✓'; setTimeout(()=>b.textContent='Salin',1200);
    });
  });
  document.getElementById('btnCopyAll')?.addEventListener('click',()=>{
    const all=[...document.querySelectorAll('.copy-kode')].map(b=>b.dataset.kode).join(', ');
    if(!all)return; navigator.clipboard.writeText(all);
    const el=document.getElementById('btnCopyAll'); el.textContent='Tersalin ✓';
    setTimeout(()=>el.textContent='Salin semua kode',1200);
  });
</script>
@endsection
