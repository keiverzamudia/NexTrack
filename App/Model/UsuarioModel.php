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
    private $estado;

    public function __construct() {
        parent::__construct();
    }

    public function getUsuario($email) {
        try {
            $query = $this->con->prepare("SELECT * FROM usuarios WHERE email = ?");
            $query->bindValue(1, $email);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            return false;
        }
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
