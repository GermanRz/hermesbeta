$(document).ready(function () {
  // Verificar si DataTable ya está inicializada
  if ($.fn.DataTable.isDataTable('#tblMantenimiento')) {
    $('#tblMantenimiento').DataTable().destroy();
  }

  // Inicializar DataTable
  $('#tblMantenimiento').DataTable({
    "responsive": true,
    "autoWidth": false,
    "searching": false,
    "paging": false,
    "info": false,
    "lengthChange": false,
    "order": [],
    "columnDefs": [
      {"orderable": false, "targets": "_all"}
    ]
    }
  );

  // Funcionalidad del botón finalizar mantenimiento
  $(document).on("click", ".btnFinalizarMantenimiento", function () {
    var btn = $(this);
    // Datos del equipo desde los atributos data
    $("#idMantenimiento").val(btn.data("id"));
    $("#equipoSerie").text(btn.data("numero-serie"));
    $("#equipoEtiqueta").text(btn.data("etiqueta"));
    $("#equipoDescripcion").text(btn.data("descripcion"));

    // Datos del usuario desde los atributos data
    $("#nombre").text(btn.data("nombre"));
    $("#apellido").text(btn.data("apellido"));
    $("#condicion").text(btn.data("condicion"));

    $("#modalFinalizarMantenimiento").modal("show");
  });

  $("#formFinalizarMantenimiento").on("submit", function (e) {
    e.preventDefault();

    var idMantenimiento = $("#idMantenimiento").val();
    var gravedad = $("input[name='gravedad']:checked", this).val();
    var detalles = $("#descripcionProblema").val();

    $.ajax({
        url: "ajax/mantenimiento.ajax.php",
        method: "POST",
        data: {
            idMantenimiento: idMantenimiento,
            gravedad: gravedad,
            detalles: detalles,
        },
        success: function (respuesta) {
            if (respuesta === "ok") {
                Swal.fire({
                    icon: "success",
                    title: "Mantenimiento finalizado correctamente",
                    showConfirmButton: false,
                    timer: 1500,
                }).then(() => location.reload());
            } else {
                Swal.fire(
                    "Error",
                    respuesta || "No se pudo finalizar el mantenimiento",
                    "error"
                );
            }
        },
        error: function (xhr, status, error) {
            Swal.fire(
                "Error",
                "Error en la comunicación con el servidor: " + error,
                "error"
            );
        },
    });
  });
});