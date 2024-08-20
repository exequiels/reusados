<?php

class ErrorLogModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // public function getAllErrors($limit, $offset)
    // {
    //     try {
    //         $stmt = $this->pdo->prepare('
    //             SELECT * FROM error_log
    //             ORDER BY created_at DESC
    //             LIMIT :offset, :limit
    //         ');
    //         $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    //         $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    //         $stmt->execute();
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     } catch (PDOException $e) {
    //         error_log('Error fetching errors: ' . $e->getMessage());
    //         return [];
    //     }
    // }

    public function getAllErrors()
    {
        try {
            $stmt = $this->pdo->prepare('
                SELECT * FROM error_log
                ORDER BY id DESC
            ');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error fetching errors: ' . $e->getMessage());
            return [];
        }
    }

    public function countErrors()
    {
        try {
            $stmt = $this->pdo->query('SELECT COUNT(*) AS total FROM error_log');
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log('Error counting errors: ' . $e->getMessage());
            return 0;
        }
    }
}
