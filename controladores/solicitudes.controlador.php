<?php

class ControladorSolicitudes{

    static public function ctrMostrarEquiposDisponible($fechaInicio, $fechaFin){
        $respuesta = ModeloSolicitudes::mdlMostrarEquiposDisponible($fechaInicio, $fechaFin);
        return $respuesta;
    }

    static public function ctrGuardarSolicitud($datos){

        //si la fecha inicio es igual a la fecha fin el tipo_prestamo es "imediata"
        if($datos["fechaInicio"] == $datos["fechaFin"]){
            $tipo_prestamo = "Inmediato";
        }else{
            $tipo_prestamo = "Reservado";
        }

        $datos = array(
            "fecha_inicio" => $datos["fechaInicio"],
            "fecha_fin" => $datos["fechaFin"],
            "tipo_prestamo" => $tipo_prestamo,
            "motivo" => $datos["motivo"],
            "estado_prestamo" => "pendiente",
            "usuario_id" => $datos["idSolicitante"],
            "equipos" => $datos["equipos"]
        );



        $tabla = "prestamos";
        $respuesta = ModeloSolicitudes::mdlGuardarSolicitud($tabla, $datos);
        return $respuesta;
        
    }

    
}