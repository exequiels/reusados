<?php

class PermisosModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getRoles()
    {
        try {
            $stmt = $this->pdo->query('SELECT id, rol FROM roles WHERE rol != "admin"');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getPermisos()
    {
        try {
            $stmt = $this->pdo->query('SELECT id, icon, color, permiso FROM permisos');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getPermisosPorRol()
    {
        try {
            $stmt = $this->pdo->query('
                SELECT rol_id, permiso_id
                FROM roles_permisos rp
                JOIN roles r ON rp.rol_id = r.id
                WHERE r.rol != "admin"
            ');
            return $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function getPermisosPorRolId($rolId)
    {
        try {
            $stmt = $this->pdo->query('
                SELECT rol_id, permiso_id
                FROM roles_permisos rp
                JOIN roles r ON rp.rol_id = r.id
                WHERE r.rol != "admin"
            ');
            $stmt->execute(['rol_id' => $rolId]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function guardarPermisos($rolId, $permisos)
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare('DELETE FROM roles_permisos WHERE rol_id = :rol_id');
            $stmt->execute(['rol_id' => $rolId]);

            $stmt = $this->pdo->prepare('INSERT INTO roles_permisos (rol_id, permiso_id) VALUES (:rol_id, :permiso_id)');

            foreach ($permisos as $permisoId) {
                $stmt->execute([
                    'rol_id' => $rolId,
                    'permiso_id' => $permisoId
                ]);
            }
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
