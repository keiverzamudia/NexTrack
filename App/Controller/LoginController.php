<?php 
session_start();

if(file_exists("app/config/component/InitComponent.php")){
    require_once("app/config/component/InitComponent.php");
}else{
    die("Error al Cargar Estilos!");
}

if (file_exists("app/Model/LoginModel.php")) {
    require_once("app/Model/LoginModel.php");
} else {
    die("Error: No se encontró el modelo de login");
}

class LoginController {
    private $model;
    private $varHeader;
    private $varJs;

    public function __construct() {
        global $varHeader, $varJs;
        $this->varHeader = $varHeader;
        $this->varJs = $varJs;
        $this->model = new usuario();
    }

    public function index() {
        // Si ya está logueado, redirigir al dashboard
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            header("Location: index.php?url=usuario");
            exit();
        }

        // Hacer las variables accesibles en la vista
        $varHeader = $this->varHeader;
        $varJs = $this->varJs;
        
        require_once("app/View/Usuario/LoginView.php");
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST["email"]) && isset($_POST["password"])) {
                $respuesta = $this->model->getLoginSistem($_POST["email"], $_POST["password"]);
                
                if ($respuesta) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['email'] = $_POST["email"];
                    header("Location: index.php?url=usuario");
                    exit();
                } else {
                    $_SESSION['error'] = "Credenciales inválidas";
                    header("Location: index.php?url=login");
                    exit();
                }
            }
        }
        header("Location: index.php?url=login");
        exit();
    }
}

// Inicializar el controlador
$controller = new LoginController();

// Manejar las acciones
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'login':
        $controller->login();
        break;
    default:
        $controller->index();
        break;
}
?>