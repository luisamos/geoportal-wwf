<?php 
	class Permisos extends Controllers{

		public function __construct()
		{
			parent::__construct();
			session_start();
		}

		public function getPermisosRol(int $id_rol)
		{
			$id_rol = intval($id_rol);
			if($id_rol > 0)
			{
				$arrModulos = $this->model->selectModulos();
				$arrPermisosRol = $this->model->selectPermisosRol($id_rol);
				$arrPermisos = array('permis_estado' => 0);
				$arrPermisoRol = array('id_rol' => $id_rol );

				if(empty($arrPermisosRol))
				{
					for ($i=0; $i < count($arrModulos) ; $i++) { 

						$arrModulos[$i]['permisos'] = $arrPermisos;
					}
				}else{
					for ($i=0; $i < count($arrModulos); $i++) {
						$arrPermisos = array('permis_estado' => 0);
						if(isset($arrPermisosRol[$i])){
							$arrPermisos = array('permis_estado' => $arrPermisosRol[$i]['permis_estado']);
						}
						$arrModulos[$i]['permisos'] = $arrPermisos;
					}
				}
				$arrPermisoRol['modulos'] = $arrModulos;
				$html = getModal("modalPermisos",$arrPermisoRol);
				//dep($arrPermisoRol);
			}
			die();
		}

		public function setPermisos()
		{
			if($_POST)
			{
				$intId_rol = intval($_POST['id_rol']);
				$modulos = $_POST['modulos'];

				$this->model->deletePermisos($intId_rol);
				foreach ($modulos as $modulo) {
					$idModulo = $modulo['id_modulo'];
					$permis_estado = empty($modulo['permis_estado']) ? 0 : 1;

					$requestPermiso = $this->model->insertPermisos($intId_rol, $idModulo, $permis_estado, $_SESSION['idUser']);
				}
				if($requestPermiso > 0)
				{
					$arrResponse = array('status' => true, 'msg' => 'Permisos asignados correctamente.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible asignar los permisos.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

	}
 ?>