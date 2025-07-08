<?php
class GplanotecaModel extends PostgreSQL
{
	public $int_Id_planoteca;
	public $int_Planot_id_tipo;
	public $str_Planot_codigo;
	public $str_Planot_nombre;
	public $str_Planot_archivo;
	public $str_Planot_img;
	public $str_Planot_tag;

	public function __construct()
	{
		parent::__construct();
	}

	public function inserPlanoteca(string $id_tipo, string $codigo, string $nombre, string $archivo, string $img, string $tag, int $id_usuario){

		$return = 0;
		$this->int_Planot_id_tipo = $id_tipo;
		$this->str_Planot_codigo = $codigo;
		$this->str_Planot_nombre = $nombre;
		$this->str_Planot_archivo = $archivo;
		$this->str_Planot_img = $img;
		$this->str_Planot_tag = $tag;

		$sql = "SELECT * FROM planoteca WHERE planot_codigo = '{$this->str_Planot_codigo}' ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$query_insert  = "INSERT INTO planoteca(planot_id_tipo,planot_codigo,planot_nombre,planot_archivo, planot_img, planot_tag, planot_estado, created, created_by) 
								VALUES(?,?,?,?,?,?,?,?,?)";
        	$arrData = array($this->int_Planot_id_tipo, 
							$this->str_Planot_codigo,
							$this->str_Planot_nombre,
							$this->str_Planot_archivo,
							$this->str_Planot_img, 
							$this->str_Planot_tag,
							1,
							date("Y-m-d H:i:s"),
							$id_usuario);
        	$request_insert = $this->insert($query_insert,$arrData);
        	$return = $request_insert;
		}
		else{
			$return = "exist";
		}
		return $return;
	}

	public function selectPlanotecas(){
		$sql = "SELECT * FROM planoteca WHERE planot_estado = 1 ";
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectPlanoteca(int $id_planoteca){
		$this->int_Id_planoteca = $id_planoteca;
		$sql = "SELECT * FROM planoteca
				WHERE planot_estado = 1 AND id_planoteca = $this->int_Id_planoteca";
		$request = $this->select($sql);
		return $request;
	}

	public function updatePlanoteca(int $id_planoteca, int $id_tipo, string $codigo, string $nombre, string $archivo,string $img, string $tag, int $id_usuario){

		$this->int_Id_planoteca = $id_planoteca;
		$this->int_Planot_id_tipo = $id_tipo;
		$this->str_Planot_codigo = $codigo;
		$this->str_Planot_nombre = $nombre;
		$this->str_Planot_archivo = $archivo;
		$this->str_Planot_img = $img;
		$this->str_Planot_tag = $tag;

		$sql = "SELECT * FROM planoteca WHERE planot_codigo = '{$this->str_Planot_codigo}' AND id_planoteca != $this->int_Id_planoteca";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$sql = "UPDATE planoteca SET planot_id_tipo = ?, planot_codigo = ?, 
					planot_nombre = ?, 
					planot_archivo = ?,
					planot_img = ?,
					planot_tag = ?, 
					updated = ?,
					updated_by = ?  WHERE id_planoteca = $this->int_Id_planoteca ";
			$arrData = array($this->int_Planot_id_tipo, 
							 $this->str_Planot_codigo,
							 $this->str_Planot_nombre,
							 $this->str_Planot_archivo, 
							 $this->str_Planot_img,
							 $this->str_Planot_tag,
							 date("Y-m-d H:i:s"),
							 $id_usuario);
			$request = $this->update($sql,$arrData);
		}else{
			$request = "exist";
		}
	    return $request;			
	}

	public function deletePlanoteca(int $id_planoteca){
		$this->int_Id_planoteca = $id_planoteca;
		
		$sql = "UPDATE planoteca SET planot_estado = ? WHERE id_planoteca = $this->int_Id_planoteca ";
		$arrData = array(0);
		$request = $this->update($sql,$arrData);
		if($request)
		{
			$request = 'ok';	
		}else{
			$request = 'error';
		}
		return $request;
	}
}
?>