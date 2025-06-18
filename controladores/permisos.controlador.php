<?php

class ControladorPermisos
{
    /*=============================================
    MOSTRAR PERMISOS
    =============================================*/

    static public function ctrMostrarPermisos($item, $valor)
    {
        $resultado = array(); // Initialize the result array

        $respuesta = ModeloPermisos::mdlMostrarPermisos($item, $valor);

        error_log("Respuesta de permisos: " . print_r($respuesta, true)); // Log the response for 
        
        $datos = count($respuesta);       
        
        if ($datos > 0) {
            foreach ($respuesta as $key => $row) {
                if (!isset($resultado[$row["id_rol"]])) {
                    $resultado[$row["id_rol"]] = array(
                        "id_rol" => $row["id_rol"],
                        "nombre_rol" => $row["nombre_rol"],
                        "descripcion_rol" => $row["descripcion_rol"],
                        "permisos" => array()
                    );
                }
                $resultado[$row["id_rol"]]["permisos"][] = $row["id_permiso"];
            }
            return $resultado;
        } else {
            $resultado[$valor] = array(
                "id_rol" => $valor,
                "nombre_rol" => null,
                "descripcion_rol" => null,
                "permisos" => array()
            );   
            return $resultado;         
        }        
    }

    static public function ctrTraerPermisos()
    {
        $tabla = "permisos";
        $respuesta = ModeloPermisos::mdlTraerPermisos($tabla);

        return $respuesta;
    }

    /*=============================================
    ACTUALIZAR PERMISOS
    =============================================*/
    static public function ctrActualizarPermisos($tabla, $datos)
    {
        // Primero eliminamos los permisos existentes para el rol
        $eliminar = ModeloPermisos::mdlEliminarPermisos($tabla, $datos["id_rol"]);

        if ($eliminar == "ok") {
            // Luego insertamos los nuevos permisos
            foreach (json_decode($datos["permisos"], true) as $idPermiso) {
                $respuesta = ModeloPermisos::mdlAgregarPermiso($tabla, $datos["id_rol"], $idPermiso);

                if ($respuesta != "ok") {
                    return "error";
                }
            }
            return "ok";
        } else {
            return "error";
        }
    }

    /*=============================================
    VERIFICAR PERMISOS
    =============================================*/
    static public function ctrVerificarPermisos($tabla, $id_rol, $id_permiso)
    {
        $respuesta = ModeloPermisos::mdlVerificarPermisos($tabla, $id_rol, $id_permiso);

        if ($respuesta) {
            return true; // Permiso encontrado
        } else {
            return false; // Permiso no encontrado
        }
    }
    static public function ctrListarTodosPermisos(){
        $tabla = "permisos";
        $respuesta = ModeloPermisos::mdlListarTodosPermisos($tabla);

        if ($respuesta) {
            return $respuesta; // Retorna la lista de permisos
        } else {
            return array(); // Retorna un array vacío si no hay permisos
        }
    }
}