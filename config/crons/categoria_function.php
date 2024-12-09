<?php 
function obtenerNombreCategoria($categoryId, $categoryMappingsConsolas) {
    logMessage("Buscando categoría para ID: $categoryId");
    logMessage("Tamaño del array de mapeo: " . count($categoryMappingsConsolas));

    if (isset($categoryMappingsConsolas[$categoryId])) {
        logMessage("Categoría encontrada directamente: " . $categoryMappingsConsolas[$categoryId]);
        return $categoryMappingsConsolas[$categoryId];
    }
    
    // Buscar categoría padre
    foreach ($categoryMappingsConsolas as $id => $name) {
        if (strpos($categoryId, $id) === 0) {
            logMessage("Categoría padre encontrada: $name");
            return $name;
        }
    }

    logMessage("Categoría no encontrada para ID: $categoryId");
    return 'Desconocida';
}

// Ejemplo de uso
$categoryMappingsConsolas = [
        "MLA1144" => "General",
        "MLA438578" => "Accesorios",
        "MLA87725" => "Accesorios", // "Para Atari"
        "MLA272190" => "Accesorios", // - "Fuentes de Alimentación"
        "MLA438750" => "Accesorios", // - "Gamepads y Joysticks"
        "MLA88481"  => "Accesorios", // - "Otros"
        "MLA37268" => "Accesorios", // "Para Family Game"
        "MLA438751" => "Accesorios", // - "Joysticks"
        "MLA72872"  => "Accesorios", // - "Otros"
        "MLA439548" => "Accesorios", // "Para NeoGeo"
        "MLA272193" => "Accesorios", // - "Fuentes de Alimentación"
        "MLA272192" => "Accesorios", // - "Gamepads y Joysticks"
        "MLA272191" => "Accesorios", // - "Otros"
        "MLA373768" => "Accesorios", // "Para Nintendo"
        "MLA6002" => "Accesorios", // "Game Boy"
        "MLA32223" => "Accesorios", // - "Game Boy Advance"
        "MLA151598" => "Accesorios", // - "Game Boy Color"
        "MLA2921" => "Accesorios", // - "GameCube"
        "MLA87733" => "Accesorios", // - "NES"
        "MLA439671" => "Accesorios", // - "Nintendo 2DS"
        "MLA92670" => "Accesorios", // - "Nintendo 3DS"
        "MLA6003" => "Accesorios", // - "Nintendo 64"
        "MLA11050" => "Accesorios", // - "Nintendo DS"
        "MLA407558" => "Accesorios", // - "Nintendo Switch"
        "MLA11223" => "Accesorios", // - "Nintendo Wii"
        "MLA126424" => "Accesorios", // - "Nintendo Wii U"
        "MLA167955" => "Accesorios", // - "Otros Nintendos"
        "MLA4378" => "Accesorios", // - "SNES - Super Nintendo"
        "MLA1152" => "Accesorios", // "Para Otras Consolas"
        "MLA373769" => "Accesorios", // "Para PlayStation"
        "MLA167956" => "Accesorios", // - "Otros PlayStations"
        "MLA106855" => "Accesorios", // - "PS Vita - PlayStation Vita"
        "MLA6004" => "Accesorios", // - "PS1 - PlayStation 1"
        "MLA5998" => "Accesorios", // - "PS2 - PlayStation 2"
        "MLA11623" => "Accesorios", // - "PS3 - PlayStation 3"
        "MLA121958" => "Accesorios", // - "PS4 - PlayStation 4"
        "MLA455263" => "Accesorios", // - "PS5 - PlayStation 5"
        "MLA6831" => "Accesorios", // - "PSP - PlayStation Portable"
        "MLA373018" => "Accesorios", // "Para SEGA"
        "MLA6000" => "Accesorios", // - "Sega Dreamcast"
        "MLA1147" => "Accesorios", // - "Sega Genesis / Mega Drive"
        "MLA439050" => "Accesorios", // - "Sega Master System"
        "MLA439523" => "Accesorios", // - "Sega Saturn"
        "MLA432326" => "Accesorios", // - "Otros"
        "MLA373767" => "Accesorios", // "Para Xbox"
        "MLA10030" => "Accesorios", // - "Xbox 360"
        "MLA3531" => "Accesorios", // - "Xbox Clásico"
        "MLA121980" => "Accesorios", // - "Xbox One"
        "MLA455245" => "Accesorios", // - "Xbox Series X/S"
        "MLA432409" => "Accesorios", // - "Otros"
        "MLA439527" => "Accesorios", // Cat: "Accesorios"
        "MLA439596" => "Accesorios", // "Auriculares"
        "MLA448169" => "Accesorios", // "Controles para Gamers"
        "MLA439595" => "Accesorios", // "Lentes VR"
        "MLA191054" => "Accesorios", // "Micrófonos"
        "MLA187264" => "Accesorios", // "Sillas Gamer"
        "MLA456572" => "Accesorios", // "Soportes para Auriculares"
        "MLA411425" => "Accesorios", // "Tarjetas Prepagas para Juegos"
        "MLA439597" => "Accesorios", // "Otros"
        "MLA438566" => "Consolas",
        "MLA8232" => "Flippers y Arcades",
        "MLA59342" => "Flippers y Arcades", // "Accesorios y Repuestos"
        "MLA430499" => "Flippers y Arcades", // - "Billeteros"
        "MLA414197" => "Flippers y Arcades", // - "Botones"
        "MLA433576" => "Flippers y Arcades", // - "Cajas de Control de Arcades"
        "MLA414127" => "Flippers y Arcades", // - "Fichas"
        "MLA414200" => "Flippers y Arcades", // - "Kits Arcade"
        "MLA414126" => "Flippers y Arcades", // - "Monederos"
        "MLA414226" => "Flippers y Arcades", // - "Pins de Carga"
        "MLA414194" => "Flippers y Arcades", // - "Placas Multijuegos"
        "MLA414125" => "Flippers y Arcades", // - "Otros"
        "MLA417297" => "Flippers y Arcades", // "Máquinas"
        "MLA16113" => "Flippers y Arcades", // - "Flippers"
        "MLA416534" => "Flippers y Arcades", // - "Mini Máquinas Multijuegos"
        "MLA16122" => "Flippers y Arcades", // - "Máquinas Multijuegos"
        "MLA416570" => "Flippers y Arcades", // - "Máquinas de Peluches"
        "MLA417298" => "Flippers y Arcades", // - "Otras"
        "MLA412654" => "Flippers y Arcades", // - "Rockolas"
        "MLA416568" => "Flippers y Arcades", // - "Simuladoras de Manejo"
        "MLA16115" => "Flippers y Arcades", // "Otros"
        "MLA438579" => "Repuestos", // * "Repuestos para consolas"
        "MLA438639" => "Repuestos", // "Para Nintendo"
        "MLA113259" => "Repuestos", // - "Baterías"
        "MLA439727" => "Repuestos", // - "Carcasas"
        "MLA439725" => "Repuestos", // - "Discos Rígidos"
        "MLA439728" => "Repuestos", // - "Lentes Láser"
        "MLA439724" => "Repuestos", // - "Pantallas"
        "MLA438756" => "Repuestos", // - "Otros"
        "MLA438636" => "Repuestos", // "Para Otras Consolas"
        "MLA438638" => "Repuestos", // "Para PlayStation"
        "MLA59335" => "Repuestos", // "Baterías"
        "MLA433176" => "Repuestos", // - "Botones"
        "MLA431349" => "Repuestos", // - "Carcasas"
        "MLA439820" => "Repuestos", // - "Discos Rígidos"
        "MLA72931" => "Repuestos", // - "Lentes Láser"
        "MLA433167" => "Repuestos", // - "Motores Centrales"
        "MLA431059" => "Repuestos", // - "Pantallas de Repuesto"
        "MLA433174" => "Repuestos", // - "Placas de Encendido"
        "MLA438757" => "Repuestos", // - "Otros"
        "MLA439687" => "Repuestos", // "Para SEGA"
        "MLA438637" => "Repuestos", // "Para Xbox"
        "MLA72959" => "Repuestos", // - "Discos Rígidos"
        "MLA413619" => "Repuestos", // - "Lentes Láser"
        "MLA438758" => "Repuestos", // - "Otros"
        "MLA373840" => "Juegos",
        "MLA438759" => "Varios"
];