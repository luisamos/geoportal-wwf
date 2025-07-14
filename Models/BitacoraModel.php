<?php 
class BitacoraModel extends PostgreSQL
{
	public $int_Id_bitacora;
	public $int_Bitaco_id_proy;
	public $int_Bitaco_id_elemento;
	public $str_Bitaco_campo1;
	public $str_Bitaco_campo2;
	public $str_Bitaco_campo3;
	public $str_Bitaco_id_ref;
	

	public function __construct()
	{
		parent::__construct();
	}

	public function selectBitacora(int $id_bitacora){
		$this->int_Id_bitacora = $id_bitacora;
		$sql = "SELECT * FROM bitacora WHERE id_bitacora = $this->int_Id_bitacora";
		$request = $this->select($sql);
		return $request;
	}

	public function insertBitacora(int $id_proy, int $id_elemento, string $campo1, string $campo2, string $campo3, int $id_ref, int $id_usuario){

		$this->int_Bitaco_id_proy = $id_proy;
		$this->int_Bitaco_id_elemento = $id_elemento;
		$this->str_Bitaco_campo1 = $campo1;
		$this->str_Bitaco_campo2 = $campo2;
		$this->str_Bitaco_campo3 = $campo3;
		$this->str_Bitaco_id_ref = $id_ref;		

		$request ="";

		if (empty($this->int_Bitaco_id_proy)) {
			$sql = "SELECT * FROM bitacora WHERE bitaco_campo1 = '{$this->str_Bitaco_campo1}' ";
			$request = $this->select_all($sql);
		}
		

		if(empty($request))
		{
			$query_insert  = "INSERT INTO bitacora ( bitaco_id_proy, bitaco_id_elemento, bitaco_campo1, bitaco_campo2, bitaco_campo3, bitaco_id_ref, bitaco_estado, created, created_by) 
				VALUES(?,?,?,?,?,?,?,?,?)";
        	$arrData = array(
        				$this->int_Bitaco_id_proy,
        				$this->int_Bitaco_id_elemento,
        				$this->str_Bitaco_campo1,
			        	$this->str_Bitaco_campo2,
			        	$this->str_Bitaco_campo3,
			        	$this->str_Bitaco_id_ref,
			        	1,
			        	date("Y-m-d H:i:s"),
			    		$id_usuario);
        	$request_insert = $this->insert($query_insert,$arrData);
        	$return = $request_insert;
		}
		else{
			$return = "exist";
		}
		return $return;
	}	

	public function updateBitacora(int $id_bitacora, int $id_elemento, string $campo1, string $campo2, string $campo3, int $id_ref, int $id_usuario){

		$this->int_Id_bitacora = $id_bitacora;
		$this->int_Bitaco_id_elemento = $id_elemento;
		$this->str_Bitaco_campo1 = $campo1;
		$this->str_Bitaco_campo2 = $campo2;
		$this->str_Bitaco_campo3 = $campo3;
		$this->int_Bitaco_id_ref = $id_ref;

			$sql = "UPDATE bitacora 
					SET 
					bitaco_id_elemento = ?,
					bitaco_campo1 = ?,
					bitaco_campo2 = ?,
					bitaco_campo3 = ?,
					bitaco_id_ref = ?,
					updated = ?,
					updated_by = ? 
					WHERE id_bitacora = $this->int_Id_bitacora ";

			$arrData = array(
					$this->int_Bitaco_id_elemento,
					$this->str_Bitaco_campo1,
					$this->str_Bitaco_campo2,
					$this->str_Bitaco_campo3,
					$this->int_Bitaco_id_ref,
					date("Y-m-d H:i:s"),
			    	$id_usuario);
			$request = $this->update($sql,$arrData);
			if ($request) {
				$request = 1;
			}
	    return $request;			
	}

	public function deleteBitacora(int $id_bitacora){
		$this->int_Id_bitacora = $id_bitacora;
		
		$sql = "UPDATE bitacora SET bitaco_estado = ? WHERE id_bitacora = $this->int_Id_bitacora ";
		$arrData = array(0);
		$request = $this->update($sql,$arrData);
		if($request)
		{
			$request = 'ok';	
		}
		else{
			$request = 'error';
		}
		
		return $request;
	}


	public function cantDatos(){
			$sql = "SELECT COUNT(*) as total_registro FROM bitacora WHERE bitaco_estado = 1 ";
			$request = $this->select($sql);
			return $request;
	}

	public function getDatosPage($desde, $porpagina){
		$sql = "SELECT * FROM bitacora b
				INNER JOIN usuario u 
				ON b.created_by = u.id_usuario 
				WHERE bitaco_estado = 1 
				ORDER BY b.id_bitacora DESC LIMIT '$porpagina' OFFSET '$desde'";
		$request =$request = $this->select_all($sql);
		return $request;
	}

	public function getDatos(){
		$sql = "SELECT ST_AsGeoJSON(st_astext(geom)),".BITAC_ATRIBUTOS." FROM ".BITAC_ESQUEMA.".".BITAC_TABLA;
		$request =$request = $this->select_all($sql);
		return $request;
	}

}
?>