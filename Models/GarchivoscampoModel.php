<?php 
class GarchivoscampoModel extends PostgreSQL
{
	public $int_Id_archivo_campo;
	public $int_Arccam_id_tipo;
	public $str_Arccam_nombre;
	public $str_Arccam_archivo;
	public $int_Arccam_estado;

	public function __construct()
	{
		parent::__construct();
	}

	public function inserArchivo(string $id_tipo, string $nombre, string $archivo, int $id_usuario){

		$return = 0;
		$this->int_Arccam_id_tipo = $id_tipo;
		$this->str_Arccam_nombre = $nombre;
		$this->str_Arccam_archivo = $archivo;

		$sql = "SELECT * FROM archivo_campo WHERE arccam_nombre = '{$this->str_Arccam_nombre}' ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$query_insert  = "INSERT INTO archivo_campo (arccam_id_tipo,arccam_nombre,arccam_archivo,arccam_estado,created, created_by) 
								VALUES(?,?,?,?,?,?)";
        	$arrData = array($this->int_Arccam_id_tipo, 
							$this->str_Arccam_nombre,
							$this->str_Arccam_archivo, 
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
		$sql = "SELECT * FROM archivo_campo";
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectArchivo(int $id_archivo_campo){
		$this->int_Id_archivo_campo = $id_archivo_campo;
		$sql = "SELECT * FROM archivo_campo
				WHERE id_archivo_campo = $this->int_Id_archivo_campo";
		$request = $this->select($sql);
		return $request;
	}

	public function updateArchivo(int $id_archivo_campo, int $id_tipo, string $nombre, string $archivo, int $id_usuario){

		$this->int_Id_archivo_campo = $id_archivo_campo;
		$this->int_Arccam_id_tipo = $id_tipo;
		$this->str_Arccam_nombre = $nombre;
		$this->str_Arccam_archivo = $archivo;

		$sql = "SELECT * FROM archivo_campo WHERE arccam_nombre = '{$this->str_Arccam_nombre}' AND id_archivo_campo != $this->int_Id_archivo_campo";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$sql = "UPDATE archivo_campo 
					SET arccam_id_tipo = ?, arccam_nombre = ?, arccam_archivo = ?, 
					updated = ?, 
					updated_by = ? 
					WHERE id_archivo_campo = $this->int_Id_archivo_campo ";
			$arrData = array($this->int_Arccam_id_tipo, 
							 $this->str_Arccam_nombre,
							 $this->str_Arccam_archivo, 
							 date("Y-m-d H:i:s"),
							 $id_usuario);
			$request = $this->update($sql,$arrData);
		}else{
			$request = "exist";
		}
	    return $request;			
	}

	public function deleteArchivo(int $id_archivo_campo){
		$this->int_Id_archivo_campo = $id_archivo_campo;
		
		$sql = "UPDATE archivo_campo SET arccam_estado = ? WHERE id_archivo_campo = $this->int_Id_archivo_campo ";
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