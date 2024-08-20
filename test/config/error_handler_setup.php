<?php

function configureGlobalExceptionHandler($pdo)
{

    $errorHandler = new ErrorHandler($pdo);

    set_exception_handler(function ($exception) use ($errorHandler) {
        $errorHandler->logError($exception);
    });
}
