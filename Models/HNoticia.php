<?php 
require_once("Libraries/Core/PostgreSQL.php");

trait HNoticia{
	private $con;

	public function getNoticiasH(){
		$this->con = new PostgreSQL();
		$sql = "SELECT * 
				FROM noticia WHERE notici_estado != 0 
				ORDER BY id_noticia DESC";
				$request = $this->con->select_all($sql);
				if(count($request) > 0){
					for ($c=0; $c < count($request) ; $c++) { 
						$request[$c]['imagen1'] = media().'/images/uploads/'.$request[$c]['notici_imagen1'];
						$request[$c]['imagen2'] = media().'/images/uploads/'.$request[$c]['notici_imagen2'];
					}
				}
		return $request;
	}
}
?>