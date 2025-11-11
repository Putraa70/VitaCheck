<x-guest-gradient-layout :title="'Verifikasi Email • VitaCheck'">
    <div class="space-y-4">
        <h2 class="text-lg font-semibold">Cek email kamu 📬</h2>
        <p class="text-sm text-gray-600">
            Kami telah mengirim tautan verifikasi ke <strong>{{ auth()->user()->email ?? 'email Anda' }}</strong>.
            Jika belum menerima, kamu bisa meminta ulang di bawah ini.
        </p>

        @if (session('status') == 'verification-link-sent')
            <x-auth-session-status :status="'Tautan verifikasi baru telah dikirim.'" />
        @endif

        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-primary-button>Kirim Ulang</x-primary-button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-secondary-button onclick="event.preventDefault(); this.closest('form').submit();">Keluar</x-secondary-button>
            </form>
        </div>
    </div>
</x-guest-gradient-layout>
