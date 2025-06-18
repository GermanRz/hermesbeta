# Scripts SQL para la Base de Datos hermes002

Este archivo contiene instrucciones y consultas SQL que deben ejecutarse en la base de datos `hermes002` para realizar actualizaciones o modificaciones específicas.

**¡IMPORTANTE!**

* Asegúrate de estar conectado a la base de datos `hermes002` antes de ejecutar estas consultas.
* Realiza una copia de seguridad de la base de datos antes de ejecutar cualquier script, por si acaso necesitas revertir los cambios.
* Ejecuta las consultas en el orden en que aparecen en este archivo, si el orden es relevante.

## Consultas y Procedimientos

### Agregar columna `condicion` a la tabla `usuarios`

Se debe agregar una columna llamada `condicion` de tipo `ENUM` a la tabla `usuarios`, ubicada después de la columna `estado`. Los posibles valores son `'penalizado'`, `'advertido'` y `'en_regla'`, siendo `'en_regla'` el valor por defecto.

```sql
ALTER TABLE usuarios
ADD COLUMN condicion ENUM('penalizado', 'advertido', 'en_regla') NOT NULL DEFAULT 'en_regla'
AFTER estado;
```

### Encriptación de claves para usuarios

Se recomienda almacenar las contraseñas de los usuarios encriptadas utilizando el algoritmo bcrypt. A continuación se muestran los valores encriptados para las contraseñas de ejemplo:

- **admin123**  
  Valor encriptado:  
  `$2a$07$asxx54ahjppf45sd87a5aunxs9bkpyGmGE/.vekdjFg83yRec789S`

- **clave123**  
  Valor encriptado:  
  `$2a$07$asxx54ahjppf45sd87a5auPSL9GB5Ad5sH/D3rUMKo4UJe4U/qGLO`

#### Consultas SQL para actualizar las contraseñas en la tabla `usuarios`

```sql
-- Asignar clave encriptada para el usuario administrador : clave=admin123
UPDATE usuarios
SET clave = '$2a$07$asxx54ahjppf45sd87a5aunxs9bkpyGmGE/.vekdjFg83yRec789S'
WHERE nombre = 'admin';

-- Asignar clave encriptada para los demás usuarios excepto para el admin clave=clave123
UPDATE usuarios
SET clave = '$2a$07$asxx54ahjppf45sd87a5auPSL9GB5Ad5sH/D3rUMKo4UJe4U/qGLO'
WHERE nombre <> 'admin';


Asegúrate de que la columna `clave` en la tabla `usuarios` tenga suficiente longitud para almacenar los hashes bcrypt (al menos `VARCHAR(200)`).

### Agregar columna `foto` a la tabla `usuarios`

Se debe agregar una columna llamada `foto` de tipo `VARCHAR(100)` a la tabla `usuarios`, ubicada después de la columna `genero`.

```sql
ALTER TABLE usuarios
ADD COLUMN foto VARCHAR(100) AFTER genero;
```

### Ruta por defecto para la foto de usuario

Al crear un nuevo usuario, el valor por defecto de la columna `foto` debe ser:  
`vistas/img/usuarios/default/anonymous.png`

### Creación automática de carpetas para fotos de usuario

Cuando se crea un usuario nuevo:
- Se debe crear automáticamente la carpeta `img` dentro de la carpeta `vistas` si no existe.
- Dentro de `img`, se debe crear la carpeta `usuarios`.
- Dentro de `usuarios`, se debe crear una carpeta con el número de documento del usuario.
- En esa carpeta es donde se almacenará la foto del usuario.

### Ejemplo de actualización de datos

```sql
-- Ejemplo: Actualizar la ruta de la foto para todos los usuarios existentes
UPDATE usuarios
SET foto = 'vistas/img/usuarios/default/anonymous.png';
```
# Carga Masiva de Usuarios

## Descripción

Este módulo permite la importación masiva de usuarios desde archivos Excel (XLSX) o CSV. El sistema valida los datos, procesa la información y genera un reporte detallado de los resultados.

## Características

- Importación desde Excel o CSV  
- Validaciones automáticas de datos  
- Asignación de roles y permisos  
- Generación de reportes de importación  
- Manejo de errores detallado  
- Compatibilidad con caracteres especiales  

## Requisitos Previos

- PHP 7.4 o superior  
- Composer instalado  
- Permisos de escritura en el directorio del proyecto  

## Instalación

1. Instalar PhpSpreadsheet vía Composer:

```bash
cd d:\appserver\Apache24\htdocs\hermesBeta
composer require phpoffice/phpspreadsheet
```

2. Verificar la instalación en `composer.json`:

```json
{
  "require": {
    "phpoffice/phpspreadsheet": "^4.3"
  }
}
```

## Configuración del Servidor

### PHP Configuration (`php.ini`)

Asegúrese de tener las siguientes extensiones habilitadas en su archivo `php.ini`:

```ini
extension=php_gd.dll
extension=php_zip.dll
extension=php_xml.dll
extension=php_xmlrpc.dll
extension=php_mbstring.dll
```

Pasos:

1. Abrir el archivo `php.ini`  
2. Buscar las líneas correspondientes  
3. Quitar el punto y coma (;) del inicio si está comentada  
4. Guardar el archivo y reiniciar Apache  

### Apache Configuration (`httpd.conf`)

Asegúrese de tener los siguientes módulos habilitados en su archivo `httpd.conf`:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule headers_module modules/mod_headers.so
```

Además, verifique que tiene las siguientes directivas en su configuración:

```apache
<Directory "d:/appserver/Apache24/htdocs/hermesBeta">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

Después de realizar estos cambios:

1. Guardar los archivos de configuración  
2. Reiniciar el servidor Apache  
3. Verificar que no hay errores en el log de Apache  

## Estructura del Archivo Excel

| Columna | Descripción        | Requerido         |
|---------|--------------------|-------------------|
| A       | Nombre             | Sí                |
| B       | Apellido           | Sí                |
| C       | Tipo Documento     | Sí                |
| D       | Número Documento   | Sí                |
| E       | Email              | Sí                |
| F       | Teléfono           | No                |
| G       | Dirección          | No                |
| H       | Género             | No                |
| I       | ID Rol             | Sí                |
| J       | ID Sede            | Solo Aprendices   |
| K       | ID Ficha           | Solo Aprendices   |

### Ejemplo de Formato Excel

| Nombre | Apellido | Tipo Doc | Num Doc  | Email           | Teléfono   | Dirección       | Género | ID Rol | ID Sede | ID Ficha |
|--------|----------|----------|----------|------------------|------------|------------------|--------|--------|----------|-----------|
| Juan   | Pérez    | CC       | 12345678 | juan@email.com   | 3001234567 | Calle 123 #45    | 2      | 6      | 1        | 2468135   |
| María  | López    | TI       | 98765432 | maria@email.com  | 3109876543 | Av Principal 78  | 1      | 6      | 2        | 1357924   |

## Proceso de Importación

1. Acceder al módulo de usuarios  
2. Hacer clic en "Importar Usuarios"  
3. Seleccionar el archivo Excel/CSV  
4. El sistema procesará y validará:
   - Campos requeridos  
   - Formato de email  
   - Existencia de roles  
   - Usuarios duplicados  
   - Relaciones sede-ficha  
5. Se generará un reporte con:
   - Usuarios importados exitosamente  
   - Errores encontrados  
   - Estadísticas de la importación  

## Consideraciones

- La contraseña inicial será el número de documento  
- Se asigna una imagen de perfil por defecto  
- Los usuarios se crean en estado "activo" y condición "en_regla"  
- Para rol Aprendiz (ID 6) son obligatorios sede y ficha  
- El reporte se genera en formato TXT con codificación UTF-8  

## Manejo de Errores

El sistema valida y reporta:

- Campos obligatorios faltantes  
- Formatos inválidos  
- Documentos/emails duplicados  
- Roles inexistentes  
- Relaciones sede-ficha inválidas  
- Errores en la base de datos  

## Ejemplos

### Formato correcto de Excel

| Nombre | Apellido | Tipo Doc | Num Doc  | Email             |
|--------|----------|----------|----------|-------------------|
| Juan   | Pérez    | CC       | 12345678 | juan@ejemplo.com  |

### Ejemplo de Reporte Generado

```txt
=== REPORTE DE IMPORTACIÓN DE USUARIOS ===
Fecha: 2025-06-12 14:30:45

USUARIOS IMPORTADOS EXITOSAMENTE (1):
----------------------------------------
Fila: 2 | Documento: 12345678 | Nombre: Juan Pérez

USUARIOS CON ERRORES (0):
----------------------------------------
```


