<?php
if (file_exists("app/config/connect/DBConnect.php")){
    require_once("app/config/connect/DBConnect.php");
}else{
    echo 'Error en la Conexión con base de datos';
}

class ActivoModel extends DBConnect {
    public function __construct() {
        parent::__construct();
    }

    public function getAllActivos() {
        $query = "SELECT * FROM activos ORDER BY codigo";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivoById($id) {
        $query = "SELECT * FROM activos WHERE id = :id";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarActivo($nombre, $tipo, $marca, $modelo, $departamento) {
        try {
            $query = "INSERT INTO activos (nombre, tipo, marca, modelo, departamento, estado) 
                     VALUES (:nombre, :tipo, :marca, :modelo, :departamento, 'disponible')";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':modelo', $modelo);
            $stmt->bindParam(':departamento', $departamento);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function modificarActivo($id, $nombre, $tipo, $marca, $modelo, $departamento, $estado) {
        try {
            $query = "UPDATE activos 
                     SET nombre = :nombre, 
                         tipo = :tipo, 
                         marca = :marca,
                         modelo = :modelo,
                         departamento = :departamento,
                         estado = :estado 
                     WHERE id = :id";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':modelo', $modelo);
            $stmt->bindParam(':departamento', $departamento);
            $stmt->bindParam(':estado', $estado);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminarActivo($id) {
        try {
            $query = "DELETE FROM activos WHERE id = :id";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function asignarActivo($id, $usuario_id) {
        try {
            $query = "UPDATE activos 
                     SET estado = 'asignado', 
                         usuario_id = :usuario_id,
                         fecha_asignacion = NOW()
                     WHERE id = :id";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?> 