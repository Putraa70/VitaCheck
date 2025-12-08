<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\{Pemesanan, Pembayaran, SlotWaktu};
use App\Services\AntrianService;
use Carbon\Carbon;

class MidtransWebhookController extends Controller
{
    public function handle(Request $r, AntrianService $antrian)
    {
        Log::info('Midtrans callback MASUK', $r->all());

        $serverKey = config('services.midtrans.server_key');

        $rawOrderId     = (string) $r->order_id;
        $rawStatusCode  = (string) $r->status_code;
        $rawGrossAmount = (string) $r->gross_amount;

        $expectedSignature = hash('sha512', $rawOrderId . $rawStatusCode . $rawGrossAmount . $serverKey);
        $incomingSignature = (string) $r->signature_key;

        if (!hash_equals($expectedSignature, $incomingSignature)) {
            Log::warning('Midtrans signature mismatch', [
                'order_id'     => $rawOrderId,
                'status_code'  => $rawStatusCode,
                'gross_amount' => $rawGrossAmount,
                'expected'     => $expectedSignature,
                'incoming'     => $incomingSignature,
            ]);

            return response()->json([
                'ok'      => false,
                'message' => 'Invalid signature',
            ], 403);
        }

        try {
            DB::transaction(function () use ($r, $antrian) {
                $bayar = Pembayaran::where('order_id', $r->order_id)
                    ->lockForUpdate()
                    ->first();

                if (! $bayar) {
                    // fallback: buat record kalau ternyata belum ada
                    $bayar = Pembayaran::create([
                        'pemesanan_id' => null, // boleh kamu isi kalau mau di-link
                        'gateway'      => 'midtrans',
                        'order_id'     => $r->order_id,
                        'tipe'         => $r->payment_type ?? null,
                        'jumlah'       => (int) $r->gross_amount,
                        'status'       => $r->transaction_status,
                        'payload'      => $r->all(),
                    ]);
                }

                $pesan = Pemesanan::where('kode', $r->order_id)
                    ->lockForUpdate()
                    ->first();

                if (! $pesan) {
                    Log::error('Pemesanan TIDAK ditemukan untuk order_id Midtrans', [
                        'order_id' => $r->order_id,
                    ]);
                    return; // jangan lanjut
                }

                $slot  = SlotWaktu::whereKey($pesan->slot_waktu_id)
                    ->lockForUpdate()
                    ->first();

                if (! $slot) {
                    Log::error('SlotWaktu TIDAK ditemukan untuk pemesanan', [
                        'pemesanan_id' => $pesan->id,
                        'slot_waktu_id' => $pesan->slot_waktu_id,
                    ]);
                    return;
                }

                $bayar->status  = $r->transaction_status;
                $bayar->tipe    = $r->payment_type ?? $bayar->tipe;
                $bayar->payload = $r->all();
                $bayar->save();

                $trxStatus  = $r->transaction_status;

                if (in_array($trxStatus, ['settlement', 'capture'], true)) {
                    if ($pesan->status !== 'terbayar') {
                        $slot->decrement('dihold');
                        $slot->increment('terpesan');
                    }

                    $waktuBayar = now();
                    if (!empty($r->settlement_time)) {
                        try {
                            $waktuBayar = Carbon::parse($r->settlement_time);
                        } catch (\Throwable $e) {
                        }
                    }

                    $pesan->update([
                        'status'       => 'terbayar',
                        'dibayar_pada' => $waktuBayar,
                        'metode_bayar' => $r->payment_type ?? $pesan->metode_bayar,
                        'total_bayar'  => $pesan->total_bayar ?: (int) $r->gross_amount,
                    ]);

                    $antrian->tetapkanAntrianDanQr($pesan);

                    Log::info('Pemesanan BERHASIL ditandai terbayar dari Midtrans', [
                        'pemesanan_id' => $pesan->id,
                        'kode'         => $pesan->kode,
                    ]);
                } elseif (in_array($trxStatus, ['expire', 'cancel', 'deny'], true)) {
                    if ($pesan->status === 'menunggu_bayar') {
                        $slot->decrement('dihold');

                        $statusBaru = $trxStatus === 'expire'
                            ? 'kedaluwarsa'
                            : 'dibatalkan';

                        $pesan->update([
                            'status' => $statusBaru,
                        ]);

                        Log::info('Pemesanan kedaluwarsa/dibatalkan dari Midtrans', [
                            'pemesanan_id' => $pesan->id,
                            'kode'         => $pesan->kode,
                            'status_baru'  => $statusBaru,
                        ]);
                    }
                }
            });

            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            Log::error('Error memproses Midtrans webhook', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'ok'      => false,
                'message' => 'Server error',
            ], 500);
        }
    }
}
