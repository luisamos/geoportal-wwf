<?php
require_once("Models/Esquemas.php");
class Esquemas extends Controllers{
    public function __construct()
	{
		parent::__construct();		
		$this->esquemas = new ModelEsquemas();
	}

    publir function listar()
    {

    }
}
?>