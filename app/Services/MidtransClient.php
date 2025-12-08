<?php

namespace App\Services;

class MidtransClient
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');   // HARUS Server Key
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.production', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    public function createSnapToken(array $params): string
    {
        return \Midtrans\Snap::getSnapToken($params);
    }
}
