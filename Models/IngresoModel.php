<?php 
class IngresoModel extends PostgreSQL
{
	private $int_Id_usuario;
	private $str_Usuari_nombre;
	private $str_Usuari_clave;
	private $strToken;

	public function __construct()
	{
		parent::__construct();
	}	

	public function loginUser2(string $usuario, string $clave)
	{
		$this->str_Usuari_nombre = $usuario;
		$this->str_Usuari_clave = $clave;
		
		$sql = "SELECT id_usuario,usuari_estado,usuari_id_rol FROM usuario 
				WHERE 
				usuari_nombre = '$this->str_Usuari_nombre' and 
				usuari_clave = '$this->str_Usuari_clave' and 
				usuari_estado != 0;";
		$request = $this->select($sql);
		return $request;
	}

	public function loginUser(string $usuario, string $clave)
	{
		$this->str_Usuari_nombre = $usuario;
		$this->str_Usuari_clave = hash('sha256', $clave); // SHA-256 para comparar con el hash en la BD

		// 1. Buscar usuario por nombre
		$sql = "SELECT id_usuario, usuari_clave, intentos_login, usuari_estado, usuari_id_rol
				FROM usuario
				WHERE usuari_nombre = ?";
		$params = [$this->str_Usuari_nombre];
		$userData = $this->select_array($sql, $params);

		if (empty($userData)) {
			return ['error' => 'Usuario no encontrado.'];
		}

		if ((int)$userData['usuari_estado'] === 0) {
			return ['error' => 'Usuario bloqueado.'];
		}

		// 2. Comparar la clave
		if ($userData['usuari_clave'] === $this->str_Usuari_clave) {
			// Login correcto → Reiniciar intentos
			$sqlReset = "UPDATE usuario SET intentos_login = 0 WHERE id_usuario = ?";
			$this->update($sqlReset, [$userData['id_usuario']]);

			// Devolver información útil
			return [
				'id_usuario' => $userData['id_usuario'],
				'usuari_estado' => $userData['usuari_estado'],
				'usuari_id_rol' => $userData['usuari_id_rol']
			];
		} else {
			// Login fallido → Incrementar intentos
			$intentos = (int)$userData['intentos_login'] + 1;
			$estado = ($intentos >= 3) ? 0 : (int)$userData['usuari_estado'];

			$sqlUpdate = "UPDATE usuario SET intentos_login = ?, usuari_estado = ? WHERE id_usuario = ?";
			$this->update($sqlUpdate, [$intentos, $estado, $userData['id_usuario']]);

			return [
				'error' => ($estado === 0)
					? 'Usuario bloqueado por 3 intentos fallidos.'
					: "Contraseña incorrecta. Intento $intentos de 3."
			];
		}
	}

	public function sessionLogin(int $id_usuario){
		$this->int_Id_usuario = $id_usuario;
		//BUSCAR ROLES 
		$sql = "SELECT  u.id_usuario,
						p.person_num_documento,
						p.person_nombres,
						p.person_apellidos,
						u.usuari_nombre,
						u.usuari_estado,
						r.id_rol,
						r.rol_nombre 
				FROM usuario u
				INNER JOIN persona p
				ON u.usuari_id_persona = p.id_persona 
				INNER JOIN rol r 
				ON u.usuari_id_rol = r.id_rol
				WHERE u.id_usuario = $this->int_Id_usuario";
		$request = $this->select($sql);
		$_SESSION['userData'] = $request;
		return $request;
	}

	public function getUserEmail(string $strEmail){
		$this->str_Usuari_nombre = $strEmail;
		$sql = "SELECT id_usuario,nombres,apellidos,status FROM persona WHERE 
				usuari_nombre = '$this->str_Usuari_nombre' and  
				status = 1 ";
		$request = $this->select($sql);
		return $request;
	}


	public function getUsuario(string $email, string $token){
		$this->str_Usuari_nombre = $email;
		$this->strToken = $token;
		$sql = "SELECT id_usuario FROM persona WHERE 
				usuari_nombre = '$this->str_Usuari_nombre' and 
				token = '$this->strToken' and 					
				status = 1 ";
		$request = $this->select($sql);
		return $request;
	}

	public function insertClave(int $id_usuario, string $clave){
		$this->int_Id_usuario = $id_usuario;
		$this->str_Usuari_clave = $clave;
		$sql = "UPDATE persona SET clave = ?, token = ? WHERE id_usuario = $this->int_Id_usuario ";
		$arrData = array($this->str_Usuari_clave,"");
		$request = $this->update($sql,$arrData);
		return $request;
	}
}
 ?>