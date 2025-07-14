<?php
class Gnoticias extends Controllers{

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
		getPermisos(MNOTICIAS);
	}

	public function Gnoticias()
	{
		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Noticias | Geoportal WWF";
		$data['page_title'] = "NOTICIAS";
		$data['page_name'] = "noticias";
		$data['page_functions_js'] = "functions_noticias.js";

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

		$this->views->getView($this,"gnoticias",$data);
	}

	public function page($pagina = null){

		$pagina = is_numeric($pagina) ? $pagina : 1;
		$cantDatos = $this->model->cantDatos();
		$total_registro = $cantDatos['total_registro'];
		$desde = ($pagina-1) * REG_XPORPAGINA;
		$total_paginas = ceil($total_registro / REG_XPORPAGINA);
		$Registros = $this->model->getDatosPage($desde,REG_XPORPAGINA);

		$data['total_registros'] = $total_registro;
		$data['registros'] = $Registros;

		$data['page_tag'] = NOMBRE_EMPRESA;
		$data['page_title'] = "NOTICIAS";
		$data['page_name'] = "noticias";
		$data['page_functions_js'] = "functions_noticias.js";

		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"gnoticias",$data);
	}

	public function setNoticia(){
		if($_POST){
			if( ($_POST['tipo'] ==2 && empty($_POST['url'])) ||  ($_POST['tipo'] ==1 && empty($_POST['titulo'])) )
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}
			else{
				
				$intId_noticia = intval($_POST['id_noticia']);
				$intTipo = intval($_POST['tipo']);
				$intUrl = strClean($_POST['url']);
				$strTitulo =  strClean($_POST['titulo']);
				$strDescripcion =  strClean($_POST['descripcion']);

				$foto   	 	= $_FILES['foto'];
				$nombre_foto1 	= $foto['name'];
				$type 		 	= $foto['type'];
				$url_temp    	= $foto['tmp_name'];
				$imgNoticia1 	= 'notici_imagen1.jpg';
				
				if($nombre_foto1 != ''){
					$extension = pathinfo($nombre_foto1, PATHINFO_EXTENSION); 
					$imgNoticia1 = 'img1_noticia_'.md5(date('d-m-Y H:i:s')). '.' . $extension;
				}

				$foto2   	 	= $_FILES['foto2'];
				$nombre_foto2 	= $foto2['name'];
				$type2 		 	= $foto2['type'];
				$url_temp2   	= $foto2['tmp_name'];
				$imgNoticia2 	= 'notici_imagen2.jpg';
				
				if($nombre_foto2 != ''){
					$extension = pathinfo($nombre_foto2, PATHINFO_EXTENSION); 
					$imgNoticia2 = 'img2_noticia_'.md5(date('d-m-Y H:i:s')). '.' . $extension;
				}

				$request_noticia = "";

				$ruta = strtolower(clear_cadena($strTitulo));
				$ruta = str_replace(" ","-",$ruta);

				if($intId_noticia == 0)
				{
					//Crear
					$request_noticia = $this->model->inserNoticia($intTipo,$intUrl,$strTitulo,$strDescripcion,$imgNoticia1,$imgNoticia2,$ruta,$_SESSION['idUser']);
					$option = 1;
					
				}else{
					//Actualizar
					if($nombre_foto1 == ''){
						if($_POST['foto_actual'] != 'notici_imagen1.jpg' && $_POST['foto_remove'] == 0 ){
							$imgNoticia1 = $_POST['foto_actual'];
						}
					}
					if($nombre_foto2 == ''){
						if($_POST['foto_actual2'] != 'notici_imagen2.jpg' && $_POST['foto_remove2'] == 0 ){
							$imgNoticia2 = $_POST['foto_actual2'];
						}
					}

					$request_noticia = $this->model->updateNoticia($intId_noticia,$intTipo,$intUrl,$strTitulo,$strDescripcion,$imgNoticia1,$imgNoticia2,$ruta,$_SESSION['idUser']);
					$option = 2;
					
				}
				if($request_noticia > 0 )
				{
					if($option == 1){
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
						if($nombre_foto1 != ''){ uploadImage($foto,$imgNoticia1); }
						if($nombre_foto2 != ''){ uploadImage($foto2,$imgNoticia2); }
					}else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						if($nombre_foto1 != ''){ uploadImage($foto,$imgNoticia1); }
						if($nombre_foto2 != ''){ uploadImage($foto2,$imgNoticia2); }

						if(($nombre_foto1 == '' && $_POST['foto_remove'] == 1 && $_POST['foto_actual'] != 'notici_imagen1.png')
							|| ($nombre_foto1 != '' && $_POST['foto_actual'] != 'notici_imagen1.png')){
							deleteFile($_POST['foto_actual']);
						}
						if(($nombre_foto2 == '' && $_POST['foto_remove2'] == 1 && $_POST['foto_actual2'] != 'notici_imagen2.png')
							|| ($nombre_foto2 != '' && $_POST['foto_actual2'] != 'notici_imagen2.png')){
							deleteFile($_POST['foto_actual2']);
						}
					}
				}
				else if($request_noticia == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! La noticia ya existe.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}


	public function getNoticia($id_noticia){
		$intId_noticia = intval($id_noticia);
		if($intId_noticia > 0)
		{
			$arrData = $this->model->selectNoticia($intId_noticia);
			if(empty($arrData))
			{
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			}
			else{
				$arrData['url_noticia'] = media().'/images/uploads/'.$arrData['notici_imagen1'];
				$arrResponse = array('status' => true, 'data' => $arrData);

				$arrData['url_noticia2'] = media().'/images/uploads/'.$arrData['notici_imagen2'];
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function delNoticia(){
		if($_POST){
			$intId_noticia = intval($_POST['id_noticia_del']);
			$requestDelete = $this->model->deleteNoticia($intId_noticia);
			if($requestDelete == 'ok')
			{
				$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la noticia.');
			}else if($requestDelete == 'exist'){
				$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar una noticia con productos asociados.');
			}else{
				$arrResponse = array('status' => false, 'msg' => 'Error al eliminar la noticia.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}
}
?>