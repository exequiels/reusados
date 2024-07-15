<?php

// Para país
function convertirPais($pais)
{
    switch ($pais) {
        case "argentina":
            return "mla";
        case "paraguay":
            return "mla";
        case "chile":
            return "mlc";
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
