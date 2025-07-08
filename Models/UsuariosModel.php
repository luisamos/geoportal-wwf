<?php
	class UsuariosModel extends PostgreSQL
	{
		private $int_Id_usuario;
		private $int_Usuari_id_persona;
		private $str_Usuari_nombre;
		private $str_Usuari_clave;
		private $str_Usuari_id_rol;
		private $int_Usuari_estado;

		public function __construct()
		{
			parent::__construct();
		}

		public function insertUsuario(string $id_persona, string $nombre, string $clave, int $id_rol, int $id_usuario){

			$return = 0;
			$this->int_Usuari_id_persona = $id_persona;
			$this->str_Usuari_nombre = $nombre;
			$this->str_Usuari_clave = $clave;
			$this->str_Usuari_id_rol = $id_rol;

			$sql = "SELECT * FROM usuario WHERE usuari_nombre = '$this->str_Usuari_nombre'";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO usuario (usuari_id_persona, usuari_nombre, usuari_clave, usuari_id_rol,usuari_estado, created, created_by) 
								  VALUES(?,?,?,?,?,?,?)";
	        	$arrData = array($this->int_Usuari_id_persona,
        						$this->str_Usuari_nombre,
        						$this->str_Usuari_clave,
        						$this->str_Usuari_id_rol,
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

		public function selectUsuarios(){
			$whereAdmin = "";
			if($_SESSION['idUser'] != 1 ){
				$whereAdmin = "WHERE id_usuario != 1 ";
			}
			$sql = "SELECT u.id_usuario,u.usuari_nombre, u.usuari_id_persona, u.usuari_id_rol, 
					(p.person_nombres || ' ' || p.person_apellidos) as person_nombres, p.person_num_documento  
					FROM usuario u 
					INNER JOIN persona p 
					ON u.usuari_id_persona = p.id_persona
					 ".$whereAdmin;
					$request = $this->select_all($sql);
					return $request;
		}

		public function selectUsuario(int $id_usuario){
			$this->int_Id_usuario = $id_usuario;
			$sql = "SELECT u.id_usuario,u.usuari_nombre, TO_CHAR(u.created, 'dd-Mon-YYYY') as fechaRegistro, u.usuari_id_persona, u.usuari_id_rol, 
				p.person_nombres, p.person_apellidos, p.person_num_documento 
					FROM usuario u 
					INNER JOIN persona p 
					ON u.usuari_id_persona = p.id_persona 
					WHERE u.id_usuario = $this->int_Id_usuario";
			$request = $this->select($sql);
			return $request;
		}

		public function updateUsuario(int $id_usuario, int $id_persona, string $nombre, string $clave, int $id_rol, int $idusuario){

			$this->int_Id_usuario = $id_usuario;
			$this->int_Usuari_id_persona = $id_persona;
			$this->str_Usuari_nombre = $nombre;
			$this->str_Usuari_clave = $clave;
			$this->str_Usuari_id_rol = $id_rol;

			if($this->str_Usuari_clave  != "")
			{
				$sql = "UPDATE usuario SET usuari_id_persona=?, usuari_nombre=?, usuari_clave=?, usuari_id_rol=?, updated = ?, updated_by = ?
						WHERE id_usuario = $this->int_Id_usuario";
				$arrData = array($this->int_Usuari_id_persona,
        						$this->str_Usuari_nombre,
        						$this->str_Usuari_clave,
        						$this->str_Usuari_id_rol,
        						date("Y-m-d H:i:s"),
								$idusuario
        						);
			}
			else{
				$sql = "UPDATE usuario SET usuari_id_persona=?, usuari_nombre=?, usuari_id_rol=?, updated = ?, updated_by = ? 
						WHERE id_usuario = $this->int_Id_usuario ";
				$arrData = array($this->int_Usuari_id_persona,
        						$this->str_Usuari_nombre,
        						$this->str_Usuari_id_rol,
        						date("Y-m-d H:i:s"),
								$idusuario);
			}
			$request = $this->update($sql,$arrData);
			
			return $request;
		
		}

		public function deleteUsuario(int $intId_usuario)
		{
			$this->int_Id_usuario = $intId_usuario;
			$sql = "UPDATE usuario SET usuari_estado = ? WHERE id_usuario = $this->int_Id_usuario;";
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function cantDatos(){

			$sql = "SELECT COUNT(*) as total_registro FROM usuario";
			$request = $this->select($sql);
			return $request;
		}

		public function getDatosPage($desde, $porpagina){
			$sql = "SELECT u.*, p.person_nombres, p.person_apellidos, r.rol_nombre 
					FROM usuario u 
					INNER JOIN persona p 
					ON u.usuari_id_persona = p.id_persona 
					LEFT JOIN rol r 
					ON u.usuari_id_rol = r.id_rol 
					ORDER BY id_usuario 
					DESC LIMIT '$porpagina' OFFSET '$desde'";
			$request =$request = $this->select_all($sql);
			return $request;
		}

		public function habilitar(int $intId_usuario)
		{
			$this->int_Id_usuario = $intId_usuario;
			$sql = "UPDATE public.usuario 
				SET usuari_estado = CASE 
					WHEN usuari_estado = 1 THEN 0 
					ELSE 1 
				END 
				WHERE id_usuario = ? RETURNING usuari_estado;";
			$arrData = array($this->int_Id_usuario);
			$request = $this->updateOne($sql, $arrData);
			return $request;
		}
	}
 ?>