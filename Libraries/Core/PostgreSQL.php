<?php

class PostgreSQL extends Conexion
{
	private $conexion;
	private $strQuery;
	private $arrValues;
	private $arrDatos;

	function __construct()
	{
		parent::__construct();
    	$this->conexion = parent::conect();
	}

	//Busca un registro.
	public function select(string $consultaSQL)
	{
		$this->strQuery = $consultaSQL;
		$result = $this->conexion->prepare($this->strQuery);
		$result->execute();
		$data = $result->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	//Busca un registro en base al parámetros que se envía.
	public function select_array(string $consultaSQL, array $parametros)
	{
		$this->strQuery = $consultaSQL;
		$this->arrValues = $parametros;
		$result = $this->conexion->prepare($this->strQuery);
		$result->execute($this->arrValues);
		$data = $result->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	//Devuelve todos los registros.
	public function select_all(string $consultaSQL)
	{
		$this->strQuery = $consultaSQL;
		$result = $this->conexion->prepare($this->strQuery);
		$result->execute();
		$data = $result->fetchall(PDO::FETCH_ASSOC);
		return $data;
	}

	//Insertar un registro.
	public function insert(string $consultaSQL, array $parametros)
	{
		$this->strQuery = $consultaSQL;
		$this->arrValues = $parametros;
		$insert = $this->conexion->prepare($this->strQuery);
		$resInsert = $insert->execute($this->arrValues);
		if($resInsert)
		{
			$lastInsert = $this->conexion->lastInsertId();
		}else{
			$lastInsert = 0;
		}
		return $lastInsert;
	}

	//Actualiza registros.
	public function update(string $consultaSQL, array $parametros)
	{
		$this->strQuery = $consultaSQL;
		$this->arrValues = $parametros;
		$update = $this->conexion->prepare($this->strQuery);
		$resExecute = $update->execute($this->arrValues);
		return $resExecute;
	}

	public function updateOne(string $consultaSQL, array $parametros)
	{
		$this->strQuery = $consultaSQL;
		$this->arrValues = $parametros;
		$update = $this->conexion->prepare($this->strQuery);
		$update->execute($this->arrValues);
		return $update->fetchColumn();
	}

	public function update_all(string $query, array $data)
	{
		try {
			$this->conexion->beginTransaction();
			$total = 0;

			foreach ($data as $row) {
				$stmt = $this->conexion->prepare($query);

				foreach ($row as $param => $value) {
					$type = is_int($value) ? PDO::PARAM_INT : (is_bool($value) ? PDO::PARAM_BOOL : PDO::PARAM_STR);
					$stmt->bindValue(':' . $param, $value, $type);
				}

				$stmt->execute();
				$total += $stmt->rowCount();
			}

			$this->conexion->commit();
			return $total;
		} catch (Exception $e) {
			$this->conexion->rollBack();
			return false;
		}
	}

	//Eliminar un registros.
	public function delete(string $query)
	{
		$this->strQuery = $query;
		$delete = $this->conexion->prepare($this->strQuery);
		$delete->execute();

		return $delete->rowCount();
	}
}
?>