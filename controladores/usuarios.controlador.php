<?php

class ControladorUsuarios{

    public function ctrIngresoUsuario(){
        if (isset($_POST["ingUsuario"])) {
            if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingUsuario"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingPassword"])) {
                
                $encriptar = crypt($_POST["ingPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                $tabla = "usuarios";
                $item = "nombre_usuario";
                $valor = $_POST["ingUsuario"];

                $respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);

                if ($respuesta["nombre_usuario"] == $_POST["ingUsuario"] && $respuesta["clave"] == $encriptar) {
                    if($respuesta["estado"] == "activo") {
                        // Iniciar sesión y guardar datos del usuario
                        $_SESSION["iniciarSesion"] = "ok";
                        $_SESSION["id_usuario"] = $respuesta["id_usuario"];
                        $_SESSION["nombre"] = $respuesta["nombre"];
                        $_SESSION["apellido"] = $respuesta["apellido"];
                        $_SESSION["usuario"] = $respuesta["nombre_usuario"];
                        // $_SESSION["foto"] = $respuesta["foto"];
                        $_SESSION["rol"] = $respuesta["id_rol"];
                        $_SESSION["nombre_rol"] = $respuesta["nombre_rol"];

                        // Obtener permisos del rol
                        $permisos = ModeloPermisos::mdlMostrarPermisos("id_rol", $respuesta["id_rol"]);
                        $_SESSION["permisos"] = array();
                        foreach($permisos as $permiso) {
                            $_SESSION["permisos"][] = $permiso["id_permiso"];
                        }

                        echo '<script>
                            window.location = "inicio";
                        </script>';
                    } else {
                        echo '<br><div class="alert alert-danger">El usuario está inactivo</div>';
                    }
                } else {
                    echo '<br><div class="alert alert-danger">Usuario y/o contraseña incorrectos</div>';
                }
            }
        }
    }

    static public function ctrCrearUsuario(){
        
        if (isset($_POST["nuevoNombre"]) &&
            isset($_POST["nuevoApellido"]) &&
            isset($_POST["nuevoTipoDocumento"]) &&
            isset($_POST["nuevoNumeroDocumento"]) ){
            if (preg_match('/^[a-zA-ZñÑáéíóÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[a-zA-ZñÑáéíóÁÉÍÓÚ ]+$/', $_POST["nuevoApellido"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["nuevoNumeroDocumento"]) ) {
                // si el usuario es aprendiz se debe validar la sede y la ficha
                if ($_POST["selectRol"] != 6) {
                    $sede = "";
                    $ficha = "";
                }else{
                    $sede = $_POST["id_sede"];
                    $ficha = $_POST["id_ficha"];
                }

                $tabla = "usuarios";
                $encriptar = crypt($_POST["nuevoPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                $datos = array(
                    "nombre" => $_POST["nuevoNombre"],
                    "apellido" => $_POST["nuevoApellido"],
                    "tipo_documento" => $_POST["nuevoTipoDocumento"],
                    "documento" => $_POST["nuevoNumeroDocumento"],
                    "email" => $_POST["nuevoEmail"],
                    "telefono" => $_POST["nuevoTelefono"],
                    "direccion" => $_POST["nuevaDireccion"],
                    "genero" => $_POST["nuevoGenero"],
                    "usuario" => $_POST["nuevoNumeroDocumento"],
                    "password" => $encriptar,
                    "rol" => $_POST["selectRol"],
                    // si es aprendiz
                    "sede" => $sede,
                    "ficha" => $ficha
                );

                $respuesta = ModeloUsuarios::mdlCrearUsuario($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                        swal.fire({
                            icon: "success",
                            title: "¡El usuario ha sido creado correctamente!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "usuarios";
                            }
                        });
                    </script>';
                } else {
                    echo '<script>
                        swal.fire({
                            icon: "error",
                            title: "¡Error al crear el usuario!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "usuarios";
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal.fire({
                        icon: "error",
                        title: "¡Revisar parametros!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "usuarios";
                        }
                    });
                </script>';
            }
        }
        // var_dump($_POST);
    }


    static public function ctrMostrarUsuarios($item, $valor){
        $tabla = "usuarios";
        $respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);
        return $respuesta;
    }

    static public function ctrMostrarFichasSede($item, $valor){
        $tabla = "fichas";
        $respuesta = ModeloUsuarios::mdlMostrarFichasSede($tabla, $item, $valor);
        return $respuesta;
    }


      
        

    static public function ctrEditarUsuario(){

        
        if (isset($_POST["idEditUsuario"]) && isset($_POST["editNombre"]) && isset($_POST["selectEditSede"])) {   

            
            if (preg_match('/^[a-zA-ZñÑáéíóÁÉÍÓÚ ]+$/', $_POST["editNombre"]) &&
                preg_match('/^[a-zA-ZñÑáéíóÁÉÍÓÚ ]+$/', $_POST["editApellido"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["editNumeroDocumento"]) &&
                preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST["editEmail"]) &&
                preg_match('/^[0-9]+$/', $_POST["editTelefono"]) &&
                preg_match('/^[a-zA-Z0-9#\- ]+$/', $_POST["editDireccion"]) ){
                    //error de rol origina y rol nuevo
                    error_log("rol original " . $_POST["rolOriginal"]. " rol nuevo " . $_POST["EditRolUsuario"]);


                // si el usuario es aprendiz se debe validar la sede y la ficha
                if ($_POST["EditRolUsuario"] != 6) {
                    $sede = "";
                    $ficha = "";
                }else{
                    $sede = $_POST["selectEditSede"];
                    $ficha = $_POST["selectEditIdFicha"];
                }

                $tabla = "usuarios";
                
                $datos = array(
                    "id_usuario" => $_POST["idEditUsuario"],
                    "tipo_documento" => $_POST["editTipoDocumento"],
                    "numero_documento" => $_POST["editNumeroDocumento"],
                    "nombre" => $_POST["editNombre"],
                    "apellido" => $_POST["editApellido"],
                    "correo_electronico" => $_POST["editEmail"],
                    "telefono" => $_POST["editTelefono"],
                    "direccion" => $_POST["editDireccion"],
                    "genero" => $_POST["editGenero"],
                    "id_rol" => $_POST["EditRolUsuario"],
                    // si es aprendiz
                    "id_sede" => $sede,
                    "id_ficha" => $ficha,
                    //datos originales ids de rol y ficha
                    "idRolOriginal" => $_POST["rolOriginal"],
                    "idFichaOriginal" => $_POST["fichaOriginal"]
                );

                error_log("Datos a enviar: " . json_encode($datos));


                $respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);

                if ($respuesta == "ok") {
                    echo '<script>
                        swal.fire({
                            icon: "success",
                            title: "¡El usuario ha sido actualizado correctamente!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "usuarios";
                            }
                        });
                    </script>';
                } else {
                    echo '<script>
                        swal.fire({
                            icon: "error",
                            title: "¡Error al actualizar el usuario!",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "usuarios";
                            }
                        });
                    </script>';
                }
            } else {
                echo '<script>
                    swal.fire({
                        icon: "error",
                        title: "¡Revisar parametros!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "usuarios";
                        }
                    });
                </script>';
            }
        }
    }
        

}  //fin de la clase ControladorUsuarios