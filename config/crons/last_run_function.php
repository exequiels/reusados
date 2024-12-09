<?php
function updateJobLog($pdo, $jobName)
{
    logMessage("Updating job log for: $jobName");
    try {
        $stmt = $pdo->prepare("INSERT INTO cron_log (job_name, last_run) VALUES (?, NOW()) ON DUPLICATE KEY UPDATE last_run = NOW()");
        $result = $stmt->execute([$jobName]);
        if ($result) {
            logMessage("Job log updated successfully for: $jobName");
        } else {
            $error = $stmt->errorInfo();
            logMessage("Failed to update job log for: $jobName. Error: " . $error[2]);
        }
    } catch (PDOException $e) {
        logMessage("Exception when updating job log for: $jobName. Error: " . $e->getMessage());
    }
}