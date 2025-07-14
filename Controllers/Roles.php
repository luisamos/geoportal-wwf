<?php 
class Roles extends Controllers{

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

	public function Roles(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Roles | Geoportal WWF";
		$data['page_title'] = "ROLES | Geoportal WWF";
		$data['page_name'] = "roles";
		$data['page_functions_js'] = "functions_roles.js";

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

		$this->views->getView($this,"roles",$data);
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
		$data['page_name'] = "ROLES";
		$data['page_title'] = "Roles";
		$data['page_functions_js'] = "functions_roles.js";
		
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"roles",$data);
	}


	public function getSelectRoles()
	{
		$htmlOptions = "";
		$arrData = $this->model->selectRoles();
		if(count($arrData) > 0 ){
			for ($i=0; $i < count($arrData); $i++) { 
				
				$htmlOptions .= '<option value="'.$arrData[$i]['id_rol'].'">'.$arrData[$i]['rol_nombre'].'</option>';
				
			}
		}
		echo $htmlOptions;
		die();		
	}

	public function getRol(int $id_rol)
	{
		//if($_SESSION['permisosMod']['permis_estado']){
			$intId_rol = intval(strClean($id_rol));
			if($intId_rol > 0)
			{
				$arrData = $this->model->selectRol($intId_rol);
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

	public function setRol(){
			$intId_rol = intval($_POST['id_rol']);
			$strRol =  strClean($_POST['nombre']);
			$strDescipcion = strClean($_POST['descripcion']);
			$request_rol = "";
			if($intId_rol == 0)
			{
				$request_rol = $this->model->insertRol($strRol, $strDescipcion,$_SESSION['idUser']);
				$option = 1;
			}
			else{
				$request_rol = $this->model->updateRol($intId_rol, $strRol, $strDescipcion,$_SESSION['idUser']);
				$option = 2;
			}

			if($request_rol > 0 )
			{
				if($option == 1)
				{
					$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
				}else{
					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
				}
			}else if($request_rol == 'exist'){
				
				$arrResponse = array('status' => false, 'msg' => '¡Atención! El Rol ya existe.');
			}else{
				$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		die();
	}

	public function delRol()
	{
		if($_POST){
				$intId_rol = intval($_POST['id_rol_del']);
				$requestDelete = $this->model->deleteRol($intId_rol);
				if($requestDelete == 'ok')
				{
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Rol');
				}else if($requestDelete == 'exist'){
					$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Rol asociado a usuarios.');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Rol.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			
		}
		die();
	}
}
?>