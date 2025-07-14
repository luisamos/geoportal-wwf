<?php 

	class UsuarioarchivoModel extends PostgreSQL
	{
		private $int_Id_usuario_archivo;
		private $int_Usuarc_id_usuario;
		private $int_Usuarc_id_archivo;
		

		public function __construct()
		{
			parent::__construct();
		}	

		public function insertUsuarioArchivo(int $id_usuario, int $id_archivo, int $idusuario){

			$return = 0;
			$this->int_Usuarc_id_usuario = $id_usuario;
			$this->int_Usuarc_id_archivo = $id_archivo;

			$sql = "SELECT * FROM usuario_archivo WHERE usuarc_id_archivo = '$this->int_Usuarc_id_archivo'";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO usuario_archivo (usuarc_id_usuario, usuarc_id_archivo, created, created_by) 
								  VALUES(?,?,?,?)";
	        	$arrData = array($this->int_Usuarc_id_usuario,
        						$this->int_Usuarc_id_archivo,
        						date("Y-m-d H:i:s"),
    							$idusuario);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}
			else{
				$return = "exist";
			}
	        return $return;
		}

		public function deleteUsuarioArchivo(int $id_usuario_archivo)
		{
			$this->int_Id_usuario_archivo = $id_usuario_archivo;
			$sql = "DELETE FROM usuario_archivo WHERE id_usuario_archivo = $this->int_Id_usuario_archivo ";
			$request = $this->delete($sql);
			return $request;
		}


		public function cantDatos(){

			$sql = "SELECT COUNT(*) as total_registro FROM usuario_archivo";
			$request = $this->select($sql);
			return $request;
		}

		public function getDatosPage($desde, $porpagina){
			$sql = "SELECT * 
					FROM usuario_archivo ua 
					INNER JOIN usuario u  
					ON ua.usuarc_id_usuario = u.id_usuario
					INNER JOIN persona p 
					ON u.usuari_id_persona = p.id_persona 
					INNER JOIN archivo a  
					ON ua.usuarc_id_archivo = a.id_archivo 
					INNER JOIN tipo_archivo ta 
					ON a.archiv_id_tipo = ta.id_tipo_archivo 
					ORDER BY u.id_usuario DESC LIMIT '$porpagina' OFFSET '$desde'";
			$request =$request = $this->select_all($sql);
			return $request;
		}



		public function getUsuario_nombre(string $nom_usuario){
			$sql = "SELECT * FROM usuario u 
			INNER JOIN persona p 
			ON u.usuari_id_persona = p.id_persona 
			WHERE u.usuari_nombre = '$nom_usuario'";
			$request = $this->select($sql);
			return $request;
		}

		public function getArchivo_codigo(string $codigo){
			$sql = "SELECT * FROM archivo 
			WHERE archiv_codigo = '$codigo' AND archiv_acceso=2";
			$request = $this->select($sql);
			return $request;
		}

	}
 ?>