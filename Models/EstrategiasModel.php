<?php 
	class EstrategiasModel extends PostgreSQL
	{
		public function __construct()
		{
			parent::__construct();
		}


		public function getEstrategias(int $id_paisaje){

			if ($id_paisaje) {
				$paisaje = "AMAZONIA";
			}
			else{
				$paisaje = "PACIFICO";
			}



			$sql = "SELECT * FROM paisaje WHERE paisaj_paisaje = '".$paisaje."' ORDER BY id_paisaje asc";
			$request = $this->select_all($sql);

			if(count($request) > 0){
				$request = array(
								   'registros' => $request,
									  'status' => true
								);
			}
			else{
				$request = array('status' => false);
			}
			return $request;
		}

	}
?>