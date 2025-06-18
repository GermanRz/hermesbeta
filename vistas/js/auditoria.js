//************************************************************
// 
//  SERVERSIDE AUDITORIA
// 
//************************************************************/
$('#tablaAuditoria').DataTable({
    processing: true,
    serverSide: true,
    sAjaxSource: "ajax/serverside/serverside.auditoria.php",
    columns: [
        { data: null }, // número
        { data: 0 },    // tipo_documento
        { data: 1 },    // numero_documento
        { data: 2 },    // nombre
        { data: 3 },    // apellido
        { data: 4 },    // nombre_editor
        { data: 5 },    // fecha_cambio
        { data: null },    // botón de detalle
        { data: 6 },    // campo_modificado (oculto para exportar)
        { data: 7 },    // valor_anterior (oculto para exportar)
        { data: 8 }     // valor_nuevo (oculto para exportar)
    ],
    "order": [[5, "desc"]], // columna 5 = fecha_cambio
    columnDefs: [
        {
            targets: 0,
            render: function (data, type, row, meta) {
                return meta.row + 1;
            }
        },
        {
            targets: 7,
            render: function (data, type, row) {
                return `<button class="btn btn-info btn-sm btnDetalle" 
                    data-detalle='${JSON.stringify({
                        campo_modificado: row[6] || '',
                        valor_anterior: row[7] || '',
                        valor_nuevo: row[8] || ''
                    }).replace(/'/g, "&apos;")}' >
                    <i class="fas fa-eye"></i></button>`;
            }
        },
        {
            targets: [8, 9, 10],
            visible: false
        }
    ],
    responsive: true,
    autoWidth: false,
    lengthChange: true,
    lengthMenu: [10, 25, 50, 100],
    language: {
        lengthMenu: "Mostrar _MENU_ registros",
        zeroRecords: "No se encontraron resultados",
        info: "Mostrando página _PAGE_ de _PAGES_",
        infoEmpty: "No hay registros disponibles",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        search: "Buscar:",
        paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        }
    },
    buttons: ["csv", "excel"],
    dom: "lfBrtip"
});

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
        let campo = campos[i];
        let valAnt = traducirCampo(campo, valoresAnt[i]);
        let valNue = traducirCampo(campo, valoresNue[i]);

        htmlDetalle += `<tr><td>${campo}</td><td>${valAnt}</td><td>${valNue}</td></tr>`;
    }

    htmlDetalle += '</tbody></table>';

    $('#detalleAuditoriaBody').html(htmlDetalle);
    $('#modalDetalleAuditoria').modal('show');
});
// Función para traducir ciertos campos
function traducirCampo(campo, valor) {
    if (campo === "genero") {
        switch (valor) {
            case "1": return "Femenino";
            case "2": return "Masculino";
            case "3": return "No declara";
            case "":
            case null:
            case "null":
                return "Sin especificar";
            default:
                return valor;
        }
    }

    return valor;
}