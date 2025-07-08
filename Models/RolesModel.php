<?php 
class RolesModel extends PostgreSQL
{
	public $int_Id_rol;
	public $str_Rol_nombre;
	public $str_Rol_escripcion;
	public $int_Rol_estado;

	public function __construct()
	{
		parent::__construct();
	}

	public function selectRoles(){
		$whereAdmin = "";
		if($_SESSION['idUser'] != 1 ){
			$whereAdmin = " and id_rol != 1 ";
		}
		//EXTRAE ROLES
		$sql = "SELECT * FROM rol WHERE rol_estado = 1 ".$whereAdmin;
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectRol(int $id_rol){
		$this->int_Id_rol = $id_rol;
		$sql = "SELECT * FROM rol WHERE rol_estado = 1 AND id_rol = $this->int_Id_rol";
		$request = $this->select($sql);
		return $request;
	}

	public function insertRol(string $rol, string $descripcion, int $id_usuario){

		$return = "";
		$this->str_Rol_nombre = $rol;
		$this->str_Rol_escripcion = $descripcion;

		$sql = "SELECT * FROM rol WHERE rol_nombre = '{$this->str_Rol_nombre}' ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$query_insert  = "INSERT INTO rol (rol_nombre,rol_descripcion,rol_estado,created, created_by) VALUES(?,?,?,?,?)";
        	$arrData = array($this->str_Rol_nombre,
        					$this->str_Rol_escripcion,
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

	public function updateRol(int $id_rol, string $rol, string $descripcion, int $id_usuario){
		$this->int_Id_rol = $id_rol;
		$this->str_Rol_nombre = $rol;
		$this->str_Rol_escripcion = $descripcion;

		$sql = "SELECT * FROM rol WHERE rol_nombre = '$this->str_Rol_nombre' AND id_rol != $this->int_Id_rol";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$sql = "UPDATE rol SET rol_nombre = ?,
					rol_descripcion = ?,
					updated = ?,
					updated_by = ?
					WHERE id_rol = $this->int_Id_rol ";

			$arrData = array($this->str_Rol_nombre,
							$this->str_Rol_escripcion,
							date("Y-m-d H:i:s"),
							$id_usuario
							);
			$request = $this->update($sql,$arrData);
		}else{
			$request = "exist";
		}
	    return $request;			
	}

	public function deleteRol(int $id_rol){
		$this->int_Id_rol = $id_rol;
		$sql = "SELECT * FROM usuario WHERE usuari_id_rol = $this->int_Id_rol";
		$request = $this->select_all($sql);
		if(empty($request))
		{
			$sql = "UPDATE rol SET rol_estado = ? WHERE id_rol = $this->int_Id_rol ";
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
			$sql = "SELECT COUNT(*) as total_registro FROM rol WHERE rol_estado = 1 ";
			$request = $this->select($sql);
			return $request;
	}

	public function getDatosPage($desde, $porpagina){
		$sql = "SELECT * FROM rol WHERE rol_estado = 1 ORDER BY id_rol DESC LIMIT '$porpagina' OFFSET '$desde'";
		$request =$request = $this->select_all($sql);
		return $request;
	}

}
?>