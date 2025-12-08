<?php

// app/Console/Commands/ExpireOrders.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\{Pemesanan, SlotWaktu};

class ExpireOrders extends Command
{
    protected $signature = 'orders:expire';
    protected $description = 'Expire unpaid orders and release holds';

    public function handle()
    {
        $expired = Pemesanan::where('status', 'menunggu_bayar')
            ->whereNotNull('kedaluwarsa_pada')
            ->where('kedaluwarsa_pada', '<', now())
            ->limit(200)
            ->get();

        foreach ($expired as $p) {
            DB::transaction(function () use ($p) {
                $p = Pemesanan::lockForUpdate()->find($p->id);
                if ($p->status !== 'menunggu_bayar') return;

                $slot = SlotWaktu::lockForUpdate()->find($p->slot_waktu_id);
                $slot?->decrement('dihold');

                $p->update(['status' => 'kedaluwarsa']);
            });
        }

        $this->info('Expired: ' . $expired->count());
        return self::SUCCESS;
    }
}
