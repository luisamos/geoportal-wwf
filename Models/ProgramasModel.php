<?php 
class ProgramasModel extends PostgreSQL
{
	public $int_Id_programa;
	public $str_Progra_nombre;
	public $str_Progra_escripcion;

	public function __construct()
	{
		parent::__construct();
	}

	public function selectProgramas(){
		$whereAdmin = "";
		if($_SESSION['idUser'] != 1 ){
			$whereAdmin = " and id_programa != 1 ";
		}
		$sql = "SELECT * FROM programa WHERE progra_estado != 0".$whereAdmin;
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectPrograma(int $id_programa){
		$this->int_Id_programa = $id_programa;
		$sql = "SELECT * FROM programa WHERE id_programa = $this->int_Id_programa";
		$request = $this->select($sql);
		return $request;
	}

	public function insertPrograma(string $nombre, string $descripcion, int $estado, int $id_usuario){

		$return = "";
		$this->str_Progra_nombre = $nombre;
		$this->str_Progra_escripcion = $descripcion;

		$sql = "SELECT * FROM programa WHERE progra_nombre = '{$this->str_Progra_nombre}' ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$query_insert  = "INSERT INTO programa (progra_nombre,progra_descripcion,progra_estado, created, created_by) VALUES(?,?,?,?,?)";
        	$arrData = array($this->str_Progra_nombre,
			        	 $this->str_Progra_escripcion,
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

	public function updatePrograma(int $id_programa, string $nombre, string $descripcion, int $id_usuario){
		$this->int_Id_programa = $id_programa;
		$this->str_Progra_nombre = $nombre;
		$this->str_Progra_escripcion = $descripcion;

		$sql = "SELECT * FROM programa WHERE progra_nombre = '$this->str_Progra_nombre' AND id_programa != $this->int_Id_programa";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$sql = "UPDATE programa SET progra_nombre = ?,
					progra_descripcion = ?,
					updated = ?,
					updated_by = ? 
					WHERE id_programa = $this->int_Id_programa ";
			$arrData = array($this->str_Progra_nombre,
					$this->str_Progra_escripcion, 
					date("Y-m-d H:i:s"),
			    	$id_usuario);
			$request = $this->update($sql,$arrData);
		}else{
			$request = "exist";
		}
	    return $request;			
	}

	public function deletePrograma(int $id_programa){
		$this->int_Id_programa = $id_programa;
		$sql = "SELECT * FROM planoteca WHERE planot_id_tipo = $this->int_Id_programa";
		$request = $this->select_all($sql);
		if(empty($request))
		{
			$sql = "UPDATE programa SET progra_estado = ? WHERE id_programa = $this->int_Id_programa ";
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			if($request)
			{
				$request = 'ok';	
			}
			else{
				$request = 'error';
			}
		}
		else{
			$request = 'exist';
		}

		return $request;
	}


	public function cantDatos(){
			$sql = "SELECT COUNT(*) as total_registro FROM programa WHERE progra_estado = 1 ";
			$request = $this->select($sql);
			return $request;
	}

	public function getDatosPage($desde, $porpagina){
		$sql = "SELECT * FROM programa WHERE progra_estado = 1 ORDER BY id_programa DESC LIMIT '$porpagina' OFFSET '$desde'";
		$request =$request = $this->select_all($sql);
		return $request;
	}

}
?>