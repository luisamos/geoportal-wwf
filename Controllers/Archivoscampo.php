<?php
require_once("Models/PArchivosCampo.php");

class Archivoscampo extends Controllers{

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->model = new PArchivosCampo();
	}

	// PUBLIC
	public function tipo($params){

		if(empty($params)){
			header("Location:".base_url());
		}
		else{
			$arrParams = explode(",",$params);
			$id_tipo = intval($arrParams[0]);
			$pagina = intval($arrParams[1]);

			$pagina = is_numeric($pagina) ? $pagina : 1;

			$cantDatos = $this->model->cantDatos($id_tipo);
			$total_registro = $cantDatos['total_registro'];
			$desde = ($pagina-1) * REG_XPORPAGINA;
			$total_paginas = ceil($total_registro / REG_XPORPAGINA);
			
			$Registros = $this->model->getRegistrosxTipo($id_tipo,$desde,REG_XPORPAGINA);

			if ($Registros['status']) {
				$data['total_registros'] = $total_registro;
				$data['page_tag'] = "Archivos de Campo - ".$Registros['tipo_archivo']." | ".NOMBRE_EMPRESA;
				$data['page_title'] = "ARCHIVOS DE CAMPO - ".$Registros['tipo_archivo'];
				$data['page_name'] = "archivoscampo";
				$data['registros'] = $Registros['registros'];
				$data['pagina'] = $pagina;
				$data['total_paginas'] = $total_paginas;
				$data['tipos'] = $this->model->getTipos();
				$data['id_tipo'] = $id_tipo;

				$this->views->getView($this,"archivoscampo",$data);
			}
			else{
				header("Location:".base_url()."/archivoscampo/tipo/1/1");
			}
		}
	}

	public function search(){
		$id_tipo = intval($_REQUEST['id_tipo']);

		if(empty($_REQUEST['s'])){
			header("Location:".base_url()."/archivoscampo/tipo/".$id_tipo."/1");
		}
		else{
			$busqueda = strClean($_REQUEST['s']);
		}

		$pagina = empty($_REQUEST['p']) ? 1 : intval($_REQUEST['p']);

		$cantDatos = $this->model->cantProdSearch($id_tipo,$busqueda);
		$total_registro = $cantDatos['total_registro'];
		$desde = ($pagina-1) * REG_XBUSCAR;
		$total_paginas = ceil($total_registro / REG_XBUSCAR);

		$Registros = $this->model->getProdSearch($id_tipo,$busqueda,$desde,REG_XBUSCAR);
		
		$data['total_registros'] = $total_registro;
		$data['page_tag'] = "Archivos de Campo - ".$Registros['tipo']." | ".NOMBRE_EMPRESA;
		$data['page_title'] = "ARCHIVOS DE CAMPO - ".$Registros['tipo'];
		$data['page_name'] = "archivoscampo";
		$data['registros'] = $Registros['registros'];
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $total_paginas;
		$data['busqueda'] = $busqueda;
		$data['tipos'] = $this->model->getTipos();
		$data['id_tipo'] = $id_tipo;

		$this->views->getView($this,"search",$data);
	}
}
?>