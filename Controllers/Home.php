<?php
	require_once("Models/HPortada.php");
	require_once("Models/HNoticia.php");

	class Home extends Controllers{

		use HPortada, HNoticia;

		public function __construct()
		{
			parent::__construct();
			session_start();
		}

		public function home()
		{
			$pageContent = getPageRout('inicio');
			$data['page_tag'] = NOMBRE_EMPRESA;
			$data['page_title'] = NOMBRE_EMPRESA;
			$data['page_name'] = "https://geoportal.wwf.org";
			$data['page'] = $pageContent;

			$data['portada_imagenes'] = $this->getPortadasH();
			$data['noticias'] = $this->getNoticiasH();

			$this->views->getView($this,"home",$data);
		}
	}
 ?>