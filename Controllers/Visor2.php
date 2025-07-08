<?php
class Visor2 extends Controllers{

    public function __construct(){
        parent::__construct();
    }

	public function listar2() {
		if(IS_DEV)
		{
			header("Access-Control-Allow-Origin: http://127.0.0.5:5173");
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
			header("Access-Control-Allow-Headers: Content-Type, Authorization");
		}
		header("Content-Type: application/json");
		$datos = $this->model->listar2();

		$resultado = [];
		foreach ($datos as $fila) {
			// Procesar padre
			$idPadre = $fila['id_padre'];
			if (!isset($resultado[$idPadre])) {
				$resultado[$idPadre] = [
					'id_padre' => $idPadre,
					'nombre' => $fila['nombre_padre'],
					'visible' => $fila['visible_padre'],
					'orden' => $fila['orden_padre'],
					'hijos' => []
				];
			}

			// Procesar hijo si existe
			if ($fila['id_hijo']) {
				$idHijo = $fila['id_hijo'];
				$hijos = &$resultado[$idPadre]['hijos'];

				if (!isset($hijos[$idHijo])) {
					$hijos[$idHijo] = [
						'id_hijo' => $idHijo,
						'nombre' => $fila['nombre_hijo'],
						'visible' => $fila['visible_hijo'],
						'orden' => $fila['orden_hijo'],
						'capas' => []
					];
				}

				// Procesar capa si existe
				if ($fila['id']) {
					$hijos[$idHijo]['capas'][] = [
						'id' => $fila['id'],
						'servicio' => $fila['servicio'],
						'tipo' => $fila['tipo'],
						'nombre' => $fila['nombre'],
						'alias' => $fila['alias'],
						'visible' => $fila['visible'],
						'orden' => $fila['orden'],
						'url' => $fila['url']
					];
				}
			}
		}

		// Convertir a arrays indexados
		$resultado = array_values($resultado);
		foreach ($resultado as &$padre) {
			$padre['hijos'] = array_values($padre['hijos']);
			foreach ($padre['hijos'] as &$hijo) {
				$hijo['capas'] = array_values($hijo['capas']);
			}
		}

		echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	public function leyenda(){
		header("Content-Type: application/json");

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
			exit;
		}

		$url = $_POST['url'] ?? '';

		if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
			http_response_code(400);
			echo json_encode(['estado' => false, 'mensaje' => 'URL inválida']);
			exit;
		}

		// Llamar al servicio con cURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);

		if (defined('IS_DEV') && IS_DEV) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}

		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			http_response_code(502);
			echo json_encode(["estado" => false, "mensaje" => "Error en cURL: " . curl_error($ch)]);
			curl_close($ch);
			exit;
		}

		curl_close($ch);

		$data = json_decode($response, true);

		if (!isset($data['layers'][0]['legend'])) {
			echo json_encode(['estado' => false, 'mensaje' => 'No se encontró la leyenda']);
			exit;
		}

		echo json_encode(["estado" => true, "leyenda" => $data['layers'][0]['legend']]);
	}

	public function conectarServicioWMS(){

	}

	public function conectarServicioRest(){
		header('Content-Type: application/json');

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
			exit;
		}

		$url = $_POST['url'] ?? '';

		if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
			http_response_code(400);
			echo json_encode(['estado' => false, 'mensaje' => 'URL inválida']);
			exit;
		}

		/*$allowed_domains = ['portalgeo.sbn.gob.pe', 'idesep.senamhi.gob.pe'];
		$parsed = parse_url($url);
		if (!isset($parsed['host']) || !in_array($parsed['host'], $allowed_domains)) {
			http_response_code(403);
			echo json_encode(['estado' => false, 'mensaje' => 'Dominio no permitido']);
			exit;
		}*/

		if (!str_contains($url, '?f=json') && !str_contains($url, '?f=pjson')) {
			$url .= (str_contains($url, '?') ? '&' : '?') . 'f=json';
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);

		if (defined('IS_DEV') && IS_DEV) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}

		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch)) {
			http_response_code(500);
			echo json_encode([
				'estado' => false,
				'mensaje' => 'Error en cURL: ' . curl_error($ch)
			]);
			curl_close($ch);
			exit;
		}
		curl_close($ch);

		if ($httpCode !== 200) {
			http_response_code($httpCode);
			echo json_encode([
				'estado' => false,
				'mensaje' => "El servidor respondió con código HTTP $httpCode"
			]);
			exit;
		}

		$data = json_decode($response, true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			echo json_encode([
				'estado' => false,
				'mensaje' => 'La respuesta no es un JSON válido'
			]);
			exit;
		}

		if (isset($data['layers'])) {
			echo json_encode([
				'estado' => true,
				'datos' => $data['layers']
			]);
		} else {
			echo json_encode([
				'estado' => true,
				'mensaje' => 'Respuesta válida, pero no se encontraron capas',
				'respuesta' => $data
			]);
		}
	}
}
?>