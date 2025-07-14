<?php
require_once("Models/PPlanoteca.php");

class Gplanoteca extends Controllers{

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
		getPermisos(MPLANOTECA);
		$this->model2 = new PPlanoteca();
	}

	public function gplanoteca(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Compendio Cartogr�fico | Geoportal WWF";
		$data['page_title'] = "COMPENDIO CARTOGR�FICO | Geoportal WWF";
		$data['page_name'] = "Compendio Cartogr�fico";
		$data['page_functions_js'] = "functions_planoteca.js";

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

		$this->views->getView($this,"gplanoteca",$data);
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
		$data['page_title'] = "COMPENDIO CARTOGR�FICO";
		$data['page_name'] = "COMPENDIO CARTOGR�FICO";
		$data['page_functions_js'] = "functions_planoteca.js";
		
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"gplanoteca",$data);
	}

	public function setPlanoteca(){
		if($_POST){
			if(empty($_POST['id_programa']) || empty($_POST['codigo']) || empty($_POST['nombre']))
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}
			else{
				
				$intId_planoteca = intval($_POST['id_planoteca']);
				$intId_tipo =  strClean($_POST['id_programa']);
				$strCodigo =  strClean($_POST['codigo']);
				$strNombre =  strClean($_POST['nombre']);
				$strTag =  strClean($_POST['tag']);

				$archivo   	 	= $_FILES['archivo'];
				$nombre_archivo = $archivo['name'];
				$type 		 	= $archivo['type'];
				$url_temp    	= $archivo['tmp_name'];
				$strArchivo 	= 'pla_archivo.pdf';
				
				if($nombre_archivo != ''){
					$extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
					$strArchivo = 'pla_'.md5(date('d-m-Y H:i:s')).'.'.$extension;
				}

				$foto   	 	= $_FILES['foto'];
				$nombre_foto 	= $foto['name'];
				$type 		 	= $foto['type'];
				$url_temp    	= $foto['tmp_name'];
				$imgPlano 	= 'plano_img.jpg';
				
				if($nombre_foto != ''){
					$extension = pathinfo($nombre_foto, PATHINFO_EXTENSION);
					$imgPlano = 'plano_img_'.md5(date('d-m-Y H:i:s')).'.'.$extension;
				}

				$request_planoteca = "";

				if($intId_planoteca == 0)
				{
					//Crear
					$request_planoteca = $this->model->inserPlanoteca($intId_tipo,$strCodigo,$strNombre,$strArchivo,$imgPlano,$strTag,$_SESSION['idUser']);
					$option = 1;
					
				}else{
					//Actualizar
					if($nombre_archivo == ''){
						if($_POST['archivo_actual'] != 'pla_archivo.pdf' && $_POST['archivo_remove'] == 0 ){
							$strArchivo = $_POST['archivo_actual'];
						}
					}
					if($nombre_foto == ''){
						if($_POST['foto_actual'] != 'plano_img.jpg' && $_POST['foto_remove'] == 0 ){
							$imgPlano = $_POST['foto_actual'];
						}
					}

					$request_planoteca = $this->model->updatePlanoteca($intId_planoteca,$intId_tipo,$strCodigo,$strNombre,$strArchivo,$imgPlano,$strTag,$_SESSION['idUser']);
					$option = 2;
					
				}
				if($request_planoteca > 0 )
				{
					if($option == 1){

						if($nombre_archivo != ''){
							$arx = uploadFile($archivo,$strArchivo);
						}
						if($nombre_foto != ''){
							$img = uploadImage($foto,$imgPlano); 
						}

						if ($arx==true && $img==true) {
							$arrResponse = array('status' => true, 'msg' => 'Datos Guardados correctamente. Archivo::'.$arx.' - Imagen::'.$img);
						}
						else{
							if ($arx==false) {
								$arrResponse = array('status' => true, 'msg' => 'Datos Guardados sin Archivo. Error::'.$arx);
							}
							if ($img==false) {
								$arrResponse = array('status' => true, 'msg' => 'Datos Guardados sin Imagen. Error::'.$img);
							}
						}
						
					}
					else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						if($nombre_archivo != ''){ 
							uploadFile($archivo,$strArchivo); 
						}
						if($nombre_foto != ''){ 
							uploadImage($foto,$imgPlano); 
						}

						if(($nombre_archivo == '' && $_POST['archivo_remove'] == 1 && $_POST['archivo_actual'] != 'pla_archivo.pdf')
							|| ($nombre_archivo != '' && $_POST['archivo_actual'] != 'pla_archivo.pdf')){
							deleteFile2($_POST['archivo_actual']);
						}

						if(($nombre_foto == '' && $_POST['foto_remove'] == 1 && $_POST['foto_actual'] != 'plano_img.jpg')
							|| ($nombre_foto != '' && $_POST['foto_actual'] != 'plano_img.jpg')){
							if (!empty($_POST['foto_actual'])) {
								deleteFile($_POST['foto_actual']);
							}
							
						}
					}
				}
				else if($request_planoteca == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '�Atenci�n! el archivo con ese codigo ya existe.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getPlanotecas(){
		$arrData = $this->model->selectPlanotecas();
		for ($i=0; $i < count($arrData); $i++) {
			$btnView = '';
			$btnEdit = '';
			$btnDelete = '';

			if($arrData[$i]['planot_estado'] == 1)
			{
				$arrData[$i]['planot_estado'] = '<span class="badge badge-success">Activo</span>';
			}else{
				$arrData[$i]['planot_estado'] = '<span class="badge badge-danger">Inactivo</span>';
			}

			$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['id_planoteca'].')" title="Ver archivo"><i class="far fa-eye"></i></button>';
		
			$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,'.$arrData[$i]['id_planoteca'].')" title="Editar archivo"><i class="fas fa-pencil-alt"></i></button>';
			
			$btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['id_planoteca'].')" title="Eliminar archivo"><i class="far fa-trash-alt"></i></button>';
			
			$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
		}
		echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		
		die();
	}

	public function getPlanoteca($id_planoteca){
		$intId_planoteca = intval($id_planoteca);
		if($intId_planoteca > 0)
		{
			$arrData = $this->model->selectPlanoteca($intId_planoteca);
			if(empty($arrData))
			{
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			}else{
				$arrData['url_planoteca'] = media().'/files/uploads/'.$arrData['planot_archivo'];
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function delPlanoteca(){
		if($_POST){
			$intId_planoteca = intval($_POST['id_planoteca_del']);
			$requestDelete = $this->model->deletePlanoteca($intId_planoteca);
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

	public function getProgramas(){

		$htmlOptions = "";
		$arrData = $this->model2->getProgramas();
		if(count($arrData) > 0 ){
			$htmlOptions = '<option value="">Seleccione...</option>';
			for ($i=0; $i < count($arrData); $i++) { 
				if($arrData[$i]['progra_estado'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['id_programa'].'">'.$arrData[$i]['progra_nombre'].'</option>';
				}
			}
		}
		echo $htmlOptions;
		die();	
	}
}
?>