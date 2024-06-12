<?php

// Para país
function convertirPais($pais)
{
    switch ($pais) {
        case "argentina":
            return "MLA";
        case "paraguay":
            return "MLP";
        case "chile":
            return "MLC";
        default:
            return "";
    }
}

// Para categoría
function convertirCategoria($categoria)
{
    switch ($categoria) {
        case "filatelia":
            return "estampillas";
        case "juguetes":
            return "juguetes";
        case "monedas":
            return "monedasybilletes";
        case "musica":
            return "musica";
        case "videojuegos":
            return "consolasyvideojuegos";
        default:
            return "";
    }
}
