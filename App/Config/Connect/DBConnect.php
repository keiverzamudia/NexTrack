<?php
class DBConnect extends PDO {
  protected $con;
  private $puerto;
  private $usuario;
  private $contra;
  private $host;
  private $nameDB;

  public function __construct(){
    $this->usuario = _USER_;
    $this->contra = _PASS_;
    $this->host = _HOST_;
    $this->nameDB = _DB_;
    $this->conexionDB();

  }

protected function conexionDB(){

try {
    $this->con= new PDO("mysql:host={$this->host};dbname={$this->nameDB}", $this->usuario, $this->contra);
    } catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}
}
}
?>