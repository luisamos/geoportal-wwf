<?php
	class PortadasModel extends PostgreSQL
	{
		public $int_Id_portada;
		public $str_Portad_titulo;
		public $str_Portad_descripcion;
		public $str_Portad_imagen;

		public function __construct()
		{
			parent::__construct();
		}

		public function inserPortada(string $titulo,  string $descripcion, string $imagen, int $id_usuario){

			$return = 0;
			$this->str_Portad_titulo = $titulo;
			$this->str_Portad_descripcion = $descripcion;
			$this->str_Portad_imagen = $imagen;

			$sql = "SELECT * FROM portada WHERE portad_titulo = '{$this->str_Portad_titulo}';";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO portada(portad_titulo,portad_descrip,portad_imagen,portad_estado,created, created_by) 
									VALUES(?,?,?,?,?,?)";
	        	$arrData = array($this->str_Portad_titulo,
								$this->str_Portad_descripcion,
								$this->str_Portad_imagen,
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

		public function selectPortadas(){
			$sql = "SELECT * FROM portada WHERE portad_estado = 1";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectPortada(int $id_portada){
			$this->int_Id_portada = $id_portada;
			$sql = "SELECT * FROM portada
					WHERE portad_estado = 1 AND id_portada = $this->int_Id_portada";
			$request = $this->select($sql);
			return $request;
		}

		public function updatePortada(int $id_portada, string $titulo, string $descripcion, string $imagen, int $id_usuario){
			$this->int_Id_portada = $id_portada;
			$this->str_Portad_titulo = $titulo;
			$this->str_Portad_descripcion = $descripcion;
			$this->str_Portad_imagen = $imagen;

			$sql = "SELECT * FROM portada WHERE portad_titulo = '{$this->str_Portad_titulo}' AND id_portada != $this->int_Id_portada";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$sql = "UPDATE portada SET portad_titulo = ?, portad_descrip = ?, portad_imagen = ?, updated = ?,
					updated_by = ? WHERE id_portada = $this->int_Id_portada ";
				$arrData = array($this->str_Portad_titulo,
								$this->str_Portad_descripcion,
								$this->str_Portad_imagen,
								date("Y-m-d H:i:s"),
								$id_usuario);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;
		}

		public function deletePortada(int $id_portada){
			$this->int_Id_portada = $id_portada;

			$sql = "UPDATE portada SET portad_estado = ? WHERE id_portada = $this->int_Id_portada ";
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

		public function cantDatos(){

			$sql = "SELECT COUNT(*) as total_registro FROM portada WHERE portad_estado = 1 ";
			$request = $this->select($sql);
			return $request;
		}

		public function getDatosPage($desde, $porpagina){
			$sql = "SELECT * FROM portada WHERE portad_estado = 1 ORDER BY id_portada DESC LIMIT '$porpagina' OFFSET '$desde'";
			$request =$request = $this->select_all($sql);
			if(count($request) > 0){
				for ($c=0; $c < count($request) ; $c++) {
					$request[$c]['portad_imagen'] = BASE_URL.'/Assets/images/uploads/'.$request[$c]['portad_imagen'];
				}
			}
			return $request;
		}

		public function actualizarPortada(int $id_portada, string $imagen, int $id_usuario){
			$this->int_Id_portada = $id_portada;
			$this->str_Portad_imagen = $imagen;

			$sql = "UPDATE portada SET portad_imagen = ?, updated = ?,
					updated_by = ? WHERE id_portada = $this->int_Id_portada;";
				$arrData = array($this->str_Portad_titulo,
								$this->str_Portad_descripcion,
								$this->str_Portad_imagen,
								date("Y-m-d H:i:s"),
								$id_usuario);
				$request = $this->update($sql,$arrData);
		    return $request;
		}
	}
 ?>