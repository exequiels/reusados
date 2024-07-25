<?php

class UserModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isAccountActivated($userId)
    {
        $stmt = $this->pdo->prepare('SELECT status FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['status'] == 1;
    }

    public function activateUserByToken($token)
    {
        $query = "SELECT * FROM usuarios WHERE token_activacion = ? AND status = 0";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$token]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $update_query = "UPDATE usuarios SET status = 1, token_activacion = NULL WHERE token_activacion = ?";
            $update_stmt = $this->pdo->prepare($update_query);
            $update_stmt->execute([$token]);
            return true;
        } else {
            return false;
        }
    }

    public function getUserRole($userId)
    {
        $stmt = $this->pdo->prepare('SELECT rol FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['rol'];
    }

    public function getUsernameById($userId)
    {
        $stmt = $this->pdo->prepare('SELECT username FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['username'];
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->prepare("SELECT id, username, rol, created_at FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
