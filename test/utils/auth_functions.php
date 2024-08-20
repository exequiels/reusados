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

function is_loged_user($role)
{
    return $role === 'usuario' || $role === 'admin';
}

function is_admin($role)
{
    return $role === 'admin';
}
