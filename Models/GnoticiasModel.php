<?php 
	class GnoticiasModel extends PostgreSQL{
		
		public $int_Id_noticia;
		public $str_Notici_tipo;
		public $str_Notici_url;
		public $str_Notici_titulo;
		public $str_Notici_descripcion;
		public $str_Notici_imagen1;
		public $str_Notici_imagen2;
		public $str_Notici_ruta;

		public function __construct()
		{
			parent::__construct();
		}

		public function inserNoticia(int $tipo,string $url,string $titulo,string $descripcion, string $imagen1, string $imagen2, string $ruta, int $id_usuario){

			$return = 0;
			$this->str_Notici_tipo = $tipo;
			$this->str_Notici_url = $url;
			$this->str_Notici_titulo = $titulo;
			$this->str_Notici_descripcion = $descripcion;
			$this->str_Notici_imagen1 = $imagen1;
			$this->str_Notici_imagen2 = $imagen2;
			$this->str_Notici_ruta = $ruta;

			$sql = "SELECT * FROM noticia WHERE notici_titulo = '{$this->str_Notici_titulo}' ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO noticia (notici_tipo,notici_url,notici_titulo,notici_descripcion,notici_imagen1,notici_imagen2,notici_ruta,notici_estado,created,created_by) 
									VALUES(?,?,?,?,?,?,?,?,?,?)";
	        	$arrData = array($this->str_Notici_tipo,
	        					$this->str_Notici_url,
	        					$this->str_Notici_titulo,
	        					$this->str_Notici_descripcion,
								$this->str_Notici_imagen1,
								$this->str_Notici_imagen2,
								$this->str_Notici_ruta,
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

		public function selectNoticias(){
			$sql = "SELECT * FROM noticia";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectNoticia(int $id_noticia){
			$this->int_Id_noticia = $id_noticia;
			$sql = "SELECT * FROM noticia
					WHERE id_noticia = $this->int_Id_noticia";
			$request = $this->select($sql);
			return $request;
		}

		public function updateNoticia(int $id_noticia,int $tipo,string $url, string $titulo, string $descripcion, string $imagen1, string $imagen2, string $ruta, int $id_usuario){

			$this->int_Id_noticia = $id_noticia;
			$this->str_Notici_titulo = $titulo;
			$this->str_Notici_tipo = $tipo;
			$this->str_Notici_url = $url;
			$this->str_Notici_descripcion = $descripcion;
			$this->str_Notici_imagen1 = $imagen1;
			$this->str_Notici_imagen2 = $imagen2;
			$this->str_Notici_ruta = $ruta;

			$sql = "SELECT * FROM noticia WHERE notici_titulo = '{$this->str_Notici_titulo}' AND id_noticia != $this->int_Id_noticia";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$sql = "UPDATE noticia SET 

					notici_tipo = ?, 
					notici_url = ?, 
					notici_titulo = ?, 
					notici_descripcion = ?,
				 	notici_imagen1 = ?,
				   	notici_imagen2 = ?,
				    updated = ?,
					updated_by = ? 
					WHERE id_noticia = $this->int_Id_noticia ";
				$arrData = array($this->str_Notici_tipo,
								$this->str_Notici_url,
								$this->str_Notici_titulo,
								$this->str_Notici_descripcion, 
								$this->str_Notici_imagen1,
								$this->str_Notici_imagen2, 
								date("Y-m-d H:i:s"),
			    				$id_usuario);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

		public function deleteNoticia(int $id_noticia)
		{
			$this->int_Id_noticia = $id_noticia;
			
			$sql = "UPDATE noticia SET notici_estado = ? WHERE id_noticia = $this->int_Id_noticia ";
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


		public function cantDatos($id_programa = null){
			$where = "";
			if($id_programa != null){
				$where = " AND notici_id_tipo = ".$id_programa;
			}
			$sql = "SELECT COUNT(*) as total_registro FROM noticia WHERE notici_estado = 1 ".$where;
			$request = $this->select($sql);
			return $request;
		}

		public function getDatosPage($desde, $porpagina){
			$sql = "SELECT * FROM noticia WHERE notici_estado = 1 ORDER BY id_noticia DESC LIMIT '$porpagina' OFFSET '$desde'";
			$request =$request = $this->select_all($sql);
			if(count($request) > 0){
				for ($c=0; $c < count($request) ; $c++) { 
					$request[$c]['notici_imagen1'] = BASE_URL.'/Assets/images/uploads/'.$request[$c]['notici_imagen1'];

					$request[$c]['notici_imagen2'] = BASE_URL.'/Assets/images/uploads/'.$request[$c]['notici_imagen2'];	
				}
			}
			return $request;
		}

	}
 ?>