<?php
class PArchivosCampo extends PostgreSQL
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
			$where = " AND arccam_id_tipo = ".$id_tipo;
		}
		$sql = "SELECT COUNT(*) as total_registro FROM archivo_campo WHERE arccam_estado = 1 ".$where;
		$request = $this->select($sql);
		return $request;
	}

	public function getDatosPage($desde, $porpagina){
		$sql = "SELECT * FROM archivo_campo ar 
		INNER JOIN tipo_archivo ta 
		ON ar.arccam_id_tipo = ta.id_tipo_archivo 
		WHERE arccam_estado = 1 
		ORDER BY id_archivo_campo DESC LIMIT '$porpagina' OFFSET '$desde'";
		$request =$request = $this->select_all($sql);
		if(count($request) > 0){
			for ($c=0; $c < count($request) ; $c++) { 
				$request[$c]['arccam_archivo'] = BASE_URL.'/Assets/files/uploads/'.$request[$c]['arccam_archivo'];		
			}
		}
		return $request;
	}

	public function getRegistrosxTipo(int $id_tipo, $desde = null, $porpagina = null){
		
		$this->intIdTipo = $id_tipo;
		$where = "";
		if(is_numeric($desde) AND is_numeric($porpagina)){
			$where = " LIMIT '$porpagina' OFFSET '$desde'";
		}
		$sql_tipo_archivo = "SELECT * FROM tipo_archivo WHERE id_tipo_archivo = '{$this->intIdTipo}'";
		$request = $this->select($sql_tipo_archivo);

		if(!empty($request)){

			$this->strtipo_nombre = $request['tiparc_nombre'];
			$sql = "SELECT * FROM archivo_campo ar 
					INNER JOIN tipo_archivo ta 
					ON ar.arccam_id_tipo = ta.id_tipo_archivo
					WHERE 
					ar.arccam_estado = 1 
					AND ar.arccam_id_tipo = $this->intIdTipo  
					ORDER BY ar.id_archivo_campo DESC ".$where;
			$request = $this->select_all($sql);
			if(count($request) > 0){
				for ($c=0; $c < count($request) ; $c++) { 
					$request[$c]['arccam_archivo'] = BASE_URL.'/Assets/files/uploads/'.$request[$c]['arccam_archivo'];		
				}
			}
			$request = array('id_tipo_archivo' => $this->intIdTipo,
								'tipo_archivo' => $this->strtipo_nombre,
								'registros' => $request,
								'status' => true
							);
		}
		else{
			$request = array('status' => false);
		}
		return $request;
	}

	public function getTipos(){
		$sql = "SELECT * FROM tipo_archivo WHERE tiparc_estado = 1 AND (tiparc_nombre = 'FOTOS' OR tiparc_nombre = 'VIDEOS')";
		$request = $this->select_all($sql);
		return $request;
	}

	public function cantProdSearch(int $id_tipo, $busqueda){
		$this->intIdTipo = $id_tipo;

		$sql = "SELECT COUNT(*) as total_registro 
				FROM archivo_campo 
				WHERE arccam_nombre ILIKE '%$busqueda%' 
				AND arccam_id_tipo='{$this->intIdTipo}' 
				AND arccam_estado = 1 ";
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
				FROM archivo_campo ar 
				INNER JOIN tipo_archivo ta 
				ON ar.arccam_id_tipo = ta.id_tipo_archivo 
				WHERE 
				ar.arccam_estado = 1 AND 
				ar.arccam_nombre ILIKE '%$busqueda%' 
				AND ar.arccam_id_tipo='{$this->intIdTipo}' 
				ORDER BY ar.id_archivo_campo DESC ".$where;

			$request = $this->select_all($sql);
			if(count($request) > 0){
				for ($c=0; $c < count($request); $c++) { 
					$request[$c]['arccam_archivo'] = BASE_URL.'/Assets/files/uploads/'.$request[$c]['arccam_archivo'];		
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

	public function gestionarEsquema($esquema_nombre, $accion, $esquema_actual)
    {
        $sql = "SELECT estado, mensaje FROM public.gestionar_esquema('$esquema_nombre', '$accion', '$esquema_actual');";
        $request = $this->select_all($sql);

        if (empty($request)) {
            return json_encode(["estado" => "error", "mensaje" => "Sin respuesta de la base de datos"]);
        }

        return json_encode([
            "estado"  => trim($request[0]['estado']),
            "mensaje" => trim($request[0]['mensaje'])
        ]);
    }
}
?>