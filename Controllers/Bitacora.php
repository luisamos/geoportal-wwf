<?php 
class Bitacora extends Controllers{

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

	public function Bitacora(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Bitacora | Geoportal WWF";
		$data['page_title'] = "BITACORA | Geoportal WWF";
		$data['page_name'] = "bitacora";
		$data['page_functions_js'] = "functions_bitacora.js";

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

		$this->views->getView($this,"bitacora",$data);
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
		$data['page_name'] = "BITACORA";
		$data['page_title'] = "Bitacora";
		$data['page_functions_js'] = "functions_bitacora.js";

		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"bitacora",$data);
	}


	public function getBitacora(int $id_bitacora)
	{
		//if($_SESSION['permisosMod']['permis_estado']){
			$intId_bitacora = intval(strClean($id_bitacora));
			if($intId_bitacora > 0)
			{
				$arrData = $this->model->selectBitacora($intId_bitacora);
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

	public function setBitacora(){
			$intId_bitacora = intval($_POST['id_bitacora']);
			$strId_proy = intval( $_POST['id_proy']);
			$strId_elemento = intval( $_POST['id_elemento']);
			$strCampo1 =  strClean($_POST['campo1']);
			$strCampo2 = strClean($_POST['campo2']);
			$strCampo3 = strClean($_POST['campo3']);
			$intId_ref = intval($_POST['id_ref']);
			$request = "";

			if($intId_bitacora == 0 || $strId_proy > 0)
			{
				$request = $this->model->insertBitacora($strId_proy, $strId_elemento, $strCampo1, 
					$strCampo2,$strCampo3,$intId_ref, $_SESSION['idUser']);
				$option = 1;
			}
			else{
				$request = $this->model->updateBitacora($intId_bitacora, $strId_elemento, $strCampo1,$strCampo2,$strCampo3,$intId_ref,$_SESSION['idUser']);
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
				$arrResponse = array('status' => false, 'msg' => '¡Atención! La Bitacora ya existe.');
			}else{
				$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		die();
	}

	public function delBitacora()
	{
		if($_POST){
				$intId_bitacora = intval($_POST['id_bitacora_del']);
				$requestDelete = $this->model->deleteBitacora($intId_bitacora);
				if($requestDelete == 'ok')
				{
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Bitacora');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Bitacora.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getDatos()
	{
		//if($_SESSION['permisosMod']['permis_estado']){
			$arrData = $this->model->getDatos();
			if(empty($arrData))
			{
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			}else{
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		//}
		die();
	}
}
?>