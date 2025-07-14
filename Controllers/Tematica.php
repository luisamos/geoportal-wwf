<?php
class Tematica extends Controllers{
	public function __construct(){
		parent::__construct();
		session_start();
		session_regenerate_id(true);
		if(empty($_SESSION['login']))
		{
			if(!IS_DEV)
            {
				header('Location: ' .base_url(). 'Ingreso');
				die();
			}
		}
		getPermisos(MUSUARIOS);
	}

	public function Tematica(){
		if(empty($_SESSION['permisosMod']['permis_estado'])){
			header('Location:' .base_url(). 'gestion');
			exit;
		}
		$data['page_tag'] = NOMBRE_EMPRESA;
		$data['page_title'] = NOMBRE_EMPRESA .' | Temática';
		$data['page_name'] = 'tematica';
		$data['page_functions_js'] = 'funciones_tematica.js';

        $listaCategorias= $this->model->listarCategorias();

        $data['lista_categorias'] = $listaCategorias;
		$this->views->getView($this,'tematica',$data);
	}

    public function listar()
	{
		header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }
        $lista = $this->model->listar();
        echo json_encode(['estado' => true, 'datos' => $lista], JSON_UNESCAPED_UNICODE);
	}

    public function listarCategorias(){
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }
        $listaCategorias = $this->model->listarCategorias();
        echo json_encode(['estado' => true, 'datos' => $listaCategorias], JSON_UNESCAPED_UNICODE);
    }

    public function listarSubCategorias(int $idCategoria){
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }

        $intIdCategoria = intval(strClean($idCategoria));

        if($intIdCategoria > 0)
        {
            $listaSubCategorias = $this->model->listarSubCategorias($intIdCategoria);
            echo json_encode(['estado' => true, 'datos' => $listaSubCategorias], JSON_UNESCAPED_UNICODE);
        }
    }

    public function listarCapasServicios(int $idSubCategoria)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }

        $intIdSubCategoria = intval(strClean($idSubCategoria));

        if($intIdSubCategoria > 0)
        {
            $listaCapasServicios = $this->model->listarCapasServicios2($intIdSubCategoria);
            echo json_encode(['estado' => true, 'datos' => $listaCapasServicios], JSON_UNESCAPED_UNICODE);
        }
    }

    public function setOrden(){
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!is_array($data)) {
                echo json_encode(["estado" => false, "mensaje" => "Datos mal formateados"]);
                exit;
            }

            $respuesta = $this->model->actualizarOrden($data);
            echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Guardado correctamente.' : 'Error, revisar conexión.'], JSON_UNESCAPED_UNICODE);
        }
        else {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }
    }

	public function getCategoria(int $idCategoria){
		header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }

        $intIdCategoria = $idCategoria;

        if($intIdCategoria > 0)
        {
            $lista = $this->model->listarPor($intIdCategoria);
            $mensaje = empty($lista) ? ['estado' => false,
				'mensaje' => 'Datos no encontrados.'] : ['estado' => true,
				'datos' => $lista];
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        }
	}

	public function setCategoria(){
		header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $intIdCategoria  = $_POST['idCategoria'];
			$strNombre = $_POST['nombre'];
            $intVisible = $_POST['visible'];
			$intIdUsuario= $_SESSION['idUser'];
			$opcion= empty($intIdCategoria)? 1 : 2;

            if(is_null($strNombre)) {
                echo json_encode(['estado' => false, 'mensaje' => 'Falta completar datos.'], JSON_UNESCAPED_UNICODE);
            } else {

                if($opcion == 1)
                {
                    $respuesta = $this->model->agregarCategoria($strNombre, $intVisible, (IS_DEV) ? 100 : $intIdUsuario);
                    echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Categoría guardado correctamente.' : 'No se guardó la categoría revisar campos.'], JSON_UNESCAPED_UNICODE);
                }
                else if($opcion == 2)
                {
                    $respuesta = $this->model->actualizarCategoria($intIdCategoria, $strNombre, $intVisible, (IS_DEV) ? 100 : $intIdUsuario);
                    echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Se actualizó la categoría.' : 'No se pudo actualizar, revisar campos.'], JSON_UNESCAPED_UNICODE);
                }
            }
        }
        else {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }
	}

	public function del(){
		header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }

        $intIdCategoria = intval($_POST['idCategoria'] ?? 0);

        $respuesta = $this->model->eliminar($intIdCategoria);
        echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Se ha eliminado la categoría y/o subcategoría.' : 'Error en eliminar la categoría y/o subcategoría.'], JSON_UNESCAPED_UNICODE);
	}

	public function setSubCategoria(){
		header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $intIdCategoria  = $_POST['idCategoria'];
			$strNombre = $_POST['nombre'];
            $intVisible = $_POST['visible'];
            $intIdPadre = $_POST['idPadre'];
			$intIdUsuario= $_SESSION['idUser'];
			$opcion= empty($intIdCategoria)? 1 : 2;

            if(is_null($strNombre)) {
                echo json_encode(['estado' => false, 'mensaje' => 'Falta completar datos.'], JSON_UNESCAPED_UNICODE);
            } else {

                if($opcion == 1)
                {
                    $respuesta = $this->model->agregarSubCategoria($strNombre, $intVisible, $intIdPadre, (IS_DEV) ? 100 : $intIdUsuario);
                    echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Subcategoría guardado correctamente.' : 'No se guardó la subcategoría revisar campos.'], JSON_UNESCAPED_UNICODE);
                }
                else if($opcion == 2)
                {
                    $respuesta = $this->model->actualizarSubCategoria($intIdCategoria, $strNombre, $intVisible, $intIdPadre, (IS_DEV) ? 100 : $intIdUsuario);
                    echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Se actualizó la subcategoría.' : 'No se pudo actualizar, revisar campos.'], JSON_UNESCAPED_UNICODE);
                }
            }
        }
        else {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }
	}
}
?>