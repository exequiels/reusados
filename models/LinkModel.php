<?php

class LinkModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Check if a row exists for the given date, category, and subcategory
    public function rowExists($tabla, $currentDate, $categoria, $subcategoria)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) 
                FROM $tabla 
                WHERE click_date = ? AND categoria = ? AND subcategoria = ?
            ");
            $stmt->execute([$currentDate, $categoria, $subcategoria]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    // Insert a new click record
    public function insertClick($tabla, $currentDate, $categoria, $subcategoria)
    {
        try {
            $insertStmt = $this->pdo->prepare("
                INSERT INTO $tabla (click_date, categoria, subcategoria, clicks) 
                VALUES (?, ?, ?, 1)
            ");
            $insertStmt->execute([$currentDate, $categoria, $subcategoria]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    // Update an existing click record
    public function updateClick($tabla, $currentDate, $categoria, $subcategoria)
    {
        try {
            $updateStmt = $this->pdo->prepare("
                UPDATE $tabla 
                SET clicks = clicks + 1 
                WHERE click_date = ? AND categoria = ? AND subcategoria = ?
            ");
            $updateStmt->execute([$currentDate, $categoria, $subcategoria]);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
