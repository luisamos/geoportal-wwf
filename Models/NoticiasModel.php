<?php
class NoticiasModel extends PostgreSQL
{
	public $intId_Noticia;
	public $strRuta;
	
	public function __construct()
	{
		parent::__construct();
	}

	public function getNoticia(int $id_noticia, string $ruta){
		$this->intId_Noticia = $id_noticia;
		$this->strRuta = $ruta;
		$sql = "SELECT *
				FROM noticia
				WHERE notici_estado = 1 AND id_noticia = '{$this->intId_Noticia}' AND notici_ruta = '{$this->strRuta}' ";
				$request = $this->select($sql);
				if(!empty($request)){
					$img1 = media().'/images/uploads/'.$request['notici_imagen1'];
					$img2 = media().'/images/uploads/'.$request['notici_imagen2'];
					$request['img1'] = $img1;
					$request['img2'] = $img2;
				}
		return $request;
	}

}
?>