let tablaAuditoria;

function inicializarTablaAuditoria(idUsuario = null) {
  let url = "ajax/auditoria.ajax.php?accion=mostrarAuditoria";
  if (idUsuario) {
    url += "&id_usuario=" + idUsuario;
  }

  tablaAuditoria = $('#tablaAuditoria').DataTable({
    ajax: {
      url: url,
      type: 'GET',
      dataSrc: "data",
      error: function (xhr, error, thrown) {
        console.error("Error en AJAX:", xhr.responseText);
      }
    },
    columns: [
      { data: "tipo_documento" },
      { data: "numero_documento" },
      { data: "nombre" },
      { data: "apellido" },
      { data: "nombre_editor" },
      { data: "fecha_cambio" },
      {
        data: null,
        render: function (data, type, row) {
          return `<button class="btn btn-info btn-sm btnDetalle" data-detalle='${JSON.stringify({
            campo_modificado: row.campo_modificado,
            valor_anterior: row.valor_anterior,
            valor_nuevo: row.valor_nuevo
          }).replace(/'/g, "&apos;")}'><i class="fas fa-eye"></i></button>`;
        }
      },
      {
        data: null,
        visible: false,
        render: function (data, type, row) {
          let campos = row.campo_modificado.split(';').map(s => s.trim());
          let valoresAnt = row.valor_anterior.split(';').map(s => s.trim());
          let valoresNue = row.valor_nuevo.split(';').map(s => s.trim());

          return campos.map((campo, i) => `${campo}: ${valoresAnt[i]} → ${valoresNue[i]}`).join(" | ");
        }
      }
    ],
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'excelHtml5',
        text: 'Excel',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 7] }
      },
      {
        extend: 'csvHtml5',
        text: 'CSV',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 7] }
      }
    ],
    responsive: true,
    language: { url: 'https://cdn.datatables.net/plug-ins/1.11.4/i18n/es_es.json' },
    order: [[5, 'desc']]
  });
}

// Modal detalle
$(document).on('click', '.btnDetalle', function () {
  let detalleData = $(this).data('detalle');
  if (typeof detalleData === 'string') {
    detalleData = JSON.parse(detalleData.replace(/&apos;/g, "'"));
  }

  let campos = detalleData.campo_modificado.split(';').map(s => s.trim());
  let valoresAnt = detalleData.valor_anterior.split(';').map(s => s.trim());
  let valoresNue = detalleData.valor_nuevo.split(';').map(s => s.trim());

  let htmlDetalle = '<table class="table table-bordered">';
  htmlDetalle += '<thead><tr><th>Campo Modificado</th><th>Valor Anterior</th><th>Valor Nuevo</th></tr></thead><tbody>';
  for (let i = 0; i < campos.length; i++) {
    htmlDetalle += `<tr><td>${campos[i]}</td><td>${valoresAnt[i]}</td><td>${valoresNue[i]}</td></tr>`;
  }
  htmlDetalle += '</tbody></table>';

  $('#detalleAuditoriaBody').html(htmlDetalle);
  $('#modalDetalleAuditoria').modal('show');
});

// Filtros y carga
$(document).ready(function () {
  inicializarTablaAuditoria();

  // Filtro por editor
  $('#filtroEditor').on('keyup change', function () {
    tablaAuditoria.column(4).search(this.value).draw();
  });
});
