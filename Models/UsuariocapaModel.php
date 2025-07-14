<?php 

	class UsuariocapaModel extends PostgreSQL
	{
		private $int_Id_usuario_capa;
		private $int_Usucap_id_usuario;
		private $int_Usucap_id_capa;
		

		public function __construct()
		{
			parent::__construct();
		}	

		public function insertUsuarioCapa(int $id_usuario, int $id_capa, int $idusuario){

			$return = 0;
			$this->int_Usucap_id_usuario = $id_usuario;
			$this->int_Usucap_id_capa = $id_capa;

			$sql = "SELECT * FROM usuario_capa WHERE usucap_id_capa = '$this->int_Usucap_id_capa'";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO usuario_capa (usucap_id_usuario, usucap_id_capa, created, created_by) 
								  VALUES(?,?,?,?)";
	        	$arrData = array($this->int_Usucap_id_usuario,
        						$this->int_Usucap_id_capa,
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

		public function deleteUsuarioCapa(int $id_usuario_capa)
		{
			$this->int_Id_usuario_capa = $id_usuario_capa;
			$sql = "DELETE FROM usuario_capa WHERE id_usuario_capa = $this->int_Id_usuario_capa ";
			$request = $this->delete($sql);
			return $request;
		}


		public function cantDatos(){

			$sql = "SELECT COUNT(*) as total_registro FROM usuario_capa";
			$request = $this->select($sql);
			return $request;
		}

		public function getDatosPage($desde, $porpagina){
			$sql = "SELECT * 
					FROM usuario_capa uc 
					INNER JOIN usuario u  
					ON uc.usucap_id_usuario = u.id_usuario
					INNER JOIN persona p 
					ON u.usuari_id_persona = p.id_persona 
					INNER JOIN capa c 
					ON uc.usucap_id_capa = c.id_capa 
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

		public function getCapa_nombre(string $nombre){
			$sql = "SELECT * FROM capa 
			WHERE capa_nombre = '$nombre' AND capa_acceso=2";
			$request = $this->select($sql);
			return $request;
		}


		public function getUsuarioCapa($id_usuario){
			$sql = "SELECT * 
					FROM usuario_capa uc 
					INNER JOIN usuario u  
					ON uc.usucap_id_usuario = u.id_usuario
					INNER JOIN persona p 
					ON u.usuari_id_persona = p.id_persona 
					INNER JOIN capa c 
					ON uc.usucap_id_capa = c.id_capa 
					WHERE uc.usucap_id_usuario = '$id_usuario'
					ORDER BY u.id_usuario DESC";
			$request =$request = $this->select_all($sql);
			return $request;
		}

	}
 ?>