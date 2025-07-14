<?php 
class Tipoarchivo extends Controllers{

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

	public function Tipoarchivo(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Tipo Archivo | Geoportal WWF";
		$data['page_title'] = "TIPO ARCHIVO | Geoportal WWF";
		$data['page_name'] = "tipoarchivo";
		$data['page_functions_js'] = "functions_tipo_archivo.js";

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

		$this->views->getView($this,"tipoarchivo",$data);
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
		$data['page_functions_js'] = "functions_tipo_archivo.js";
		
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"tipoarchivo",$data);
	}

	public function getSelectTipo_Archivos()
	{
		$htmlOptions = "";
		$arrData = $this->model->selectTipo_Archivos();
		if(count($arrData) > 0 ){
			for ($i=0; $i < count($arrData); $i++) { 
				$htmlOptions .= '<option value="'.$arrData[$i]['id_tipo_archivo'].'">'.$arrData[$i]['tiparc_nombre'].'</option>';
			}
		}
		echo $htmlOptions;
		die();		
	}

	public function getTipo_Archivo(int $id_tipo_archivo)
	{
		//if($_SESSION['permisosMod']['permis_estado']){
			$intId_tipo_archivo = intval(strClean($id_tipo_archivo));
			if($intId_tipo_archivo > 0)
			{
				$arrData = $this->model->selectTipo_Archivo($intId_tipo_archivo);
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

	public function setTipo_Archivo(){
			$intId_tipo_archivo = intval($_POST['id_tipo_archivo']);
			$strNombre =  strClean($_POST['nombre']);
			$request_programa = "";
			if($intId_tipo_archivo == 0)
			{
				$request_programa = $this->model->insertTipo_Archivo($strNombre,$_SESSION['idUser']);
				$option = 1;
			}
			else{
				$request_programa = $this->model->updateTipo_Archivo($intId_tipo_archivo, $strNombre,$_SESSION['idUser']);
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

	public function delTipo_Archivo()
	{
		if($_POST){
				$intId_tipo_archivo = intval($_POST['id_tipo_archivo_del']);
				$requestDelete = $this->model->deleteTipo_Archivo($intId_tipo_archivo);
				if($requestDelete == 'ok')
				{
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Tipo Archivo');
				}else if($requestDelete == 'exist'){
					$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Tipo Archivo asociado a un Archivo.');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Tipo Archivo.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			
		}
		die();
	}
}
?>