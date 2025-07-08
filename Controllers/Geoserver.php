<?php
class Geoserver extends Controllers {

	public function __construct() {
        parent::__construct();
    }

    public function crearDataStore() {
        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["estado" => false, "mensaje" => "Método no permitido."]);
            exit;
        }

        $schema = $_POST["schema"] ?? null;
        $workspace = $schema;

        if (!$schema) {
            echo json_encode(["estado" => false, "mensaje" => "Faltan parámetros: esquema requerido."]);
            exit;
        }

        $datastore_name = "ds_$schema";

        //$workspace = "$schema;
        $geoserver_base_url = GEOSERVER_URL . "/workspaces/$workspace/datastores";
        $auth = GEOSERVER_USER . ":" . GEOSERVER_PASS;

        // Primero, verificar si el datastore ya existe
        $check_url = "$geoserver_base_url/$datastore_name.json";

        $ch = curl_init($check_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            // El datastore ya existe
            echo json_encode(["estado" => true, "mensaje" => "El datastore '$datastore_name' ya existe en GeoServer.", "datastore" => $datastore_name]);
            exit;
        } elseif ($http_code != 404) {
            // Otro error (diferente de 404)
            echo json_encode(["estado" => false, "mensaje" => "Error al verificar el datastore. Código HTTP: $http_code"]);
            exit;
        }

        // Si el datastore no existe (HTTP 404), proceder a crearlo
        $data = json_encode([
            "dataStore" => [
                "name" => $datastore_name,
                "connectionParameters" => [
                    "host" => DB_HOST,
                    "port" => DB_PORT,
                    "database" => DB_NAME,
                    "user" => DB_USER,
                    "passwd" => DB_PASSWORD,
                    "dbtype" => "postgis",
                    "schema" => $schema
                ]
            ]
        ]);

        $ch = curl_init($geoserver_base_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 201) {
            echo json_encode(["estado" => true, "mensaje" => "Datastore '$datastore_name' creado correctamente.", "datastore" => $datastore_name]);
        } else {
            echo json_encode(["estado" => false, "mensaje" => "Error al crear el datastore. Código HTTP: $http_code, Respuesta: $response"]);
        }
        exit;
    }

    public function publicarVector() {
        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["estado" => false, "mensaje" => "Método no permitido."]);
            exit;
        }

        // Obtener parámetros del POST
        $schema    = $_POST["schema"] ?? null;
        $tabla     = $_POST["tabla"] ?? null;
        $workspace = $_POST["workspace"] ?? null;
        $datastore = $_POST["datastore"] ?? null;
        $srid      = $_POST["srid"] ?? null;

        // Validar que no falten parámetros
        if (!$schema || !$tabla || !$workspace || !$datastore || !$srid) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "Faltan parámetros requeridos: schema, tabla, workspace, datastore y srid son obligatorios."
            ]);
            exit;
        }

        $layer_name = "{$schema}:{$tabla}";
        $geoserver_url = GEOSERVER_URL. "/workspaces/$workspace/datastores/$datastore/featuretypes";
        $auth = GEOSERVER_USER . ":" . GEOSERVER_PASS;

        // Verificar si la capa ya existe
        $check_url = "$geoserver_url/$tabla.json";

        $ch = curl_init($check_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            echo json_encode(["estado" => true, "mensaje" => "La capa '$layer_name' ya existe en GeoServer."]);
            exit;
        } elseif ($http_code != 404) {
            echo json_encode(["estado" => false, "mensaje" => "Error al verificar la capa. Código HTTP: $http_code"]);
            exit;
        }

        // Si la capa no existe, proceder a publicarla
        $data = json_encode([
            "featureType" => [
                "name"       => $tabla,
                "nativeName" => $tabla,
                "namespace"  => ["name" => $workspace],
                "title"      => $tabla,
                "srs"        => "EPSG:$srid",
                "nativeCRS"  => "EPSG:$srid",
                "enabled"    => true
            ]
        ]);

        $ch = curl_init($geoserver_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 201){
            echo json_encode(["estado" => true, "mensaje" => "Capa '$layer_name' publicada correctamente."]);
        } else {
            echo json_encode(["estado" => false, "mensaje" => "Error al publicar la capa. Código HTTP: $http_code, Respuesta: $response"]);
        }
        exit;
    }

    public function publicarRaster() {
        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["estado" => false, "mensaje" => "Método no permitido."]);
            exit;
        }

        // Verificar si se recibió el archivo correctamente
        if (!isset($_FILES['raster']) || $_FILES['raster']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "Error al recibir el archivo.",
                "debug" => $_FILES
            ]);
            exit;
        }

        $schema = $_POST["schema"] ?? null;
        $workspace = $schema;

        if (!$schema) {
            echo json_encode(["estado" => false, "mensaje" => "Faltan parámetros: esquema requerido."]);
            exit;
        }

        //$storeName = "r_" . time(); // nombre único
        $storeName = date('dmYHis');
        $auth = GEOSERVER_USER . ":" . GEOSERVER_PASS;

        $nombreOriginal = basename($_FILES['raster']['name']);
        $tamano = $_FILES['raster']['size'];
        $tmp = $_FILES['raster']['tmp_name'];
        $urlStore = GEOSERVER_URL . "/workspaces/$workspace/coveragestores/$storeName/file.geotiff";

        // Crear el coverageStore
        $xml = <<<XML
        <coverageStore>
            <name>$storeName</name>
            <type>GeoTIFF</type>
            <enabled>true</enabled>
            <workspace>$workspace</workspace>
        </coverageStore>
        XML;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, GEOSERVER_URL . "/workspaces/$workspace/coveragestores");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: text/xml"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $responseCreate = curl_exec($ch);
        $codeCreate = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($codeCreate !== 201 && $codeCreate !== 200) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "Error creando coverageStore",
                "codigo" => $codeCreate,
                "respuesta" => $responseCreate
            ]);
            exit;
        }

        // Subir el archivo TIFF
        $fp = fopen($tmp, "r");

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $urlStore,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $auth,
            CURLOPT_PUT => true,
            CURLOPT_INFILE => $fp,
            CURLOPT_INFILESIZE => $tamano,
            CURLOPT_HTTPHEADER => ["Content-Type: image/tiff"],
        ]);

        $responseUpload = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        fclose($fp);

        if ($httpCode === 201 || $httpCode === 202) {
            echo json_encode([
                "estado" => true,
                "mensaje" => "✅ Capa publicada con éxito",
                "capa" => $storeName,
                "url_wms" => GEOSERVER_URL . "/$workspace/wms"
            ]);
        } else {
            echo json_encode([
                "estado" => false,
                "mensaje" => "❌ Error al subir el raster",
                "codigo" => $httpCode,
                "respuesta" => $responseUpload,
                "error_curl" => $curlError
            ]);
        }
    }

    public function crearEspacioTrabajo() {
        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["estado" => false, "mensaje" => "Método no permitido."]);
            exit;
        }

        $schema = $_POST["schema"] ?? null;

        if(is_null($schema))
        {
            $arrResponse = array('estado' => false, 'mensaje' => 'Falta datos que completar.');
        }
        else
        {
            $geoserver_url = GEOSERVER_URL ."/workspaces";
            $auth = GEOSERVER_USER . ":" . GEOSERVER_PASS;
            $workspace_name = $schema;
            $data = json_encode([
                "workspace" => ["name" => $workspace_name]
            ]);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $geoserver_url);
            curl_setopt($ch, CURLOPT_USERPWD, $auth);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json"
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 201) {
                echo json_encode(["estado" => true, "mensaje" => "El espacio de trabajo '$workspace_name' creado exitosamente."]);
            } else {
                echo json_encode(["estado" => false, "mensaje" => "Error al crear el workspace. Código HTTP: $http_code\n"]);
            }
        }
        exit;
    }

    public function ActualizarEstilo2() {
        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["estado" => false, "mensaje" => "Método no permitido."]);
            exit;
        }

        $nombre = $_POST["nombre"] ?? null;
        $workspace = "geo";

        if (is_null($nombre) || !isset($_FILES['file'])) {
            echo json_encode(["estado" => false, "mensaje" => "Faltan datos requeridos."]);
            exit;
        }

        $archivo = $_FILES['file']['tmp_name'];
        if (!file_exists($archivo)) {
            echo json_encode(["estado" => false, "mensaje" => "El archivo no se ha subido correctamente."]);
            exit;
        }

        $contenidoSLD = file_get_contents($archivo);

        if (!preg_match('/<StyledLayerDescriptor[^>]*version=["\']1\.1\.0["\']/', $contenidoSLD)) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "Este archivo SLD no es compatible. Use la versión 1.1.0 o superior."
            ]);
            exit;
        }

        $usaSE = strpos($contenidoSLD, '<se:') !== false;
        $contentType = $usaSE ? "application/vnd.ogc.se+xml" : "application/vnd.ogc.sld+xml";

        // Normalizar nombres de propiedades
        $contenidoSLD = preg_replace_callback(
            '/<ogc:PropertyName>(.*?)<\/ogc:PropertyName>/i',
            fn($m) => '<ogc:PropertyName>' . strtolower($m[1]) . '</ogc:PropertyName>',
            $contenidoSLD
        );

        // Normalizar los datos
        $contenidoSLD = preg_replace_callback(
            '/<ogc:Literal>(.*?)<\/ogc:Literal>/i',
            fn($m) => '<ogc:Literal>' . mb_convert_encoding($m[1], 'ISO-8859-1', 'UTF-8') . '</ogc:Literal>',
            $contenidoSLD
        );

        // Reemplazar <Name> en NamedLayer y UserStyle
        $contenidoSLD = preg_replace_callback(
            '/<NamedLayer\b[^>]*>.*?<((se:)?Name)>.*?<\/\1>/is',
            fn($m) => preg_replace('/<((se:)?Name)>.*?<\/\1>/i', "<$1>{$nombre}</$1>", $m[0]),
            $contenidoSLD
        );

        $contenidoSLD = preg_replace_callback(
            '/<UserStyle\b[^>]*>.*?<((se:)?Name)>.*?<\/\1>/is',
            fn($m) => preg_replace('/<((se:)?Name)>.*?<\/\1>/i', "<$1>{$nombre}</$1>", $m[0]),
            $contenidoSLD
        );

        $simbolizadores = ['PolygonSymbolizer', 'LineSymbolizer', 'PointSymbolizer', 'TextSymbolizer'];
        $encontrados = [];
        foreach ($simbolizadores as $simbolizador) {
            if (stripos($contenidoSLD, $simbolizador) !== false) {
                $encontrados[] = $simbolizador;
            }
        }

        $auth = GEOSERVER_USER . ":" . GEOSERVER_PASS;
        $urlBase = GEOSERVER_URL . "/workspaces/{$workspace}/styles/{$nombre}";

        // Verificar si ya existe
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlBase . ".xml");
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            // Eliminar estilo existente
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlBase . "?purge=true&recurse=true");
            curl_setopt($ch, CURLOPT_USERPWD, $auth);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/xml"]);
            $deleteResp = curl_exec($ch);
            $deleteCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (!in_array($deleteCode, [200, 202, 204])) {
                echo json_encode([
                    "estado" => false,
                    "mensaje" => "No se pudo eliminar el estilo existente.",
                    "codigo_http" => $deleteCode,
                    "respuesta" => $deleteResp
                ]);
                exit;
            }
        }

        // Crear nuevo estilo
        $urlCreate = GEOSERVER_URL . "/workspaces/{$workspace}/styles";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlCreate);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: {$contentType}; charset=UTF-8",
            "Content-Disposition: attachment; filename=\"{$nombre}.sld\""
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $contenidoSLD);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!in_array($httpCode, [200, 201])) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "Error al crear el nuevo estilo.",
                "codigo_http" => $httpCode,
                "respuesta" => $response
            ]);
            exit;
        }

        // Asignar el estilo por defecto a capa si existe
        $urlLayer = GEOSERVER_URL . "/layers/{$workspace}:{$nombre}";
        $layerPayload = json_encode([
            "layer" => [
                "defaultStyle" => [
                    "name" => $nombre,
                    "workspace" => $workspace
                ],
                    "styles" => [
                        ["name" => $nombre, "workspace" => $workspace]
                    ]
            ]
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlLayer);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $layerPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $layerResp = curl_exec($ch);
        $layerCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $mensaje = "Estilo creado correctamente.";
        if (!in_array($layerCode, [200, 201])) {
            $mensaje .= " ⚠️ No se pudo asignar el estilo como predeterminado a la capa.";
        }

        echo json_encode([
            "estado" => true,
            "mensaje" => $mensaje,
            "advertencia" => count($encontrados) === 0
                ? "⚠️ El estilo fue cargado, pero **no contiene simbolizadores válidos** (Polygon, Line, Point, Text). GeoServer podría interpretarlo como vacío."
                : ""
        ]);
        exit;
    }

    public function ActualizarEstilo() {
        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["estado" => false, "mensaje" => "Método no permitido."]);
            exit;
        }

        $nombre = $_POST["nombre"] ?? null;
        $workspace = "geo";

        if (is_null($nombre) || !isset($_FILES['file'])) {
            echo json_encode(["estado" => false, "mensaje" => "Faltan datos requeridos."]);
            exit;
        }

        $archivo = $_FILES['file']['tmp_name'];
        if (!file_exists($archivo)) {
            echo json_encode(["estado" => false, "mensaje" => "El archivo no se ha subido correctamente."]);
            exit;
        }

        $contenidoSLD = file_get_contents($archivo);
        $auth = GEOSERVER_USER . ":" . GEOSERVER_PASS;

        // Obtener info de la capa
        $urlLayerInfo = GEOSERVER_URL . "/layers/{$workspace}:{$nombre}.json";
        $ch = curl_init($urlLayerInfo);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
        $resLayerInfo = curl_exec($ch);
        $infoCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($infoCode !== 200) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "No se pudo obtener información de la capa.",
                "codigo_http" => $infoCode,
                "respuesta" => $resLayerInfo
            ]);
            exit;
        }

        $layerData = json_decode($resLayerInfo, true);
        $isRaster = isset($layerData["layer"]["type"]) && $layerData["layer"]["type"] === "RASTER";

        // Validar versión y simbolizador
        $esSLD10 = preg_match('/<StyledLayerDescriptor[^>]*version=["\']1\.0\.0["\']/', $contenidoSLD);
        $esSLD11oSuperior = preg_match('/<StyledLayerDescriptor[^>]*version=["\']1\.[1-9][0-9]*["\']/', $contenidoSLD);
        $contieneRaster = stripos($contenidoSLD, 'RasterSymbolizer') !== false;

        if (!$esSLD11oSuperior) {
            if (!$esSLD10 || !$contieneRaster || !$isRaster) {
                echo json_encode([
                    "estado" => false,
                    "mensaje" => "SLD no compatible. Use versión 1.1.0+ o asegure RasterSymbolizer para raster en 1.0.0"
                ]);
                exit;
            }
        }

        // MIME Type
        $usaSE = strpos($contenidoSLD, '<se:') !== false;
        $contentType = $usaSE ? "application/vnd.ogc.se+xml" : "application/vnd.ogc.sld+xml";

        // Reemplazar <Name>, <se:Name>, <sld:Name>
        $contenidoSLD = preg_replace_callback(
            '/<((?:se:|sld:)?Name)>.*?<\/\1>/i',
            fn($m) => "<{$m[1]}>{$nombre}</{$m[1]}>",
            $contenidoSLD
        );

        // Normalizar propiedades y literales
        $contenidoSLD = preg_replace_callback(
            '/<ogc:PropertyName>(.*?)<\/ogc:PropertyName>/i',
            fn($m) => '<ogc:PropertyName>' . strtolower($m[1]) . '</ogc:PropertyName>',
            $contenidoSLD
        );
        $contenidoSLD = preg_replace_callback(
            '/<ogc:Literal>(.*?)<\/ogc:Literal>/i',
            fn($m) => '<ogc:Literal>' . mb_convert_encoding($m[1], 'ISO-8859-1', 'UTF-8') . '</ogc:Literal>',
            $contenidoSLD
        );

        // Detectar simbolizadores
        $simbolizadores = ['PolygonSymbolizer', 'LineSymbolizer', 'PointSymbolizer', 'TextSymbolizer', 'RasterSymbolizer'];
        $encontrados = [];
        foreach ($simbolizadores as $s) {
            if (stripos($contenidoSLD, $s) !== false) {
                $encontrados[] = $s;
            }
        }

        // Eliminar estilo existente si lo hay
        $urlBase = GEOSERVER_URL . "/workspaces/{$workspace}/styles/{$nombre}";
        $ch = curl_init($urlBase . ".xml");
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlBase . "?purge=true&recurse=true");
            curl_setopt($ch, CURLOPT_USERPWD, $auth);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/xml"]);
            curl_exec($ch);
            curl_close($ch);
        }

        // Crear nuevo estilo
        $ch = curl_init(GEOSERVER_URL . "/workspaces/{$workspace}/styles");
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: {$contentType}; charset=UTF-8",
            "Content-Disposition: attachment; filename=\"{$nombre}.sld\""
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $contenidoSLD);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $createResp = curl_exec($ch);
        $createCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!in_array($createCode, [200, 201])) {
            echo json_encode([
                "estado" => false,
                "mensaje" => "Error al crear el nuevo estilo.",
                "codigo_http" => $createCode,
                "respuesta" => $createResp
            ]);
            exit;
        }

        // Asociar estilo por defecto
        $mensaje = "Estilo creado correctamente.";

        if ($isRaster) {
            $resourceName = $layerData["layer"]["resource"]["name"];
            $store = $nombre;
            $urlCoverageInfo = GEOSERVER_URL . "/workspaces/{$workspace}/coveragestores/{$store}/coverages/{$resourceName}.json";

            // Primer intento
            $ch = curl_init($urlCoverageInfo);
            curl_setopt($ch, CURLOPT_USERPWD, $auth);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
            $resCoverage = curl_exec($ch);
            $coverageCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Si falla, detectar store desde href
            if ($coverageCode !== 200) {
                $resourceHref = $layerData["layer"]["resource"]["href"] ?? null;
                if ($resourceHref) {
                    $ch = curl_init($resourceHref);
                    curl_setopt($ch, CURLOPT_USERPWD, $auth);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
                    $res = curl_exec($ch);
                    curl_close($ch);
                    $resourceData = json_decode($res, true);
                    $store = $resourceData["coverage"]["store"]["name"] ?? $nombre;
                    $urlCoverageInfo = GEOSERVER_URL . "/workspaces/{$workspace}/coveragestores/{$store}/coverages/{$resourceName}.json";

                    // Intento final
                    $ch = curl_init($urlCoverageInfo);
                    curl_setopt($ch, CURLOPT_USERPWD, $auth);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
                    $resCoverage = curl_exec($ch);
                    $coverageCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                }
            }

            if ($coverageCode === 200) {
                $urlCoverage = GEOSERVER_URL . "/workspaces/{$workspace}/coveragestores/{$store}/coverages/{$resourceName}";
                $payload = json_encode([
                    "coverage" => [
                        "defaultStyle" => [
                            "name" => $nombre,
                            "workspace" => $workspace
                        ]
                    ]
                ]);

                $ch = curl_init($urlCoverage);
                curl_setopt($ch, CURLOPT_USERPWD, $auth);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch);
                $putCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if (!in_array($putCode, [200, 201])) {
                    $mensaje .= " ⚠️ No se pudo asignar estilo por defecto a la capa raster.";
                }
            } else {
                $mensaje .= " ⚠️ No se encontró coverage en el coverageStore.";
            }
        } else {
            $urlLayer = GEOSERVER_URL . "/layers/{$workspace}:{$nombre}";
            $layerPayload = json_encode([
                "layer" => [
                    "defaultStyle" => [
                        "name" => $nombre,
                        "workspace" => $workspace
                    ],
                    "styles" => [
                        ["name" => $nombre, "workspace" => $workspace]
                    ]
                ]
            ]);

            $ch = curl_init($urlLayer);
            curl_setopt($ch, CURLOPT_USERPWD, $auth);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $layerPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            $layerCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (!in_array($layerCode, [200, 201])) {
                $mensaje .= " ⚠️ No se pudo asignar estilo por defecto a capa vectorial.";
            }
        }

        echo json_encode([
            "estado" => true,
            "mensaje" => $mensaje,
            "advertencia" => count($encontrados) === 0
                ? "⚠️ El estilo fue cargado, pero no contiene simbolizadores válidos (Polygon, Line, Point, Text o Raster)."
                : ""
        ]);
        exit;
    }
}
?>