<?php

$logFile = '/home/username/public_html/config/cron_test_output.log';

file_put_contents($logFile, "Esto es un test a las " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

error_log("El cron job 'cron_test.php' se ejecutó a las " . date('Y-m-d H:i:s'));

echo "Esto es un test";
