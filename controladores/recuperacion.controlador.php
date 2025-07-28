<?php

class ControladorRecuperacion {

    /*=============================================
    VERIFICAR CAPTCHA
    =============================================*/
    public function verificarCaptcha($respuestaUsuario) {
        // Captcha fijo: 5 + 3 = 8
        return (int)$respuestaUsuario === 8;
    }

    /*=============================================
    RECUPERAR CONTRASEÑA
    =============================================*/
    public function ctrRecuperarContrasena() {
        if(isset($_POST["nombreUsuario"]) && 
           isset($_POST["correoElectronico"]) && 
           isset($_POST["nuevaContrasena"]) && 
           isset($_POST["confirmarContrasena"]) &&
           isset($_POST["captcha"])) {
            
            // Validar formato de entrada
            if(preg_match('/^[a-zA-Z0-9]+$/', $_POST["nombreUsuario"]) && 
               filter_var($_POST["correoElectronico"], FILTER_VALIDATE_EMAIL)) {
                
                // Verificar que las contraseñas coincidan
                if($_POST["nuevaContrasena"] !== $_POST["confirmarContrasena"]) {
                    echo '<div class="alert alert-danger">Las contraseñas no coinciden.</div>';
                    return;
                }
                
                // Validar longitud mínima de contraseña
                if(strlen($_POST["nuevaContrasena"]) < 6) {
                    echo '<div class="alert alert-danger">La contraseña debe tener al menos 6 caracteres.</div>';
                    return;
                }
                
                // Verificar captcha
                if(!$this->verificarCaptcha($_POST["captcha"])) {
                    echo '<div class="alert alert-danger">La respuesta del captcha es incorrecta.</div>';
                    return;
                }
                
                $nombreUsuario = $_POST["nombreUsuario"];
                $correoElectronico = $_POST["correoElectronico"];
                
                // Verificar que el usuario y correo coincidan
                $usuario = ModeloRecuperacion::mdlVerificarUsuario("usuarios", $nombreUsuario, $correoElectronico);
                
                if($usuario) {
                    // Encriptar nueva contraseña
                    $nuevaContrasenaEncriptada = crypt($_POST["nuevaContrasena"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');
                    
                    $datosActualizacion = array(
                        "id_usuario" => $usuario["id_usuario"],
                        "nueva_clave" => $nuevaContrasenaEncriptada
                    );
                    
                    // Actualizar contraseña
                    $respuesta = ModeloRecuperacion::mdlActualizarContrasena("usuarios", $datosActualizacion);
                    
                    if($respuesta == "ok") {
                        echo '<div class="alert alert-success">
                                <h5>¡Contraseña actualizada!</h5>
                                <a>Ir al inicio</a>
                              </div>';
                    } else {
                        echo '<div class="alert alert-danger">Error al actualizar la contraseña. Intenta nuevamente.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">El nombre de usuario y correo electrónico no coinciden o el usuario no está activo.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">Por favor, verifica que el nombre de usuario y correo electrónico sean válidos.</div>';
            }
        }
    }
}