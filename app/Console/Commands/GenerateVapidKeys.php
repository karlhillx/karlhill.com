<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateVapidKeys extends Command
{
    protected $signature = 'push:vapid';

    protected $description = 'Generate VAPID keys for Web Push and print env assignments';

    public function handle(): int
    {
        $key = openssl_pkey_new([
            'private_key_type' => OPENSSL_KEYTYPE_EC,
            'curve_name' => 'prime256v1',
        ]);

        if ($key === false) {
            $this->error('Unable to generate an EC key (openssl).');

            return self::FAILURE;
        }

        $details = openssl_pkey_get_details($key);
        if ($details === false || ! is_string($details['ec']['x'] ?? null) || ! is_string($details['ec']['y'] ?? null)) {
            $this->error('Unexpected OpenSSL EC key shape.');

            return self::FAILURE;
        }

        $uncompressed = "\x04".$details['ec']['x'].$details['ec']['y'];
        $rawPrivate = $details['ec']['d'] ?? '';

        $publicKey = self::base64Url($uncompressed);
        $privateKey = self::base64Url((string) $rawPrivate);

        $this->line('VAPID_PUBLIC_KEY='.$publicKey);
        $this->line('VAPID_PRIVATE_KEY='.$privateKey);
        $this->comment('Add these to .env. Do not commit the private key.');

        return self::SUCCESS;
    }

    protected static function base64Url(string $bytes): string
    {
        return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
    }
}
