<?php 
require_once("Libraries/Core/PostgreSQL.php");

trait HPortada{
	private $con;

	public function getPortadasH(){
		$this->con = new PostgreSQL();
		$sql = "SELECT id_portada, portad_titulo, portad_descrip, portad_imagen, portad_estado 
				 FROM portada WHERE portad_estado=1";
		$request = $this->con->select_all($sql);
		if(count($request) > 0){
			for ($c=0; $c < count($request) ; $c++) {
				$request[$c]['portad_imagen'] = BASE_URL.'/Assets/images/uploads/'.$request[$c]['portad_imagen'];
			}
		}
		return $request;
	}
}

 ?>