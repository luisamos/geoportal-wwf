<?php
require_once("Models/PArchivos.php");

class Archivos extends Controllers{

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->model = new PArchivos();
	}

	// PUBLIC
	public function tipo($params){

		if(empty($params)){
			header("Location:".base_url());
		}
		else{

			if (isset($_SESSION['idUser'])) {
			    $id_login = $_SESSION['idUser'];
			}
			else{
				$id_login = 0;
			}

			$arrParams = explode(",",$params);
			$id_tipo = intval($arrParams[0]);
			$pagina = intval($arrParams[1]);

			$pagina = is_numeric($pagina) ? $pagina : 1;

			$cantDatos = $this->model->cantDatos($id_tipo);
			$total_registro = $cantDatos['total_registro'];
			$desde = ($pagina-1) * REG_XPORPAGINA;
			$total_paginas = ceil($total_registro / REG_XPORPAGINA);

			$Registros = $this->model->getRegistrosxTipo($id_tipo,$id_login,$desde,REG_XPORPAGINA);

			if ($Registros['status']) {
				$data['total_registros'] = $total_registro;
				$data['page_tag'] = "Archivos - ".$Registros['tipo_archivo']." | ".NOMBRE_EMPRESA;
				$data['page_title'] = "ARCHIVOS - ".$Registros['tipo_archivo'];
				$data['page_name'] = "archivos";
				$data['registros'] = $Registros['registros'];
				$data['pagina'] = $pagina;
				$data['total_paginas'] = $total_paginas;
				$data['tipos'] = $this->model->getTipos();
				$data['id_tipo'] = $id_tipo;

				$this->views->getView($this,"archivos",$data);
			}
			else{
				header("Location:".base_url()."/archivos/tipo/1/1");
			}
		}
	}

	public function search(){
		$id_tipo = intval($_REQUEST['id_tipo']);

		if(empty($_REQUEST['s'])){
			header("Location:".base_url()."/archivos/tipo/".$id_tipo."/1");
		}
		else{
			$busqueda = strClean($_REQUEST['s']);
		}

		$pagina = empty($_REQUEST['p']) ? 1 : intval($_REQUEST['p']);

		$cantDatos = $this->model->cantProdSearch($id_tipo,$busqueda);
		$total_registro = $cantDatos['total_registro'];
		$desde = ($pagina-1) * REG_XBUSCAR;
		$total_paginas = ceil($total_registro / REG_XBUSCAR);

		$Registros = $this->model->getProdSearch($id_tipo,$busqueda,$desde,REG_XBUSCAR);

		$data['total_registros'] = $total_registro;
		$data['page_tag'] = "Archivos - ".$Registros['tipo']." | ".NOMBRE_EMPRESA;
		$data['page_title'] = "ARCHIVOS - ".$Registros['tipo'];
		$data['page_name'] = "archivos";
		$data['registros'] = $Registros['registros'];
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$data['busqueda'] = $busqueda;
		$data['tipos'] = $this->model->getTipos();
		$data['id_tipo'] = $id_tipo;

		$this->views->getView($this,"search",$data);
	}

	public function subirZip(){
		header("Content-Type: application/json");
		$mensaje="";
		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {

			$fecha = date("YmdHis");
			$id = sprintf("%04d", rand(0, 9999));

			$uploadDir = DIR_TMP_ZIP;
			if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

			$filename = basename($_POST['filename']);
			$chunkIndex = (int)$_POST['chunk'];
			$totalChunks = (int)$_POST['totalChunks'];

			$tmpPath = $uploadDir . $filename . '.part';

			$chunkData = file_get_contents($_FILES['file']['tmp_name']);
			file_put_contents($tmpPath, $chunkData, FILE_APPEND);

			if ($chunkIndex + 1 === $totalChunks) {
				$finalPath = $uploadDir . $filename;
				rename($tmpPath, $finalPath);

				$fecha = date("YmdHis");
				$id = sprintf("%04d", rand(0, 9999));
				$newFileName = "{$fecha}{$id}.zip";
				$finalZipPath = $uploadDir . $newFileName;
				rename($finalPath, $finalZipPath);

				$zip = new ZipArchive;
				if ($zip->open($finalZipPath) === TRUE) {
					$extractPath = DIR_TMP_SHP . "{$fecha}{$id}/";
					if (!is_dir($extractPath)) mkdir($extractPath, 0777, true);
					if ($zip->extractTo($extractPath)) {
						$zip->close();
						$mensaje = json_encode([
							"estado" => true,
							"mensaje" => "Archivo subido y descomprimido correctamente.",
							"nombre_carpeta" => $extractPath
						]);
					} else {
						$mensaje = json_encode(["estado" => false, "mensaje" => "No se pudo descomprimir el archivo."]);
					}
				} else {
					$mensaje = json_encode(["estado" => false, "mensaje" => "No se pudo abrir el archivo ZIP."]);
				}
			} else {
				$mensaje = json_encode(["estado" => true, "mensaje" => "Chunk {$chunkIndex} recibido"]);
			}
		}
		else {
			$mensaje= json_encode(["estado" => false, "mensaje" => "No se ha enviado ningún archivo."]);
		}
		http_response_code(200);
		echo $mensaje;
	}
}
?>