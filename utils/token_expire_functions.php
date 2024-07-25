<?php

function isTokenExpired($updatedAt, $expiresIn)
{
    $updatedAtDateTime = new DateTime($updatedAt);
    $currentDateTime = new DateTime();
    $expiryDateTime = clone $updatedAtDateTime;
    $expiryDateTime->modify("+{$expiresIn} seconds");
    return $currentDateTime > $expiryDateTime;
}

$refreshTokenExpiresIn = 15552000;
$tokenExpired = false;
if (!empty($tokenData)) {
    $updatedAt = $tokenData[0]['updated_at'];
    $expiresIn = $tokenData[0]['expires_in'];
    $tokenExpired = isTokenExpired($updatedAt, $expiresIn);
    $refreshTokenExpired = isTokenExpired($updatedAt, $refreshTokenExpiresIn);
}
