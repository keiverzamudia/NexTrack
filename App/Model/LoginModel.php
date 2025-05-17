<?php 

if (file_exists("app/config/connect/DBConnect.php")){
    require_once("app/config/connect/DBConnect.php");
}else{
    echo 'erro en la Conexion con base de dato';
}
class usuario extends DBConnect{
    private $email;
    private $password;
    public function __construct(){
        parent::__construct();

}
public function getLoginSistem($email,$password){
    if (preg_match_all("[!#-'*+\\-\\/0-9=?A-Z\\^-~]",$email)){
        return "Error en el email o contraseña";
    }
    if (preg_match_all("[!#-'*+\\-\\/0-9=?A-Z\\^-~]",$password)){
        return "Error en el email o contraseña";
}
$this->email = $email;
$this->password = $password;

return $this->loginSistem();

}
private function loginSistem(){

    try{
        $new =$this->con->prepare("SELECT `email`,`password` FROM `usuarios` WHERE `estate` = 1 and `email` =?");
        $new->bindValue(1, $this->email);
        $new->execute();
        $data = $new->fetchAll();
        if (isset($data[0]["password"])){
            if ($data[0]["password"] == $this->password){
                session_start();
                $_SESSION['email'] = $this->email;
                $_SESSION['logged_in'] = true;
                header("Location: index.php?url=usuario");
                exit();
            }else{
                return "Error en el email o Contraseña";
            }
        }else{
            return "Error en el email o Contraseña";
        }
    }catch(Exception $error){
        return $error;
}
    }
}


?>