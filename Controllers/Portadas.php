<?php
class Portadas extends Controllers{

	public function __construct() {
		parent::__construct();
		session_start();
		session_regenerate_id(true);
		if(empty($_SESSION['login']))
		{
			header('Location: '.base_url().'ingreso');
			die();
		}
		getPermisos(MPORTADAS);
	}

	public function Portadas() {
		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Portadas  | ".NOMBRE_EMPRESA;
		$data['page_title'] = "PORTADAS  | ".NOMBRE_EMPRESA;
		$data['page_name'] = "portadas";
		$data['page_functions_js'] = "functions_portadas.js";

		$pagina = 1;
		$cantDatos = $this->model->cantDatos();
		$total_registro = $cantDatos['total_registro'];
		$desde = ($pagina-1) * REG_XPORPAGINA;
		$total_paginas = ceil($total_registro / REG_XPORPAGINA);

		$Registros = $this->model->getDatosPage($desde,REG_XPORPAGINA);
		$data['registros'] = $Registros;
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$data['total_registros'] = $total_registro;

		$this->views->getView($this,"portadas",$data);
	}

	public function page($pagina = null) {

		$pagina = is_numeric($pagina) ? $pagina : 1;
		$cantDatos = $this->model->cantDatos();
		$total_registro = $cantDatos['total_registro'];
		$desde = ($pagina-1) * REG_XPORPAGINA;
		$total_paginas = ceil($total_registro / REG_XPORPAGINA);
		$Registros = $this->model->getDatosPage($desde,REG_XPORPAGINA);

		$data['total_registros'] = $total_registro;
		$data['registros'] = $Registros;

		$data['page_tag'] = NOMBRE_EMPRESA;
		$data['page_title'] = "PORTADAS";
		$data['page_name'] = "portadas";
		$data['page_functions_js'] = "functions_portadas.js";

		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"portadas",$data);
	}

	public function setPortada() {
		if($_POST){
			if(empty($_POST['titulo']))
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}else{

				$intId_portada = intval($_POST['id_portada']);
				$strTitulo =  strClean($_POST['titulo']);
				$strDescripcion = strClean($_POST['descripcion']);

				$ruta = strtolower(clear_cadena($strTitulo));
				$ruta = str_replace(" ","-",$ruta);

				$foto   	 	= $_FILES['foto'];
				$nombre_foto 	= $foto['name'];
				$type 		 	= $foto['type'];
				$url_temp    	= $foto['tmp_name'];
				$imgPortada 	= 'portad_imagen.png';

				$request_portada = "";
				if($nombre_foto != ''){
					$imgPortada = 'img_'.md5(date('d-m-Y H:i:s')).'.jpg';
				}

				if($intId_portada == 0)
				{
					//Crear
					$request_portada = $this->model->inserPortada($strTitulo,$strDescripcion,$imgPortada,$_SESSION['idUser']);
					$option = 1;
				}else{
					//Actualizar
					if($nombre_foto == ''){
						if($_POST['foto_actual'] != 'portad_imagen.png' && $_POST['foto_remove'] == 0 ){
							$imgPortada = $_POST['foto_actual'];
						}
					}
					$request_portada = $this->model->updatePortada($intId_portada,$strTitulo,$strDescripcion,$imgPortada,$_SESSION['idUser']);
					$option = 2;
				}
				if($request_portada > 0 )
				{
					if($option == 1){
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
						if($nombre_foto != ''){ uploadImage($foto,$imgPortada); }
					}else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						if($nombre_foto != ''){ uploadImage($foto,$imgPortada); }

						if(($nombre_foto == '' && $_POST['foto_remove'] == 1 && $_POST['foto_actual'] != 'portad_imagen.png')
							|| ($nombre_foto != '' && $_POST['foto_actual'] != 'portad_imagen.png')){
							deleteFile($_POST['foto_actual']);
						}
					}
				}
				else if($request_portada == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! La portada ya existe.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getPortadas() {
		$arrData = $this->model->selectPortadas();
		echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		die();
	}

	public function getPortada($id_portada) {
		$intId_portada = intval($id_portada);
		if($intId_portada > 0)
		{
			$arrData = $this->model->selectPortada($intId_portada);
			if(empty($arrData))
			{
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			}else{
				$arrData['url_portada'] = media().'/images/uploads/'.$arrData['portad_imagen'];
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function delPortada() {
		if($_POST){
			$intId_portada = intval($_POST['id_portada_del']);
			$requestDelete = $this->model->deletePortada($intId_portada);
			if($requestDelete == 'ok')
			{
				$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la portada');
			}else if($requestDelete == 'exist'){
				$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar una portada con productos asociados.');
			}else{
				$arrResponse = array('status' => false, 'msg' => 'Error al eliminar la portada.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getSelectPortadas() {
		$htmlOptions = "";
		$arrData = $this->model->selectPortadas();
		if(count($arrData) > 0 ){
			$htmlOptions = '<option value=""></option>';
			for ($i=0; $i < count($arrData); $i++) {
				if($arrData[$i]['status'] == 1 ){
				$htmlOptions .= '<option value="'.$arrData[$i]['id_portada'].'">'.$arrData[$i]['nombre'].'</option>';
				}
			}
		}
		echo $htmlOptions;
		die();
	}

	public function setActualizarPortada(){
		if($_POST){
			if(empty($_POST['id_portada']))
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}else{

				$intId_portada = intval($_POST['id_portada']);

				$foto   	 	= $_FILES['foto'];
				$nombre_foto 	= $foto['name'];
				$type 		 	= $foto['type'];
				$url_temp    	= $foto['tmp_name'];
				$imgPortada 	= 'portada_imagen.png';

				$request_portada = "";
				if($nombre_foto != ''){
					$imgPortada = 'img_'.md5(date('d-m-Y H:i:s')).'.jpg';
				}

				if($intId_portada != 0)
				{
					$request_portada = $this->model->actualizarPortada($intId_portada, $imgPortada, $_SESSION['idUser']);

					if($request_portada > 0 )
					{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						if($nombre_foto != ''){ uploadImage($foto,$imgPortada); }
					}
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}
}
 ?>