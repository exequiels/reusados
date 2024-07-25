<?php

class ConfigModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getMaintenanceMode()
    {
        $stmt = $this->pdo->prepare("SELECT value FROM config WHERE name = 'maintenance_mode'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function setMaintenanceMode($value)
    {
        $stmt = $this->pdo->prepare("
        UPDATE config 
        SET value = :value 
        WHERE name = 'maintenance_mode'
    ");

        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        $stmt->execute();
    }
}
