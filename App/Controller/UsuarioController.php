<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php?url=login");
    exit();
}

// Cargar configuración del sistema
if (file_exists("app/config/component/ConfigSystem.php")) {
    require_once("app/config/component/ConfigSystem.php");
} else {
    die("Error: No se encontró la configuración del sistema");
}

// Cargar componentes
if (file_exists("app/config/component/InitComponent.php")) {
    require_once("app/config/component/InitComponent.php");
} else {
    die("Error: No se encontraron los componentes");
}

// Cargar modelo
if (file_exists("app/Model/UsuarioModel.php")) {
    require_once("app/Model/UsuarioModel.php");
} else {
    die("Error: No se encontró el modelo de usuario");
}

class UsuarioController {
    private $model;
    private $usuario;
    private $varHeader;
    private $varJs;

    public function __construct() {
        global $varHeader, $varJs;
        $this->varHeader = $varHeader;
        $this->varJs = $varJs;
        $this->model = new UsuarioModel();
        $this->usuario = $this->model->getUsuario($_SESSION['email']);
    }

    public function index() {
        if ($this->usuario) {
            // Hacer las variables accesibles en la vista
            $varHeader = $this->varHeader;
            $varJs = $this->varJs;
            require_once("app/View/Usuario/PerfilView.php");
        } else {
            header("Location: index.php?url=login");
            exit();
        }
    }

    public function actualizarPerfil() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);

            if ($this->model->actualizarPerfil($this->usuario['id'], $nombre, $apellido)) {
                $_SESSION['mensaje'] = "Perfil actualizado correctamente";
            } else {
                $_SESSION['error'] = "Error al actualizar el perfil";
            }
            header("Location: index.php?url=usuario");
            exit();
        }
    }

    public function cambiarPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $passwordActual = $_POST['password_actual'];
            $nuevaPassword = $_POST['nueva_password'];
            $confirmarPassword = $_POST['confirmar_password'];

            if ($passwordActual !== $this->usuario['password']) {
                $_SESSION['error'] = "La contraseña actual es incorrecta";
            } elseif ($nuevaPassword !== $confirmarPassword) {
                $_SESSION['error'] = "Las contraseñas no coinciden";
            } else {
                if ($this->model->cambiarPassword($this->usuario['id'], $nuevaPassword)) {
                    $_SESSION['mensaje'] = "Contraseña actualizada correctamente";
                } else {
                    $_SESSION['error'] = "Error al actualizar la contraseña";
                }
            }
            header("Location: index.php?url=usuario");
            exit();
        }
    }

    public function cerrarSesion() {
        session_destroy();
        header("Location: index.php?url=login");
        exit();
    }
}

// Inicializar el controlador
$controller = new UsuarioController();

// Manejar las acciones
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'actualizar':
        $controller->actualizarPerfil();
        break;
    case 'cambiar-password':
        $controller->cambiarPassword();
        break;
    case 'cerrar-sesion':
        $controller->cerrarSesion();
        break;
    default:
        $controller->index();
        break;
}
?>