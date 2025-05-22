/* ==================================================
BOTÓN PARA EDITAR EQUIPOS
================================================== */
var idEquipoTraspaso;
var idEquipoTraspasoUbicacion;

// Escuchamos el evento "click" sobre cualquier botón con clase "btnEditarEquipo"
$(document).on("click", ".btnEditarEquipo", function() {
    var idEquipo = $(this).attr("idEquipo");
    console.log("IdEquipo: ", idEquipo);
    // $("#idEditEquipo").val(idEquipo);
    
    var datos = new FormData();
    datos.append("idEquipo", idEquipo);

    $.ajax({
        url: "ajax/equipos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            console.log("Respuesta completa del servidor:", respuesta);
            
            try {
                // Verificamos que la respuesta sea válida
                if (respuesta) {
                    console.log("datos: ", respuesta);
                    
                    // Llenamos los campos del formulario del modal con los datos recibidos
                    $("#idEditEquipo").val(respuesta["equipo_id"]);
                    $("#numeroSerieEdit").val(respuesta["numero_serie"]);
                    $("#etiquetaEdit").val(respuesta["etiqueta"]);
                    $("#descripcionEdit").val(respuesta["descripcion"]);
                    $("#estadoEdit").val(respuesta["id_estado"]);
                    $("#categoriaEditId").val(respuesta["categoria_id"]);
                } else {
                    console.error("Respuesta inválida del servidor");
                    alert("Error: La respuesta del servidor no tiene el formato esperado");
                }
            } catch (e) {
                console.error("Error al procesar la respuesta:", e);
                alert("Error al procesar la respuesta del servidor");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición Ajax:");
            console.error("Status:", status);
            console.error("Error:", error);
            console.error("Respuesta:", xhr.responseText);
            alert("Error al comunicarse con el servidor. Por favor, intente nuevamente.");
        }
    });
});

/* ==================================================
BOTÓN PARA INSERTAR EL CUENTADANTE Y UBICACIÓN ACTUAL
================================================== */

$(document).on("click", ".btnTraspasarEquipo", function(){
    idEquipoTraspaso = $(this).attr("idEquipoTraspaso");
    console.log("Id equipo traspaso: ", idEquipoTraspaso);

    var datos = new FormData();

    datos.append("idEquipoTraspaso", idEquipoTraspaso);

    $.ajax({
        url: "ajax/equipos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            try {
                // Verificamos que la respuesta sea válida
                if (respuesta) {
                    console.log("datos: ", respuesta);
                    
                    // Llenamos los campos del formulario del modal con los datos recibidos
                    $("#idEditEquipo").val(respuesta["equipo_id"]);
                    $("#cuentadanteOrigenTraspaso").val(respuesta["nombre"]);
                    $("#ubicacionOrigenTraspaso").val(respuesta["ubicacion_nombre"]);
                } else {
                    console.error("Respuesta inválida del servidor");
                    alert("Error: La respuesta del servidor no tiene el formato esperado");
                }
            } catch (e) {
                console.error("Error al procesar la respuesta:", e);
                alert("Error al procesar la respuesta del servidor");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la petición Ajax:");
            console.error("Status:", status);
            console.error("Error:", error);
            console.error("Respuesta:", xhr.responseText);
            alert("Error al comunicarse con el servidor. Por favor, intente nuevamente.");
        }
    });
});

/* ==================================================
BOTÓN PARA BUSCAR EL CUENTADANTE Y AGREGARLO EN EL INPUT
================================================== */

$(document).on("click", ".btnBuscarCuentadante", function (event){
    event.preventDefault(); // Prevenir la recarga de la página
    // Limpiar los campos antes de buscar de nuevo
    console.log("Id equipo traspaso: ", idEquipoTraspaso);

    $("#cuentadanteDestino").val("");
    $("#ubicacionTraspaso").val("");

    var buscarDocumentoId = $("#buscarDocumentoId").val();
    let datos = new FormData();

    datos.append("buscarDocumentoId", buscarDocumentoId);
    if (buscarDocumentoId === ""){
        Toast.fire({
            icon: 'info',
            title: 'Por favor ingrese un documento a buscar',
            position: "center"
        })
        return;
    } else {
        $.ajax({
            url: "ajax/equipos.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(resultado){
                const docIngresado = String(buscarDocumentoId).trim();
                const docEncontrado = String(resultado["numero_documento"] || '').trim();
                console.log("rol usuario: ", resultado["nombre_rol"]);
                if(docIngresado != docEncontrado){
                    Toast.fire({
                        icon: 'error',
                        title: 'No se encontró el documento del cuentadante',
                        position: "center"
                    });
                    $("#buscarDocumentoId").val("");
                } else if(resultado["nombre_rol"] == "Aprendiz"){
                    Toast.fire({
                        icon: 'error',
                        title: 'No se puede asignar equipo a este documento',
                        position: "center"
                    });
                    $("#buscarDocumentoId").val("");
                } else {
                    console.log("id_usuario: ", resultado["id_usuario"]);
                    $("#idTraspasoEquipo").val(idEquipoTraspaso);
                    console.log("ESTE ES EL EQUIPO ID AL CUAL VOY A PASAR: ", idEquipoTraspaso);
                    $("#cuentadanteDestinoId").val(resultado["id_usuario"]);
                    $("#cuentadanteDestino").val(resultado["id_usuario"] + " " + resultado["cuentadante_nombre"]);
                    // $("#ubicacionTraspaso").val(resultado["ubicacion_id"] + " " + resultado["ubicacion_nombre"]);
                }
            }
        });
    }
});

/* ==================================================
BOTÓN PARA MOSTRAR LOS DATOS DE LA UBICACIÓN ACTUAL
================================================== */

$(document).on("click", ".btnTraspasarUbicacion", function() {
    idEquipoTraspasoUbicacion = $(this).attr("idEquipoTraspasoUbicacion"); //Id del equipo
    console.log(idEquipoTraspasoUbicacion);

    let datos = new FormData();
    datos.append("idEquipoTraspasoUbicacion", idEquipoTraspasoUbicacion);

    $.ajax({
        url: "ajax/equipos.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(resultado){
                console.log(resultado);
                // console.log("ubicacion_id hola: ", resultado["ubicacion_id"]);
                $("#ubicacionActualId").val(resultado["ubicacion_id"]); // el id hidden
                $("#ubicacionActual").val(resultado["ubicacion_id"] + " " + resultado["nombre_ubicacion"]);
                // $("#nuevaUbicacionId").val();
            }
    });
});

/* ==================================================
BOTÓN PARA AGREGAR AL INPUT DE LA NUEVA UBICACIÓN DEL EQUIPO
================================================== */

$(document).on("change", "#nuevaUbicacionId", function() {
    var nuevaUbicacionId = $(this).val();
    console.log("id ubicacion destino: ", nuevaUbicacionId);

    var datos = new FormData();
    datos.append("nuevaUbicacionId", nuevaUbicacionId);

    $.ajax({
        url: "ajax/equipos.ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(resultado){
                console.log("RESULTADO: ", resultado);
                $("#idTraspasoUbicacion").val(resultado["equipo_id"]);
                $("#nuevaUbicacionId").val(resultado["ubicacion_id"]);
            }
    })
});

$(document).ready(function() {
    $('#tblEquipos').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "ajax/equipos.ajax.php", // Endpoint for data
            "type": "POST"
        },
        "columns": [
            { 
                "data": null, 
                "orderable": false, 
                "searchable": false, 
                "render": function (data, type, row, meta) { 
                    return meta.row + 1 + meta.settings._iDisplayStart; 
                } 
            },
            { "data": "numero_serie" },
            { "data": "etiqueta" },
            { "data": "descripcion" },
            { "data": "ubicacion_nombre", "defaultContent": "N/A" },
            { "data": "categoria_nombre", "defaultContent": "N/A" },
            { "data": "cuentadante_nombre", "defaultContent": "N/A" },
            { "data": "estado_nombre", "defaultContent": "N/A" },
            { 
                "data": "equipo_id", 
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button title="Editar datos equipo" class="btn btn-default btn-xs btnEditarEquipo bg-warning" idEquipo="${data}" data-toggle="modal" data-target="#modalEditarEquipo"><i class="fas fa-edit mr-1 ml-1"></i></button>
                            <button title="Traspaso de cuentadante" class="btn btn-default btn-xs btnTraspasarEquipo ml-2 bg-success" idEquipoTraspaso="${data}" data-toggle="modal" data-target="#modalTraspaso"><i class="fas fa-share mr-1 ml-1"></i></button>
                            <button title="Traspaso de ubicación" class="btn btn-default btn-xs btnTraspasarUbicacion ml-2 bg-info" idEquipoTraspasoUbicacion="${data}" data-toggle="modal" data-target="#modalTraspasoUbicacion"><i class="fas fa-map-pin mr-1 ml-1"></i></button>
                        </div>
                    `;
                }
            }
        ],
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
});
