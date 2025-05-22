
function cargarDetallesPrestamo(idPrestamo) {
    $.ajax({
        url: "ajax/prestamos.ajax.php",
        method: "POST",
        data: {
            idPrestamo: idPrestamo,
            accion: "obtenerDetalles"
        },
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta) {
                $("#modalIdPrestamo").text(respuesta.id_prestamo);
                $("#modalUsuario").text(respuesta.usuario_id);
                $("#modalTipo").text(respuesta.tipo_prestamo);
                $("#modalFechaInicio").text(respuesta.fecha_inicio);
                $("#modalFechaFin").text(respuesta.fecha_fin);
                $("#modalEstado").text(respuesta.estado_prestamo);
                $("#modalMotivo").text(respuesta.motivo);
            }
        }
    });
}


