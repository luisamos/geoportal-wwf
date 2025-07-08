<?php
class TematicaModel extends PostgreSQL
{
	private $intIdCategoria;
	private $strNombre;
	private $bolVisible;
	private $intIdPadre;
	private $intIdSubTematica;
	private $intIdUsuario;
	private $arrDatos;

	public function __construct()
	{
		parent::__construct();
	}

	public function listar(){
		$consultaSQL = "SELECT h.id_tematica, COALESCE(p.nombre || ' > ', '') || h.nombre AS nombre
			FROM public.tematica AS h LEFT JOIN public.tematica AS p ON h.id_padre = p.id_tematica
			WHERE h.habilitado = true AND h.id_padre != 0 ORDER BY p.orden, h.orden;";
		$respuesta = $this->select_all($consultaSQL);
		return $respuesta;
	}

	public function listarCategorias(){
		$consultaSQL = "SELECT id_tematica, nombre  FROM public.tematica
			WHERE habilitado = True
			AND id_padre = 0
			ORDER BY orden ASC;";
		$respuesta = $this->select_all($consultaSQL);
		return $respuesta;
	}

	public function listarSubCategorias(int $idPadre)
	{
		$this->intIdPadre = $idPadre;
		$consultaSQL = "SELECT id_tematica, nombre, id_padre, orden FROM public.tematica
			WHERE habilitado = True
			AND id_padre = $this->intIdPadre
			ORDER BY orden ASC;";
		$respuesta = $this->select_all($consultaSQL);
		return $respuesta;
	}

	public function listarCapasServicios(int $idSubCategoria)
	{
		$this->intIdSubTematica = $idSubCategoria;
		$consultaSQL = "SELECT id_capa_geografica, alias, orden
			FROM public.capas_geograficas
			WHERE habilitado = True AND id_tematica= $this->intIdSubTematica
			ORDER BY orden ASC;";
		$respuesta = $this->select_all($consultaSQL);
		return $respuesta;
	}

	public function listarCapasServicios2(int $idSubCategoria)
	{
		$this->intIdSubTematica = $idSubCategoria;
		$consultaSQL = "SELECT * FROM v_listar_capas_servicios_by
			WHERE id_tematica= $this->intIdSubTematica
			ORDER BY orden ASC;";
		$respuesta = $this->select_all($consultaSQL);
		return $respuesta;
	}

	public function listarPor(int $idCategoria){
		$this->intIdCategoria = $idCategoria;
		$consultaSQL = "SELECT h.id_tematica, h.nombre, h.id_padre, COALESCE(p.nombre, '') AS nombre_padre, h.visible
		FROM public.tematica AS h LEFT JOIN public.tematica AS p ON h.id_padre = p.id_tematica
		WHERE h.habilitado = true AND h.id_tematica = $this->intIdCategoria";
		$respuesta = $this->select($consultaSQL);
		return $respuesta;
	}

	public function agregarCategoria(string $nombre, int $visible, int $idUsuario){
		$this->strNombre = $nombre;
		$this->bolVisible= $visible;
		$this->intIdUsuario= $idUsuario;

		$consultaSQL  = "INSERT INTO public.tematica(nombre, visible, id_usuario_crea, id_usuario_modifica) 
							VALUES(?,?,?,?);";
		$parametros = array($this->strNombre,
						$this->bolVisible,
						$this->intIdUsuario,
						$this->intIdUsuario);

		$respuesta = $this->insert($consultaSQL,$parametros);
		return $respuesta;
	}

	public function actualizarCategoria(int $idCategoria, string $nombre, int $visible, int $idUsuario){

		$this->intIdCategoria = $idCategoria;
		$this->strNombre = $nombre;
		$this->bolVisible= $visible;
		$this->intIdUsuario= $idUsuario;

		$consultaSQL = "UPDATE public.tematica 
		SET nombre=?, visible=?, id_usuario_modifica= ?, fecha_modifica=? 
		WHERE id_tematica = ?;";
		$parametros = array(
			$this->strNombre,
			$this->bolVisible,
			$this->intIdUsuario,
			date("Y-m-d H:i:s"),
			$this->intIdCategoria);

		$respuesta = $this->update($consultaSQL,$parametros);
		return $respuesta;
	}

	public function eliminar(int $idCategoria){
		$this->intIdCategoria = $idCategoria;
		$consultaSQL = "UPDATE public.tematica SET habilitado = False WHERE id_tematica = ?;";
		$parametros = array($this->intIdCategoria);

		$respuesta = $this->update($consultaSQL,$parametros);
		return $respuesta;
	}

	public function actualizarOrden(array $datos)
	{
		$this->arrDatos = $datos;
		$consultaSQL ="UPDATE public.tematica SET orden = :orden WHERE id_tematica = :id";

		$respuesta = $this->update_all($consultaSQL, $this->arrDatos);
		return $respuesta;
	}

	public function agregarSubCategoria(string $nombre, int $visible, int $idPadre, int $idUsuario){
		$this->strNombre = $nombre;
		$this->bolVisible= $visible;
		$this->intIdPadre = $idPadre;
		$this->intIdUsuario= $idUsuario;

		$consultaSQL  = "INSERT INTO public.tematica(nombre, visible, id_padre, id_usuario_crea, id_usuario_modifica) 
							VALUES(?,?,?,?,?);";
		$parametros = array($this->strNombre,
						$this->bolVisible,
						$this->intIdPadre,
						$this->intIdUsuario,
						$this->intIdUsuario);

		$respuesta = $this->insert($consultaSQL,$parametros);
		return $respuesta;
	}

	public function actualizarSubCategoria(int $idCategoria, string $nombre, int $visible, int $idPadre,  int $idUsuario){

		$this->intIdCategoria = $idCategoria;
		$this->strNombre = $nombre;
		$this->bolVisible= $visible;
		$this->intIdPadre = $idPadre;
		$this->intIdUsuario= $idUsuario;

		$consultaSQL = "UPDATE public.tematica 
		SET nombre=?, visible=?, id_padre=?, id_usuario_modifica= ?, fecha_modifica=? 
		WHERE id_tematica = ?;";
		$parametros = array(
			$this->strNombre,
			$this->bolVisible,
			$this->intIdPadre,
			$this->intIdUsuario,
			date("Y-m-d H:i:s"),
			$this->intIdCategoria);

		$respuesta = $this->update($consultaSQL,$parametros);
		return $respuesta;
	}
}
 ?>