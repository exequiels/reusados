<?php

function nivelDeUsuario($usuario)
{
    switch ($usuario) {
        case "user":
            return "1";
        case "member":
            return "2";
        case "veteran":
            return "3";
        case "mod":
            return "4";
        case "admin":
            return "5";
        default:
            return "-";
    }
}
