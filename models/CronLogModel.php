<?php

class CronLogModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getLastRunByJobName($jobName)
    {
        try {
            $sql = "SELECT last_run FROM cron_log WHERE job_name = :job_name LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':job_name', $jobName);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['last_run'] : null;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
