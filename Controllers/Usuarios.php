<?php
class Usuarios extends Controllers{

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

	public function Usuarios(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "GEOPORTAL WWF | Usuarios";
		$data['page_title'] = "GEOPORTAL WWF | Usuarios";
		$data['page_name'] = "usuarios";
		$data['page_functions_js'] = "functions_usuarios.js";

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

		$this->views->getView($this,"usuarios",$data);
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
		$data['page_title'] = "USUARIOS";
		$data['page_name'] = "usuarios";
		$data['page_functions_js'] = "functions_usuarios.js";

		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"usuarios",$data);
	}

	public function setUsuario(){
		if($_POST){
			if(empty($_POST['usuario']) ||   empty($_POST['id_persona']) )
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}
			else{
				$intId_persona = intval($_POST['id_persona']);
				$intId_usuario = intval($_POST['id_usuario']);
				$strNombre = strtolower(strClean($_POST['usuario']));
				$intId_rol = intval($_POST['id_rol']);

				$request_user = "";
				if($intId_usuario == 0)
				{
					$option = 1;
					$strClave =  empty($_POST['clave']) ? hash("SHA256",passGenerator()) : hash("SHA256",$_POST['clave']);

					$request_user = $this->model->insertUsuario($intId_persona,
																$strNombre,
																$strClave,
																$intId_rol,
																$_SESSION['idUser']);
				}
				else{
					$option = 2;
					$strClave =  empty($_POST['clave']) ? "" : hash("SHA256",$_POST['clave']);
					$request_user = $this->model->updateUsuario($intId_usuario,
																$intId_persona,
																$strNombre,
																$strClave,
																$intId_rol,
																$_SESSION['idUser']);
				}

				if($request_user > 0 )
				{
					if($option == 1){
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					}
					else{
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				}
				else if($request_user == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! el Usuario ya existe, ingrese otro.');
				}
				else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function getUsuarios(){
		$arrData = $this->model->selectUsuarios();
		echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		die();
	}

	public function getUsuario($id_usuario){
		$id_usuario = intval($id_usuario);
		if($id_usuario > 0)
		{
			$arrData = $this->model->selectUsuario($id_usuario);
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

	public function delUsuario(){
		if($_POST){
			$int_Id_usuario = intval($_POST['id_usuario_del']);
			$requestDelete = $this->model->deleteUsuario($int_Id_usuario);
			if($requestDelete)
			{
				$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el usuario');
			}else{
				$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el usuario.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function habilitar($id_usuario_hab)
	{
		if($_GET){
			$int_Id_usuario = intval($id_usuario_hab);
			$requestHabilitar = $this->model->habilitar($int_Id_usuario);
			if($requestHabilitar)
			{
				$arrResponse = array('status' => true, 'msg' => 'Se habilitó el usuario');
			}else
			{
				$arrResponse = array('status' => true, 'msg' => 'Se deshabilitó el usuario.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}
}
?>