<?php

function check_auth($required_roles = null)
{

    global $userModel;

    if (empty($_SESSION['user_id'])) {
        if ($required_roles) {
            header('Location: ?dir=login');
            exit();
        }
    } else {
        $userRole = $userModel->getUserRole($_SESSION['user_id']);
        if ($required_roles && !in_array($userRole, $required_roles)) {
            header('Location: ?dir=denegado');
            exit();
        }
    }
}

function get_valid_roles($roleLevel)
{
    $roleHierarchy = [
        'usuario' => ['usuario', 'conocido', 'distinguido', 'guru', 'admin'],
        'conocido' => ['conocido', 'distinguido', 'guru', 'admin'],
        'distinguido' => ['distinguido', 'guru', 'admin'],
        'guru' => ['guru', 'admin'],
        'admin' => ['admin']
    ];

    return $roleHierarchy[$roleLevel] ?? [];
}

function has_role($userRole, $roleLevel)
{
    $validRoles = get_valid_roles($roleLevel);
    return in_array($userRole, $validRoles, true);
}
