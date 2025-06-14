<?php
require_once "../controladores/auditoria.controlador.php";
header('Content-Type: application/json');

$idUsuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : null;

$data = AuditoriaControlador::ctrMostrarAuditoria($idUsuario);

echo json_encode(['data' => $data]);
