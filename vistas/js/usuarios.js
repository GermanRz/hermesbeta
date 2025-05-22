//************************************************************
// 
//  SCRIPT PARA AGREGAR USUARIOS
// 
//************************************************************/

$(document).on("change", "#selectRol", function() {
    var idRol = $(this).val();
    console.log(idRol);
    // si el rol es aprendiz, mostrar los inputs de ficha y sede
    if (idRol == 6) {
        $("#sede").removeClass("d-none");
        $("#selectSede").prop("required", true);
        $("#id_ficha").prop("required", true);
        // $("#ficha").removeClass("d-none");
    } else {
        $("#sede").addClass("d-none");
        $("#ficha").addClass("d-none");
        $("#selectSede").removeAttr("required");
        $("#id_ficha").removeAttr("required");

        // $("#ficha").addClass("d-none");
    }
});
//************************************************************
// script para cambiar los estados de los usuarios
//************************************************************/
$(document).on('click', '.btnActivarUsuario', function() {
    var idUsuario = $(this).data('id');
    var nuevoEstado = $(this).data('estado');
    var boton = $(this);

    $.ajax({
        url: 'ajax/usuarios.ajax.php',
        method: 'POST',
        data: { idUsuarioEstado: idUsuario, estado: nuevoEstado }, // <-- CAMBIA AQUÍ
        success: function(respuesta) {
            if (respuesta == 'ok') {
                if (nuevoEstado == 'activo') {
                    boton.removeClass('btn-danger').addClass('btn-success').text('Activo');
                    boton.data('estado', 'inactivo');
                } else {
                    boton.removeClass('btn-success').addClass('btn-danger').text('Inactivo');
                    boton.data('estado', 'activo');
                }
            } else {
                alert('Error al cambiar el estado');
            }
        }
    });
});

$(document).on("change", "#selectSede", function() {
    var idSede = $(this).val();
    console.log("Sede seleccionada:", idSede);

    // Verifica si se seleccionó una sede válida
    if (!idSede) {
        console.warn("No se seleccionó una sede válida.");
        $("#id_ficha").empty().append('<option value="">Seleccione una ficha</option>');
        $("#ficha").addClass("d-none");
        return;
    }

    var datos = new FormData();
    datos.append("idSede", idSede);

    // Realiza una petición AJAX para obtener las fichas de la sede seleccionada
    $.ajax({
        url: "ajax/fichas.ajax.php", // Archivo PHP que devolverá las fichas
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            // console.log("Fichas obtenidas:", respuesta);

            var fichaSelect = $("#id_ficha");
            fichaSelect.empty();
            // console.log("registros: ",      respuesta[0]);


            // Verifica si se recibieron fichas
            if (!respuesta || respuesta.length === 0 || respuesta[0] == null || respuesta[0] == undefined) {
                console.warn("No se encontraron fichas para la sede seleccionada.");
                $("#id_ficha").empty().append('<option value="">No hay fichas disponibles</option>');
                $("#ficha").addClass("d-none");
            } else if (Array.isArray(respuesta) && respuesta.length > 0) { //Se se reciben varias fichas
                // console.log("Respuesta es un array y tiene elementos:", respuesta);
                fichaSelect.append('<option value="">Ficha</option>');
                // Agrega las nuevas opciones al select de ficha
                respuesta.forEach(function(ficha) {
                    fichaSelect.append('<option value="' + ficha.id_ficha + '">' + ficha.codigo + '</option>');
                });
                $("#ficha").removeClass("d-none");
            } else { //Se recibe una sola ficha
                // console.log("Respuesta no es un array o tiene un solo elemento:", respuesta);
                fichaSelect.append('<option value="' + respuesta["id_ficha"] + '">' + respuesta["codigo"] + '</option>');
                $("#nombre_programa").prop("placeholder", respuesta["descripcion"]);
                $("#ficha").removeClass("d-none");
            }
        },
        error: function(error) {
            console.error("Error al obtener las fichas:", error);
            // Manejo de error: Limpia el select y oculta el contenedor
            $("#id_ficha").empty().append('<option value="">Error al cargar fichas</option>');
            $("#ficha").addClass("d-none");
        }
    });
});


// ======================================
// PREVISUALIZACIÓN DE IMAGEN (EDITAR PERFIL)
// ======================================

function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('vistaPreviaFoto');
    const file = input.files[0];

    // Validar tipo y tamaño de archivo
    if (file) {
        if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El archivo debe ser una imagen JPG o PNG'
            });
            input.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) { // 2MB
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La imagen no debe superar los 2MB'
            });
            input.value = '';
            return;
        }

        // Mostrar vista previa
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

// ======================================
// SCRIPT PARA CONSULTAR USUARIOS
// ======================================
$(document).on("click", ".btnConsultarUsuario", function(){
    var idUsuario = $(this).attr("idUsuario");
    
    var datos = new FormData();
    datos.append("idUsuario", idUsuario);

    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            $("#idConsultarUsuario").val(respuesta.id_usuario);
            $("#consultarNombre").val(respuesta.nombre);
            $("#consultarApellido").val(respuesta.apellido);
            $("#consultarTipoDocumento").val(respuesta.tipo_documento);
            $("#consultarNumeroDocumento").val(respuesta.numero_documento);
            $("#consultarRol").val(respuesta.nombre_rol);
            $("#consultarEmail").val(respuesta.correo_electronico);
            $("#consultarTelefono").val(respuesta.telefono);
            $("#consultarDireccion").val(respuesta.direccion);

            // Mostrar género si existe
            if (respuesta.genero !== undefined && respuesta.genero !== null) {
                let generoTexto = '';
                switch (parseInt(respuesta.genero)) {
                    case 1:
                        generoTexto = 'Femenino';
                        break;
                    case 2:
                        generoTexto = 'Masculino';
                        break;
                    case 3:
                        generoTexto = 'No definido / Prefiere no decir';
                        break;
                    case 0:
                    default:
                        generoTexto = 'No declara';
                        break;
                }
                $("#consultarGenero").val(generoTexto);
            } else if (respuesta.genero_texto !== undefined && respuesta.genero_texto !== null) {
                $("#consultarGenero").val(respuesta.genero_texto);
            } else {
                $("#consultarGenero").val('');
            }

            // Mostrar sede y ficha si el rol es aprendiz 
            if(respuesta.id_rol == 6) {
                $("#consultarSedeFicha").removeClass("d-none");
                $("#consultarSede").val(respuesta.nombre_sede || '');

                // Mostrar ficha 
                let ficha = respuesta.codigo_ficha || respuesta.codigo || '';
                let programa = respuesta.nombre_programa || respuesta.descripcion || respuesta.descripcion_ficha || '';
                $("#consultarFicha").val(ficha && programa ? ficha + " - " + programa : ficha || programa);
                } else {
                    $("#consultarSedeFicha").addClass("d-none");
                    $("#consultarSede").val('');
                    $("#consultarFicha").val('');
                }
        }
    });
});

//************************************************************
// 
//  SCRIPT PARA EDITAR USUARIOS
// 
//************************************************************/

$(document).on("change", "#selectEditRolUsuario", function() {
    var idRol = $(this).val();
    console.log(idRol);
    // si el rol es aprendiz, mostrar los inputs de ficha y sede
    if (idRol == 6) {
        $("#editSede").removeClass("d-none");
        $("#selectEditSede").prop("required", true);
        $("#selectEditIdFicha").prop("required", true);
        // $("#ficha").removeClass("d-none");
    } else {
        $("#editSede").addClass("d-none");
        $("#EditFicha").addClass("d-none");
        $("#selectEditSede").removeAttr("required");
        $("#selectEditIdFicha").removeAttr("required");

        // $("#ficha").addClass("d-none");
    }
})

$(document).on("change", "#selectEditSede", function() {

    var idSede = $(this).val();
    // var inicial = $(this).attr("inicial");

    console.log("Sede a editar seleccionada:", idSede);


    var datos = new FormData();
    datos.append("idSede", idSede);

    // Realiza una petición AJAX para obtener las fichas de la sede seleccionada
    $.ajax({
        url: "ajax/fichas.ajax.php", // Archivo PHP que devolverá las fichas
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            console.log("Fichas obtenidas:", respuesta);

            var fichaSelect = $("#selectEditIdFicha");
            fichaSelect.empty();



            // Verifica si se recibieron fichas
            if (!respuesta || respuesta.length === 0 || respuesta[0] == null || respuesta[0] == undefined) {
                console.warn("No se encontraron fichas para la sede seleccionada.");
                $("#selectEditIdFicha").empty().append('<option value="">No hay fichas disponibles</option>');
                Toast.fire({
                    icon: 'error',
                    title: 'No hay fichas disponibles para la sede seleccionada.'
                });
                $("#EditFicha").addClass("d-none");
            } else if (Array.isArray(respuesta) && respuesta.length > 0) { //Se se reciben varias fichas
                // Agrega las nuevas opciones al select de ficha
                fichaSelect.append('<option value="">Ficha</option>');
                $("#nombreEditPrograma").prop("placeholder", "Programa");
                respuesta.forEach(function(ficha) {
                    fichaSelect.append('<option value="' + ficha.id_ficha + '">' + ficha.codigo + '</option>');
                });
                $("#EditFicha").removeClass("d-none");
            } else { //Se recibe una sola ficha
                // console.log("Respuesta no es un array o tiene un solo elemento:", respuesta);
                fichaSelect.append('<option value="' + respuesta["id_ficha"] + '">' + respuesta["codigo"] + '</option>');
                $("#nombreEditPrograma").prop("placeholder", respuesta["descripcion"]);
                $("#EditFicha").removeClass("d-none");
            }
            $("#selectEditSede").attr("inicial", "false");
        },
        error: function(error) {
            console.error("Error al obtener las fichas:", error);
            // Manejo de error: Limpia el select y oculta el contenedor
            $("#selectEditIdFicha").empty().append('<option value="">Error al cargar fichas</option>');
            $("#EditFicha").addClass("d-none");
        }
    });
})


$(document).on("change", "#id_ficha", function() {
    var idFicha = $(this).val();
    console.log("Ficha seleccionada:", idFicha);
    data = new FormData();
    data.append("idFicha", idFicha);
    // Realiza una petición AJAX para obtener los datos de la ficha seleccionada
    $.ajax({
        url: "ajax/fichas.ajax.php", // Archivo PHP que devolverá los datos de la ficha
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            // console.log("Datos de la ficha:", respuesta);
            $("#nombre_programa").prop("placeholder", respuesta["descripcion"]);
            // Aquí puedes realizar cualquier acción adicional que necesites con los datos de la ficha
        },
        error: function(error) {
            console.error("Error al obtener los datos de la ficha:", error);
            // Manejo de error
        }
    });
})


$(document).on("change", "#selectEditIdFicha", function() {
    var idFicha = $(this).val();
    console.log("Ficha seleccionada:", idFicha);
    data = new FormData();
    data.append("idFicha", idFicha);
    // Realiza una petición AJAX para obtener los datos de la ficha seleccionada
    $.ajax({
        url: "ajax/fichas.ajax.php", // Archivo PHP que devolverá los datos de la ficha
        method: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            // console.log("Datos de la ficha:", respuesta);
            $("#nombreEditPrograma").prop("placeholder", respuesta["descripcion"]);
        },
        error: function(error) {
            console.error("Error al obtener los datos de la ficha:", error);
            // Manejo de error
        }
    });
})

$(document).on("click", ".btnEditarUsuario", function() {
    
    var idUsuario = $(this).attr("idUsuario") || $(this).attr("idusuario");
    var datos = new FormData();
    datos.append("idUsuario", idUsuario);
    $.ajax({
        url: "ajax/usuarios.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta) {
            console.log("Usuario", respuesta);
            $("#idEditUsuario").val(respuesta["id_usuario"]);
            $("#editNombre").val(respuesta["nombre"]);
            $("#editApellido").val(respuesta["apellido"]);
            $("#editTipoDocumento").val(respuesta["tipo_documento"]);
            $("#editTipoDocumento").html(respuesta["tipo_documento"]);
            $("#editNumeroDocumento").val(respuesta["numero_documento"]);
            $("#rolOriginal").val(respuesta["id_rol"]);
            $("#EditRolUsuario").val(respuesta["id_rol"]);
            $("#EditRolUsuario").html(respuesta["nombre_rol"]);

            if (respuesta["id_rol"] == 6) {
                $("#editSede").removeClass("d-none");
                $("#EditFicha").removeClass("d-none");
                $("#selectEditSede").prop("required", true);
                $("#selectEditIdFicha").prop("required", true);
            } else {
                $("#editSede").addClass("d-none");
                $("#EditFicha").addClass("d-none");
                $("#selectEditSede").removeAttr("required");
                $("#selectEditIdFicha").removeAttr("required");
            }

            $("#selectEditSede").attr("idSede", respuesta["id_sede"]);
            $("#optionEditSede").val(respuesta["id_sede"]);
            $("#optionEditSede").html(respuesta["nombre_sede"]);
            //$("#selectEditSede").trigger("change");
            //disparar el evento change para cargar las fichas de la sede seleccionada
            // $("#selectEditSede").trigger("change");

            $("#optionEditIdFicha").val(respuesta["id_ficha"]);
            $("#optionEditIdFicha").html(respuesta["codigo"]);
            $("#editEmail").val(respuesta["correo_electronico"]);
            $("#editTelefono").val(respuesta["telefono"]);
            $("#editDireccion").val(respuesta["direccion"]);
            $("#nombreEditPrograma").prop("placeholder", respuesta["descripcion_ficha"]);

            // Aquí agregamos estado y condicion
            $("#editEstado").val(respuesta["estado"]);
            $("#editCondicion").val(respuesta["condicion"]);
        }
    });
});

// Limpiar inputs al cerrar modal (opcional)
$('#modalEditarUsuario').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
    // También puedes limpiar select2 u otros componentes si usas
    //recargar las vista de usuarios
    // location.reload();
    // Clear all input fields inside the modal
});

$(document).ready(function() {
    $('#tblUsuarios').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "ajax/usuarios.ajax.php",
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
            { "data": "tipo_documento" },
            { "data": "numero_documento" },
            { "data": "nombre" },
            { "data": "apellido" },
            { "data": "correo_electronico" },
            { "data": "nombre_rol" },
            { "data": "codigo_ficha", "defaultContent": "N/A" }, 
            { 
                "data": "estado", 
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row) {
                    // Assuming '1' or 'activo' means active from the DB.
                    // The button's data-estado should be the state to *change to*.
                    if (data === '1' || data === 1 || data === 'activo') { 
                        return '<button class="btn btn-success btn-xs btnActivarUsuario" data-id="' + row.id_usuario + '" data-estado="0">Activo</button>';
                    } else {
                        return '<button class="btn btn-danger btn-xs btnActivarUsuario" data-id="' + row.id_usuario + '" data-estado="1">Inactivo</button>';
                    }
                }
            },
            { 
                "data": "condicion", 
                "orderable": false, 
                "searchable": false, 
                "render": function(data, type, row) { 
                    if (data === 'en_regla') {
                        return '<button class="btn btn-success btn-xs btnCambiarCondicionUsuario" data-idusuario="' + row.id_usuario + '" data-condicionusuario="advertido">En regla</button>';
                    } else if (data === 'advertido') {
                        return '<button class="btn btn-warning btn-xs btnCambiarCondicionUsuario" data-idusuario="' + row.id_usuario + '" data-condicionusuario="penalizado">Advertido</button>';
                    } else if (data === 'penalizado') {
                         return '<button class="btn btn-danger btn-xs btnCambiarCondicionUsuario" data-idusuario="' + row.id_usuario + '" data-condicionusuario="en_regla">Penalizado</button>';
                    }
                    return data || "N/A"; 
                }
            },
            { 
                "data": "id_usuario", 
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button title="Consultar detalles de usuario" class="btn btn-default btn-xs btnConsultarUsuario" idUsuario="${data}" data-toggle="modal" data-target="#modalConsularUsuario"><i class="fas fa-eye"></i></button>
                            <button title="Editar usuario" class="btn btn-default btn-xs btnEditarUsuario" idUsuario="${data}" data-toggle="modal" data-target="#modalEditarUsuario"><i class="fas fa-edit"></i></button>
                            <button title="Solicitudes del usuario" class="btn btn-default btn-xs btnSolicitudesUsuario" idUsuario="${data}" data-toggle="modal" data-target="#modalSolicitudesUsuario"><i class="fas fa-laptop"></i></button>
                            <button title="Asignar Novedad" class="btn btn-default btn-xs btnNovedadUsuario" idUsuario="${data}" data-toggle="modal" data-target="#modalNovedad"><i class="fas fa-file"></i></button> 
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