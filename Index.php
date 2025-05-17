<?php 

if (file_exists("app/controller/FrontController.php")) {
    require_once("app/controller/FrontController.php");
} else {
    die("Error: Index Falta controlador principal");
}
?>