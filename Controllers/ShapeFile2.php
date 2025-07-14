<?php
require_once("Models/SRIDModel.php");
ini_set('memory_limit', IS_DEV? '2048M' : '6144M');

class ShapeFile extends Controllers {

    public function __construct() {
        parent::__construct();
        session_start();
        $this->model = new SRIDModel();
    }

    function obtenerSRID($shapefilePath) {
        $command = "gdalsrsinfo -o proj4 " . escapeshellarg($shapefilePath);
        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || empty($output)) {
            return null;
        }

        $proj4 = trim(implode(" ", $output));
        $proj4 = trim($proj4, "'");
        $proj4 = addslashes($proj4);

        return $this->model->obtenerSRID($proj4);
    }

    function obtenerNombreCapa(string $shapefilePath) {
        $command = "ogrinfo -ro -so -al " . escapeshellarg($shapefilePath);
        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || empty($output)) {
            return null;
        }

        $layerName = null;
        foreach ($output as $line) {
            if (preg_match('/^Layer name:\s*(.+)$/', trim($line), $matches)) {
                $layerName = trim($matches[1]);
                break;
            }
        }

        if ($layerName === null) {
            return null;
        }
        return $layerName;
    }

    function sanitizeFilename($filename) {
        $unwanted_array = [
            'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u',
            'Á'=>'A', 'É'=>'E', 'Í'=>'I', 'Ó'=>'O', 'Ú'=>'U',
            'ñ'=>'n', 'Ñ'=>'N'
        ];
        $filename = strtr($filename, $unwanted_array);
        $filename = strtolower($filename);
        $filename = str_replace(' ', '_', $filename);
        $filename = preg_replace('/[^a-z0-9_]/', '', $filename); // Eliminar caracteres especiales
        return $filename;
    }

    public function renombrar() {
        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["estado" => false, "mensaje" => "Método no permitido."]);
            exit;
        }

        $shapeFileDir = $_POST["shapeFileDir"] ?? null;

        if (!$shapeFileDir) {
            echo json_encode(["estado" => false, "mensaje" => "Faltan parámetros: shapeFileDir."]);
            exit;
        }

        if (!is_dir($shapeFileDir)) {
            echo json_encode(["estado" => false, "mensaje" => "La carpeta no existe o no es un directorio válido."]);
            exit;
        }

        // Obtener archivos .shp
        $shpFiles = glob(rtrim($shapeFileDir, '/') . "/*.shp");

        if (!$shpFiles || count($shpFiles) === 0) {
            echo json_encode(["estado" => false, "mensaje" => "No se encontró un archivo .shp en la carpeta.", "contenido" => scandir($shapeFileDir)]);
            exit;
        }

        $originalShapefile = pathinfo($shpFiles[0], PATHINFO_FILENAME);
        $newShapefile = $this->sanitizeFilename($originalShapefile);

        $input_shp = "{$shapeFileDir}/{$originalShapefile}.shp";
        $ruta_tmp = "{$shapeFileDir}tmp/";
        $output_shp = "{$ruta_tmp}{$newShapefile}.shp";

        // Crear carpeta tmp si no existe
        if (!file_exists($ruta_tmp) && !mkdir($ruta_tmp, 0777, true)) {
            echo json_encode(["estado" => false, "mensaje" => "Error al crear la carpeta temporal."]);
            exit;
        }

        // Verificar si el archivo de entrada existe antes de procesarlo
        if (!file_exists($input_shp)) {
            echo json_encode(["estado" => false, "mensaje" => "El archivo .shp no existe en la ruta proporcionada.", "ruta" => $input_shp]);
            exit;
        }

        if (file_exists($ruta_tmp && PHP_OS_FAMILY != "Windows")) {
            exec("chcon -R -t httpd_sys_rw_content_t " . escapeshellarg($ruta_tmp));
        }

        // Ejecutar el comando ogr2ogr
        $cmd = "ogr2ogr -f \"ESRI Shapefile\" \"$output_shp\" \"$input_shp\" -skipfailures 2>&1";
        exec($cmd, $output, $return_var);

        if ($return_var !== 0) {
            echo json_encode(["estado" => false, "mensaje" => "Error al renombrar el Shapefile.", "error" => implode("\n", $output)]);
            exit;
        } else {
            echo json_encode(["estado" => true, "mensaje" => "Shapefile renombrado correctamente.", "ruta" => $ruta_tmp, "nuevo" => $output_shp]);
            exit;
        }
    }

    private function esFecha($valor) {
        if (!is_string($valor)) return false;
        return preg_match('/\d{4}[-\/]\d{2}[-\/]\d{2}/', $valor);
    }

    private function esFechaValida($fecha) {
        $formatos = ["Y-m-d", "d/m/Y", "m-d-Y", "Y/m/d H:i:s", "Y-m-d H:i:s"];
        foreach ($formatos as $formato) {
            $dateTime = DateTime::createFromFormat($formato, $fecha);
            if ($dateTime && $dateTime->format($formato) === $fecha) {
                return true;
            }
        }
        return false;
    }

    public function consultarEsquema() {
        if($_GET)
        {
            $esquema_nombre = $_GET['esquema_nombre'];
            $accion = $_GET['accion'];
            $esquema_actual = $_GET['esquema_actual'];
            if(empty($esquema_nombre) || empty($accion))
            {
				$arrResponse = array("estado" => false, "mensaje" => 'Datos incorrectos.');
			}
            header("Content-Type: application/json");
            $respuesta = $this->model->gestionarEsquema($esquema_nombre, $accion, $esquema_actual);
            echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);
        }
    }

    function normalizarNombreColumna($nombre) {
        $reservadas = [
            'area', 'perimeter', 'user', 'group', 'select', 'insert', 
            'update', 'delete', 'table', 'database', 'date', 'time', 
            'year', 'month', 'key', 'index', 'text', 'view', 'where'
        ];

        // 1. Eliminar tildes y convertir a ASCII
        $normalizado = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nombre);

        // 2. Reemplazar espacios y caracteres no válidos por guiones bajos
        $normalizado = preg_replace('/[^a-zA-Z0-9_]/', '_', $normalizado);

        // 3. Convertir a minúsculas
        $normalizado = strtolower($normalizado);

        // 4. Si empieza con un número, anteponer 'col_'
        if (preg_match('/^\d/', $normalizado)) {
            $normalizado = 'col_' . $normalizado;
        }

        // 5. Evitar conflicto con palabras reservadas
        if (in_array($normalizado, $reservadas)) {
            $normalizado .= '_col';
        }

        return $normalizado;
    }

    public function validar() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', IS_DEV ? '2048M' : '6144M');
        set_time_limit(0);
        ignore_user_abort(true);
        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["estado" => false, "mensaje" => "Método no permitido."]);
            exit;
        }

        $shapefileDir = $_POST["shapeFileDir"] ?? null;

        if (!$shapefileDir) {
            echo json_encode(["estado" => false, "mensaje" => "Faltan parámetros: 'shapeFileDir' es requerido."]);
            exit;
        }

        if (!is_dir($shapefileDir)) {
            echo json_encode(["estado" => false, "mensaje" => "La carpeta no existe o no es un directorio válido."]);
            exit;
        }

        $shapefileFiles = glob($shapefileDir . "*.shp");

        if (!$shapefileFiles || count($shapefileFiles) === 0) {
            echo json_encode(["estado" => false, "mensaje" => "No se encontró ningún archivo Shapefile en la carpeta indicada."]);
            exit;
        }

        $shapefilePath = $shapefileFiles[0];
        $jsonFilePath = "{$shapefileDir}output.geojson";

        if (file_exists($jsonFilePath)) {
            unlink($jsonFilePath);
        }

        $gdalPath = (PHP_OS_FAMILY === "Windows") ? '"C:\\Program Files\\GDAL\\ogr2ogr.exe"' : 'ogr2ogr';

        if (PHP_OS_FAMILY === "Windows") {
            $nameLayer = $this->obtenerNombreCapa($shapefilePath);
            $consultaSQL = "\"SELECT * FROM $nameLayer LIMIT 10\"";
            $commandJson = "$gdalPath -f \"GeoJSON\" -overwrite " . escapeshellarg($jsonFilePath) . " " . escapeshellarg($shapefilePath) . " -sql " . $consultaSQL;
        } else {
            $commandJson = "$gdalPath -f GeoJSON -overwrite " . escapeshellarg($jsonFilePath) . " " . escapeshellarg($shapefilePath) . " -limit 10";
        }

        $outputJson = [];
        $returnJson = 0;
        exec($commandJson . " 2>&1", $outputJson, $returnJson);

        if ($returnJson !== 0 || !file_exists($jsonFilePath)) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "No se pudo generar el GeoJSON para validar datos.",
                "detalle" => implode("\n", $outputJson),
                "codigo" => $returnJson,
                "cmd" => $commandJson
            ]);
            exit;
        }

        $jsonData = json_decode(file_get_contents($jsonFilePath), true);

        if (!$jsonData || empty($jsonData["features"])) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "El archivo GeoJSON está vacío o tiene un formato inválido."
            ]);
            exit;
        }

        $castColumns = [];
        $allColumns = [];

        foreach ($jsonData["features"] as $feature) {
            foreach ($feature["properties"] as $column => $value) {
                $allColumns[] = $column;

                if ($this->esFecha($value)) {
                    $castColumns[$column] = "DATE";
                    continue;
                }

                if (is_string($value)) {
                    if (is_numeric($value) && !preg_match('/[a-zA-Z\-]/', $value)) {
                        $castColumns[$column] = "NUMERIC(19,11)";
                    } else {
                        $castColumns[$column] = "TEXT";
                    }
                } elseif (is_numeric($value)) {
                    $castColumns[$column] = "NUMERIC(19,11)";
                }
            }
            break;
        }


        $castSql = [];
        $i = 1;

        foreach ($allColumns as $colOriginal) {
            $colNormalizado = $this->normalizarNombreColumna($colOriginal);
            if (isset($castColumns[$colOriginal])) {
                $type = $castColumns[$colOriginal];
                $castSql[] = "CAST(\"$colOriginal\" AS $type) AS \"$colNormalizado\"";
            } else {
                $castSql[] = "\"$colOriginal\" AS \"$colNormalizado\"";
            }
        }

        echo json_encode([
            "estado" => true,
            "mensaje" => "El archivo Shapefile es válido y puede ser exportado a PostGIS.",
            "ruta" => $shapefileDir,
            "columnas" => implode(", ", $castSql),
            "cmd" => $commandJson
        ]);
    }

    public function exportarPostGIS() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', IS_DEV? '2048M' : '6144M');
        set_time_limit(0);
        ignore_user_abort(true);
        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["estado" => false, "mensaje" => "Método no permitido."]);
            exit;
        }

        $shapefileDir = $_POST["shapeFileDir"] ?? null;
        $tableName = $_POST["tableName"] ?? null;
        $schema = $_POST["schema"] ?? null;
        $columnasCast = $_POST["columnas"] ?? null;

        if (!$shapefileDir || !$tableName || !$schema || !$columnasCast) {
            echo json_encode(["estado" => false, "mensaje" => "Faltan parámetros requeridos."]);
            exit;
        }

        if (!is_dir($shapefileDir)) {
            echo json_encode(["estado" => false, "mensaje" => "La carpeta no existe o no es un directorio válido."]);
            exit;
        }

        $shpFiles = glob($shapefileDir . "*.shp");

        if (!$shpFiles || count($shpFiles) === 0) {
            echo json_encode(["estado" => false, "mensaje" => "No se encontró un archivo .shp en la carpeta."]);
            exit;
        }

        $shapefilePath = trim($shpFiles[0]);
        $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $tableName));
        $tableName = strtolower($tableName);

        $gdalPath = (PHP_OS_FAMILY === "Windows") ? '"C:\\Program Files\\GDAL\\ogr2ogr.exe"' : 'ogr2ogr';

        try {
            $srid = $this->obtenerSRID($shapefilePath);
            if (!$srid) {
                echo json_encode(["estado" => false, "mensaje" => "No se pudo obtener el SRID del Shapefile."]);
                exit;
            }

            $cadena = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
            $conexion = "\"$cadena\"";
            $nameLayer = $this->obtenerNombreCapa($shapefilePath);
            $consultaSQL = 'SELECT ' . $columnasCast . ' FROM ' . $nameLayer;

            $commandImport = "$gdalPath -f PostgreSQL PG:$conexion " .
                escapeshellarg($shapefilePath) .
                " -nln " . escapeshellarg("$schema.$tableName") .
                " -lco GEOMETRY_NAME=geom" .
                " -lco FID=gid" .
                " -lco PRECISION=NO" .
                " -overwrite -progress -nlt PROMOTE_TO_MULTI" .
                " --config SHAPE_ENCODING \"LATIN1\"" .
                " -sql \"" . $consultaSQL ."\"";

            $outputImport = [];
            $returnImport = 0;
            exec($commandImport . " 2>&1", $outputImport, $returnImport);

            if ($returnImport !== 0) {
                echo json_encode([
                    "estado" => false,
                    "mensaje" => "Error al importar el Shapefile a PostGIS.",
                    "detalle" => implode("\n", $outputImport),
                    "codigo" => $returnImport,
                    "cmd" => $commandImport,
                    "sql" => $consultaSQL
                ]);
            } else {
                echo json_encode([
                    "estado" => true,
                    "mensaje" => "Shapefile importado correctamente.",
                    "tabla" => "$tableName",
                    "srid" => $srid,
                    "schema" => $schema,
                    "detalle" => implode("\n", $outputImport),
                    "cmd" => $commandImport,
                    "sql" => $consultaSQL
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "Error en la conexión a la base de datos.",
                "detalle" => $e->getMessage()
            ]);
        }
    }
}
?>