<?php

require_once "conexion.php";

class ModeloUsuarios{

    static public function mdlMostrarUsuariosServerSide($tabla, $params){
        $conexion = Conexion::conectar();
        $response = array();

        $sqlSelect = "SELECT u.id_usuario, u.tipo_documento, u.numero_documento, u.nombre, u.apellido, u.correo_electronico, u.foto, r.nombre_rol, f.codigo AS codigo_ficha, u.estado, u.condicion ";
        
        $sqlCountBase = "SELECT COUNT(u.id_usuario) "; 

        $fromClause = "FROM $tabla u 
                 LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                 LEFT JOIN roles r ON ur.id_rol = r.id_rol
                 LEFT JOIN aprendices_ficha af ON u.id_usuario = af.id_usuario
                 LEFT JOIN fichas f ON af.id_ficha = f.id_ficha ";

        $baseWhereClause = "WHERE u.id_usuario != 1 "; 

        $finalWhereClause = $baseWhereClause; 
        $bindings = []; 

        if (!empty($params['search']['value'])) {
            $searchValue = '%' . $params['search']['value'] . '%';
            $searchConditions = [];
            $searchableColumns = ['u.numero_documento', 'u.nombre', 'u.apellido', 'u.correo_electronico', 'r.nombre_rol', 'f.codigo'];
            foreach ($searchableColumns as $idx => $column) {
                $placeholder = ":searchValue" . $idx;
                $searchConditions[] = "$column LIKE $placeholder";
                $bindings[$placeholder] = $searchValue;
            }
            if(count($searchConditions) > 0){
                $finalWhereClause .= "AND (" . implode(" OR ", $searchConditions) . ") ";
            }
        }

        $stmtTotal = $conexion->prepare($sqlCountBase . $fromClause . $baseWhereClause);
        $stmtTotal->execute();
        $response['recordsTotal'] = (int)$stmtTotal->fetchColumn();
        $stmtTotal->closeCursor();

        $stmtFiltered = $conexion->prepare($sqlCountBase . $fromClause . $finalWhereClause);
        foreach ($bindings as $key => $value) {
            $stmtFiltered->bindValue($key, $value, PDO::PARAM_STR);
        }
        $stmtFiltered->execute();
        $response['recordsFiltered'] = (int)$stmtFiltered->fetchColumn();
        $stmtFiltered->closeCursor();
        
        $orderByClause = "ORDER BY u.id_usuario ASC "; 
        if (isset($params['order']) && count($params['order'])) {
            $columnMap = [
                0 => 'u.id_usuario', 1 => 'u.tipo_documento', 2 => 'u.numero_documento',
                3 => 'u.nombre', 4 => 'u.apellido', 5 => 'u.correo_electronico',
                6 => 'r.nombre_rol', 7 => 'f.codigo', 8 => 'u.estado', 9 => 'u.condicion'
            ];
            $columnIndex = intval($params['order'][0]['column']);
            if (array_key_exists($columnIndex, $columnMap)) {
                $columnToSortBy = $columnMap[$columnIndex];
                $sortDir = strtoupper($params['order'][0]['dir']);
                if ($sortDir === 'ASC' || $sortDir === 'DESC') {
                    $orderByClause = "ORDER BY $columnToSortBy $sortDir ";
                }
            }
        }

        $limitClause = "";
        $limitBindings = [];
        if (isset($params['start']) && isset($params['length']) && $params['length'] != -1) {
            $limitClause = "LIMIT :start, :length ";
            $limitBindings[':start'] = (int)$params['start'];
            $limitBindings[':length'] = (int)$params['length'];
        }
        
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
        $stmtData->closeCursor();

        $response['draw'] = isset($params['draw']) ? intval($params['draw']) : 0;
        
        $conexion = null; 
        return $response;
    }

    static public function mdlCrearUsuario($tabla, $datos){
        $conexion = Conexion::conectar();
        $stmt = null; $stmt2 = null; $stmt3 = null;
        try{
            $conexion->beginTransaction();
            $stmt = $conexion->prepare("INSERT INTO $tabla(tipo_documento, numero_documento, nombre, apellido, correo_electronico, nombre_usuario, clave, telefono, direccion, genero, foto) VALUES (:tipo_documento, :documento, :nombre, :apellido, :email, :usuario, :clave, :telefono, :direccion, :genero, :foto)");
            $stmt->bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
            $stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_STR);
            $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
            $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
            $stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_STR);
            $stmt->bindParam(":clave", $datos["password"], PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
            $stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_STR);
            $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);

            $stmt -> execute();

        
            //insertar los datos en la tabla usuario_rol
            $id_usuario = $conexion->lastInsertId();
            $stmt2 = $conexion->prepare("INSERT INTO usuario_rol(id_usuario, id_rol) VALUES (:id_usuario, :id_rol)");
            $stmt2->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt2->bindParam(":id_rol", $datos["rol"], PDO::PARAM_INT);

            $stmt2 -> execute();



            //si el rol del usuario es 6 (aprendiz) se guarda el id de la ficha y el id del nuevo usuario en la tabla aprendices_ficha
            if ($datos["rol"] == "6") {
                $stmt3 = $conexion->prepare("INSERT INTO aprendices_ficha(id_usuario, id_ficha) VALUES (:id_usuario, :id_ficha)");
                $stmt3->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                $stmt3->bindParam(":id_ficha", $datos["ficha"], PDO::PARAM_INT);

                $stmt3 -> execute();
    
            }

            //Confirmar transacción
            $conexion->commit();
            return "ok";
        } catch (Exception $e) {
            // Si ocurre un error, se revierte la transacción
            $conexion->rollBack();
            return "error";
        } finally {
            // Cerrar la conexión
            $conexion = null;
        }       
    }
    

    static public function mdlMostrarUsuarios($tabla, $item, $valor){

        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT u.*, 
                                                            r.id_rol, r.nombre_rol, 
                                                            f.id_ficha, f.descripcion AS descripcion_ficha, f.codigo, 
                                                            s.id_sede, s.nombre_sede
                                                    FROM $tabla as u      
                                                    LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                                                    LEFT JOIN roles r ON ur.id_rol = r.id_rol
                                                    LEFT JOIN aprendices_ficha af ON u.id_usuario = af.id_usuario
                                                    LEFT JOIN fichas f ON af.id_ficha = f.id_ficha 
                                                    LEFT JOIN sedes s ON f.id_sede = s.id_sede
                                                    WHERE u.$item = :$item LIMIT 1");
            if ($item == "id_usuario") {
                $stmt -> bindParam(":".$item, $valor, PDO::PARAM_INT);
            } else {
                $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
            }            
            $stmt -> execute();
            return $stmt -> fetch(PDO::FETCH_ASSOC);
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT u.*, r.id_rol, r.nombre_rol, f.id_ficha, f.descripcion AS descripcion_ficha, f.codigo
                                                    FROM $tabla as u      LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                                                    LEFT JOIN roles r ON ur.id_rol = r.id_rol
                                                    LEFT JOIN aprendices_ficha af ON u.id_usuario = af.id_usuario
                                                    LEFT JOIN fichas f ON af.id_ficha = f.id_ficha;");
            $stmt -> execute();
            return $stmt -> fetchAll();
        }
        $stmt -> close();
        $stmt = null;
        
    }

    static public function mdlMostrarFichasSede($tabla, $item, $valor){

        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt -> execute();
            return $stmt -> fetchAll();
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt -> execute();
            return $stmt -> fetchAll();
        }
        $stmt -> close();
        $stmt = null;
        
    }

    
        /*=============================================
        EDITAR PERFIL
        =============================================*/
        static public function mdlEditarPerfil($tabla, $datos){
            error_log("Consulta SQL: UPDATE $tabla SET tipo_documento = {$datos['tipo_documento']}, numero_documento = {$datos['numero_documento']}, nombre = {$datos['nombre']}, apellido = {$datos['apellido']}, correo_electronico = {$datos['correo_electronico']}, telefono = {$datos['telefono']}, direccion = {$datos['direccion']}, genero = {$datos['genero']}, foto = {$datos['foto']} WHERE id_usuario = {$datos['id_usuario']}");
            try {
                $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
                    correo_electronico = :correo_electronico,
                    telefono = :telefono,
                    direccion = :direccion,
                    genero = :genero,
                    foto = :foto
                    WHERE id_usuario = :id_usuario");

                $stmt->bindParam(":correo_electronico", $datos["correo_electronico"], PDO::PARAM_STR);
                $stmt->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
                $stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
                $stmt->bindParam(":genero", $datos["genero"], PDO::PARAM_STR);
                $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
                $stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);

                if($stmt->execute()){
                    return "ok";
                }

                return "error";

            } catch(PDOException $e) {
                return "error: " . $e->getMessage();
            } finally {
                if(isset($stmt)){
                    $stmt = null;
                }
            }
        }
        /*=============================================
        CAMBIAR ESTADO DE USUARIO
        =============================================*/
        static public function mdlCambiarEstadoUsuario($id, $estado) {
            $stmt = Conexion::conectar()->prepare("UPDATE usuarios SET estado = :estado WHERE id_usuario = :id");
            $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        }


    
       static public function mdlEditarUsuario($conexion, $tabla, $datos){
    error_log("Consulta SQL: UPDATE $tabla SET tipo_documento = {$datos['tipo_documento']}, numero_documento = {$datos['numero_documento']}, nombre = {$datos['nombre']}, apellido = {$datos['apellido']}, correo_electronico = {$datos['correo_electronico']}, telefono = {$datos['telefono']}, direccion = {$datos['direccion']}, genero = {$datos['genero']}, estado = {$datos['estado']}, condicion = {$datos['condicion']} WHERE id_usuario = {$datos['id_usuario']}");

    try {
        // Iniciar transacción
        $conexion->beginTransaction();

        $stmt1 = $conexion->prepare("
            UPDATE $tabla SET 
                tipo_documento = :tipo_documento, 
                numero_documento = :numero_documento, 
                nombre = :nombre, 
                apellido = :apellido, 
                correo_electronico = :correo_electronico, 
                telefono = :telefono, 
                direccion = :direccion, 
                genero = :genero, 
                foto = :foto,
                estado = :estado,
                condicion = :condicion
            WHERE id_usuario = :id_usuario
        ");

        $stmt1->bindParam(":tipo_documento", $datos["tipo_documento"], PDO::PARAM_STR);
        $stmt1->bindParam(":numero_documento", $datos["numero_documento"], PDO::PARAM_STR);
        $stmt1->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt1->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
        $stmt1->bindParam(":correo_electronico", $datos["correo_electronico"], PDO::PARAM_STR);
        $stmt1->bindParam(":telefono", $datos["telefono"], PDO::PARAM_STR);
        $stmt1->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
        $stmt1->bindParam(":genero", $datos["genero"], PDO::PARAM_INT);
        $stmt1->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
        $stmt1->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt1->bindParam(":condicion", $datos["condicion"], PDO::PARAM_STR);
        $stmt1->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
        $stmt1->execute();

        // Datos para roles y fichas
        $rolOriginal = $datos["idRolOriginal"];
        $fichaOriginal = $datos["idFichaOriginal"];
        $rolNuevo = $datos["id_rol"];
        $fichaNueva = $datos["id_ficha"];

        error_log("Rol original: $rolOriginal, Rol nuevo: $rolNuevo, Ficha original: $fichaOriginal, Ficha nueva: $fichaNueva");

        if ($rolOriginal != $rolNuevo) {
            $stmt2 = $conexion->prepare("UPDATE usuario_rol SET id_rol = :id_rol WHERE id_usuario = :id_usuario");
            $stmt2->bindParam(":id_rol", $rolNuevo, PDO::PARAM_INT);
            $stmt2->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt2->execute();
        }

        if ($rolOriginal == 6 && $rolNuevo != 6) {
            $stmt3 = $conexion->prepare("DELETE FROM aprendices_ficha WHERE id_usuario = :id_usuario");
            $stmt3->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt3->execute();
        }

        if ($rolOriginal != 6 && $rolNuevo == 6) {
            $stmt4 = $conexion->prepare("INSERT INTO aprendices_ficha(id_usuario, id_ficha) VALUES (:id_usuario, :id_ficha)");
            $stmt4->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt4->bindParam(":id_ficha", $fichaNueva, PDO::PARAM_INT);
            $stmt4->execute();
        }

        if ($rolNuevo == 6 && $fichaOriginal != $fichaNueva) {
            $stmt5 = $conexion->prepare("UPDATE aprendices_ficha SET id_ficha = :id_ficha WHERE id_usuario = :id_usuario");
            $stmt5->bindParam(":id_ficha", $fichaNueva, PDO::PARAM_INT);
            $stmt5->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
            $stmt5->execute();
        }

        $conexion->commit();
        return "ok";

    } catch (Exception $e) {
        $conexion->rollBack();
        error_log("Error al editar usuario: " . $e->getMessage());
        return "Error: " . $e->getMessage();
    }
}
}