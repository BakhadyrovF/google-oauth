<?php


function decrypt_cookie(?string $cookie)
{
    try {
        if (isset($cookie)) {
            $decrypted = \Illuminate\Support\Facades\Crypt::decryptString($cookie);
            $cookieIdEndlinePosition = strpos($decrypted, '|');
            return substr($decrypted, $cookieIdEndlinePosition + 1);
        }
    } catch (\Exception $e) {
        return $cookie;
    }



}
