<?php
require_once "../controladores/auditoria.controlador.php";
require_once "../modelos/auditoria.modelo.php";

class AuditoriaAjax {

    public function mostrarAuditoria() {
        // Establece el tipo de contenido de la respuesta como JSON
        header('Content-Type: application/json');

        // Obtiene el id del usuario desde la petición GET, si existe
        $idUsuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : null;

        // Llama al método del controlador para obtener los datos de auditoría
        $data = AuditoriaControlador::ctrMostrarAuditoria($idUsuario);

        // Devuelve los datos en formato JSON
        echo json_encode(['data' => $data]);
    }
}

// Instancia y ejecuta el método si corresponde
if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarAuditoria') {
    $auditoriaAjax = new AuditoriaAjax();
    $auditoriaAjax->mostrarAuditoria();
}
