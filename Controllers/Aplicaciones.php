<?php 
	class Aplicaciones extends Controllers{

		
		public function __construct()
		{
			parent::__construct();
			session_start();
		}

		public function aplicaciones()
		{
			$pageContent = getPageRout('inicio');
			$data['page_tag'] = NOMBRE_EMPRESA;
			$data['page_title'] = NOMBRE_EMPRESA;
			$data['page_name'] = "https://geoportal.wwf.org";
			$data['page'] = $pageContent;

			$this->views->getView($this,"aplicaciones",$data);
		}

	}
 ?>
