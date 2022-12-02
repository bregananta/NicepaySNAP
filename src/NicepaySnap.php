<?php

namespace Bregananta\NicepaySnap;

use Carbon\Carbon;
use Faker\Core\Number;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NicepaySnap
{
    public function requestToken(array $additionalInfo = null):string
    {
        if ($additionalInfo == null) $additionalInfo = [];
        $body = [
            'grant_type' => 'client_credentials',
            'additionalInfo' => $additionalInfo,
        ];

        return $this->apiRequestToken('v1.0/access-token/b2b', $body);
    }

    
    protected function apiRequestToken(string $path, array $body)
    {
        $url = $this->getBaseUrl() .'/'. $path;

        if (config('nicepaysnap-config.log') == true) {
            Log::info('Nicepay Endpoint : '. PHP_EOL);
            Log::info($url);
        }

        $xTimeStamp = $this->getTimeStamp();
        $signature = $this->getSignature($xTimeStamp);

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-TIMESTAMP' => $xTimeStamp,
            'X-CLIENT KEY' => $this->getClientId(),
            'X-SIGNATURE' => $signature
        ])->post($url, $body)->body();
    }

    protected function getClientId():string
    {
        return config('nicepaysnap-config.client_id');
    }

    protected function getClientSecret():string
    {
        return config('nicepaysnap-config.client_secret');
    }

    protected function getBaseUrl():string
    {
        return config('nicepaysnap-config.base_url');
    }

    protected function getPrivateKey():string
    {
        return config('nicepaysnap-config.private_key');
    }

    protected function getTimeStamp():string
    {
        $d = Carbon::now('Asia/Jakarta');
        $xTimeStamp = $d->toDate()->format('Y-m-d') .'T'. $d->toDate()->format('H:i:sP');

        return $xTimeStamp;
    }

    protected function getSignature($timeStamp):string
    {
        $string = $this->getClientId() . '|' . $timeStamp;
        $private_key = $this->getPrivateKey();
        openssl_sign($string, $signature, $private_key, OPENSSL_ALGO_SHA256);

        return $signature;
    }

}