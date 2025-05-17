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

class GestionUsuarioController {
    private $model;
    private $varHeader;
    private $varJs;
    private $usuario;

    public function __construct() {
        global $varHeader, $varJs;
        $this->varHeader = $varHeader;
        $this->varJs = $varJs;
        $this->model = new UsuarioModel();
        $this->usuario = $this->model->getUsuario($_SESSION['email']);
    }

    public function index() {
        try {
            // Obtener lista de usuarios
            $usuarios = $this->model->getAllUsuarios();
            
            // Hacer las variables accesibles en la vista
            $varHeader = $this->varHeader;
            $varJs = $this->varJs;
            $usuario = $this->usuario; // Pasar la información del usuario actual
            
            require_once("app/View/Usuario/Gestion_User.php");
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $cedula = filter_input(INPUT_POST, 'cedula', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            if ($this->model->registrarUsuario($nombre, $apellido, $cedula, $email, $password)) {
                $_SESSION['mensaje'] = "Usuario registrado correctamente";
            } else {
                $_SESSION['error'] = "Error al registrar el usuario";
            }
            header("Location: index.php?url=gestionUsuario");
            exit();
        }
        // Si no es POST, mostrar el formulario de registro
        $varHeader = $this->varHeader;
        $varJs = $this->varJs;
        require_once("app/View/Usuario/RegistrarUsuario.php");
    }

    public function modificar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $cedula = filter_input(INPUT_POST, 'cedula', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

            if ($this->model->modificarUsuario($id, $nombre, $apellido, $cedula, $email)) {
                $_SESSION['mensaje'] = "Usuario modificado correctamente";
            } else {
                $_SESSION['error'] = "Error al modificar el usuario";
            }
            header("Location: index.php?url=gestionUsuario");
            exit();
        }
        // Si no es POST, mostrar el formulario de modificación
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $usuario = $this->model->getUsuarioById($id);
        $varHeader = $this->varHeader;
        $varJs = $this->varJs;
        require_once("app/View/Usuario/ModificarUsuario.php");
    }

    public function eliminar() {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if ($this->model->eliminarUsuario($id)) {
            $_SESSION['error'] = "Usuario eliminado correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario";
        }
        header("Location: index.php?url=gestionUsuario");
        exit();
    }
}

// Inicializar el controlador
$controller = new GestionUsuarioController();

// Manejar las acciones
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'registrar':
        $controller->registrar();
        break;
    case 'modificar':
        $controller->modificar();
        break;
    case 'eliminar':
        $controller->eliminar();
        break;
    default:
        $controller->index();
        break;
}
?> 