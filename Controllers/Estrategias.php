<?php 

	class Estrategias extends Controllers{

		public function __construct()
		{
			parent::__construct();
			session_start();
		}

		public function paisaje($params)
		{
			if(empty($params)){
				header("Location:".base_url());
			}
			else{
				$arrParams = explode(",",$params);
				$id_paisaje = intval($arrParams[0]);
				
				$Registros = $this->model->getEstrategias($id_paisaje);

				if ($Registros['status']) {
					$data['page_tag'] = "Paisaje - | ".NOMBRE_EMPRESA;
					$data['page_name'] = "Paisaje";
					$data['registros'] = $Registros['registros'];
					$data['id_paisaje'] = $id_paisaje;

					$this->views->getView($this,"estrategias",$data);
				}
				else{
					header("Location:".base_url()."/paisaje/1");
				}
			}

		}
		


	}

?>