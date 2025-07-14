<?php 
class PersonasModel extends PostgreSQL
{
	private $int_Id_persona;
	private $str_Person_num_documento;
	private $str_Person_nombres;
	private $str_Person_apellidos;
	private $str_Person_celular;
	private $str_Person_email;


	public function __construct()
	{
		parent::__construct();
	}	

	public function insertPersona(string $num_documento, string $nombres, string $apellidos, string $celular, string $email, int $id_usuario){

		$this->str_Person_num_documento = $num_documento;
		$this->str_Person_nombres = $nombres;
		$this->str_Person_apellidos = $apellidos;
		$this->str_Person_celular = $celular;
		$this->str_Person_email = $email;

		$return = 0;
		$sql = "SELECT * FROM persona WHERE person_num_documento = '{$this->str_Person_num_documento}' ";
		$request = $this->select_all($sql);

		if(empty($request))
		{
			$query_insert  = "INSERT INTO persona (person_num_documento,person_nombres,person_apellidos,person_celular,person_email, person_estado, created, created_by) 
							  VALUES(?,?,?,?,?,?,?,?)";
        	$arrData = array($this->str_Person_num_documento,
    						$this->str_Person_nombres,
    						$this->str_Person_apellidos,
    						$this->str_Person_celular,
    						$this->str_Person_email,
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

	public function selectPersonas(){
		$sql = "SELECT id_persona,person_num_documento,person_nombres,person_apellidos,person_celular,person_email,person_estado 
			FROM persona WHERE person_estado = 1";
		$request = $this->select_all($sql);
		return $request;
	}

	public function selectPersona(int $id_persona){
		$this->int_Id_persona = $id_persona;
		$sql = "SELECT id_persona,person_num_documento,person_nombres,person_apellidos,person_celular,person_email,person_estado, TO_CHAR(created, 'dd-Mon-YYYY') as fecharegistro 
				FROM persona 
				WHERE id_persona = $this->int_Id_persona AND person_estado = 1";
		$request = $this->select($sql);
		return $request;
	}

	public function updatePersona(int $id_persona, string $num_documento, string $nombres, string $apellidos, string $celular, string $email, int $id_usuario){

		$this->int_Id_persona = $id_persona;
		$this->str_Person_num_documento = $num_documento;
		$this->str_Person_nombres = $nombres;
		$this->str_Person_apellidos = $apellidos;
		$this->str_Person_celular = $celular;
		$this->str_Person_email = $email;
		
		
		$sql = "UPDATE persona 
			SET person_num_documento=?,
			 	person_nombres=?,
			 	person_apellidos=?,
			 	person_celular=?,
				person_email=?,
			 	updated=?,
			 	updated_by=?  
				WHERE id_persona = $this->int_Id_persona ";
		$arrData = array($this->str_Person_num_documento,
    					$this->str_Person_nombres,
    					$this->str_Person_apellidos,
    					$this->str_Person_celular,
    					$this->str_Person_email,
    					date("Y-m-d H:i:s"),
    					$id_usuario);
		
		$request = $this->update($sql,$arrData);
		
		return $request;
	}

	public function deletePersona(int $int_Id_persona){
		$this->int_Id_persona = $int_Id_persona;
		$sql = "UPDATE persona SET person_estado = ? WHERE id_persona = $this->int_Id_persona ";
		$arrData = array(0);
		$request = $this->update($sql,$arrData);
		return $request;
	}

	public function cantDatos(){
			$sql = "SELECT COUNT(*) as total_registro FROM persona WHERE person_estado = 1 ";
			$request = $this->select($sql);
			return $request;
	}

	public function getDatosPage($desde, $porpagina){
		$sql = "SELECT * FROM persona 
				WHERE person_estado = 1 
				ORDER BY id_persona 
				DESC LIMIT '$porpagina' OFFSET '$desde'";
		$request =$request = $this->select_all($sql);
		return $request;
	}


	public function getPersona_NumDocumento(string $num_documento){
		$this->str_Person_num_documento = $num_documento;
		$sql = "SELECT id_persona,person_num_documento,person_nombres,person_apellidos 
				FROM persona 
				WHERE person_num_documento = '$this->str_Person_num_documento' AND person_estado = 1 ";
		$request = $this->select($sql);
		return $request;
	}
}
?>