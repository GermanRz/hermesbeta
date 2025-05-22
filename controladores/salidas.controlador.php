<?php

class ControladorSalidas {
    
    static public function ctrMostrarSalidas() {
        try {
            $tabla = "salidas"; // Asegúrate de reemplazar con el nombre correcto de tu tabla
            
            $stmt = Conexion::conectar()->prepare("SELECT id, nombre_rol, fecha, estado, acciones 
                                                 FROM $tabla 
                                                 ORDER BY fecha DESC");
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }
}

?>