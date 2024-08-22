<?php

function convertToLocalTime($timestamp, $serverTimeZone = 'UTC', $targetTimeZone = 'America/Argentina/Buenos_Aires')
{
    $date = new DateTime($timestamp, new DateTimeZone($serverTimeZone));
    $date->setTimezone(new DateTimeZone($targetTimeZone));
    return $date->format('Y-m-d H:i:s');
}
