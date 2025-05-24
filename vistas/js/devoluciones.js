$(document).ready(function() {
    // Cuando se hace clic en el botón Ver
    $('.btnVerUsuario').click(function() {
        var idPrestamo = $(this).attr('data-id');

        // Realizar petición AJAX
        $.ajax({
            url: "ajax/devoluciones.ajax.php",
            method: "POST",
            data: {
                idPrestamo: idPrestamo
            },
            dataType: "json",
            success: function(respuesta) {
                // La respuesta ahora es un array de objetos (uno por equipo)
                if(respuesta && respuesta.length > 0) {
                    // Tomamos los datos del usuario y préstamo del primer objeto del array
                    // Asumimos que los datos del préstamo y usuario son los mismos para todos los equipos en el mismo préstamo
                    var datosPrestamo = respuesta[0];

                    // Imagen y datos básicos del usuario
                    $('#userImage').attr('src', datosPrestamo.foto ? datosPrestamo.foto : 'vistas/img/usuarios/default/anonymous.png');
                    $('#userName').text(datosPrestamo.nombre + ' ' + datosPrestamo.apellido);
                    $('#userRol').text(datosPrestamo.idrRol || 'No especificado');

                    // Información del préstamo
                    $('#prestamoIdentificacion').text(datosPrestamo.numero_documento || 'No disponible');
                    $('#prestamoNombre').text(datosPrestamo.nombre || 'No disponible');
                    $('#prestamoApellido').text(datosPrestamo.apellido || 'No disponible');
                    $('#prestamoTelefono').text(datosPrestamo.telefono || 'No disponible');
                    $('#prestamoFicha').text(datosPrestamo.ficha_codigo || 'No asignada');
                    $('#prestamoTipo').text(datosPrestamo.tipo_prestamo || 'No especificado');
                    $('#prestamoFechaInicio').text(datosPrestamo.fecha_inicio || 'No especificada');
                    $('#prestamoFechaFin').text(datosPrestamo.fecha_fin || 'No especificada');
                    $('#prestamoEstado').text(datosPrestamo.estado_prestamo || 'No especificado');

                    // Información de los equipos (iterar sobre el array)
                    var equiposHtml = '';
                    respuesta.forEach(function(equipo) {
                        equiposHtml += `
                            <div class="card card-outline card-secondary mb-3">
                                <div class="card-header">
                                    <h5 class="card-title">Equipo: ${equipo.descripcion || 'Sin descripción'}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 40%">Serial:</th>
                                                        <td>${equipo.numero_serie || 'No disponible'}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Etiqueta:</th>
                                                        <td>${equipo.etiqueta || 'No disponible'}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 40%">Categoría:</th>
                                                        <td>${equipo.categoria_nombre || 'No disponible'}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Descripción:</th>
                                                        <td>${equipo.descripcion || 'No disponible'}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Contenedor para los botones de devolución de este equipo -->
                                    <div class="col-12 text-center mt-3 equipo-buttons-container">`;

                        // Lógica para mostrar los botones de devolución según el tipo de préstamo
                        if (datosPrestamo.tipo_prestamo === 'Inmediato') {
                            equiposHtml += `
                                <button type="button" class="btn btn-success btn-devolver-equipo mr-2" data-prestamo-id="${datosPrestamo.id_prestamo}" data-equipo-id="${equipo.equipo_id}" data-estado="buen_estado">
                                    <i class="fas fa-check-circle mr-2"></i>Buen Estado
                                </button>
                                <button type="button" class="btn btn-danger btn-devolver-equipo" data-prestamo-id="${datosPrestamo.id_prestamo}" data-equipo-id="${equipo.equipo_id}" data-estado="mal_estado">
                                    <i class="fas fa-times-circle mr-2"></i>Mal Estado
                                </button>
                            `;
                        } else if (datosPrestamo.tipo_prestamo === 'Reservado') {
                            equiposHtml += `
                                <button type="button" class="btn btn-success btn-devolver-equipo" data-prestamo-id="${datosPrestamo.id_prestamo}" data-equipo-id="${equipo.equipo_id}" data-estado="devuelto">
                                    <i class="fas fa-check-circle mr-2"></i>Marcar como Devuelto
                                </button>
                            `;
                        }
                        equiposHtml += `
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    $('#equiposListContainer').html(equiposHtml); // Insertar los detalles de los equipos en el contenedor

                    // Mostrar la modal consolidada
                    $('#modalVerDetallesPrestamo').modal('show');

                } else {
                    // Si no hay equipos asociados al préstamo o la respuesta está vacía
                    alert("No se encontraron datos de equipos para este préstamo.");
                    // Limpiar la sección de equipos si no hay datos
                    $('#equiposListContainer').html('<p>No hay equipos asociados a este préstamo.</p>');
                     // Mostrar la modal con la información del préstamo/usuario si existe
                    if(respuesta && respuesta.length === 0) {
                         // Si la respuesta es un array vacío, aún podemos mostrar la info del préstamo si la API lo permite
                         // (aunque el modelo actual devuelve un array vacío si no hay equipos, no la info del préstamo)
                         // Si la API pudiera devolver la info del préstamo aunque no haya equipos, la lógica iría aquí.
                         // Con el modelo actual, si no hay equipos, respuesta es [], así que no mostramos la modal.
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la petición AJAX:", error);
                alert("Error al cargar los datos del préstamo");
            }
        });
    });

    // Cuando se hace clic en los botones de devolución de cada equipo (delegación de eventos)
    // Usamos delegación porque los botones se añaden dinámicamente
    $(document).on('click', '.equipo-buttons-container .btn-devolver-equipo', function() {
        var prestamoId = $(this).attr('data-prestamo-id');
        var equipoId = $(this).attr('data-equipo-id'); // Obtener el ID del equipo
        var estado = $(this).attr('data-estado'); // 'buen_estado', 'mal_estado', 'devuelto'

        console.log("Botón de devolución de equipo clickeado:");
        console.log("ID del Préstamo:", prestamoId);
        console.log("ID del Equipo:", equipoId);
        console.log("Estado:", estado);

        // Lógica para mostrar la modal si el estado es 'mal_estado'
        if (estado === 'mal_estado') {
            // Guardar el ID del préstamo y del equipo en la modal para usarlo después
            $('#malEstadoPrestamoId').val(prestamoId);
            $('#malEstadoEquipoId').val(equipoId);
            $('#modalMalEstado').modal('show');
        } else {
            // Lógica para procesar la devolución en buen estado o la reserva devuelta
            // Aquí iría la llamada AJAX para procesar la devolución de este equipo específico
            console.log(`Procesando devolución del equipo ${equipoId} en estado: ${estado}...`);
            // TODO: Implementar llamada AJAX para actualizar el estado del equipo y/o préstamo
        }
    });
});