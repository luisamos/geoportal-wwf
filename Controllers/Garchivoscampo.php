<?php
require_once("Models/PArchivosCampo.php");

class Garchivoscampo extends Controllers{

	public function __construct()
	{
		parent::__construct();
		session_start();
		session_regenerate_id(true);
		if(empty($_SESSION['login']))
		{
			header('Location: '.base_url().'ingreso');
			die();
		}
		getPermisos(MARCHIVOS);
		$this->model2 = new PArchivosCampo();
	}

	public function garchivoscampo(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Archivos de Campo | Geoportal WWF";
		$data['page_title'] = "ARCHIVOS DE CAMPO";
		$data['page_name'] = "archivos_campo";
		$data['page_functions_js'] = "functions_archivos_campo.js";

		$pagina = 1;
		$cantDatos = $this->model2->cantDatos();
		$total_registro = $cantDatos['total_registro'];
		$desde = ($pagina-1) * REG_XPORPAGINA;
		$total_paginas = ceil($total_registro / REG_XPORPAGINA);

		$Registros = $this->model2->getDatosPage($desde,REG_XPORPAGINA);
		$data['registros'] = $Registros;
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$data['total_registros'] = $total_registro;

		$this->views->getView($this,"garchivoscampo",$data);
	}
	
	public function page($pagina = null){

		$pagina = is_numeric($pagina) ? $pagina : 1;
		$cantDatos = $this->model2->cantDatos();
		$total_registro = $cantDatos['total_registro'];
		$desde = ($pagina-1) * REG_XPORPAGINA;
		$total_paginas = ceil($total_registro / REG_XPORPAGINA);
		$Registros = $this->model2->getDatosPage($desde,REG_XPORPAGINA);

		$data['total_registros'] = $total_registro;
		$data['registros'] = $Registros;

		$data['page_tag'] = NOMBRE_EMPRESA;
		$data['page_title'] = "ARCHIVOS DE CAMPO";
		$data['page_name'] = "archivos_campo";
		$data['page_functions_js'] = "functions_archivos_campo.js";
		
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"garchivoscampo",$data);
	}

	public function setArchivo(){
		if($_POST){
			if(empty($_POST['id_tipo']) || empty($_POST['nombre']))
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}
			else{
				
				$intId_archivo_campo = intval($_POST['id_archivo_campo']);
				$intId_tipo =  strClean($_POST['id_tipo']);
				$strNombre =  strClean($_POST['nombre']);

				$ruta = strtolower(clear_cadena($strNombre));
				$ruta = str_replace(" ","-",$ruta);

				$archivo   	 	= $_FILES['archivo'];
				$nombre_archivo = $archivo['name'];
				$type 		 	= $archivo['type'];
				$url_temp    	= $archivo['tmp_name'];
				$strArchivo 	= 'arx_foto_video.jpg';
				
				if ($archivo['error'] === UPLOAD_ERR_OK) {
					$request_archivo = "";
					if($nombre_archivo != ''){
						$extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
						$strArchivo = 'arx_'.md5(date('d-m-Y H:i:s')).'.'.$extension;
					}

					if($intId_archivo_campo == 0)
					{
						//Crear
						$request_archivo = $this->model->inserArchivo($intId_tipo,$strNombre,$strArchivo,$_SESSION['idUser']);
						$option = 1;
						
					}
					else{
						//Actualizar
						if($nombre_archivo == ''){
							if($_POST['archivo_actual'] != 'arx_foto_video.jpg' && $_POST['archivo_remove'] == 0 ){
								$strArchivo = $_POST['archivo_actual'];
							}
						}
						$request_archivo = $this->model->updateArchivo($intId_archivo_campo,$intId_tipo,$strNombre,$strArchivo,$_SESSION['idUser']);
						$option = 2;
						
					}
					if($request_archivo > 0 )
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'msg' => 'Datos Guardados correctamente.');
							if($nombre_archivo != ''){ uploadFile($archivo,$strArchivo); }
						}else{
							$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
							if($nombre_archivo != ''){ uploadFile($archivo,$strArchivo); }

							if(($nombre_archivo == '' && $_POST['archivo_remove'] == 1 && $_POST['archivo_actual'] != 'arx_archivo.pdf')
								|| ($nombre_archivo != '' && $_POST['archivo_actual'] != 'arx_archivo.pdf')){
								deleteFile2($_POST['archivo_actual']);
							}
						}
					}
					else if($request_archivo == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! el archivo con el nombre ya existe.');
					}
					else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				else{
					$arrResponse = array("status" => false, "msg" => 'No es posible cargar el archivo.');
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getArchivo($id_archivo){
		$intId_archivo_campo = intval($id_archivo);
		if($intId_archivo_campo > 0)
		{
			$arrData = $this->model->selectArchivo($intId_archivo_campo);
			if(empty($arrData))
			{
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			}else{
				$arrData['url_archivo'] = media().'/files/uploads/'.$arrData['arccam_archivo'];
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function delArchivo(){
		if($_POST){
			$intId_archivo_campo = intval($_POST['id_archivo_campo_del']);
			$requestDelete = $this->model->deleteArchivo($intId_archivo_campo);
			if($requestDelete == 'ok')
			{
				$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el archivo');
			}else if($requestDelete == 'exist'){
				$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un archivo asociados.');
			}else{
				$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el archivo.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getTipos(){
		$htmlOptions = "";
		$arrData = $this->model2->getTipos();
		if(count($arrData) > 0 ){
			$htmlOptions = '<option value="">Seleccione...</option>';
			for ($i=0; $i < count($arrData); $i++) { 
				$htmlOptions .= '<option value="'.$arrData[$i]['id_tipo_archivo'].'">'.$arrData[$i]['tiparc_nombre'].'</option>';
				
			}
		}
		echo $htmlOptions;
		die();	
	}
}
?>