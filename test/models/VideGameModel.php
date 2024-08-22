<?php

class VideoGameModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllVideoGames($filters, $page = 1, $perPage = 15)
    {
        try {
            $sql = "SELECT * FROM mla_consolasyvideojuegos_principal WHERE 1=1";
            $params = [];

            if (!empty($filters['palabra'])) {
                $sql .= " AND all_titles LIKE :palabra";
                $params[':palabra'] = '%' . $filters['palabra'] . '%';
            }

            if (!empty($filters['subcategoria'])) {
                $sql .= " AND all_item_categoria = :subcategoria";
                $params[':subcategoria'] = $filters['subcategoria'];
            }

            if (!empty($filters['precio_min'])) {
                $sql .= " AND CAST(all_prices AS DECIMAL) >= :precio_min";
                $params[':precio_min'] = $filters['precio_min'];
            }

            if (!empty($filters['precio_max'])) {
                $sql .= " AND CAST(all_prices AS DECIMAL) <= :precio_max";
                $params[':precio_max'] = $filters['precio_max'];
            }

            if (!empty($filters['orden'])) {
                switch ($filters['orden']) {
                    case 'precio_asc':
                        $sql .= " ORDER BY CAST(all_prices AS DECIMAL) ASC";
                        break;
                    case 'precio_desc':
                        $sql .= " ORDER BY CAST(all_prices AS DECIMAL) DESC";
                        break;
                    case 'alfabetico':
                        $sql .= " ORDER BY all_titles ASC";
                        break;
                }
            }

            $offset = ($page - 1) * $perPage;
            $sql .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $perPage;
            $params[':offset'] = $offset;

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function countVideoGames($filters)
    {
        try {
            $sql = "SELECT COUNT(*) FROM mla_consolasyvideojuegos_principal WHERE 1=1";
            $params = [];

            if (!empty($filters['palabra'])) {
                $sql .= " AND all_titles LIKE :palabra";
                $params[':palabra'] = '%' . $filters['palabra'] . '%';
            }

            if (!empty($filters['subcategoria'])) {
                $sql .= " AND all_item_categoria = :subcategoria";
                $params[':subcategoria'] = $filters['subcategoria'];
            }

            if (!empty($filters['precio_min'])) {
                $sql .= " AND CAST(all_prices AS DECIMAL) >= :precio_min";
                $params[':precio_min'] = $filters['precio_min'];
            }

            if (!empty($filters['precio_max'])) {
                $sql .= " AND CAST(all_prices AS DECIMAL) <= :precio_max";
                $params[':precio_max'] = $filters['precio_max'];
            }

            if (!empty($filters['orden'])) {
                switch ($filters['orden']) {
                    case 'precio_asc':
                        $sql .= " ORDER BY CAST(all_prices AS DECIMAL) ASC";
                        break;
                    case 'precio_desc':
                        $sql .= " ORDER BY CAST(all_prices AS DECIMAL) DESC";
                        break;
                    case 'alfabetico':
                        $sql .= " ORDER BY all_titles ASC";
                        break;
                }
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();

        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getLastInsertion()
    {
        try {
            $sql = "SELECT created_at FROM mla_consolasyvideojuegos_principal ORDER BY created_at DESC LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();

        } catch (PDOException $e) {
            throw $e;
        }
    }
}
