<?php 

	class PermisosModel extends PostgreSQL
	{
		public $int_Id_permiso;
		public $int_Permis_id_rol;
		public $int_Permis_id_modulo;
		public $int_Permis_estado;


		public function __construct()
		{
			parent::__construct();
		}

		public function selectModulos()
		{
			$sql = "SELECT * FROM modulo WHERE modulo_estado != 0 AND (modulo_descripcion<>NULL OR modulo_descripcion <> '')  ORDER BY id_modulo asc";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectPermisosRol(int $id_rol)
		{
			$this->int_Permis_id_rol = $id_rol;
			$sql = "SELECT * FROM permiso WHERE permis_id_rol = $this->int_Permis_id_rol";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deletePermisos(int $id_rol)
		{
			$this->int_Permis_id_rol = $id_rol;
			$sql = "DELETE FROM permiso WHERE permis_id_rol = $this->int_Permis_id_rol";
			$request = $this->delete($sql);
			return $request;
		}

		public function insertPermisos(int $id_rol, int $id_modulo, int $estado, int $id_usuario){
			$this->int_Permis_id_rol = $id_rol;
			$this->int_Permis_id_modulo = $id_modulo;
			$this->int_Permis_estado = $estado;
			
			$query_insert  = "INSERT INTO permiso (permis_id_rol, permis_id_modulo, permis_estado, created, created_by) 
								VALUES(?,?,?,?,?)";
        	$arrData = array($this->int_Permis_id_rol, 
        					$this->int_Permis_id_modulo, 
        					$this->int_Permis_estado,
        					date("Y-m-d H:i:s"),
    						$id_usuario);

        	$request_insert = $this->insert($query_insert,$arrData);		
	        return $request_insert;
		}

		public function permisosModulo(int $id_rol){
			$this->int_Permis_id_rol = $id_rol;
			$sql = "SELECT p.permis_id_rol,
						   p.permis_id_modulo,
						   m.modulo_titulo as modulo,
						   p.permis_estado 
					FROM permiso p 
					INNER JOIN modulo m 
					ON p.permis_id_modulo = m.id_modulo
					WHERE p.permis_id_rol = $this->int_Permis_id_rol";
			$request = $this->select_all($sql);
			$arrPermisos = array();
			for ($i=0; $i < count($request); $i++) { 
				$arrPermisos[$request[$i]['permis_id_modulo']] = $request[$i];
			}
			return $arrPermisos;
		}
	}
 ?>