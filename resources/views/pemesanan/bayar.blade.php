@extends('layouts.app', ['title' => 'Pembayaran Tes'])

@section('content')
    @php
        // Warna VitaCheck Unila
        $primaryColor = '#0f52ba';
        $secondaryColor = '#3f67ba';
        $totalBayar = number_format($pesan->total_bayar, 0, ',', '.');
    @endphp

    <div class="max-w-xl mx-auto space-y-6">
        <h1 class="text-3xl font-bold text-center" style="color: {{ $primaryColor }}">
            Selesaikan Pembayaran
        </h1>

        <div class="bg-white rounded-2xl ring-1 ring-gray-100 p-8 shadow-xl">
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 mb-1">Kode Pemesanan Anda:</p>
                <span class="text-2xl font-mono font-extrabold text-gray-800 tracking-wider inline-block px-3 py-1 rounded-lg bg-gray-50 border"
                      style="color: {{ $secondaryColor }}; border-color: {{ $primaryColor }}33;">
                    {{ $pesan->kode }}
                </span>
            </div>

            {{-- Rangkuman Pembayaran --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-dashed border-gray-200">
                <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                    <p class="text-base text-gray-700 font-medium">Total yang harus dibayar:</p>
                    <p class="text-4xl font-extrabold" style="color: {{ $secondaryColor }}">
                        Rp {{ $totalBayar }}
                    </p>
                </div>
                
                <div class="flex items-center justify-between pt-3">
                    <p class="text-xs text-gray-500">Batas Pembayaran:</p>
                    <div class="text-right">
                        <p class="text-sm font-bold text-rose-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            @if($pesan->kedaluwarsa_pada)
                                {{ \Illuminate\Support\Carbon::parse($pesan->kedaluwarsa_pada)->translatedFormat('d M Y, H:i') }} WIB
                            @else
                                -
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">(Pastikan pop-up Midtrans tidak diblokir)</p>
                    </div>
                </div>
            </div>

            {{-- Tombol Bayar --}}
            <div class="text-center">
                <button id="payBtn" type="button" 
                        class="inline-flex items-center justify-center w-full px-8 py-4 text-lg font-extrabold rounded-xl transition duration-200 shadow-xl text-white hover:opacity-90"
                        style="background-color: {{ $primaryColor }}">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10h18M7 15h1m4 0h1m-9 5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Bayar Sekarang
                </button>
            </div>

            {{-- Pesan error fallback --}}
            <div id="snapError" class="hidden mt-6 text-sm rounded-xl bg-rose-50 text-rose-700 ring-1 ring-rose-200 px-4 py-3 font-medium">
                <svg class="w-4 h-4 inline mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span></span>
            </div>
            
            {{-- Detail Pemesanan --}}
            <div class="mt-6 border-t border-gray-100 pt-6 grid grid-cols-2 gap-y-3 text-sm">
                
                <p class="font-medium text-gray-800 col-span-2 border-b pb-2 mb-2" style="color: {{ $primaryColor }}">
                    Detail Pemesanan
                </p>

                <p class="text-gray-600">Jenis Tes</p>
                <p class="text-gray-800 font-semibold text-right">{{ $pesan->jenisTes->nama ?? '-' }}</p>

                <p class="text-gray-600">Jadwal Tes</p>
                <p class="text-gray-800 font-semibold text-right">
                    @if($pesan->slotWaktu?->tanggal)
                        {{ \Illuminate\Support\Carbon::parse($pesan->slotWaktu->tanggal)->translatedFormat('l, d M Y') }}
                    @else
                        -
                    @endif
                    ({{ optional($pesan->slotWaktu)->mulai }} - {{ optional($pesan->slotWaktu)->selesai }})
                </p>

                <p class="text-gray-600">Biaya Tes</p>
                <p class="text-gray-800 font-semibold text-right">
                    Rp {{ number_format($pesan->jenisTes->biaya ?? 0, 0, ',', '.') }}
                </p>

                <p class="text-gray-600">Status Pembayaran</p>
                <div class="text-right">
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 border shadow-sm 
                        {{ match($pesan->status) {
                            'menunggu_bayar' => 'bg-amber-100 text-amber-800 ring-amber-300 border-amber-400',
                            'terbayar'       => 'bg-emerald-100 text-emerald-800 ring-emerald-300 border-emerald-400',
                            'kedaluwarsa'    => 'bg-rose-100 text-rose-800 ring-rose-300 border-rose-400',
                            default          => 'bg-gray-100 text-gray-700 ring-gray-200 border-gray-300'
                        } }}">
                        {{ ucfirst(str_replace('_',' ',$pesan->status)) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

@php
    $isProd = (bool) config('services.midtrans.production', false);
@endphp

{{-- Midtrans Snap Script --}}
<script src="{{ $isProd
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ config('services.midtrans.client_key') }}">
</script>

<script>
    const btn = document.getElementById('payBtn');
    const errBox = document.getElementById('snapError');
    const errSpan = errBox ? errBox.querySelector('span') : null;

    // redirect ke route SUKSES yang mengubah status jadi "terbayar"
    const redirectUrl = @json(route('pemesanan.sukses', ['order_id' => $pesan->kode]));
    const token = @json($snapToken);

    function showError(msg){
        if(!errBox || !errSpan) return;
        errSpan.textContent = msg || 'Terjadi kesalahan saat memulai pembayaran.';
        errBox.classList.remove('hidden');
    }

    btn?.addEventListener('click', function(){
        errBox?.classList.add('hidden');
        btn.setAttribute('disabled', 'disabled');
        btn.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2zm0 2c-4.411 0-8 3.589-8 8s3.589 8 8 8 8-3.589 8-8-3.589-8-8-8zM12 6v2a4 4 0 014 4h2a6 6 0 00-6-6z"/></svg> Memproses Pembayaran...';

        try {
            if (!window.snap || !token) {
                showError('Konfigurasi Midtrans belum siap. Coba refresh halaman.');
                btn.removeAttribute('disabled');
                btn.innerHTML = '<svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-9 5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Bayar Sekarang';
                return;
            }

            window.snap.pay(token, {
                onSuccess: function(){
                    window.location = redirectUrl;
                },
                onPending: function(){
                    // DEV: pending dianggap sukses
                    window.location = redirectUrl;
                },
                onError: function(result){
                    let msg = 'Pembayaran gagal atau dibatalkan.';
                    if (result && result.status_code === '406') {
                        msg = 'Anda menutup jendela pembayaran. Silakan coba lagi.';
                    } else if (result && result.status_code === '407') {
                        msg = 'Terjadi kesalahan sistem, silakan coba beberapa saat lagi.';
                    }
                    showError(msg);
                    btn.removeAttribute('disabled');
                    btn.innerHTML = '<svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-9 5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Bayar Sekarang';
                },
                onClose: function(){
                    btn.removeAttribute('disabled');
                    btn.innerHTML = '<svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-9 5h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> Bayar Sekarang';
                }
            });
        } catch(e){
            console.error(e);
            showError('Gagal membuka popup pembayaran. Pastikan pop-up tidak diblokir.');
            btn.removeAttribute('disabled');
            btn.innerHTML = '<svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-9 5h14a2 2 0 002-2V7a2 2 0 002 2z"/></svg> Bayar Sekarang';
        }
    });
</script>
@endsection
