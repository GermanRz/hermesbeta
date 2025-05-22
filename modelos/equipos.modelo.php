<?php

require_once "conexion.php";

class ModeloEquipos{
    public static function mdlMostrarEquipos($tabla, $item, $valor){
        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT 
            e.equipo_id,
            e.numero_serie,
            e.etiqueta,
            e.descripcion,
            e.fecha_entrada,
            u.ubicacion_id,
            c.categoria_id,
            c.nombre AS categoria_nombre,
            cu.id_usuario,
            e.id_estado,
            es.estado AS estado_nombre,
            u.nombre AS ubicacion_nombre,
            CONCAT_WS(' ',cu.nombre,cu.apellido) AS cuentadante_nombre
        FROM 
            $tabla e
        LEFT JOIN 
            ubicaciones u ON e.ubicacion_id = u.ubicacion_id
        LEFT JOIN 
            categorias c ON e.categoria_id = c.categoria_id
        LEFT JOIN 
            usuarios cu ON e.cuentadante_id = cu.id_usuario
        LEFT JOIN
            estados es ON e.id_estado = es.id_estado
        -- LEFT JOIN
            
        WHERE e." . $item . " = :" . $item);

            if ($item == "numero_serie" || $item == "etiqueta" || $item == "descripcion" || $item == "categoria_nombre" || $item == "estado_nombre" || $item == "ubicacion_nombre" || $item == "cuentadante_nombre") {
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            } else {
                $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
            }
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT 
            e.equipo_id,
            e.numero_serie,
            e.etiqueta,
            e.descripcion,
            e.fecha_entrada,
            u.ubicacion_id,
            c.categoria_id,
            c.nombre AS categoria_nombre,
            cu.id_usuario,
            e.id_estado,
            es.estado AS estado_nombre,
            u.nombre AS ubicacion_nombre,
            CONCAT_WS(' ',cu.nombre,cu.apellido) AS cuentadante_nombre
        FROM 
            $tabla e
        LEFT JOIN 
            ubicaciones u ON e.ubicacion_id = u.ubicacion_id
        LEFT JOIN 
            categorias c ON e.categoria_id = c.categoria_id
        LEFT JOIN 
            usuarios cu ON e.cuentadante_id = cu.id_usuario
        LEFT JOIN
            estados es ON e.id_estado = es.id_estado");
            $stmt->execute();
            return $stmt->fetchAll();
        }

        // $stmt->close(); // This line is unreachable due to returns. Kept for historical context if needed.
        // $stmt = null;
    } // fin del metodo mdlMostrarEquipos


    
    // =====================================
    //     REALIZAR TRASPASO CUENTADANTE
    // =====================================
    public static function mdlMostrarDatosCuentadanteOrigen($tabla, $item, $valor){
        $stmt1 = null; // Initialize to null
        try {
            // SQL CAPTURANDO LOS DATOS DEL CUENTADANTE ACTUAL A MOSTRAR EN EL MODAL
            $stmt1 = Conexion::conectar()->prepare("SELECT e.equipo_id,
                                                us.nombre,
                                                us.numero_documento,
                                                ub.nombre as ubicacion_nombre,
                                                ur.id_rol
                                                FROM $tabla e
                                                LEFT JOIN usuarios us ON e.cuentadante_id = us.id_usuario
                                                LEFT JOIN ubicaciones ub ON e.ubicacion_id = ub.ubicacion_id
                                                LEFT JOIN usuario_rol ur ON us.id_usuario = ur.id_usuario
                                                WHERE e.$item = :$item;"); // Corrected variable concatenation
            if ($item == "equipo_id") {
                $stmt1->bindParam(":" . $item, $valor, PDO::PARAM_INT);
            } else {
                $stmt1->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            }
            $stmt1->execute();
            return $stmt1->fetch();
        } catch (Exception $e) {
            error_log("Error al mostrar datos cuentadante origen: " . $e->getMessage()); // Corrected error message
            return false; // Indicate failure
        } finally {
            if ($stmt1) $stmt1->closeCursor();
            // $conexion = null; // Connection usually managed by Conexion class or caller
        }
    } // fin del metodo mdlRealizarTraspasoCuentadante

    

    public static function mdlMostrarDatosCuentadanteTraspaso($tabla, $item, $valor){
        $stmt = null; // Initialize to null
        try{
            $stmt = Conexion::conectar()->prepare("SELECT 
                                                us.numero_documento,
                                                us.nombre AS cuentadante_nombre,
                                                e.cuentadante_id,
                                                e.ubicacion_id,
                                                us.id_usuario,
                                                e.equipo_id,
                                                ro.nombre_rol,
                                                ub.nombre AS ubicacion_nombre
                                            FROM 
                                                $tabla us
                                            LEFT JOIN 
                                                usuario_rol ur ON us.id_usuario = ur.id_usuario
                                            LEFT JOIN 
                                                roles ro ON ur.id_rol = ro.id_rol
                                            LEFT JOIN
                                                equipos e ON us.id_usuario = e.cuentadante_id
                                            LEFT JOIN
                                                ubicaciones ub ON e.ubicacion_id = ub.ubicacion_id
                                            WHERE 
                                                us.$item = :$item;"); // Corrected table alias and variable concatenation
            if($item == "id_rol" || $item == "equipo_id" || $item == "id_usuario"){ // Added id_usuario as potential INT
                $stmt -> bindParam(":" . $item, $valor, PDO::PARAM_INT);
            } else {
                $stmt -> bindParam(":" . $item, $valor, PDO::PARAM_STR);
            }
            $stmt -> execute();
            return $stmt -> fetch();
        } catch (Exception $e){
            error_log("Error al mostrar datos cuentadante traspaso: " . $e->getMessage()); // Corrected error message
            return false; // Indicate failure
        } finally {
            if ($stmt) $stmt->closeCursor();
        }
    }

    public static function mdlMostrarUbicacion($tabla, $item, $valor){
        $stmt = null; // Initialize to null
        try{
            $stmt = Conexion::conectar()->prepare("SELECT ub.ubicacion_id, 
                                                    ub.nombre AS nombre_ubicacion
                                                    FROM $tabla e
                                                    JOIN ubicaciones ub ON e.ubicacion_id = ub.ubicacion_id
                                                    WHERE e.$item = :$item"); // Corrected variable concatenation
            if($item == "nombre_ubicacion"){
                $stmt -> bindParam(":" . $item, $valor, PDO::PARAM_STR);
            } else { // Assuming other items like ubicacion_id are INT
                $stmt -> bindParam(":" . $item, $valor, PDO::PARAM_INT);
            }
            $stmt -> execute();
            return $stmt -> fetch();
        } catch (Exception $e){
            error_log("Error al mostrar ubicación: " . $e->getMessage()); // Corrected error message
            return false; // Indicate failure
        } finally {
            if ($stmt) $stmt->closeCursor();
        }
    }

    public static function mdlMostrarUbicacionDestino($tabla, $item, $valor){
        $stmt = null; // Initialize to null
        try{
            // This query implies $tabla is 'equipos' and you want the ubicacion of a specific equipo.
            // If $tabla is 'ubicaciones' and $item is 'ubicacion_id', it's simpler.
            // Assuming $tabla is 'equipos' as per context of other similar functions.
            $stmt = Conexion::conectar()->prepare("SELECT ub.ubicacion_id, 
                                                    ub.nombre AS nombre_ubicacion
                                                    FROM $tabla e 
                                                    JOIN ubicaciones ub ON e.ubicacion_id = ub.ubicacion_id
                                                    WHERE e.$item = :$item LIMIT 1");
            if($item == "nombre_ubicacion"){ // This condition seems unlikely if $item refers to a column in 'e'
                $stmt -> bindParam(":" . $item, $valor, PDO::PARAM_STR);
            } else { // Assuming $item is like 'equipo_id' or 'ubicacion_id' from 'e' table
                $stmt -> bindParam(":" . $item, $valor, PDO::PARAM_INT);
            }
            $stmt -> execute();
            return $stmt -> fetch();
        } catch (Exception $e){
            error_log("Error al mostrar ubicación destino: " . $e->getMessage()); // Corrected error message
            return false; // Indicate failure
        } finally {
            if ($stmt) $stmt->closeCursor();
        }
        
    }

    static public function mdlRealizarTraspasoUbicacion($tabla, $datos){
        $stmt = null; // Initialize to null
        try{
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla 
                                                    SET ubicacion_id = :ubicacion_id
                                                    WHERE equipo_id = :equipo_id");
            
            $stmt -> bindParam(":ubicacion_id", $datos["ubicacion_id"], PDO::PARAM_INT);
            $stmt -> bindParam(":equipo_id", $datos["equipo_id"], PDO::PARAM_INT);

            if($stmt ->execute()){
                return "ok";
            } else {
                $errorInfo = $stmt -> errorInfo();
                error_log("Error SQL en mdlRealizarTraspasoUbicacion: " . $errorInfo[2]); // Log specific SQL error
                return "error"; // Simplified error return
            }
        } catch (PDOException $e){
            error_log("Error al cambiar de ubicación: " . $e->getMessage());
            return "error";
        } finally {
            if ($stmt) $stmt->closeCursor();
        }
    }

    public static function mdlRealizarTraspasoCuentadante($tabla, $datos){
        $stmt = null; // Initialize to null
        try{
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla 
                                                    SET cuentadante_id = :cuentadante_id
                                                    WHERE equipo_id = :equipo_id");
            
            $stmt->bindParam(":equipo_id", $datos["equipo_id"], PDO::PARAM_INT);
            $stmt->bindParam(":cuentadante_id", $datos["cuentadante_id"], PDO::PARAM_INT);

            if($stmt ->execute()){
                return "ok";
            } else {
                 $errorInfo = $stmt -> errorInfo();
                 error_log("Error SQL en mdlRealizarTraspasoCuentadante: " . $errorInfo[2]);
                 return "error";
            }
        } catch (Exception $e){
            error_log("Error al cambiar de cuentadante: " . $e->getMessage());
            return "error";
        } finally {
            if ($stmt) $stmt->closeCursor();
        }
    }

     // =====================================
    //    AGREGAR EQUIPOS
    // =====================================

    public static function mdlAgregarEquipos($tabla, $datos){
        $stmt = null; // Initialize to null
        try {
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla 
                                                    (numero_serie, 
                                                    etiqueta, 
                                                    descripcion, 
                                                    ubicacion_id, 
                                                    categoria_id, 
                                                    cuentadante_id,
                                                    id_estado) 
                                                    VALUES (:numero_serie, 
                                                    :etiqueta, 
                                                    :descripcion, 
                                                    :ubicacion_id, 
                                                    :categoria_id, 
                                                    :cuentadante_id,
                                                    :id_estado)");
    
            $stmt->bindParam(":numero_serie", $datos["numero_serie"], PDO::PARAM_STR);
            $stmt->bindParam(":etiqueta", $datos["etiqueta"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
            $stmt->bindParam(":ubicacion_id", $datos["ubicacion_id"], PDO::PARAM_INT);
            $stmt->bindParam(":categoria_id", $datos["categoria_id"], PDO::PARAM_INT);
            $stmt->bindParam(":cuentadante_id", $datos["cuentadante_id"], PDO::PARAM_INT);
            $stmt->bindParam(":id_estado", $datos["id_estado"], PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                return "ok";
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("Error SQL en mdlAgregarEquipos: " . $errorInfo[2]);
                return "error"; 
            }
        } catch (PDOException $e) {
            error_log("Error en mdlAgregarEquipos: " . $e->getMessage()); 
            return "error";
        } finally {
            if ($stmt) $stmt->closeCursor();
        }
    }

    static public function mdlEditarEquipos($tabla, $datos){
        $stmt = null; // Initialize to null
        try{
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
                etiqueta = :etiquetaEdit,
                descripcion = :descripcionEdit,
                categoria_id = :categoriaEdit,
                id_estado = :estadoEdit
                WHERE equipo_id = :equipo_id");
                
            $stmt->bindParam(":equipo_id", $datos["equipo_id"], PDO::PARAM_INT);
            $stmt->bindParam(":etiquetaEdit", $datos["etiquetaEdit"], PDO::PARAM_STR);
            $stmt->bindParam(":descripcionEdit", $datos["descripcionEdit"], PDO::PARAM_STR);
            $stmt->bindParam(":categoriaEdit", $datos["categoriaEdit"], PDO::PARAM_INT);
            $stmt->bindParam(":estadoEdit", $datos["estadoEdit"], PDO::PARAM_INT);
    
            if($stmt->execute()){
                return "ok";
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("Error SQL en mdlEditarEquipos: " . $errorInfo[2]);
                return "error";
            }
        } catch(PDOException $e){
            error_log("Error en mdlEditarEquipos: " . $e->getMessage());
            return "error"; // Simplified error
        } finally {
            if($stmt) $stmt->closeCursor();
        }
    }

    static public function mdlMostrarEquiposServerSide($tabla, $params){
        $conexion = Conexion::conectar();
        $response = [];
    
        $sqlSelect = "SELECT e.equipo_id, e.numero_serie, e.etiqueta, e.descripcion, u.nombre AS ubicacion_nombre, c.nombre AS categoria_nombre, CONCAT_WS(' ', cu.nombre, cu.apellido) AS cuentadante_nombre, es.estado AS estado_nombre ";
        $fromClause = "FROM {$tabla} e 
                       LEFT JOIN ubicaciones u ON e.ubicacion_id = u.ubicacion_id
                       LEFT JOIN categorias c ON e.categoria_id = c.categoria_id
                       LEFT JOIN usuarios cu ON e.cuentadante_id = cu.id_usuario
                       LEFT JOIN estados es ON e.id_estado = es.id_estado ";
        
        $baseWhereClause = ""; 
        $finalWhereClause = $baseWhereClause;
        $bindings = [];
        $param_idx = 0; 
    
        if (!empty($params['search']['value'])) {
            $searchValue = '%' . $params['search']['value'] . '%';
            $searchConditions = [];
            // Note: CONCAT_WS needs to be searched as a whole string, not individual parts easily with a single LIKE here.
            // For more complex searches on concatenated fields, full-text search or multiple OR conditions on individual fields might be better.
            $searchableColumns = ['e.numero_serie', 'e.etiqueta', 'e.descripcion', 'u.nombre', 'c.nombre', "CONCAT_WS(' ', cu.nombre, cu.apellido)", 'es.estado'];
            
            foreach ($searchableColumns as $column) {
                $placeholder = ":searchValue" . $param_idx++;
                $searchConditions[] = "$column LIKE $placeholder";
                $bindings[$placeholder] = $searchValue;
            }
            if(count($searchConditions) > 0){
                $finalWhereClause .= (empty($baseWhereClause) ? "WHERE " : "AND ") . "(" . implode(" OR ", $searchConditions) . ") ";
            }
        }
    
        $stmtTotal = null;
        try {
            $stmtTotal = $conexion->prepare("SELECT COUNT(e.equipo_id) " . $fromClause . $baseWhereClause);
            $stmtTotal->execute();
            $response['recordsTotal'] = (int)$stmtTotal->fetchColumn();
        } finally {
            if($stmtTotal) $stmtTotal->closeCursor();
        }
    
        $stmtFiltered = null;
        try {
            $stmtFiltered = $conexion->prepare("SELECT COUNT(e.equipo_id) " . $fromClause . $finalWhereClause);
            foreach ($bindings as $key => $value) {
                $stmtFiltered->bindValue($key, $value, PDO::PARAM_STR);
            }
            $stmtFiltered->execute();
            $response['recordsFiltered'] = (int)$stmtFiltered->fetchColumn();
        } finally {
            if($stmtFiltered) $stmtFiltered->closeCursor();
        }
    
        $orderByClause = "ORDER BY e.equipo_id ASC "; 
        if (isset($params['order']) && count($params['order'])) {
            // Ensure column indices match the 'data' properties sent from DataTables
            $columnMap = [
                0 => 'e.equipo_id', // Assuming # is not sent, or this is the first data column
                1 => 'e.numero_serie', 
                2 => 'e.etiqueta', 
                3 => 'e.descripcion', 
                4 => 'ubicacion_nombre', 
                5 => 'categoria_nombre', 
                6 => 'cuentadante_nombre', 
                7 => 'estado_nombre'
            ];
            $columnIndex = intval($params['order'][0]['column']);
            if (array_key_exists($columnIndex, $columnMap)) {
                $columnToSortBy = $columnMap[$columnIndex];
                 // Check if alias needs table prefix (e.g. u.nombre AS ubicacion_nombre). Usually not for ORDER BY alias.
                if ($columnToSortBy === 'ubicacion_nombre') $columnToSortBy = 'u.nombre';
                if ($columnToSortBy === 'categoria_nombre') $columnToSortBy = 'c.nombre';
                if ($columnToSortBy === 'cuentadante_nombre') $columnToSortBy = "CONCAT_WS(' ', cu.nombre, cu.apellido)";
                if ($columnToSortBy === 'estado_nombre') $columnToSortBy = 'es.estado';

                $sortDir = strtoupper($params['order'][0]['dir']);
                if ($sortDir === 'ASC' || $sortDir === 'DESC') {
                    $orderByClause = "ORDER BY $columnToSortBy $sortDir ";
                }
            }
        }
    
        $limitClause = "";
        $limitBindings = []; // Separate bindings for limit to avoid conflict if :start/:length are used as search placeholders
        if (isset($params['start']) && isset($params['length']) && $params['length'] != -1) {
            $limitClause = "LIMIT :ss_start, :ss_length "; // Use unique placeholders for limit
            $limitBindings[':ss_start'] = (int)$params['start'];
            $limitBindings[':ss_length'] = (int)$params['length'];
        }
        
        $stmtData = null;
        try {
            $mainQuery = $sqlSelect . $fromClause . $finalWhereClause . $orderByClause . $limitClause;
            $stmtData = $conexion->prepare($mainQuery);
        
            foreach ($bindings as $key => $value) { 
                $stmtData->bindValue($key, $value, PDO::PARAM_STR);
            }
            foreach ($limitBindings as $key => $value) { 
                $stmtData->bindValue($key, $value, PDO::PARAM_INT);
            }
            
            $stmtData->execute();
            $response['data'] = $stmtData->fetchAll(PDO::FETCH_ASSOC);
        } finally {
            if($stmtData) $stmtData->closeCursor();
        }
    
        $response['draw'] = isset($params['draw']) ? intval($params['draw']) : 0;
        
        $conexion = null; 
        return $response;
    }
}
