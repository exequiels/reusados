<?php

class ArchivoModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getArchivoConsolas()
    {
        try {
            $sql = "SELECT * FROM archivo_consolas ORDER BY sistema ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getJuegosPorConsola($consola)
    {
        $sql = $this->pdo->prepare("
            SELECT DISTINCT juego, game_id
            FROM archivo_juegos
            WHERE sistema = :consola
            ORDER BY juego ASC
        ");
        $sql->bindParam(':consola', $consola);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJuegosConPortadaPorConsola($consola)
    {
        $sql = $this->pdo->prepare("
        SELECT aj.juego, aj.game_id, aj.sistema, ai.image_path
        FROM archivo_juegos aj
        LEFT JOIN archivo_imagenes ai 
            ON ai.game_id = aj.game_id 
            AND ai.image_path LIKE '%thumbnail_portada%'
        WHERE aj.sistema = :consola
        ORDER BY aj.juego ASC
    ");
        $sql->bindParam(':consola', $consola);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJuegoDetalles($gameId)
    {
        try {
            $sql = $this->pdo->prepare("
                SELECT * FROM archivo_juegos
                WHERE game_id = :game_id
            ");
            $sql->bindParam(':game_id', $gameId);
            $sql->execute();
            return $sql->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    // public function getImagenesJuego($gameId)
    // {
    //     $sql = "SELECT * FROM archivo_imagenes WHERE game_id = :game_id AND image_path NOT LIKE '%thumbnail%' ORDER BY id";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute(['game_id' => $gameId]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function getImagenesJuegoThumbnail($gameId)
    {
        try {
            $sql = $this->pdo->prepare("
                SELECT * FROM archivo_imagenes
                WHERE game_id = :game_id
            ");
            $sql->bindParam(':game_id', $gameId);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function getImagenesJuego($gameId)
    {
        $sql = "SELECT * FROM archivo_imagenes 
            WHERE game_id = :game_id AND image_path NOT LIKE '%thumbnail%' 
            ORDER BY CASE WHEN LOWER(image_path) LIKE '%portada%' THEN 0 ELSE 1 END, id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['game_id' => $gameId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateJuegoDetalles($gameId, $data)
    {
        try {
            $sql = "UPDATE archivo_juegos SET 
                lanzamiento = :lanzamiento,
                desarrollador = :desarrollador,
                franquicia = :franquicia,
                sistema = :sistema,
                region = :region,
                remakes = :remakes
                WHERE game_id = :game_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':game_id', $gameId, PDO::PARAM_STR);

            $this->bindParamAllowNull($stmt, ':lanzamiento', $data['lanzamiento'] ?? null);
            $this->bindParamAllowNull($stmt, ':desarrollador', $data['desarrollador'] ?? null);
            $this->bindParamAllowNull($stmt, ':franquicia', $data['franquicia'] ?? null);
            $this->bindParamAllowNull($stmt, ':sistema', $data['sistema'] ?? null);
            $this->bindParamAllowNull($stmt, ':region', $data['region'] ?? null);
            $this->bindParamAllowNull($stmt, ':remakes', $data['remakes'] ?? null);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function subirImagen($gameId, $imagePath)
    {
        try {
            $sql = "INSERT INTO archivo_imagenes (game_id, image_path, uploaded_at) 
                    VALUES (:game_id, :image_path, :uploaded_at)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':game_id', $gameId, PDO::PARAM_INT);
            $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
            $stmt->bindParam(':uploaded_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    // public function subirImagenes($gameId, $imagenesSubidas)
    // {
    //     logMessage("Iniciando subirImagenes", ["gameId" => $gameId]);

    //     $rutaDestino = 'uploads/imgs/archivo/' . $gameId . '/';
    //     logMessage("Ruta destino", $rutaDestino);

    //     if (!is_dir($rutaDestino)) {
    //         if (!mkdir($rutaDestino, 0777, true)) {
    //             logMessage("Error al crear directorio", error_get_last());
    //             return false;
    //         }
    //     }

    //     $subidaExitosa = true;
    //     foreach ($imagenesSubidas['tmp_name'] as $index => $tmpName) {
    //         $nombreArchivo = $imagenesSubidas['name'][$index];
    //         $rutaArchivo = $rutaDestino . $nombreArchivo;
    //         logMessage("Intentando mover archivo", ["tmp" => $tmpName, "destino" => $rutaArchivo]);

    //         if (move_uploaded_file($tmpName, $rutaArchivo)) {
    //             logMessage("Archivo movido exitosamente");
    //             if (!$this->guardarImagenEnBD($gameId, $rutaArchivo)) {
    //                 logMessage("Error al guardar imagen en BD");
    //                 $subidaExitosa = false;
    //             }
    //         } else {
    //             logMessage("Error al mover archivo", error_get_last());
    //             $subidaExitosa = false;
    //         }
    //     }
    //     return $subidaExitosa;
    // }
    public function subirImagenes($gameId, $imagenesSubidas)
    {
        $rutaDestino = 'uploads/imgs/archivo/' . $gameId . '/';

        if (!is_dir($rutaDestino)) {
            if (!mkdir($rutaDestino, 0777, true)) {
                return false;
            }
        }

        $subidaExitosa = true;
        foreach ($imagenesSubidas['tmp_name'] as $index => $tmpName) {
            $nombreArchivo = $imagenesSubidas['name'][$index];

            // Validar que el archivo sea una imagen
            if (!$this->esImagen($tmpName)) {
                $subidaExitosa = false;
                continue; // Saltar a la siguiente imagen
            }

            $rutaArchivo = $rutaDestino . $nombreArchivo;

            // Verificar si el archivo ya existe y renombrar si es necesario
            $rutaArchivo = $this->renombrarArchivoSiNecesario($rutaArchivo);

            if (move_uploaded_file($tmpName, $rutaArchivo)) {
                if (!$this->guardarImagenEnBD($gameId, $rutaArchivo)) {
                    $subidaExitosa = false;
                }

                // Si el nombre de la imagen contiene "portada", crear la miniatura
                if (strpos(strtolower($nombreArchivo), 'portada') !== false) {
                    $rutaMiniatura = $rutaDestino . 'thumbnail_' . $nombreArchivo;
                    if ($this->crearMiniatura($rutaArchivo, $rutaMiniatura, 100, 100)) {
                        // Solo guardar la miniatura si se creó exitosamente
                        if (!$this->guardarImagenEnBD($gameId, $rutaMiniatura)) {
                            $subidaExitosa = false;
                        }
                    } else {
                        // Manejar el error si la creación de la miniatura falló
                        $subidaExitosa = false;
                    }
                }
            } else {
                $subidaExitosa = false;
            }
        }
        return $subidaExitosa;
    }

    // Método para crear la miniatura
    private function crearMiniatura($rutaOrigen, $rutaDestino, $ancho, $alto)
    {
        $info = getimagesize($rutaOrigen);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($rutaOrigen);
                break;
            case 'image/png':
                $image = imagecreatefrompng($rutaOrigen);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($rutaOrigen);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($rutaOrigen);
                break;
            default:
                return false; // Formato de imagen no soportado
        }

        // Crear imagen en blanco para la miniatura
        $thumbnail = imagecreatetruecolor($ancho, $alto);

        // Redimensionar la imagen original y copiarla en la miniatura
        imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $ancho, $alto, $info[0], $info[1]);

        // Guardar la miniatura en la ruta especificada
        $result = false;
        switch ($mime) {
            case 'image/jpeg':
                $result = imagejpeg($thumbnail, $rutaDestino);
                break;
            case 'image/png':
                $result = imagepng($thumbnail, $rutaDestino);
                break;
            case 'image/gif':
                $result = imagegif($thumbnail, $rutaDestino);
                break;
            case 'image/webp':
                $result = imagewebp($thumbnail, $rutaDestino);
                break;
        }

        // Liberar memoria
        imagedestroy($image);
        imagedestroy($thumbnail);
        return $result;
    }

    private function esImagen($tmpName)
    {
        $info = getimagesize($tmpName);
        if ($info === false) {
            return false; // No es una imagen
        }

        // Validar el tipo MIME
        $mimeType = mime_content_type($tmpName);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']; // Agregar más tipos si es necesario
        if (!in_array($mimeType, $allowedTypes)) {
            return false;
        }

        // Validar el tamaño de la imagen
        $maxWidth = 1920; // Ancho máximo
        $maxHeight = 1080; // Alto máximo
        if ($info[0] > $maxWidth || $info[1] > $maxHeight) {
            return false; // Imagen demasiado grande
        }

        return true; // Es una imagen válida
    }

    // Función para renombrar el archivo si ya existe
    private function renombrarArchivoSiNecesario($rutaArchivo)
    {
        $contador = 1;
        $pathInfo = pathinfo($rutaArchivo);

        // Verificar si el archivo ya existe
        while (file_exists($rutaArchivo)) {
            // Crear un nuevo nombre con un sufijo numérico
            $rutaArchivo = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "($contador)." . $pathInfo['extension'];
            $contador++;
        }
        return $rutaArchivo;
    }

    private function guardarImagenEnBD($gameId, $rutaImagen)
    {
        if (empty($rutaImagen)) {
            return false;
        }

        $query = "INSERT INTO archivo_imagenes (game_id, image_path) VALUES (:game_id, :image_path)";
        $stmt = $this->pdo->prepare($query);
        $result = $stmt->execute([
            ':game_id' => $gameId,
            ':image_path' => $rutaImagen
        ]);
        return $result;
    }

    // Helper para manejar valores nulos
    private function bindParamAllowNull($stmt, $param, $value)
    {
        if ($value === '') {
            $stmt->bindValue($param, null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
        }
    }

    public function borrarImagenes($imageIds)
    {
        // 1. Obtener las rutas de los archivos antes de eliminarlos de la base de datos
        $placeholders = implode(',', array_fill(0, count($imageIds), '?'));
        $sqlSelect = "SELECT image_path FROM archivo_imagenes WHERE id IN ($placeholders)";
        $stmtSelect = $this->pdo->prepare($sqlSelect);
        $stmtSelect->execute($imageIds);
        $imagePaths = $stmtSelect->fetchAll(PDO::FETCH_COLUMN); // Obtener solo las rutas

        // 2. Borrar los registros de la base de datos
        $sqlDelete = "DELETE FROM archivo_imagenes WHERE id IN ($placeholders)";
        $stmtDelete = $this->pdo->prepare($sqlDelete);
        $stmtDelete->execute($imageIds);

        foreach ($imagePaths as $path) {
            if (file_exists($path)) {
                unlink($path); // Eliminar archivo del folder

                // Verificar si el archivo es una portada y eliminar el thumbnail
                if (strpos(strtolower($path), 'portada') !== false) {
                    $thumbnailPath = dirname($path) . '/thumbnail_' . basename($path);
                    if (file_exists($thumbnailPath)) {
                        unlink($thumbnailPath); // Eliminar thumbnail
                    }

                    // Eliminar el registro del thumbnail de la base de datos
                    $sqlDeleteThumbnail = "DELETE FROM archivo_imagenes WHERE image_path = ?";
                    $stmtDeleteThumbnail = $this->pdo->prepare($sqlDeleteThumbnail);
                    $stmtDeleteThumbnail->execute([$thumbnailPath]);
                }
            }
        }
    }

    public function getAportadoPor($gameId, $tipo)
    {
        try {
            $sql = "SELECT aportado_por, created_at FROM archivo_aportes WHERE game_id = :game_id AND tipo = :tipo";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':game_id', $gameId, PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function insertAporte($gameId, $aportadoPor, $tipo)
    {
        try {
            $sql = "INSERT INTO archivo_aportes (game_id, aportado_por, tipo) VALUES (:game_id, :aportado_por, :tipo)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':game_id', $gameId, PDO::PARAM_STR);
            $stmt->bindParam(':aportado_por', $aportadoPor, PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
