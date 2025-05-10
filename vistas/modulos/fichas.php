    <?php
        $item = "id_modulo";
        $valor = 7;
        $respuesta = ControladorModulos::ctrMostrarModulos($item, $valor);
        if ($respuesta["estado"] == "inactivo") {
            echo '<script>
                window.location = "desactivado";
            </script>';
        }

    ?>
    
    
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Fichas</h1>
                    </div>
                    <div class="col-sm-6">
                        <button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalAddFicha">Agregar Ficha</button>
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
                                <table id="tblFichas" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Sede</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            // Fetch the list of Fichas
                                            $item = null;
                                            $valor = null;
                                            $fichas = ControladorFichas::ctrMostrarFichas($item, $valor);
                                            // Loop through the Fichas and display them in the table
                                            foreach ($fichas as $key => $value) {
                                                echo '<tr>
                                                    <td>' . ($key + 1) . '</td>
                                                    <td>' . $value["codigo"] . '</td>
                                                    <td>' . $value["descripcion"] . '</td>
                                                    <td>' . $value["nombre_sede"] . '</td>
                                                    <td>' . $value["fecha_inicio"] . '</td>
                                                    <td>' . $value["fecha_fin"] . '</td>
                                                    <td>' ;
                                                        if ($value["estado"] == "activa") {
                                                            echo '<button class="btn btn-success btn-xs btnActivarFicha" idFicha="'.$value["id_ficha"].'" estadoFicha="inactiva"><i class="fas fa-check"></i></button>';
                                                        } else {
                                                            echo '<button class="btn btn-danger btn-xs btnActivarFicha" idFicha="'.$value["id_ficha"].'" estadoFicha="activa"><i class="fas fa-ban"></i></button>';
                                                        };
                                                echo '</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-default btn-xs btnEditarFicha" idFicha="'.$value["id_ficha"].'" data-toggle="modal" data-target="#modalEditFicha"><i class="fas fa-edit"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>';
                                            }
                                        ?>
                                    <!-- Data will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Modal Add Ficha -->
    <div class="modal fade" id="modalAddFicha" tabindex="-1" role="dialog" aria-labelledby="modalAddFichaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddFichaLabel">Agregar Nueva Ficha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAddFicha" method="POST">
                        <div class="form-group">
                            <label for="codigo">Ficha</label>
                            <input type="text" class="form-control" id="codigoFicha" name="codigoFicha" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input class="form-control" id="descripcionFicha" name="descripcionFicha" required>
                        </div>
                        <div class="form-group">
                            <label for="fechaInicio">Fecha Inicio</label>
                            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
                        </div>
                        <div class="form-group">
                            <label for="fechaFinal">Fecha Final</label>
                            <input type="date" class="form-control" id="fechaFin" name="fechaFin" required>
                        </div>
                        <div class="form-group">
                            <?php
                                $item = null;
                                $valor = null;
                                $sedes = ControladorSedes::ctrMostrarSedes($item, $valor);
                                // Create a dropdown for sedes
                                echo '<label for="sede">Sede</label>';
                                echo '<select class="form-control" id="sede" name="id_sede" required>';
                                echo '<option value="">Seleccione una sede</option>';
                                // Loop through the sedes and create options
                                foreach ($sedes as $key => $value) {
                                    if ($value["estado"] != "inactiva") {
                                        echo '<option value="'.$value["id_sede"].'">'.$value["nombre_sede"].'</option>';
                                    }
                                }
                                echo '</select>';

                            ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <?php

                            $crearFicha = new ControladorFichas();
                            $crearFicha->ctrCrearFicha();
                            
                        ?>                        
                    </form>
                </div>    
            </div>
        </div>
    </div>


    <!-- Modal Edit Ficha -->
    <div class="modal fade" id="modalEditFicha" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditFichaLabel">Editar Ficha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formEditFicha" method="POST">

                        <div class="form-group" hidden>
                            <input type="text" class="form-control" id="idEditFicha" name="idEditFicha">
                        </div>

                        <div class="form-group">
                            <label for="editCodigo">Ficha</label>
                            <input type="text" class="form-control" id="editCodigoFicha" name="editCodigoFicha" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescripcion">Descripción</label>
                            <input class="form-control" id="editDescripcionFicha" name="editDescripcionFicha" required>
                        </div>
                        <div class="form-group">
                            <label for="editFechaInicio">Fecha Inicio</label>
                            <input type="date" class="form-control" id="editFechaInicioFicha" name="editFechaInicioFicha" required>
                        </div>
                        <div class="form-group">
                            <label for="editFechaFin">Fecha Final</label>
                            <input type="date" class="form-control" id="editFechaFinFicha" name="editFechaFinFicha" required>
                        </div>

                        <div class="form-group">
                            <label for="editSede">Sede</label>
                            <?php
                                $item = null;
                                $valor = null;
                                $sedes = ControladorSedes::ctrMostrarSedes($item, $valor);
                                // Create a dropdown for sedes
                                echo '<select class="form-control" name="editSedeFicha" requiered>
                                        <option id="editSedeFicha" value=""></option>';
                                    
                                // Loop through the sedes and create options
                                foreach ($sedes as $key => $value) {
                                    if ($value["estado"] != "inactivo") {
                                        echo '<option value="'.$value["id_sede"].'">'.$value["nombre_sede"].'</option>';
                                    }
                                }
                                echo '</select>';
                            ?>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <?php
                            $editarFicha = new ControladorFichas();
                            $editarFicha->ctrEditarFicha();
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
 
