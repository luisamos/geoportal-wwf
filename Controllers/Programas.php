<?php 
class Programas extends Controllers{

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

	public function Programas(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Programas | Geoportal WWF";
		$data['page_title'] = "PROGRAMAS | Geoportal WWF";
		$data['page_name'] = "programas";
		$data['page_functions_js'] = "functions_programas.js";

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

		$this->views->getView($this,"programas",$data);
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
		$data['page_name'] = "PROGRAMAS";
		$data['page_title'] = "Programas";
		$data['page_functions_js'] = "functions_programas.js";
		
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"programas",$data);
	}

	public function getSelectProgramas()
	{
		$htmlOptions = "";
		$arrData = $this->model->selectProgramas();
		if(count($arrData) > 0 ){
			for ($i=0; $i < count($arrData); $i++) { 
				$htmlOptions .= '<option value="'.$arrData[$i]['id_programa'].'">'.$arrData[$i]['progra_nombre'].'</option>';
			}
		}
		echo $htmlOptions;
		die();		
	}

	public function getPrograma(int $id_programa)
	{
		//if($_SESSION['permisosMod']['permis_estado']){
			$intId_programa = intval(strClean($id_programa));
			if($intId_programa > 0)
			{
				$arrData = $this->model->selectPrograma($intId_programa);
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

	public function setPrograma(){
			$intId_programa = intval($_POST['id_programa']);
			$strNombre =  strClean($_POST['nombre']);
			$strDescipcion = strClean($_POST['descripcion']);
			$request_programa = "";
			if($intId_programa == 0)
			{
				$request_programa = $this->model->insertPrograma($strNombre, $strDescipcion,$_SESSION['idUser']);
				$option = 1;
			}
			else{
				$request_programa = $this->model->updatePrograma($intId_programa, $strNombre, $strDescipcion,$_SESSION['idUser']);
				$option = 2;
			}

			if($request_programa > 0 )
			{
				if($option == 1)
				{
					$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
				}else{
					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
				}
			}else if($request_programa == 'exist'){
				
				$arrResponse = array('status' => false, 'msg' => '¡Atención! El Programa ya existe.');
			}else{
				$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		die();
	}

	public function delPrograma()
	{
		if($_POST){
				$intId_programa = intval($_POST['id_programa_del']);
				$requestDelete = $this->model->deletePrograma($intId_programa);
				if($requestDelete == 'ok')
				{
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Programa');
				}else if($requestDelete == 'exist'){
					$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Programa asociado a una Planoteca.');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Programa.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			
		}
		die();
	}
}
?>