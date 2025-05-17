<?php
session_start();

if (!isset($_SESSION['usuario'])) {
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

// Cargar modelos
if (file_exists("app/Model/ActivoModel.php")) {
    require_once("app/Model/ActivoModel.php");
} else {
    die("Error: No se encontró el modelo de activo");
}

if (file_exists("app/Model/UsuarioModel.php")) {
    require_once("app/Model/UsuarioModel.php");
} else {
    die("Error: No se encontró el modelo de usuario");
}

class GestionActivoController {
    private $activoModel;
    private $usuarioModel;
    private $varHeader;
    private $varJs;
    private $usuario;

    public function __construct() {
        global $varHeader, $varJs;
        $this->varHeader = $varHeader;
        $this->varJs = $varJs;
        $this->activoModel = new ActivoModel();
        $this->usuarioModel = new UsuarioModel();
        $this->usuario = $this->usuarioModel->getUsuario($_SESSION['usuario']);
    }

    public function index() {
        try {
            // Obtener lista de activos
            $activos = $this->activoModel->getAllActivos();
            // Obtener lista de usuarios para el modal de asignación
            $usuarios = $this->usuarioModel->getAllUsuarios();
            
            // Hacer las variables accesibles en la vista
            $varHeader = $this->varHeader;
            $varJs = $this->varJs;
            $usuario = $this->usuario;
            
            require_once("app/View/Usuario/Gestion_Activos.php");
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
            $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
            $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
            $departamento = filter_input(INPUT_POST, 'departamento', FILTER_SANITIZE_STRING);

            if ($this->activoModel->registrarActivo($nombre, $tipo, $marca, $modelo, $departamento)) {
                $_SESSION['mensaje'] = "Equipo registrado correctamente";
            } else {
                $_SESSION['error'] = "Error al registrar el equipo";
            }
        }
        header("Location: index.php?url=gestionActivo");
        exit();
    }

    public function modificar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
            $marca = filter_input(INPUT_POST, 'marca', FILTER_SANITIZE_STRING);
            $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_STRING);
            $departamento = filter_input(INPUT_POST, 'departamento', FILTER_SANITIZE_STRING);
            $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);

            if ($this->activoModel->modificarActivo($id, $nombre, $tipo, $marca, $modelo, $departamento, $estado)) {
                $_SESSION['mensaje'] = "Equipo modificado correctamente";
            } else {
                $_SESSION['error'] = "Error al modificar el equipo";
            }
        }
        header("Location: index.php?url=gestionActivo");
        exit();
    }

    public function eliminar() {
        if (isset($_GET['id'])) {
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            if ($this->activoModel->eliminarActivo($id)) {
                $_SESSION['error'] = "Equipo eliminado correctamente";
            } else {
                $_SESSION['error'] = "Error al eliminar el equipo";
            }
        }
        header("Location: index.php?url=gestionActivo");
        exit();
    }

    public function asignar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_SANITIZE_NUMBER_INT);

            if ($this->activoModel->asignarActivo($id, $usuario_id)) {
                $_SESSION['mensaje'] = "Equipo asignado correctamente";
            } else {
                $_SESSION['error'] = "Error al asignar el equipo";
            }
        }
        header("Location: index.php?url=gestionActivo");
        exit();
    }
}

// Inicializar el controlador
$controller = new GestionActivoController();

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
    case 'asignar':
        $controller->asignar();
        break;
    default:
        $controller->index();
        break;
}
?> 