<?php

require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";

// New block for DataTables
if (isset($_POST['draw']) && isset($_POST['start']) && isset($_POST['length'])) {
    // It's a DataTables request, pass all $_POST params to the model function
    $response = ModeloUsuarios::mdlMostrarUsuariosServerSide('usuarios', $_POST); 
    if ($response === null) {
        // Handle error case, perhaps mdlMostrarUsuariosServerSide returned null
        header('Content-Type: application/json');
        echo json_encode([
            "draw" => intval($_POST['draw']),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
            "error" => "Failed to retrieve data from model"
        ]);
        exit;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
// END OF NEW DATATABLES HANDLER BLOCK

class AjaxUsuarios
{

    public $sede;
    public $idUsuario;
    public $item;

    public function ajaxFichasSede()
    {
        $item = "sede";
        $valor = $_POST["sede"];
        // Assuming ModeloUsuarios::mdlMostrarFichasSede is more direct if no complex logic in controller
        $respuesta = ModeloUsuarios::mdlMostrarFichasSede("fichas", $item, $valor); 
        echo json_encode($respuesta);
    }


    public function ajaxMostrarUsuario()
    {
        // $item = "id_usuario";
        $valor = $this->idUsuario;
        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($this->item, $valor);
        echo json_encode($respuesta);
    }


}

if (isset($_POST["sede"])) {
    $fichas = new AjaxUsuarios();
    $fichas->sede = $_POST["sede"];
    $fichas->ajaxFichasSede();
}

if (isset($_POST["idUsuario"])) {
    $usuario = new AjaxUsuarios();
    $usuario->idUsuario = $_POST["idUsuario"];
    $usuario->item = "id_usuario";
    $usuario->ajaxMostrarUsuario();
} 

if (isset($_POST["idSolicitante"])) {
    $solicitante = new AjaxUsuarios();
    $solicitante->idUsuario = $_POST["idSolicitante"];
    $solicitante->item = "numero_documento";
    $solicitante->ajaxMostrarUsuario();
}
// Cambiar estado de usuario

if (isset($_POST["idUsuarioEstado"], $_POST["estado"])) {
    $id = $_POST["idUsuarioEstado"];
    $estado = $_POST["estado"];
    $respuesta = ControladorUsuarios::ctrCambiarEstadoUsuario($id, $estado);
    echo $respuesta ? 'ok' : 'error';
    exit;
}