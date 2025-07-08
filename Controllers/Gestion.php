<?php

class Gestion extends Controllers
{

	public function __construct()
	{
		parent::__construct();
		session_start();
		session_regenerate_id(true);

		if (empty($_SESSION['login'])) {
			header('Location: ' . base_url() . 'ingreso');
			die();
		}
		getPermisos(1);
	}

	public function gestion()
	{
		$data['page_id'] = 2;
		$data['page_tag'] = NOMBRE_EMPRESA;
		$data['page_title'] = NOMBRE_EMPRESA;
		$data['page_name'] = 'gestion';
		$data['page_functions_js'] = 'funciones_gestion.js';

		// VALIDAMOS LA VISTA DE USUARIO CLIENTE Y U ADMINISTRADOR
		// if ($_SESSION['userData']['idrol'] == RCLIENTES){
		$this->views->getView($this, "gestion_cliente", $data);
		// }else {
		// 	$this->views->getView($this,"gestion",$data);
		// }
	}
}