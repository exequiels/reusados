<?php

function check_maintenance()
{
    global $configModel;

    $currentPage = basename($_SERVER['REQUEST_URI'], ".php");
    if ($currentPage === '?dir=mantenimiento' || $currentPage === '?dir=login' || $currentPage === '?dir=cpanel') {
        return;
    }


    $maintenanceMode = $configModel->getMaintenanceMode();
    if ($maintenanceMode === '1') {
        header('Location: ?dir=mantenimiento');
        exit();
    }
}

function is_maintenance_on($maintenanceMode)
{
    return $maintenanceMode === '1';
}
