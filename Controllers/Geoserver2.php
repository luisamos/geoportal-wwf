<?php
class Geoserver2 extends Controllers{
    private $url;
    private $user;
    private $password;

    public function __construct() {
        $this->url = GEOSERVER_URL;
        $this->user = GEOSERVER_USER;
        $this->password = GEOSERVER_PASS;
        parent::__construct();
    }

    private function request($endpoint, $method = 'GET', $data = null) {
        $url = $this->url . $endpoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->user:$this->password");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        //return ($httpCode >= 200 && $httpCode < 300) ? json_decode($response, true) : null;
        return $httpCode;

    }

    private function getLayers() {
        return $this->request("/layers.json");
    }

    private function getWorkspaces() {
        return $this->request("/workspaces.json");
    }

    public function workspaceExists($name) {
        $response = $this->getWorkspaces();
        if ($response && isset($response['workspaces']['workspace'])) {
            $workspaces = $response['workspaces']['workspace'];

            if (!is_array($workspaces) || (is_array($workspaces) && !isset($workspaces[0]))) {
                $workspaces = [$workspaces];
            }
            foreach ($workspaces as $ws) {
                if (isset($ws['name']) && $ws['name'] === $name) {
                    return true;
                }
            }
        }
        return false;
    }

    private function createWorkspace($name) {
        $data = ["workspace" => ["name" => $name]];
        return $this->request("/workspaces", "POST", $data);
    }

    private function deleteWorkspace($name) {
        return $this->request("/workspaces/$name", "DELETE");
    }

    private function createDatastore($workspace, $datastoreName, $connectionParams) {
        $data = [
            "dataStore" => [
                "name" => $datastoreName,
                "connectionParameters" => $connectionParams
            ]
        ];
        return $this->request("/workspaces/$workspace/datastores", "POST", $data);
    }

    private function updateDatastore($workspace, $datastoreName, $connectionParams) {
        $data = [
            "dataStore" => [
                "connectionParameters" => $connectionParams
            ]
        ];
        return $this->request("/workspaces/$workspace/datastores/$datastoreName", "PUT", $data);
    }

    private function deleteDatastore($workspace, $datastoreName) {
        return $this->request("/workspaces/$workspace/datastores/$datastoreName", "DELETE");
    }

    private function uploadRaster($filePath, $workspace, $storeName) {
        $url = "$this->url/workspaces/$workspace/coveragestores/$storeName/file.geotiff";
        $ch = curl_init($url);
        $file = new CURLFile($filePath, 'image/tiff');

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->user:$this->password");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => $file]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($httpCode >= 200 && $httpCode < 300) ? json_decode($response, true) : null;
    }

    private function publishPostGISLayer($workspace, $datastore, $layerName, $tableName, $srs) {
        $data = [
            "featureType" => [
                "name" => $layerName,
                "nativeName" => $tableName,
                "srs" => $srs
            ]
        ];

        return $this->request("/workspaces/$workspace/datastores/$datastore/featuretypes", "POST", $data);
    }

    public function crearEspacioTrabajo()
    {
        header("Content-Type: application/json");

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo json_encode(["estado" => false, "mensaje" => "Método no permitido."]);
            exit;
        }

        $schema = $_POST["schema"] ?? null;

        if(is_null($schema))
        {
            echo json_encode(['estado' => false, 'mensaje' => 'Falta datos que completar.']);
        }
        else
        {
            $existe = $this->workspaceExists($schema);
            echo json_encode([$existe]);
            /*if(!$this->workspaceExists($schema))
            {
                $respuesta2 = $this->createWorkspace($schema);
                if ($respuesta2 == 201) {
                    echo json_encode(["estado" => true, "mensaje" => "El espacio de trabajo '$schema' creado exitosamente."]);
                } else {
                    echo json_encode(["estado" => false, "mensaje" => "Error al crear el workspace. Código HTTP: $respuesta2."]);
                }
            }
            else echo json_encode(["estado" => false, "mensaje" => "Ya existe el espacio de trabajo '$schema'."]);*/
        }
    }
}
?>