<?php

function has_permission($permission_id)
{
    global $permisosModel, $userModel;

    // Verificar si hay una sesión de usuario iniciada
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    // Obtener el rol del usuario actual
    $userRole = $userModel->getUserRole($_SESSION['user_id']);

    // Obtener los IDs de los roles
    $rolesMap = [
        'usuarios' => 1,
        'conocidos' => 2,
        'distinguidos' => 3,
        'gurus' => 4,
        'admin' => 5
    ];

    // Convertir el nombre del rol a su ID
    $rolId = $rolesMap[$userRole] ?? null;

    if ($rolId === null) {
        return false;
    }

    // Obtener los permisos para este rol
    $userPermissions = $permisosModel->getPermisosPorRolId($rolId);

    // Verificar si el permiso existe para este rol
    return in_array($permission_id, $userPermissions);
}
