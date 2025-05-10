<?php

class ControladorValidacion {

    static public function validarSesion() {
        
        if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] != "ok") {
            echo '<script>
                window.location = "login";
            </script>';
            exit;
        } else {
            // Si la sesión es válida, se puede continuar con el flujo normal
            return true;
        }
    }   

    static public function validarPermisoSesion($permiso) {
        // Si no hay sesión iniciada, no tiene permisos
        // Si no hay permisos cargados en sesión, no tiene permisos
        if (!isset($_SESSION["permisos"]) || !is_array($_SESSION["permisos"])) {
            return false;
        }
        // Si el permiso es un array, verificar si alguno de los permisos existe en el array de permisos
        if (is_array($permiso)) {
            foreach ($permiso as $p) {
                if (in_array($p, $_SESSION["permisos"])) {
                    return true;
                }
            }
            return false;
        }

        // // Verificar si el permiso existe en el array de permisos
        // return in_array($permiso, $_SESSION["permisos"]);
    }

}

