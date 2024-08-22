<?php

error_log("Esto es un test");

echo "Esto es un test";

file_put_contents('/home/username/public_html/config/cron_test_output.log', "Esto es un test a las " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
