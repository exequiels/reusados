<?php

function encryptEmail($email, $key, $iv)
{
    $encrypted = openssl_encrypt($email, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($encrypted);
}

function decryptEmail($encryptedData, $key, $iv)
{
    $data = base64_decode($encryptedData);
    return openssl_decrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}

$key_emails = base64_decode($key_emails);
$key_emails_iv = base64_decode($key_emails_iv);
