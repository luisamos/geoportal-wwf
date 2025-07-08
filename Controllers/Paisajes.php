<?php 

	class Paisajes extends Controllers{

		public function __construct()
		{
			parent::__construct();
			session_start();
			
		}

		public function paisajes()
		{
			$data['page_tag'] = "Paisajes | Geoportal WWF";
			$data['page_title'] = " Paisajes | ".NOMBRE_EMPRESA;
			$data['page_name'] = "paisajes";
			$data['page_functions_js'] = "functions_paisajes.js";

			$this->views->getView($this,"paisajes",$data);
		}
	}
 ?>