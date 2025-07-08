<?php 
require_once("Libraries/Core/PostgreSQL.php");

trait HModulo{
	private $con;

	public function getModulosH(string $modulos){
		$this->con = new PostgreSQL();
		$sql = "SELECT id_modulo, modulo_titulo, modulo_descripcion, modulo_ruta, modulo_estado 
				 FROM modulo WHERE modulo_estado != 0 AND id_modulo IN ($modulos) ORDER BY id_modulo asc";
		$request = $this->con->select_all($sql);
		
		return $request;
	}
}

 ?>