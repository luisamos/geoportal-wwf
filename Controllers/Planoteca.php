<?php
require_once("Models/PPlanoteca.php");

class Planoteca extends Controllers{

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->model = new PPlanoteca();
	}

	// PUBLIC
	public function programas($params){

		if(empty($params)){
			header("Location:".base_url());
		}
		else{
			$arrParams = explode(",",$params);
			$id_programa = intval($arrParams[0]);
			$pagina = intval($arrParams[1]);

			$pagina = is_numeric($pagina) ? $pagina : 1;

			$cantDatos = $this->model->cantDatos($id_programa);
			$total_registro = $cantDatos['total_registro'];
			$desde = ($pagina-1) * REG_XPORPAGINA;
			$total_paginas = ceil($total_registro / REG_XPORPAGINA);
			
			$Registros = $this->model->getRegistrosxPrograma($id_programa,$desde,REG_XPORPAGINA);

			if ($Registros['status']) {
				$data['total_registros'] = $total_registro;
				$data['page_tag'] = "Compendio Cartográfico - ".$Registros['programa']." | ".NOMBRE_EMPRESA;
				$data['page_title'] = "Compendio Cartográfico - ".$Registros['programa'];
				$data['page_name'] = "Compendio Cartográfico";
				$data['registros'] = $Registros['registros'];
				$data['pagina'] = $pagina;
				$data['total_paginas'] = $total_paginas;
				$data['programas'] = $this->model->getProgramas();
				$data['id_programa'] = $id_programa;

				$this->views->getView($this,"planoteca",$data);
			}
			else{
				header("Location:".base_url()."/planoteca/programas/1/1");
			}
		}
	}

	public function search(){
		$id_programa = intval($_REQUEST['id_programa']);

		if(empty($_REQUEST['s'])){
			header("Location:".base_url()."/planoteca/programas/".$id_programa."/1");
		}
		else{
			$tipo_busqueda = strClean($_REQUEST['t']);
			$busqueda = strClean($_REQUEST['s']);
		}

		$pagina = empty($_REQUEST['p']) ? 1 : intval($_REQUEST['p']);

		$cantDatos = $this->model->cantProdSearch($id_programa,$tipo_busqueda,$busqueda);
		$total_registro = $cantDatos['total_registro'];
		$desde = ($pagina-1) * REG_XBUSCAR;
		$total_paginas = ceil($total_registro / REG_XBUSCAR);

		$Registros = $this->model->getProdSearch($id_programa,$tipo_busqueda,$busqueda,$desde,REG_XBUSCAR);
		
		$data['total_registros'] = $total_registro;
		$data['page_tag'] = "Planoteca - ".$Registros['programa']." | ".NOMBRE_EMPRESA;
		$data['page_title'] = "PLANOTECA - ".$Registros['programa'];
		$data['page_name'] = "planoteca";
		$data['registros'] = $Registros['registros'];
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$data['busqueda'] = $busqueda;
		$data['programas'] = $this->model->getProgramas();
		$data['id_programa'] = $id_programa;
		$data['tipobusqueda'] = $tipo_busqueda;

		$this->views->getView($this,"search",$data);
	}
}
?>