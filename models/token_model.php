<?php

class TokenModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getToken()
    {
        $stmt = $this->pdo->prepare('SELECT access_token, refresh_token, expires_in, created_at, updated_at FROM mla_tokens');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function upsertToken($access_token, $refresh_token, $expires_in)
    {
        $sql = "
            INSERT INTO mla_tokens (id, access_token, refresh_token, expires_in, created_at, updated_at)
            VALUES (1, :access_token, :refresh_token, :expires_in, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ON DUPLICATE KEY UPDATE 
                access_token = VALUES(access_token),
                refresh_token = VALUES(refresh_token),
                expires_in = VALUES(expires_in),
                updated_at = CURRENT_TIMESTAMP
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':access_token', $access_token);
        $stmt->bindParam(':refresh_token', $refresh_token);
        $stmt->bindParam(':expires_in', $expires_in);

        $stmt->execute();
    }
}
