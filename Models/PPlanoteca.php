<?php
class PPlanoteca extends PostgreSQL
{
	public $intIdPrograma;
	public $strProgra_nombre;

	public function __construct()
	{
		parent::__construct();
	}

	
	public function cantDatos($id_programa = null){
		$where = "";
		if($id_programa != null){
			$where = " AND planot_id_tipo = ".$id_programa;
		}
		$sql = "SELECT COUNT(*) as total_registro FROM planoteca WHERE planot_estado = 1 ".$where;
		$request = $this->select($sql);
		return $request;
	}

	public function getDatosPage($desde, $porpagina){
		$sql = "SELECT * FROM planoteca pl 
			INNER JOIN programa pr 
			ON pl.planot_id_tipo = pr.id_programa 
			WHERE pl.planot_estado = 1 
			ORDER BY pl.planot_id_tipo DESC LIMIT '$porpagina' OFFSET '$desde'";
		$request =$request = $this->select_all($sql);
		if(count($request) > 0){
			for ($c=0; $c < count($request) ; $c++) { 
				$request[$c]['planot_archivo'] = BASE_URL.'/Assets/files/uploads/'.$request[$c]['planot_archivo'];		
			}
		}
		return $request;
	}

	public function getRegistrosxPrograma(int $id_programa, $desde = null, $porpagina = null){
		
		$this->intIdPrograma = $id_programa;
		
		$sql_programa = "SELECT * FROM programa WHERE id_programa = '{$this->intIdPrograma}'";
		$request = $this->select($sql_programa);

		if(!empty($request)){
			
			$this->strProgra_nombre = $request['progra_nombre'];

			$where = "";
			if(is_numeric($desde) AND is_numeric($porpagina)){
				$where = " LIMIT '$porpagina' OFFSET '$desde'";
			}

			$sql = "SELECT * FROM planoteca 
			WHERE planot_estado = 1 
			AND planot_id_tipo = '{$this->intIdPrograma}'  
			ORDER BY id_planoteca DESC ".$where;

			$request = $this->select_all($sql);
			if(count($request) > 0){
				for ($c=0; $c < count($request) ; $c++) { 
					$request[$c]['planot_archivo'] = BASE_URL.'/Assets/files/uploads/'.$request[$c]['planot_archivo'];		
				}
			}
			$request = array(   'programa' => $this->strProgra_nombre,
							   'registros' => $request,
								  'status' => true
							);
		}
		else{
			$request = array('status' => false);
		}
		return $request;
	}

	public function getProgramas(){
		$sql = "SELECT * FROM programa WHERE progra_estado = 1 ORDER BY id_programa DESC";
		$request = $this->select_all($sql);
		return $request;
	}

	public function cantProdSearch(int $id_programa, $tipo_busqueda, $busqueda){
		$this->intIdPrograma = $id_programa;

		
		if ($tipo_busqueda==1) {
			$campo = "planot_nombre";
		}
		if ($tipo_busqueda==2) {
			$campo = "planot_tag";
		}

		$sql = "SELECT COUNT(*) as total_registro 
				FROM planoteca 
				WHERE $campo ILIKE '%$busqueda%' 
				AND planot_id_tipo='{$this->intIdPrograma}'  
				AND planot_estado = 1 ";

		$result_register = $this->select($sql);
		$total_registro = $result_register;
		return $total_registro;
	}

	public function getProdSearch(int $id_programa, $tipo_busqueda, $busqueda, $desde, $porpagina){

		$this->intIdPrograma = $id_programa;

		if ($tipo_busqueda==1) {
			$campo = "planot_nombre";
		}
		if ($tipo_busqueda==2) {
			$campo = "planot_tag";
		}

		$sql_programa = "SELECT * FROM programa WHERE id_programa = '{$this->intIdPrograma}'";
		$request = $this->select($sql_programa);

		if(!empty($request)){
			$where = "";
			if(is_numeric($desde) AND is_numeric($porpagina)){
				$where = " LIMIT '$porpagina' OFFSET '$desde'";
			}

			$this->strProgra_nombre = $request['progra_nombre'];

			$sql = "SELECT  *
				FROM planoteca 
				WHERE 
				planot_estado = 1 AND 
				$campo ILIKE '%$busqueda%' 
				AND planot_id_tipo='{$this->intIdPrograma}' 
				ORDER BY id_planoteca DESC ".$where;

			$request = $this->select_all($sql);
			if(count($request) > 0){
				for ($c=0; $c < count($request); $c++) { 
					$request[$c]['planot_archivo'] = BASE_URL.'/Assets/files/uploads/'.$request[$c]['planot_archivo'];		
				}
			}
			$request = array( 'programa' => $this->strProgra_nombre,
							 'registros' => $request,
								'status' => true
							);
		}
		else{
			$request = array('status' => false);
		}
		return $request;
	}
}
?>