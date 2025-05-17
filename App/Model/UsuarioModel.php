<?php
if (file_exists("app/config/connect/DBConnect.php")){
    require_once("app/config/connect/DBConnect.php");
}else{
    echo 'Error en la Conexión con base de datos';
}

class UsuarioModel extends DBConnect {
    private $id;
    private $email;
    private $password;
    private $nombre;
    private $apellido;

    public function __construct() {
        parent::__construct();
    }

    public function getAllUsuarios() {
        $query = "SELECT id, nombre, apellido, cedula, email FROM usuarios";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuarioById($id) {
        $query = "SELECT id, nombre, apellido, cedula, email FROM usuarios WHERE id = :id";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarUsuario($nombre, $apellido, $cedula, $email, $password) {
        try {
            $query = "INSERT INTO usuarios (nombre, apellido, cedula, email, password) 
                     VALUES (:nombre, :apellido, :cedula, :email, :password)";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function modificarUsuario($id, $nombre, $apellido, $cedula, $email) {
        try {
            $query = "UPDATE usuarios 
                     SET nombre = :nombre, 
                         apellido = :apellido, 
                         cedula = :cedula, 
                         email = :email 
                     WHERE id = :id";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminarUsuario($id) {
        try {
            $query = "DELETE FROM usuarios WHERE id = :id";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getUsuario($email) {
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPerfil($id, $nombre, $apellido) {
        try {
            $query = $this->con->prepare("UPDATE usuarios SET nombre = ?, apellido = ? WHERE id = ?");
            $query->bindValue(1, $nombre);
            $query->bindValue(2, $apellido);
            $query->bindValue(3, $id);
            return $query->execute();
        } catch(Exception $e) {
            return false;
        }
    }

    public function cambiarPassword($id, $nuevaPassword) {
        try {
            $query = $this->con->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
            $query->bindValue(1, $nuevaPassword);
            $query->bindValue(2, $id);
            return $query->execute();
        } catch(Exception $e) {
            return false;
        }
    }
}
?>
