<?php

require_once "conexion.php";

class ModeloDevoluciones
{
    static public function mdlMostrarDevoluciones($tabla, $item, $valor)
    {
        if ($item != null) {
            // Consulta para un registro específico con JOIN, devolviendo cada equipo por separado
            $stmt = Conexion::conectar()->prepare(
                "SELECT p.*, u.numero_documento, u.nombre, u.apellido, u.telefono,
                        f.codigo as ficha_codigo,
                        e.equipo_id, e.numero_serie, e.descripcion, e.etiqueta,
                        c.nombre AS categoria_nombre
                 FROM $tabla p
                 JOIN usuarios u ON p.usuario_id = u.id_usuario
                 LEFT JOIN aprendices_ficha af ON u.id_usuario = af.id_usuario
                 LEFT JOIN fichas f ON af.id_ficha = f.id_ficha
                 LEFT JOIN detalle_prestamo dp ON p.id_prestamo = dp.id_prestamo
                 LEFT JOIN equipos e ON dp.equipo_id = e.equipo_id
                 LEFT JOIN categorias c ON e.categoria_id = c.categoria_id
                 WHERE p.$item = :$item
                 AND p.estado_prestamo IN ('Prestado', 'Autorizado')"
                // Eliminamos GROUP_CONCAT y GROUP BY para obtener una fila por equipo
            );

            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
            $stmt->execute();
            // Cambiamos fetch() a fetchAll() para obtener todos los equipos del préstamo
            return $stmt->fetchAll();
        } else {
            // Consulta para todos los registros con JOIN (sin cambios aquí)
            $stmt = Conexion::conectar()->prepare(
                "SELECT p.id_prestamo, u.numero_documento, u.nombre, u.apellido, u.telefono,
                        f.codigo as ficha_codigo,
                        p.fecha_inicio, p.fecha_fin, p.tipo_prestamo,
                        CASE
                            WHEN p.tipo_prestamo = 'Inmediato' THEN 'Inmediato'
                            ELSE 'Reservado'
                        END as estado_prestamo
                 FROM $tabla p
                 JOIN usuarios u ON p.usuario_id = u.id_usuario
                 LEFT JOIN aprendices_ficha af ON u.id_usuario = af.id_usuario
                 LEFT JOIN fichas f ON af.id_ficha = f.id_ficha
                 WHERE p.estado_prestamo IN ('Prestado', 'Autorizado')
                 ORDER BY p.fecha_inicio DESC"
            );

            $stmt->execute();
            return $stmt->fetchAll();
        }

        $stmt->close();
        $stmt = null;
    }
}
