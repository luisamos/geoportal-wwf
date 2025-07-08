<?php
class PArchivos extends PostgreSQL
{
	public $intIdTipo;
	public $strTipo_nombre;
	
	public function __construct()
	{
		parent::__construct();		
	}

	public function cantDatos($id_tipo = null){
		$where = "";
		if($id_tipo != null){
			$where = " AND archiv_id_tipo = ".$id_tipo;
		}
		$sql = "SELECT COUNT(*) as total_registro FROM archivo WHERE archiv_estado = 1 ".$where;
		$request = $this->select($sql);
		return $request;
	}

	public function getDatosPage($desde, $porpagina){
		$sql = "SELECT * FROM archivo ar 
		INNER JOIN tipo_archivo ta 
		ON ar.archiv_id_tipo = ta.id_tipo_archivo 
		WHERE archiv_estado = 1 
		ORDER BY id_archivo DESC LIMIT '$porpagina' OFFSET '$desde'";
		$request =$request = $this->select_all($sql);
		if(count($request) > 0){
			for ($c=0; $c < count($request) ; $c++) { 
				$request[$c]['archiv_archivo'] = BASE_URL.'/Assets/files/uploads/'.$request[$c]['archiv_archivo'];		
			}
		}
		return $request;
	}

	public function getRegistrosxTipo(int $id_tipo, int $id_login, $desde = null, $porpagina = null){
		
		$this->intIdTipo = $id_tipo;

		$AND = "";
		if ($id_login==0) {
			$AND = " AND archiv_acceso = 1 ";
		}

		$LIMIT = "";
		if(is_numeric($desde) AND is_numeric($porpagina)){
			$LIMIT = " LIMIT '$porpagina' OFFSET '$desde'";
		}

		$sql_tipo_archivo = "SELECT * FROM tipo_archivo WHERE id_tipo_archivo = '{$this->intIdTipo}'";
		$request = $this->select($sql_tipo_archivo);

		if(!empty($request)){

			$this->strtipo_nombre = $request['tiparc_nombre'];

			//LISTA DE ARCHIVOS TOTAL PUBLICOS Y PRIVADOS SI SE HA LOGEADO SINO SOLO PUBLICOS
			$sql = "SELECT * FROM archivo 
					WHERE 
					archiv_estado = 1  
					AND archiv_id_tipo = $this->intIdTipo 
					".$AND." 
					ORDER BY id_archivo DESC ".$LIMIT;
			$request = $this->select_all($sql);

			if(count($request) > 0){
				for ($c=0; $c < count($request) ; $c++) { 
					$request[$c]['archiv_archivo'] = BASE_URL.'/Assets/files/uploads/'.$request[$c]['archiv_archivo'];		
				}
			}

			if (count($request) > 0 && $id_login > 0) {

				//OBTENER ARCHIVOS CON PERMISOS POR TIPO
				$sql2 = "SELECT * FROM usuario_archivo ua 
						INNER JOIN archivo a 
						ON ua.usuarc_id_archivo = a.id_archivo 
						WHERE 
						archiv_estado = 1 
						AND ua.usuarc_id_usuario = $id_login   
						AND a.archiv_id_tipo = $this->intIdTipo 
						AND a.archiv_acceso = 2 
						ORDER BY a.id_archivo DESC";
				$request2 = $this->select_all($sql2);

				$arrayResultante = [];
				

				if(count($request) > 0){

					$j=0;
					for ($i=0; $i < count($request) ; $i++) { 

						//SI ES PUBLICO :  se agrega
						if ($request[$i]['archiv_acceso']==1) {
							$arrayResultante[$j] = $request[$i];
							$j++;
						}
						else{
							for ($k=0; $k < count($request2) ; $k++) {
								if ($request[$i]['id_archivo'] == $request2[$k]['usuarc_id_archivo']) {
									$arrayResultante[$j] = $request[$i];
									$j++;
								}
							}
						}
					}
				}

				$request = array('id_tipo_archivo' => $this->intIdTipo,
									'tipo_archivo' => $this->strtipo_nombre,
									'registros' => $arrayResultante,
									'status' => true
								);
			}
			else{
				$request = array('id_tipo_archivo' => $this->intIdTipo,
									'tipo_archivo' => $this->strtipo_nombre,
									'registros' => $request,
									'status' => true
								);
			}

		}
		else{
			$request = array('status' => false);
		}
		return $request;
	}

	public function getTipos(){
		$sql = "SELECT * FROM tipo_archivo WHERE tiparc_estado = 1 AND tiparc_nombre NOT IN ('FOTOS', 'VIDEOS')";
		$request = $this->select_all($sql);
		return $request;
	}

	public function cantProdSearch(int $id_tipo, $busqueda){
		$this->intIdTipo = $id_tipo;

		$sql = "SELECT COUNT(*) as total_registro FROM archivo WHERE archiv_nombre ILIKE '%$busqueda%' AND archiv_id_tipo='{$this->intIdTipo}' AND archiv_estado = 1 ";
		$result_register = $this->select($sql);
		$total_registro = $result_register;
		return $total_registro;
	}

	public function getProdSearch(int $id_tipo, $busqueda, $desde, $porpagina){

		$this->intIdTipo = $id_tipo;

		$sql_tipo = "SELECT * FROM tipo_archivo WHERE id_tipo_archivo = '{$this->intIdTipo}'";
		$request = $this->select($sql_tipo);

		if(!empty($request)){
			$where = "";
			if(is_numeric($desde) AND is_numeric($porpagina)){
				$where = " LIMIT '$porpagina' OFFSET '$desde'";
			}

			$this->strTipo_nombre = $request['tiparc_nombre'];

			$sql = "SELECT  *
				FROM archivo 
				WHERE 
				archiv_estado = 1 
				AND archiv_nombre ILIKE '%$busqueda%' 
				AND archiv_id_tipo='{$this->intIdTipo}' 
				ORDER BY id_archivo DESC ".$where;

			$request = $this->select_all($sql);
			if(count($request) > 0){
				for ($c=0; $c < count($request); $c++) { 
					$request[$c]['archiv_archivo'] = BASE_URL.'/Assets/files/uploads/'.$request[$c]['archiv_archivo'];		
				}
			}
			$request = array( 'tipo' => $this->strTipo_nombre,
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