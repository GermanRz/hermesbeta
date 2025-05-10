<?php

    include_once "../controladores/solicitudes.controlador.php";
    include_once "../modelos/solicitudes.modelo.php";

    include_once "../controladores/equipos.controlador.php";
    include_once "../modelos/equipos.modelo.php";

    class AjaxSolicitudes
    {
        public $fechaInicio;
        public $fechaFin;
        public $idEquipoAgregar;
        public $idSolicitante;
        public $equipos;
        public $motivo;

        
        /*=============================================
            TRAER EQUIPOS DISPONIBLES
            EN EL RENGO DE FECHAS DE SOLICITUDES
        =============================================*/
        public function ajaxMostrarEquiposDisponible()
        {
            
            $valor1 = $this->fechaInicio;
            $valor2 = $this->fechaFin;
            $respuesta = ControladorSolicitudes::ctrMostrarEquiposDisponible($valor1, $valor2);
            echo json_encode($respuesta);
        }

        public function ajaxTraerEquipo()
        {
            $item = "equipo_id";
            $valor = $this->idEquipoAgregar;
            $respuesta = ControladorEquipos::crtMostrarEquipos($item, $valor);
            echo json_encode($respuesta);
        }

        public function ajaxGuardarSolicitud()
        {
            $datos = array(
                "idSolicitante" => $this->idSolicitante,
                "equipos" => $this->equipos,
                "fechaInicio" => $this->fechaInicio,
                "fechaFin" => $this->fechaFin,
                "motivo" => $this->motivo
            );
            $respuesta = ControladorSolicitudes::ctrGuardarSolicitud($datos);
            echo json_encode($respuesta);
        }

    }// class AjaxSolicitudes

if (isset($_POST["fechaInicio"]) && isset($_POST["fechaFin"])) {
    $solicitud = new AjaxSolicitudes();
    $solicitud->fechaInicio = $_POST["fechaInicio"];
    $solicitud->fechaFin = $_POST["fechaFin"];
    $solicitud->ajaxMostrarEquiposDisponible();
}

if (isset($_POST["idEquipoAgregar"])) {
    $solicitud = new AjaxSolicitudes();
    $solicitud->idEquipoAgregar = $_POST["idEquipoAgregar"];
    $solicitud->ajaxTraerEquipo();
}

if (isset($_POST["idSolicitante"]) && isset($_POST["equipos"])) {
    $solicitud = new AjaxSolicitudes();
    $solicitud->idSolicitante = $_POST["idSolicitante"];
    $solicitud->equipos = json_decode($_POST["equipos"], true);
    $solicitud->fechaInicio = $_POST["fechaInicio"];
    $solicitud->fechaFin = $_POST["fechaFin"];
    $solicitud->motivo = $_POST["motivo"];
    $solicitud->ajaxGuardarSolicitud();    
}
  




