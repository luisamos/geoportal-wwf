<?php
class CapasGeograficas extends Controllers{

	public function __construct()
	{
		parent::__construct();
		session_start();
		session_regenerate_id(true);
		if(empty($_SESSION['login']))
		{
			if(!IS_DEV)
			{
				header('Location: '.base_url().'ingreso');
				die();
			}
		}
		getPermisos(MCAPAS);
	}

	public function CapasGeograficas(){

		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header('Location:'.base_url().'gestion');
		}
		$data['page_tag'] = NOMBRE_EMPRESA;
		$data['page_title'] = NOMBRE_EMPRESA. ' | Capas Geográficas';
		$data['page_name'] = 'capas_geograficas';
		$data['page_functions_js'] = 'funciones_capas_geograficas.js';

		$pagina = 1;
		$cantDatos = $this->model->getCantidadTotal();
		$totalRegistro = $cantDatos['total_registro'];
		$desde = ($pagina-1) * REG_XPORPAGINA;
		$totalPaginas = ceil($totalRegistro / REG_XPORPAGINA);

		$Registros = $this->model->getPagina($desde,REG_XPORPAGINA);
		$data['registros'] = $Registros;
		$data['pagina'] = $pagina;
		$data['total_paginas'] = $totalPaginas;
		$data['total_registros'] = $totalRegistro;

		$this->views->getView($this,'capas_geograficas',$data);
	}

	public function Page($pagina = null){

		$pagina = is_numeric($pagina) ? $pagina : 1;
		$cantDatos = $this->model->getCantidadTotal();
		$totalRegistro = $cantDatos['total_registro'];
		$desde = ($pagina-1) * REG_XPORPAGINA;
		$totalPaginas = ceil($totalRegistro / REG_XPORPAGINA);
		$Registros = $this->model->getPagina($desde,REG_XPORPAGINA);

		$data['total_registros'] = $totalRegistro;
		$data['registros'] = $Registros;

		$data['page_tag'] = NOMBRE_EMPRESA;
		$data['page_title'] = NOMBRE_EMPRESA . ' | Capas Geográficas';
		$data['page_name'] = 'capas_geograficas';
		$data['page_functions_js'] = 'funciones_capas_geograficas.js';

		$data['pagina'] = $pagina;
		$data['total_paginas'] = $totalPaginas;
		$this->views->getView($this,'capas_geograficas',$data);
	}

	public function getCapasGeograficas(){
		//if($_SESSION['permisosMod']['r']){
			$arrData = $this->model->select();
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		//}
		die();
	}

	public function get($idCapaGeografica){
		header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }

        $intIdCapaGeografica = intval(strClean($idCapaGeografica));

        if($intIdCapaGeografica > 0)
        {
            $lista = $this->model->listarPor($intIdCapaGeografica);
            $mensaje = empty($lista) ? ['estado' => false, 'mensaje' => 'Datos no encontrados.'] : ['estado' => true, 'datos' => $lista];
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        }
	}

	public function set(){
		header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$intIdCapaGeografica= intval($_POST['id_capa_geografica'] ?? 0);
			$strNombre= $_POST['nombre'] ?? null;
			$strAlias= $_POST['alias'] ?? null;
			$intTematica= $_POST['id_tematica'] ?? null;
			$intTipo= $_POST['tipo'] ?? null;
			$bolVisible = $_POST['visible'] ?? null;

			$opcion= empty($intIdCapaGeografica)? 1 : 2;

			if(is_null($strNombre) ||
			is_null($strAlias) ||
			is_null($intTematica) ||
			is_null($intTipo) ||
			is_null($bolVisible))
			{
				echo json_encode(['estado' => false, 'mensaje' => 'Falta completar datos.']);
			}
			else
			{
				$respuesta='';
				if($opcion == 1)
				{
					$respuesta = $this->model->agregar(
						$strNombre,
						$strAlias,
						$intTematica,
						$intTipo,
						$bolVisible,
						(IS_DEV)? 100 : $_SESSION['idUser']);
				}
				else if($opcion == 2){
					$respuesta = $this->model->actualizar(
						$intIdCapaGeografica,
						$strNombre,
						$strAlias,
						$intTematica,
						$intTipo,
						$bolVisible,
						(IS_DEV)? 100 : $_SESSION['idUser']);
				}

				if($respuesta > 0)
					echo json_encode(['estado' => true, 'mensaje' => 'Datos guardados correctamente.'], JSON_UNESCAPED_UNICODE);
				else echo json_encode(['estado' => false, 'mensaje' => 'No se pudo ingresar, revisar campos', JSON_UNESCAPED_UNICODE]);
			}
		}
		else {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }
	}

	public function del()
	{
		header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }

        $intIdCapaGeografica = intval($_POST['id_capa_geografica'] ?? 0);

        $respuesta = $this->model->eliminar($intIdCapaGeografica);
        echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Se ha eliminado la capa geográfica.' : 'Error en eliminar la capa geográfica.'], JSON_UNESCAPED_UNICODE);
	}

	public function setOrden(){
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!is_array($data)) {
                echo json_encode(["estado" => false, "mensaje" => "Datos mal formateados"]);
                exit;
            }

            $respuesta = $this->model->actualizarOrden($data);
            echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Guardado correctamente.' : 'Error, revisar conexión.'], JSON_UNESCAPED_UNICODE);
        }
        else {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }
    }
}
?>