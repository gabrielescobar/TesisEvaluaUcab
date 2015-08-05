<?php

class db{

  static private $instancia = NULL;
  private $servidor;
  private $usuario;
  private $password;
  private $basedatos;
  private $conexion;

   public function __construct($servidor,$usuario,$password,$basedatos){
    $this->servidor = $servidor;
    $this->usuario = $usuario;
    $this->password = $password;
    $this->basedatos = $basedatos;
  }

    static public function getInstancia($servidor,$usuario,$password,$basedatos) {
    
      if (self::$instancia == NULL) {
      self::$instancia = new db($servidor,$usuario,$password,$basedatos);
      }
      return self::$instancia;
    }

    private function conectar(){
      $this->conexion = mysql_connect($this->servidor,$this->usuario,$this->password) or DIE(mysql_error());
      mysql_select_db($this->basedatos, $this->conexion);
    } 

    public function desconectar(){
      mysql_close($this->conexion);
    }

    public function getQuery($query){
     $this->conectar();
     $res = mysql_query($query) or die (mysql_error());
     $this->desconectar();
     return $res;	
    }
  

}


?>