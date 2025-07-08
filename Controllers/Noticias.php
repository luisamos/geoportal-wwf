<?php 

	class Noticias extends Controllers{

		public function __construct()
		{
			parent::__construct();
			session_start();
			
		}

		public function ver($params){
			if(empty($params)){
				header("Location:".base_url());
			}else{
				$arrParams = explode(",",$params);
				$id_noticia = intval($arrParams[0]);
				$ruta = strClean($arrParams[1]);
				$infoNoticia = $this->model->getNoticia($id_noticia,$ruta);
				if(empty($infoNoticia)){
					header("Location:".base_url());
				}
				$data['page_tag'] = "Noticias | ".NOMBRE_EMPRESA;
				$data['page_title'] = $infoNoticia['notici_titulo'];
				$data['page_name'] = "noticias";
				$data['noticia'] = $infoNoticia;
				$this->views->getView($this,"noticias",$data);
			}
		}
	}
 ?>