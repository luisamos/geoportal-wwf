<?php 
class Gpaisajes extends Controllers{

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
		getPermisos(MUSUARIOS);
	}

	public function Gpaisajes(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Paisajes | Geoportal WWF";
		$data['page_title'] = "PAISAJES | Geoportal WWF";
		$data['page_name'] = "paisajes";
		$data['page_functions_js'] = "functions_gpaisajes.js";

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

		$this->views->getView($this,"gpaisajes",$data);
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
		$data['page_name'] = "PAISAJES";
		$data['page_title'] = "Paisajes";
		$data['page_functions_js'] = "functions_gpaisajes.js";
		
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"gpaisajes",$data);
	}


	public function getPaisaje(int $id_paisaje)
	{
		//if($_SESSION['permisosMod']['permis_estado']){
			$intId_paisaje = intval(strClean($id_paisaje));
			if($intId_paisaje > 0)
			{
				$arrData = $this->model->selectPaisaje($intId_paisaje);
				if(empty($arrData))
				{
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				}else{
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		//}
		die();
	}

	public function setPaisaje(){
			$intId_paisaje = intval($_POST['id_paisaje']);
			$strPaisaje =  strClean($_POST['paisaje']);
			$strEstrategia = strClean($_POST['estrategia']);
			$strObjetivo = strClean($_POST['objetivo']);
			$strMeta1 = strClean($_POST['meta1']);
			$strMeta2 = strClean($_POST['meta2']);
			$strMeta3 = strClean($_POST['meta3']);
			$strMeta4 = strClean($_POST['meta4']);
			$strMeta5 = strClean($_POST['meta5']);
			$strIndicador = intval($_POST['indicador']);
			$strId_ref = intval( $_POST['id_ref']);

			$request = "";

			if($intId_paisaje == 0 || $strId_ref > 0)
			{
				$request = $this->model->insertPaisaje($strPaisaje, 
					$strEstrategia,
					$strObjetivo,
					$strMeta1,
					$strMeta2,
					$strMeta3,
					$strMeta4,
					$strMeta5,
					$strIndicador,
					$strId_ref,$_SESSION['idUser']);
				$option = 1;
			}
			else{
				$request = $this->model->updatePaisaje($intId_paisaje, 
					$strPaisaje, 
					$strEstrategia,
					$strObjetivo,
					$strMeta1,
					$strMeta2,
					$strMeta3,
					$strMeta4,
					$strMeta5,
					$strIndicador,
					$strId_ref,$_SESSION['idUser']);
					$option = 2;
			}

			if(is_numeric($request) && $request > 0 )
			{
				if($option == 1)
				{
					$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
				}else{
					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
				}
			}
			else if($request === "exist"){
				
				$arrResponse = array('status' => false, 'msg' => '¡Atención! El Paisaje ya existe.');
			}else{
				$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		die();
	}

	public function delPaisaje()
	{
		if($_POST){
				$intId_paisaje = intval($_POST['id_paisaje_del']);
				$requestDelete = $this->model->deletePaisaje($intId_paisaje);
				if($requestDelete == 'ok')
				{
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Paisaje');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Paisaje.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			
		}
		die();
	}
}
?>