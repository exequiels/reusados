<?php

function handleError($errno, $errstr, $errfile, $errline)
{
    global $errorHandler;
    $errorHandler->logError(new ErrorException($errstr, 0, $errno, $errfile, $errline));
}

set_error_handler('handleError');
