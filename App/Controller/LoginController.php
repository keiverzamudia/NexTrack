<?php 

if(file_exists("app/config/component/InitComponent.php")){
    require_once("app/config/component/InitComponent.php");
}else{
    die("Error al Cargar Estilos!");
}

if (file_exists("app/Model/LoginModel.php")) {
    require_once("app/Model/LoginModel.php");
} else {
    header("Location: index.php?url=login");
    exit();
}

$objModel = new usuario();
if(isset($_POST["email"]) && isset($_POST["password"])){
    $objModel = new usuario();
    $respuesta = $objModel->getLoginSistem($_POST["email"],$_POST["password"]);
}

if (file_exists("app/View/Usuario/LoginView.php")) {
    require_once("app/View/Usuario/LoginView.php");
} else {
    header("Location: index.php?url=login");
    exit();
}

?>