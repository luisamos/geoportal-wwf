<?php 
class GpaisajesModel extends PostgreSQL
{
	public $int_Id_paisaje;
	public $str_Paisaj_paisaje;
	public $str_Paisaj_estrategia;
	public $str_Paisaj_objetivo;
	public $str_Paisaj_meta1;
	public $str_Paisaj_meta2;
	public $str_Paisaj_meta3;
	public $str_Paisaj_meta4;
	public $str_Paisaj_meta5;
	public $str_Paisaj_indicador;
	public $int_Paisaj_id_ref;

	public function __construct()
	{
		parent::__construct();
	}

	public function selectPaisaje(int $id_paisaje){
		$this->int_Id_paisaje = $id_paisaje;
		$sql = "SELECT * FROM paisaje WHERE id_paisaje = $this->int_Id_paisaje";
		$request = $this->select($sql);
		return $request;
	}

	public function insertPaisaje(string $paisaje, string $estrategia, string $objetivo, 
		string $meta1,string $meta2,string $meta3,string $meta4,string $meta5,
		int $indicador, int $id_ref, int $id_usuario){

		$this->str_Paisaj_paisaje = $paisaje;
		$this->str_Paisaj_estrategia = $estrategia;
		$this->str_Paisaj_objetivo = $objetivo;
		$this->str_Paisaj_meta1 = $meta1;
		$this->str_Paisaj_meta2 = $meta2;
		$this->str_Paisaj_meta3 = $meta3;
		$this->str_Paisaj_meta4 = $meta4;
		$this->str_Paisaj_meta5 = $meta5;
		$this->str_Paisaj_indicador = $indicador;
		$this->int_Paisaj_id_ref = $id_ref;

		$request ="";

		if (empty($this->int_Paisaj_id_ref)) {
			$sql = "SELECT * FROM paisaje WHERE paisaj_paisaje = '{$this->str_Paisaj_paisaje}' ";
			$request = $this->select_all($sql);
		}
		


		if(empty($request))
		{
			$query_insert  = "INSERT INTO paisaje (paisaj_paisaje, paisaj_estrategia, paisaj_objetivo, paisaj_meta1, paisaj_meta2, paisaj_meta3, paisaj_meta4, paisaj_meta5, paisaj_indicador, paisaj_id_ref, paisaj_estado, created, created_by) 
				VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        	$arrData = array($this->str_Paisaj_paisaje,
			        	 $this->str_Paisaj_estrategia,
			        	 $this->str_Paisaj_objetivo,
			        	 $this->str_Paisaj_meta1,
			        	 $this->str_Paisaj_meta2,
			        	 empty($this->str_Paisaj_meta3) ? null : $this->str_Paisaj_meta3,
			        	 empty($this->str_Paisaj_meta4) ? null : $this->str_Paisaj_meta4,
			        	 empty($this->str_Paisaj_meta5) ? null : $this->str_Paisaj_meta5,
			        	 $this->str_Paisaj_indicador,
			        	 empty($this->int_Paisaj_id_ref) ? null : $this->int_Paisaj_id_ref,
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

	public function updatePaisaje(int $id_paisaje, string $paisaje, string $estrategia, 
		string $objetivo, 
		string $meta1,string $meta2,string $meta3,string $meta4,string $meta5,
		int $indicador, int $id_ref, int $id_usuario){

		$this->int_Id_paisaje = $id_paisaje;
		$this->str_Paisaj_paisaje = $paisaje;
		$this->str_Paisaj_estrategia = $estrategia;
		$this->str_Paisaj_objetivo = $objetivo;
		$this->str_Paisaj_meta1 = $meta1;
		$this->str_Paisaj_meta2 = $meta2;
		$this->str_Paisaj_meta3 = $meta3;
		$this->str_Paisaj_meta4 = $meta4;
		$this->str_Paisaj_meta5 = $meta5;
		$this->str_Paisaj_indicador = $indicador;
		$this->int_Paisaj_id_ref = $id_ref;

			$sql = "UPDATE paisaje 
					SET 
					paisaj_paisaje = ?,
					paisaj_estrategia = ?,
					paisaj_objetivo = ?,
					paisaj_meta1 = ?,
					paisaj_meta2 = ?,
					paisaj_meta3 = ?,
					paisaj_meta4 = ?,
					paisaj_meta5 = ?,
					paisaj_indicador = ?,
					updated = ?,
					updated_by = ? 
					WHERE id_paisaje = $this->int_Id_paisaje ";

			$arrData = array(
					$this->str_Paisaj_paisaje,
					$this->str_Paisaj_estrategia,
					$this->str_Paisaj_objetivo,
					$this->str_Paisaj_meta1,
					$this->str_Paisaj_meta2,
					empty($this->str_Paisaj_meta3) ? null : $this->str_Paisaj_meta3,
					empty($this->str_Paisaj_meta4) ? null : $this->str_Paisaj_meta4,
					empty($this->str_Paisaj_meta5) ? null : $this->str_Paisaj_meta5,
					$this->str_Paisaj_indicador,
					date("Y-m-d H:i:s"),
			    	$id_usuario);
			$request = $this->update($sql,$arrData);
			if ($request) {
				$request = 1;
			}
	    return $request;			
	}

	public function deletePaisaje(int $id_paisaje){
		$this->int_Id_paisaje = $id_paisaje;
		
		$sql = "UPDATE paisaje SET paisaj_estado = ? WHERE id_paisaje = $this->int_Id_paisaje ";
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
			$sql = "SELECT COUNT(*) as total_registro FROM paisaje WHERE paisaj_estado = 1 ";
			$request = $this->select($sql);
			return $request;
	}

	public function getDatosPage($desde, $porpagina){
		$sql = "SELECT * FROM paisaje WHERE paisaj_estado = 1 ORDER BY id_paisaje DESC LIMIT '$porpagina' OFFSET '$desde'";
		$request =$request = $this->select_all($sql);
		return $request;
	}

}
?>