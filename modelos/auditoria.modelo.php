<?php
require_once "Conexion.php";

class AuditoriaModelo {

    /**
     * Obtener historial de auditoría.
     * 
     * @param int|null $idUsuarioAfectado (opcional) para filtrar por usuario
     * @return array|false
     */
    public static function mdlMostrarAuditoria($idUsuarioAfectado = null) {
        try {
            $conexion = Conexion::conectar();

            $query = "
                SELECT 
                    a.id_usuario_afectado,
                    u.tipo_documento,
                    u.numero_documento,
                    u.nombre,
                    u.apellido,
                    u.correo_electronico,
                    u.nombre_usuario,
                    u.telefono,
                    u.direccion,
                    u.genero,
                    u.foto,
                    u.estado,
                    u.condicion,
                    u.fecha_registro,
                    a.id_usuario_editor,
                    editor.nombre_usuario AS nombre_editor,
                    a.campo_modificado,
                    a.valor_anterior,
                    a.valor_nuevo,
                    a.fecha_cambio
                FROM auditoria_usuarios a
                LEFT JOIN usuarios u ON a.id_usuario_afectado = u.id_usuario
                LEFT JOIN usuarios editor ON a.id_usuario_editor = editor.id_usuario
            ";

            // Si hay filtro, agregamos condición
            if ($idUsuarioAfectado !== null) {
                $query .= " WHERE a.id_usuario_afectado = :idUsuarioAfectado ";
            }

            $query .= " ORDER BY a.fecha_cambio DESC";

            $stmt = $conexion->prepare($query);

            // Bind si hay filtro
            if ($idUsuarioAfectado !== null) {
                $stmt->bindParam(':idUsuarioAfectado', $idUsuarioAfectado, PDO::PARAM_INT);
            }

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error en mdlMostrarAuditoria: " . $e->getMessage());
            return false;
        }
    }
}
