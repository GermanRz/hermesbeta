<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Salidas</h1>
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalAddSede">Agregar Sede</button>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="tblSedes" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Préstamo</th>
                                        <th>Usuario</th>
                                        <th>Tipo de Préstamo</th>
                                        <th>Fecha</th>
                                        <th>Estado De Préstamo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once "controladores/prestamos.controlador.php";
                                    require_once "modelos/prestamos.modelo.php";

                                    $prestamos = ControladorPrestamos::ctrMostrarPrestamos();

                                    foreach ($prestamos as $prestamo) {
                                        if ($prestamo["tipo_prestamo"] !== "Reservado") {
                                            continue;
                                        }
                                        $estadoActual = $prestamo["estado_prestamo"];

                                        // Determinar la clase y texto según el estado actual
                                        $claseBoton = "";
                                        $textoBoton = "";
                                        switch ($estadoActual) {
                                            case "En Trámite":
                                                $claseBoton = "btn-warning";
                                                $textoBoton = "En Trámite";
                                                break;
                                            case "En Préstamo":
                                                $claseBoton = "btn-success";
                                                $textoBoton = "En Préstamo";
                                                break;
                                            case "Aprobado":
                                                $claseBoton = "btn-info";
                                                $textoBoton = "Aprobado";
                                                break;
                                            default:
                                                $claseBoton = "btn-warning";
                                                $textoBoton = "En Trámite";
                                        }

                                        echo '<tr>
                                            <td>' . $prestamo["id_prestamo"] . '</td>
                                            <td>' . $prestamo["usuario_id"] . '</td>
                                            <td>' . $prestamo["tipo_prestamo"] . '</td>
                                            <td>' . $prestamo["fecha_inicio"] . '</td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-sm ' . $claseBoton . '" 
                                                        onclick="cambiarEstadoSolicitud(this, ' . $prestamo["id_prestamo"] . ', \'' . $estadoActual . '\')"
                                                        data-estado="' . $estadoActual . '">
                                                    ' . $textoBoton . '
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" name="accion1" value="' . $prestamo["id_prestamo"] . '">
                                                <input type="checkbox" name="accion2" value="' . $prestamo["id_prestamo"] . '">
                                                <input type="checkbox" name="accion3" value="' . $prestamo["id_prestamo"] . '">
                                                <button class="btn btn-info btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#modalDetallesPrestamo"
                                                            onclick="cargarDetallesPrestamo(' . $prestamo["id_prestamo"] . ')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

    <!-- Modal de Detalles del Préstamo -->
    <div class="modal fade" id="modalDetallesPrestamo" tabindex="-1" role="dialog" aria-labelledby="modalDetallesPrestamoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesPrestamoLabel">Detalles del Préstamo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID Préstamo:</strong> <span id="modalIdPrestamo"></span></p>
                            <p><strong>Usuario:</strong> <span id="modalUsuario"></span></p>
                            <p><strong>Tipo:</strong> <span id="modalTipo"></span></p>
                            <p><strong>Fecha Inicio:</strong> <span id="modalFechaInicio"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha Fin:</strong> <span id="modalFechaFin"></span></p>
                            <p><strong>Estado:</strong> <span id="modalEstado"></span></p>
                            <p><strong>Motivo:</strong> <span id="modalMotivo"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

