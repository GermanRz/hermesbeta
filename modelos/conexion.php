<?php

class Conexion{

    static public function conectar(){
<<<<<<< HEAD
        $link = new PDO("mysql:host=localhost;dbname=hermes002", "root", "");
=======
        $link = new PDO("mysql:host=localhost;dbname=hermes002", "root", "root");
>>>>>>> upstream/main
        $link -> exec("set names utf8");
        return $link;
    }
}