# Scripts SQL para la Base de Datos hermes002

Este archivo contiene instrucciones y consultas SQL que deben ejecutarse en la base de datos `hermes002` para realizar actualizaciones o modificaciones específicas.

**¡IMPORTANTE!**

* Asegúrate de estar conectado a la base de datos `hermes002` antes de ejecutar estas consultas.
* Realiza una copia de seguridad de la base de datos antes de ejecutar cualquier script, por si acaso necesitas revertir los cambios.
* Ejecuta las consultas en el orden en que aparecen en este archivo, si el orden es relevante.

## Consultas y Procedimientos

### 1. Agregar columna `foto` a la tabla `usuarios`

- Se debe agregar una columna llamada `foto` de tipo `VARCHAR(100)` a la tabla `usuarios`, ubicada después de la columna `genero`.

```sql
ALTER TABLE usuarios
ADD COLUMN foto VARCHAR(100) AFTER genero;
```

### 2. Ruta por defecto para la foto de usuario

- Al crear un nuevo usuario, el valor por defecto de la columna `foto` debe ser:  
    `vistas/img/usuarios/default/anonymous.png`

### 3. Creación automática de carpetas para fotos de usuario

- Cuando se crea un usuario nuevo:
    - Se debe crear automáticamente la carpeta `img` dentro de la carpeta `vistas` si no existe.
    - Dentro de `img`, se debe crear la carpeta `usuarios`.
    - Dentro de `usuarios`, se debe crear una carpeta con el número de documento del usuario.
    - En esa carpeta es donde se almacenará la foto del usuario.

### 4. Ejemplo de actualización de datos

```sql
-- Ejemplo: Actualizar la ruta de la foto para un usuario existente
UPDATE usuarios
SET foto = 'vistas/img/usuarios/default/anonymous.png'
WHERE id_usuario = 1;
```



### 5. Creación de tabla de auditoría para usuarios

- Se debe crear una nueva tabla llamada `auditoria_usuarios` para registrar cambios en los usuarios:
```sql
CREATE TABLE `auditoria_usuarios` (
  `id_auditoria` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_afectado` int(11) NOT NULL,
  `id_usuario_editor` int(11) DEFAULT NULL,
  `campo_modificado` varchar(50) NOT NULL,
  `valor_anterior` text DEFAULT NULL,
  `valor_nuevo` text DEFAULT NULL,
  `fecha_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_auditoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

6. Modificación de la tabla usuarios
Actualizar el campo estado para aceptar más valores:

sql
ALTER TABLE `usuarios` 
MODIFY `estado` enum('activo','inactivo','advertido','penalizado') DEFAULT 'activo';
Agregar nuevo campo condicion:

sql
ALTER TABLE `usuarios`
ADD COLUMN `condicion` enum('penalizado','advertido','en_regla') NOT NULL DEFAULT 'en_regla';

7. Creación de trigger para auditoría automática
Crear un trigger que registre automáticamente los cambios en los usuarios:

sql
DELIMITER $$
CREATE TRIGGER `trg_auditar_usuarios` AFTER UPDATE ON `usuarios` FOR EACH ROW BEGIN
  DECLARE cambios TEXT DEFAULT '';
  DECLARE cambios_anterior TEXT DEFAULT '';
  DECLARE campos TEXT DEFAULT '';
  DECLARE separador VARCHAR(3) DEFAULT '';

  -- tipo_documento
  IF NOT (OLD.tipo_documento <=> NEW.tipo_documento) THEN
    SET cambios = CONCAT(cambios, separador, NEW.tipo_documento);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.tipo_documento);
    SET campos = CONCAT(campos, separador, 'tipo_documento');
    SET separador = '; ';
  END IF;

  -- numero_documento
  IF NOT (OLD.numero_documento <=> NEW.numero_documento) THEN
    SET cambios = CONCAT(cambios, separador, NEW.numero_documento);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.numero_documento);
    SET campos = CONCAT(campos, separador, 'numero_documento');
    SET separador = '; ';
  END IF;

  -- nombre
  IF NOT (OLD.nombre <=> NEW.nombre) THEN
    SET cambios = CONCAT(cambios, separador, NEW.nombre);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.nombre);
    SET campos = CONCAT(campos, separador, 'nombre');
    SET separador = '; ';
  END IF;

  -- apellido
  IF NOT (OLD.apellido <=> NEW.apellido) THEN
    SET cambios = CONCAT(cambios, separador, NEW.apellido);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.apellido);
    SET campos = CONCAT(campos, separador, 'apellido');
    SET separador = '; ';
  END IF;

  -- correo_electronico
  IF NOT (OLD.correo_electronico <=> NEW.correo_electronico) THEN
    SET cambios = CONCAT(cambios, separador, NEW.correo_electronico);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.correo_electronico);
    SET campos = CONCAT(campos, separador, 'correo_electronico');
    SET separador = '; ';
  END IF;

  -- nombre_usuario
  IF NOT (OLD.nombre_usuario <=> NEW.nombre_usuario) THEN
    SET cambios = CONCAT(cambios, separador, NEW.nombre_usuario);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.nombre_usuario);
    SET campos = CONCAT(campos, separador, 'nombre_usuario');
    SET separador = '; ';
  END IF;

  -- telefono
  IF NOT (OLD.telefono <=> NEW.telefono) THEN
    SET cambios = CONCAT(cambios, separador, NEW.telefono);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.telefono);
    SET campos = CONCAT(campos, separador, 'telefono');
    SET separador = '; ';
  END IF;

  -- direccion
  IF NOT (OLD.direccion <=> NEW.direccion) THEN
    SET cambios = CONCAT(cambios, separador, NEW.direccion);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.direccion);
    SET campos = CONCAT(campos, separador, 'direccion');
    SET separador = '; ';
  END IF;

  -- genero
  IF NOT (OLD.genero <=> NEW.genero) THEN
    SET cambios = CONCAT(cambios, separador, NEW.genero);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.genero);
    SET campos = CONCAT(campos, separador, 'genero');
    SET separador = '; ';
  END IF;

  -- estado
  IF NOT (OLD.estado <=> NEW.estado) THEN
    SET cambios = CONCAT(cambios, separador, NEW.estado);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.estado);
    SET campos = CONCAT(campos, separador, 'estado');
    SET separador = '; ';
  END IF;

  -- condicion
  IF NOT (OLD.condicion <=> NEW.condicion) THEN
    SET cambios = CONCAT(cambios, separador, NEW.condicion);
    SET cambios_anterior = CONCAT(cambios_anterior, separador, OLD.condicion);
    SET campos = CONCAT(campos, separador, 'condicion');
    SET separador = '; ';
  END IF;

  -- Insertar solo si hubo cambios
  IF cambios <> '' THEN
    INSERT INTO auditoria_usuarios (
      id_usuario_afectado,
      id_usuario_editor,
      campo_modificado,
      valor_anterior,
      valor_nuevo,
      fecha_cambio
    ) VALUES (
      OLD.id_usuario,
      @id_usuario_editor,
      campos,
      cambios_anterior,
      cambios,
      NOW()
    );
  END IF;

END$$
DELIMITER ;

8. Actualización de datos de usuario admin
Actualizar los datos del usuario administrador:
sql
UPDATE `usuarios` SET 
  `estado` = 'activo',
  `condicion` = 'en_regla',
  `clave` = '$2a$07$asxx54ahjppf45sd87a5aunxs9bkpyGmGE/.vekdjFg83yRec789S',
  `telefono` = '3175325038',
  `direccion` = 'cra 28 e 11-10',
  `genero` = 0,
  `foto` = 'vistas/img/usuarios/1/266.jpg'
WHERE `id_usuario` = 1;