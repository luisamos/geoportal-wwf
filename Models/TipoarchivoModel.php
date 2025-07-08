<?php 
class TipoarchivoModel extends PostgreSQL
{
	public $int_Id_tipo_archivo;
	public $str_Tiparc_nombre;
	public $str_Tiparc_escripcion;

	public function __construct()
	{
		parent::__construct();
	}

	public function selectTipo_Archivos(){
		$whereAdmin = "";
		if($_SESSION['idUser'] != 1 ){
			$whereAdmin = " and id_tipo_archivo != 1 ";
		}
		$sql = "SELECT * FROM tipo_archivo WHERE tiparc_estado != 0".$whereAdmin;
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectTipo_Archivo(int $id_tipo_archivo){
		$this->int_Id_tipo_archivo = $id_tipo_archivo;
		$sql = "SELECT * FROM tipo_archivo WHERE id_tipo_archivo = $this->int_Id_tipo_archivo";
		$request = $this->select($sql);
		return $request;
	}

	public function insertTipo_Archivo(string $nombre, int $id_usuario){

		$return = "";
		$this->str_Tiparc_nombre = $nombre;

		$sql = "SELECT * FROM tipo_archivo WHERE tiparc_nombre = '{$this->str_Tiparc_nombre}' ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$query_insert  = "INSERT INTO tipo_archivo (tiparc_nombre,tiparc_estado, created, created_by) VALUES(?,?,?,?)";
        	$arrData = array($this->str_Tiparc_nombre,
			        	 1,
			        	 date("Y-m-d H:i:s"),
			    		 $id_usuario);
        	$request_insert = $this->insert($query_insert,$arrData);
        	$return = $request_insert;
		}else{
			$return = "exist";
		}
		return $return;
	}	

	public function updateTipo_Archivo(int $id_tipo_archivo, string $nombre, int $id_usuario){
		$this->int_Id_tipo_archivo = $id_tipo_archivo;
		$this->str_Tiparc_nombre = $nombre;

		$sql = "SELECT * FROM tipo_archivo WHERE tiparc_nombre = '$this->str_Tiparc_nombre' AND id_tipo_archivo != $this->int_Id_tipo_archivo";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$sql = "UPDATE tipo_archivo SET tiparc_nombre = ?,
					updated = ?,
					updated_by = ? 
					WHERE id_tipo_archivo = $this->int_Id_tipo_archivo ";
			$arrData = array($this->str_Tiparc_nombre,
					date("Y-m-d H:i:s"),
			    	$id_usuario);
			$request = $this->update($sql,$arrData);
		}else{
			$request = "exist";
		}
	    return $request;			
	}

	public function deleteTipo_Archivo(int $id_tipo_archivo){
		$this->int_Id_tipo_archivo = $id_tipo_archivo;
		$sql = "UPDATE tipo_archivo SET tiparc_estado = ? WHERE id_tipo_archivo = $this->int_Id_tipo_archivo ";
		$arrData = array(0);
		$request = $this->update($sql,$arrData);
		return $request;
	}


	public function cantDatos(){
			$sql = "SELECT COUNT(*) as total_registro FROM tipo_archivo WHERE tiparc_estado = 1 ";
			$request = $this->select($sql);
			return $request;
	}

	public function getDatosPage($desde, $porpagina){
		$sql = "SELECT * FROM tipo_archivo WHERE tiparc_estado = 1 ORDER BY id_tipo_archivo DESC LIMIT '$porpagina' OFFSET '$desde'";
		$request =$request = $this->select_all($sql);
		return $request;
	}

}
?>