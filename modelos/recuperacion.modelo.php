<?php

require_once "conexion.php";

class ModeloRecuperacion {

    /*=============================================
    VERIFICAR USUARIO Y CORREO
    =============================================*/
    static public function mdlVerificarUsuario($tabla, $nombreUsuario, $correoElectronico) {
        $stmt = Conexion::conectar()->prepare("SELECT id_usuario, nombre_usuario, correo_electronico, nombre, apellido 
                                               FROM $tabla 
                                               WHERE nombre_usuario = :nombre_usuario 
                                               AND correo_electronico = :correo_electronico 
                                               AND estado = 'activo'");
        
        $stmt->bindParam(":nombre_usuario", $nombreUsuario, PDO::PARAM_STR);
        $stmt->bindParam(":correo_electronico", $correoElectronico, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    /*=============================================
    ACTUALIZAR CONTRASEÑA
    =============================================*/
    static public function mdlActualizarContrasena($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla 
                                               SET clave = :nueva_clave 
                                               WHERE id_usuario = :id_usuario");
        
        $stmt->bindParam(":nueva_clave", $datos["nueva_clave"], PDO::PARAM_STR);
        $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        
        if($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }
}