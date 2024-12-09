<?php

function nivelDeUsuario($usuario)
{
    switch ($usuario) {
        case "usuario":
            return "1";
        case "conocido":
            return "2";
        case "distinguido":
            return "3";
        case "guru":
            return "4";
        case "admin":
            return "5";
        default:
            return "-";
    }
}
