<?php

function denied_permissions_functions($permission_id, $redirect_url = '?dir=denegado')
{
    if (!has_permission($permission_id)) {
        header("Location: $redirect_url");
        exit();
    }
}
