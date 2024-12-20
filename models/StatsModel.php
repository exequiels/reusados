<?php

class StatsModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getClicksSubcategorias($pais)
    {
        try {
            $categoria = "consolasyvideojuegos";
            $tableName = $pais . "_" . $categoria . "_clicks";

            $query = "SELECT subcategoria, SUM(clicks) AS total_clicks 
                      FROM $tableName 
                      GROUP BY subcategoria 
                      ORDER BY subcategoria";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getTotalClicks($pais)
    {
        try {
            $categoria = "consolasyvideojuegos";
            $tableName = $pais . "_" . $categoria . "_clicks";

            $query = "SELECT SUM(clicks) AS total_clicks 
                      FROM $tableName";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC)['total_clicks'];
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getClicksPorDia($pais, $startDate, $endDate)
    {
        try {
            $query = "SELECT DAY(click_date) as dia, SUM(clicks) as total_clicks 
                  FROM {$pais}_consolasyvideojuegos_clicks
                  WHERE click_date BETWEEN :start_date AND :end_date
                  GROUP BY dia 
                  ORDER BY dia";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
