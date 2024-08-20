<?php

class ErrorHandler
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function logError($exception)
    {
        try {
            $message = $exception->getMessage();
            $code = $exception->getCode();
            $url = $_SERVER['REQUEST_URI'];
            $file = $exception->getFile();
            $line = $exception->getLine();

            $stmt = $this->pdo->prepare('
                INSERT INTO error_log (message, code, url, file, line, created_at) 
                VALUES (:message, :code, :url, :file, :line, NOW())
            ');
            $stmt->bindValue(':message', $message);
            $stmt->bindValue(':code', $code);
            $stmt->bindValue(':url', $url);
            $stmt->bindValue(':file', $file);
            $stmt->bindValue(':line', $line);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error logging error: ' . $e->getMessage());
        }
    }
}
