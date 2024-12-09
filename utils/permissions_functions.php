<?php

function has_permission($permission_id)
{
    global $permisosModel;

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    global $userModel;
    $userRole = $userModel->getUserRole($_SESSION['user_id']);

    $userPermissions = $permisosModel->getPermisosPorRolId($userRole);

    return in_array($permission_id, $userPermissions);
}
