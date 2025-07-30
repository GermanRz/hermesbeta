$(document).ready(function () {
    // Inicializar DataTable con responsive
    $('#tblModulos').DataTable({
        "responsive": true,
        "paging": false,
        "searching": false,
        "info": false,
        "lengthChange": false,
        "ordering": false
    });
});
// Activar/Desactivar Módulo
$(document).on("click", ".btnActivarModulo", function () {
    var idModuloActivar = $(this).attr("idModulo");
    var estadoModulo = $(this).attr("estadoModulo");
    var datos = new FormData();
    datos.append("idModuloActivar", idModuloActivar);
    datos.append("estadoModulo", estadoModulo);

    $.ajax({
        url: "ajax/modulos.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function (respuesta) {
            console.log("Estado del módulo cambiado", respuesta);
        },
    });

    // Cambiar el botón visualmente
    if (estadoModulo == "inactivo") {
        $(this).removeClass("btn-success");
        $(this).addClass("btn-danger");
        $(this).html("Inactivo");
        $(this).attr("estadoModulo", "activo");
    } else {
        $(this).removeClass("btn-danger");
        $(this).addClass("btn-success");
        $(this).html("Activo");
        $(this).attr("estadoModulo", "inactivo");
    }
});