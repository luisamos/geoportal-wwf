<?php
class Ingreso extends Controllers{

	public function __construct()
	{
		session_start();
		session_regenerate_id(true);
		if(isset($_SESSION['login']))
		{
			header('Location: '.base_url().'gestion');
			die();
		}
		parent::__construct();
	}

	public function ingreso()
	{
		$data['page_tag'] = NOMBRE_EMPRESA . " | Ingreso";
		$data['page_title'] = NOMBRE_EMPRESA;
		$data['page_name'] = "ingreso";
		$data['page_functions_js'] = "funciones_ingreso.js";
		$this->views->getView($this,"ingreso",$data);
	}

	public function loginUser2(){
		if($_POST){
			if(empty($_POST['txtUsuario']) || empty($_POST['txtClave'])){
				$arrResponse = array('status' => false, 'msg' => 'Error de datos' );
			}
			else{
				$str_Usuari_nombre  =  strtolower(strClean($_POST['txtUsuario']));
				$str_Usuari_clave = hash("SHA256",$_POST['txtClave']);
				
				$requestUser = $this->model->loginUser($str_Usuari_nombre, $str_Usuari_clave);

				if(empty($requestUser)){
					$arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.' ); 
				}
				else{
					$arrData = $requestUser;
					if($arrData['usuari_estado'] == 1){
						$_SESSION['idUser'] = $arrData['id_usuario'];
						$_SESSION['login'] = true;
						$_SESSION['id_rol'] = $arrData['usuari_id_rol'];

						$arrData = $this->model->sessionLogin($_SESSION['idUser']);
						sessionUser($_SESSION['idUser']);
						$arrResponse = array('status' => true, 'msg' => 'ok');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Usuario inactivo.');
					}
				}
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function loginUser()
	{
	if ($_POST) {
		if (empty($_POST['txtUsuario']) || empty($_POST['txtClave'])) {
			$arrResponse = array('status' => false, 'msg' => 'Error de datos');
		} else {
			$str_Usuari_nombre = strtolower(strClean($_POST['txtUsuario']));
			$str_Usuari_clave = $_POST['txtClave'];

			// Llama al modelo
			$requestUser = $this->model->loginUser($str_Usuari_nombre, $str_Usuari_clave);

			// Verifica si el modelo retornó un error
			if (isset($requestUser['error'])) {
				$arrResponse = array('status' => false, 'msg' => $requestUser['error']);
			}
			// Si no se encontró el usuario o hay otro fallo
			elseif (empty($requestUser)) {
				$arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.');
			}
			// Autenticación exitosa
			else {
				$arrData = $requestUser;

				if ($arrData['usuari_estado'] == 1) {
					$_SESSION['idUser'] = $arrData['id_usuario'];
					$_SESSION['login'] = true;
					$_SESSION['id_rol'] = $arrData['usuari_id_rol'];

					// Obtener sesión extendida si corresponde
					$arrData = $this->model->sessionLogin($_SESSION['idUser']);
					sessionUser($_SESSION['idUser']);

					$arrResponse = array('status' => true, 'msg' => 'ok');
				} else {
					$arrResponse = array('status' => false, 'msg' => 'Usuario inactivo.');
				}
			}
		}

		// Enviar respuesta JSON al frontend
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
	}
	die();
	}
}
 ?>