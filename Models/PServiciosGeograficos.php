<?php 
class PServiciosGeograficos extends PostgreSQL
{
	public $int_Id_servicio_geografico;
	public $str_Sergeo_nombre;
	public $str_Sergeo_url;
	public $int_Sergeo_estado;

	public function __construct()
	{
		parent::__construct();
	}

	public function cantDatos(){
		$sql = "SELECT COUNT(*) as total_registro FROM servicio_geografico WHERE sergeo_estado = 1 ";
		$request = $this->select($sql);
		return $request;
	}

	public function getDatosPage($desde, $porpagina){
		$sql = "SELECT * FROM servicio_geografico WHERE sergeo_estado = 1  ORDER BY id_servicio_geografico DESC LIMIT '$porpagina' OFFSET '$desde'";
		$request =$request = $this->select_all($sql);
		return $request;
	}
}
?>