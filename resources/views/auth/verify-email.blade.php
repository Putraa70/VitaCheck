<x-guest-gradient-layout :title="'Verifikasi Email'">
  @if (session('status') == 'verification-link-sent')
    <div class="mb-4 rounded-xl bg-emerald-50/80 text-emerald-800 ring-1 ring-emerald-200 px-4 py-3 text-sm">
      Tautan verifikasi baru telah dikirim ke email Anda.
    </div>
  @endif

  <div class="space-y-4">
    <p class="text-sm text-white/90">
      Kami telah mengirim tautan verifikasi ke
      <strong>{{ auth()->user()->email ?? 'email Anda' }}</strong>.
      Jika belum menerima, Anda dapat meminta ulang.
    </p>

    <div class="flex items-center gap-3">
      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-white text-sm
                       bg-indigo-600 hover:bg-indigo-700 shadow-sm">
          Kirim Ulang
        </button>
      </form>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-white text-sm
                       bg-white/20 hover:bg-white/30 ring-1 ring-white/30">
          Keluar
        </button>
      </form>
    </div>
  </div>
</x-guest-gradient-layout>
