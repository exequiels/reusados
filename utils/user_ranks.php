<?php

function nivelDeUsuario($usuario)
{
    switch ($usuario) {
        case "usuarios":
            return "1";
        case "conocidos":
            return "2";
        case "distinguidos":
            return "3";
        case "gurus":
            return "4";
        case "admin":
            return "5";
        default:
            return "-";
    }
}
