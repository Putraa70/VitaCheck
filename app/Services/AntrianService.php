<?php

namespace App\Services;

use App\Models\Pemesanan;
use App\Models\SlotWaktu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AntrianService
{
    public const MENIT_PER_TES = 20;

    public function tetapkanAntrianDanQr(Pemesanan $p): Pemesanan
    {
        // Sudah punya antrian + QR? lewati
        if ($p->nomor_antrian && $p->waktu_tes && $p->qr_checkin) {
            return $p;
        }

        // Pastikan status eligible
        if (!in_array($p->status, ['terbayar', 'terkonfirmasi'], true)) {
            return $p;
        }

        return DB::transaction(function () use ($p) {
            /** @var SlotWaktu $slot */
            $slot = SlotWaktu::whereKey($p->slot_waktu_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$slot->tanggal || !$slot->mulai || !$slot->selesai || !$slot->kuota) {
                return $p;
            }

            $tgl     = $slot->tanggal instanceof Carbon ? $slot->tanggal->format('Y-m-d') : (string) $slot->tanggal;
            $mulai   = Carbon::parse($tgl . ' ' . $slot->mulai);
            $selesai = Carbon::parse($tgl . ' ' . $slot->selesai);

            $durasiMenit = max(0, $selesai->diffInMinutes($mulai));
            $maxByTime   = intdiv($durasiMenit, self::MENIT_PER_TES);
            $kapasitas   = max(1, min((int) $slot->kuota, $maxByTime));

            // Cari nomor antrian terakhir di slot ini
            $lastNomor = Pemesanan::where('slot_waktu_id', $slot->id)
                ->whereNotNull('nomor_antrian')
                ->orderByDesc('nomor_antrian')
                ->lockForUpdate()
                ->value('nomor_antrian') ?? 0;

            $nomor    = min($kapasitas, $lastNomor + 1);
            $waktuTes = (clone $mulai)->addMinutes(self::MENIT_PER_TES * ($nomor - 1));

            // Generate QR
            Storage::disk('public')->makeDirectory('qrcodes');

            $payload = json_encode([
                'kode' => $p->kode,
                'pid'  => $p->id,
                'slot' => $slot->id,
                'at'   => now()->toIso8601String(),
                'sig'  => substr(hash('sha256', $p->id . $p->kode . config('app.key')), 0, 16),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            $rel = "qrcodes/{$p->kode}";

            try {
                $png  = QrCode::format('png')->size(512)->errorCorrection('H')->generate($payload);
                $path = "{$rel}.png";
                Storage::disk('public')->put($path, $png);
            } catch (\Throwable $e) {
                $svg  = QrCode::format('svg')->size(512)->errorCorrection('H')->generate($payload);
                $path = "{$rel}.svg";
                Storage::disk('public')->put($path, $svg);
            }

            $p->nomor_antrian = $nomor;
            $p->waktu_tes     = $waktuTes;
            $p->qr_checkin    = $path;
            $p->save();

            return $p->refresh();
        });
    }
}
