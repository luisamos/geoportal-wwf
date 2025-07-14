<?php
class Personas extends Controllers{

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
		getPermisos(MPERSONAS);
	}

	public function Personas(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Personas | Geoportal WWF";
		$data['page_title'] = "PERSONAS | Geoportal WWF";
		$data['page_name'] = "personas";
		$data['page_functions_js'] = "functions_personas.js";


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

		$this->views->getView($this,"personas",$data);
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
			$data['page_name'] = "PERSONAS";
			$data['page_title'] = "Personas";
			$data['page_functions_js'] = "functions_personas.js";
			
			$data['pagina'] = $pagina;
			$data['total_paginas'] = $total_paginas;
			$this->views->getView($this,"personas",$data);
	}

	public function setPersona(){
		error_reporting(0);
		if($_POST){
			if(empty($_POST['num_documento']) || empty($_POST['nombres']) || empty($_POST['apellidos']) || empty($_POST['celular']) || empty($_POST['email']))
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}
			else{ 
				$id_persona = intval($_POST['id_persona']);
				$strNum_documento = strClean($_POST['num_documento']);
				$strNombres = ucwords(strClean($_POST['nombres']));
				$strApellidos = ucwords(strClean($_POST['apellidos']));
				$strCelular = ucwords(strClean($_POST['celular']));
				$strEmail = strtolower(strClean($_POST['email']));

				$request_persona = "";
				if($id_persona == 0)
				{
					$option = 1;
					$request_persona = $this->model->insertPersona($strNum_documento,
																	$strNombres, 
																	$strApellidos,
																	$strCelular,
																	$strEmail,
																	$_SESSION['idUser']);	
				}
				else{
					$option = 2;
					$request_persona = $this->model->updatePersona($id_persona,
																$strNum_documento, 
																$strNombres,
																$strApellidos,
																$strCelular,
																$strEmail,
																$_SESSION['idUser']);
				}

				if($request_persona > 0 )
				{
					if($option == 1){
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					}
					else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				}
				else if($request_persona == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! Nº de documento ya existe, ingrese otro.');		
				}
				else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getPersonas(){
			$arrData = $this->model->selectPersonas();
			for ($i=0; $i < count($arrData); $i++) {
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';
					$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['id_persona'].')" title="Ver Persona"><i class="far fa-eye"></i></button>';
				
					$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,'.$arrData[$i]['id_persona'].')" title="Editar Persona"><i class="fas fa-pencil-alt"></i></button>';
				
					$btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['id_persona'].')" title="Eliminar Persona"><i class="far fa-trash-alt"></i></button>';
				
				$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		die();
	}

	public function getPersona($id_persona){
		$id_perosna = intval($id_persona);
		if($id_perosna > 0)
		{
			$arrData = $this->model->selectPersona($id_perosna);
			if(empty($arrData))
			{
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			}else{
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		
		die();
	}

	public function delPersona(){
		if($_POST){
			$int_Id_persona = intval($_POST['id_persona_del']);
			$requestDelete = $this->model->deletePersona($int_Id_persona);
			if($requestDelete)
			{
				$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la persona');
			}else{
				$arrResponse = array('status' => false, 'msg' => 'Error al eliminar la persona.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getPersona_NumDocumento($num_documento){
		$num_documento = strClean($num_documento);
		if($num_documento !== '')
		{
			$arrData = $this->model->getPersona_NumDocumento($num_documento);
			if(empty($arrData))
			{
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
			}else{
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		
		die();
	}
}
?>