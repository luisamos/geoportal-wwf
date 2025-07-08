<?php
class ServiciosGeograficos extends Controllers{

    public function __construct(){
        parent::__construct();
        session_start();
        session_regenerate_id(true);
        if(empty($_SESSION['login']))
        {
            if(!IS_DEV)
            {
                header('Location: '.base_url().'ingreso');
                die();
            }
        }
        getPermisos(MSERVICIOSGEOGRAFICOS);
    }

    public function ServiciosGeograficos(){

        if(empty($_SESSION['permisosMod']['permis_estado'])){
            header('Location:'.base_url().'gestion');
            exit;
        }

        $data['page_tag'] = NOMBRE_EMPRESA;
        $data['page_title'] = NOMBRE_EMPRESA . ' | Servicios Geográficos';
        $data['page_name'] = 'servicios_geograficos';
        $data['page_functions_js'] = 'funciones_servicios_geograficos.js';

        $pagina = 1;
        $cantidadDatos = $this->model->getCantidadTotal();
        $totalRegistro = $cantidadDatos['total_registro'];
        $desde = ($pagina - 1) * REG_XPORPAGINA;
        $totalPaginas = ceil($totalRegistro / REG_XPORPAGINA);

        $Registros = $this->model->getPagina($desde, REG_XPORPAGINA);
        $data['registros'] = $Registros;
        $data['pagina'] = $pagina;
        $data['total_paginas'] = $totalPaginas;
        $data['total_registros'] = $totalRegistro;

        $this->views->getView($this, 'servicios_geograficos', $data);
    }

    public function Page($pagina = null){

        $pagina = is_numeric($pagina) ? $pagina : 1;
        $cantidadDatos = $this->model->getCantidadTotal();
        $totalRegistro = $cantidadDatos['total_registro'];
        $desde = ($pagina - 1) * REG_XPORPAGINA;
        $totalPaginas = ceil($totalRegistro / REG_XPORPAGINA);
        $registros = $this->model->getPagina($desde, REG_XPORPAGINA);

        $data['total_registros'] = $totalRegistro;
        $data['registros'] = $registros;
        $data['page_tag'] = NOMBRE_EMPRESA;
        $data['page_title'] = NOMBRE_EMPRESA . ' | Servicios Geográficos';
        $data['page_name'] = 'servicios_geograficos';
        $data['page_functions_js'] = 'funciones_servicios_geograficos.js';
        $data['pagina'] = $pagina;
        $data['total_paginas'] = $totalPaginas;

        $this->views->getView($this, 'servicios_geograficos', $data);
    }

    public function listar(){
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }
        $lista = $this->model->listar();
        echo json_encode(['estado' => true, 'datos' => $lista], JSON_UNESCAPED_UNICODE);
    }

    public function get($idServicioGeografico){
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            echo json_encode(['estado' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }

        $intIdServicioGeografico = intval(strClean($idServicioGeografico));

        if($intIdServicioGeografico > 0)
        {
            $lista = $this->model->listarPor($intIdServicioGeografico);
            $mensaje = empty($lista) ? ['estado' => false, 'mensaje' => 'Datos no encontrados.'] : ['estado' => true, 'datos' => $lista];
            echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
        }
    }

    public function set(){
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $intIdServicioGeografico = $_POST['id_servicio_geografico'] ?? null;
            $intTipo = $_POST['tipo'] ?? null;
            $intIdTematica = $_POST['id_tematica'] ?? null;
            $strDireccionWeb = $_POST['direccion_web'] ?? null;
            $strCapa = $_POST['capa'] ?? null;
            $strNombre = $_POST['nombre'] ?? null;
            $strAlias = $_POST['alias'] ?? null;
            $bolVisible = $_POST['visible'] ?? null;

            $opcion= empty($intIdServicioGeografico)? 1 : 2;

            if(is_null($intTipo) ||
                is_null($intIdTematica) ||
                is_null($strDireccionWeb) ||
                is_null($strCapa) ||
                is_null($strNombre) ||
                is_null($strAlias) ||
                is_null($bolVisible)) {
                echo json_encode(['estado' => false, 'mensaje' => 'Falta completar datos.'], JSON_UNESCAPED_UNICODE);
            } else {

                if($opcion == 1)
                {
                    $respuesta = $this->model->agregar($intTipo, $intIdTematica, $strDireccionWeb, $strCapa, $strNombre, 
                    $strAlias, $bolVisible, (IS_DEV) ? 100 : $_SESSION['idUser']);
                    echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Datos guardados correctamente.' : 'No se pudo ingresar, revisar campos.'], JSON_UNESCAPED_UNICODE);
                }
                else if($opcion == 2)
                {
                    $respuesta = $this->model->actualizar($intIdServicioGeografico, $intTipo, $intIdTematica, 
                    $strDireccionWeb, $strCapa, $strNombre, $strAlias, $bolVisible, (IS_DEV) ? 100 : $_SESSION['idUser']);
                    echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Datos actualizados correctamente.' : 'No se pudo actualizar, revisar campos.'], JSON_UNESCAPED_UNICODE);
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

        $intIdServicioGeografico = intval($_POST['id_servicio_geografico'] ?? 0);

        $respuesta = $this->model->eliminar($intIdServicioGeografico);
        echo json_encode(['estado' => $respuesta > 0, 'mensaje' => $respuesta > 0 ? 'Se ha eliminado el servicio geográfico.' : 'Error en eliminar el servicio geográfico.'], JSON_UNESCAPED_UNICODE);

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
}
?>