<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Shared\Exceptions\BusinessException;
use Exception;

class Google2FAService
{
    /**
     * Generate a new 2FA secret key.
     */
    public function generateSecretKey(int $length = 16): string
    {
        $validChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        
        try {
            for ($i = 0; $i < $length; $i++) {
                $secret .= $validChars[random_int(0, 31)];
            }
        } catch (Exception) {
            // Fallback in case random_int fails
            for ($i = 0; $i < $length; $i++) {
                $secret .= $validChars[rand(0, 31)];
            }
        }

        return $secret;
    }

    /**
     * Generate QR Code URL.
     */
    public function getQRCodeUrl(string $company, string $holder, string $secret): string
    {
        return 'otpauth://totp/' . rawurlencode($company) . ':' . rawurlencode($holder) 
            . '?secret=' . $secret . '&issuer=' . rawurlencode($company);
    }

    /**
     * Validate verification code.
     */
    public function verifyCode(string $secret, string $code, int $discrepancy = 1): bool
    {
        $currentTimeSlice = (int) floor(time() / 30);

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = $this->calculateCode($secret, $currentTimeSlice + $i);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate code for a given secret and time slice.
     */
    private function calculateCode(string $secret, int $timeSlice): string
    {
        $secretKey = $this->base32Decode($secret);

        // Pack time slice into binary string
        $timeBin = pack('N*', 0) . pack('N*', $timeSlice);

        // Calculate HMAC-SHA1
        $hmac = hash_hmac('sha1', $timeBin, $secretKey, true);

        // Dynamic truncation
        $offset = ord($hmac[19]) & 0xf;
        $hashPart = substr($hmac, $offset, 4);

        // Unpack value
        $value = unpack('N', $hashPart);
        $value = $value[1];
        $value = $value & 0x7fffffff;

        // Modulo to get 6-digit code
        $code = $value % 1000000;

        return str_pad((string)$code, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Decode a base32 string.
     */
    private function base32Decode(string $base32): string
    {
        $base32 = strtoupper($base32);
        $validChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $lookupTable = array_flip(str_split($validChars));

        $paddingCharCount = 0;
        if (str_ends_with($base32, '=')) {
            $paddingCharCount = substr_count($base32, '=');
        }

        $base32 = str_replace('=', '', $base32);
        $base32Len = strlen($base32);
        $binaryString = '';

        for ($i = 0; $i < $base32Len; $i = $i + 8) {
            $x = '';
            if (!isset($base32[$i])) {
                break;
            }
            
            for ($j = 0; $j < 8; $j++) {
                if (isset($base32[$i + $j])) {
                    $char = $base32[$i + $j];
                    if (!isset($lookupTable[$char])) {
                        continue;
                    }
                    $x .= str_pad(decbin($lookupTable[$char]), 5, '0', STR_PAD_LEFT);
                }
            }

            $eightBits = str_split($x, 8);
            foreach ($eightBits as $z) {
                if (strlen($z) === 8) {
                    $binaryString .= chr((int)bindec($z));
                }
            }
        }

        return $binaryString;
    }
}
