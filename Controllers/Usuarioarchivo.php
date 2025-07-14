<?php 
class Usuarioarchivo extends Controllers{

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

	public function Usuarioarchivo(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header("Location:".base_url().'gestion');
		}
		$data['page_tag'] = "Usuario Archivo | ".NOMBRE_EMPRESA;
		$data['page_title'] = "USUARIO ARCHIVO | ".NOMBRE_EMPRESA;
		$data['page_name'] = "usuario_archivo";
		$data['page_functions_js'] = "functions_usuario_archivo.js";

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

		$this->views->getView($this,"usuarioarchivo",$data);
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

		$data['page_tag'] = "Usuario Archivo | ".NOMBRE_EMPRESA;
		$data['page_title'] = "USUARIO ARCHIVO | ".NOMBRE_EMPRESA;
		$data['page_name'] = "usuarios";
		$data['page_functions_js'] = "functions_usuarios.js";

		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$this->views->getView($this,"usuarioarchivo",$data);
	}

	public function setUsuarioArchivo(){
		if($_POST){			
			if(empty($_POST['id_usuario']) ||   empty($_POST['id_archivo']) )
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}
			else{ 
				$intId_usuario = intval($_POST['id_usuario']);
				$intId_archivo = intval($_POST['id_archivo']);

				$request_user = "";
				
				$request_user = $this->model->insertUsuarioArchivo($intId_usuario,
																$intId_archivo, 
																$_SESSION['idUser']);
				
				if($request_user > 0 )
				{
					$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					
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


	public function delUsuarioArchivo(){
		if($_POST){
			$int_Id_usuario_archivo = intval($_POST['id_usuario_archivo_del']);
			$requestDelete = $this->model->deleteUsuarioArchivo($int_Id_usuario_archivo);
			if($requestDelete)
			{
				$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el permiso del usuario');
			}else{
				$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el permiso del usuario.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}


	public function getUsuario_nombre($nom_usuario){
		$nom_usuario = strClean($nom_usuario);
		if($nom_usuario !== '')
		{
			$arrData = $this->model->getUsuario_nombre($nom_usuario);
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

	public function getArchivo_codigo($codigo){
		$codigo = strClean($codigo);
		if($codigo !== '')
		{
			$arrData = $this->model->getArchivo_codigo($codigo);
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