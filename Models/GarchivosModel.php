<?php 
class GarchivosModel extends PostgreSQL
{
	public $int_Id_archivo;
	public $int_Archiv_id_tipo;
	public $str_Archiv_codigo;
	public $str_Archiv_nombre;
	public $str_Archiv_archivo;
	public $int_Archiv_acceso;

	public function __construct()
	{
		parent::__construct();
	}

	public function inserArchivo(string $id_tipo, string $codigo, string $nombre, string $archivo, int $acceso, int $id_usuario){

		$return = 0;
		$this->int_Archiv_id_tipo = $id_tipo;
		$this->str_Archiv_codigo = $codigo;
		$this->str_Archiv_nombre = $nombre;
		$this->str_Archiv_archivo = $archivo;
		$this->int_Archiv_acceso = $acceso;

		$sql = "SELECT * FROM archivo WHERE archiv_codigo = '{$this->str_Archiv_codigo}' ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$query_insert  = "INSERT INTO Archivo(archiv_id_tipo,archiv_codigo,archiv_nombre,archiv_archivo,archiv_acceso,archiv_estado,created, created_by) 
								VALUES(?,?,?,?,?,?,?,?)";
        	$arrData = array($this->int_Archiv_id_tipo, 
							$this->str_Archiv_codigo,
							$this->str_Archiv_nombre,
							$this->str_Archiv_archivo, 
							$this->int_Archiv_acceso,
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

	public function selectArchivos(){
		$sql = "SELECT * FROM archivo WHERE archiv_estado = 1";
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectArchivo(int $id_archivo){
		$this->int_Id_archivo = $id_archivo;
		$sql = "SELECT * FROM archivo
				WHERE archiv_estado = 1 AND id_archivo = $this->int_Id_archivo";
		$request = $this->select($sql);
		return $request;
	}

	public function updateArchivo(int $id_archivo, int $id_tipo, string $codigo, string $nombre, string $archivo, int $acceso, int $id_usuario){

		$this->int_Id_archivo = $id_archivo;
		$this->int_Archiv_id_tipo = $id_tipo;
		$this->str_Archiv_codigo = $codigo;
		$this->str_Archiv_nombre = $nombre;
		$this->str_Archiv_archivo = $archivo;
		$this->int_Archiv_acceso = $acceso;

		$sql = "SELECT * FROM archivo WHERE archiv_codigo = '{$this->str_Archiv_codigo}' AND id_archivo != $this->int_Id_archivo";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$sql = "UPDATE archivo SET archiv_id_tipo = ?, archiv_codigo = ?, archiv_nombre = ?, archiv_archivo = ?, archiv_acceso = ?, updated = ?,
					updated_by = ? WHERE id_archivo = $this->int_Id_archivo ";
			$arrData = array($this->int_Archiv_id_tipo, 
							 $this->str_Archiv_codigo,
							 $this->str_Archiv_nombre,
							 $this->str_Archiv_archivo, 
							 $this->int_Archiv_acceso,
							 date("Y-m-d H:i:s"),
							 $id_usuario);
			$request = $this->update($sql,$arrData);
		}else{
			$request = "exist";
		}
	    return $request;			
	}

	public function deleteArchivo(int $id_archivo){
		$this->int_Id_archivo = $id_archivo;
		
		$sql = "UPDATE archivo SET archiv_estado = ? WHERE id_archivo = $this->int_Id_archivo ";
		$arrData = array(0);
		$request = $this->update($sql,$arrData);
		if($request)
		{
			$request = 'ok';	
		}else{
			$request = 'error';
		}
		return $request;
	}
}
?>