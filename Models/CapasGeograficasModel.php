<?php
class CapasGeograficasModel extends PostgreSQL
{
	private $intIdCapaGeografica;
	private $strNombre;
	private $strAlias;
	private $intIdTematica;
	private $intTipo;
	private $bolVisible;
	private $bolHabilitado;
	private $intIdUsuarioCrea;
	private $intIdUsuarioModifica;
	private $arrDatos;

	public function __construct(){
		parent::__construct();
	}

	public function ListarPor(int $idCapaGeografica){
		$this->intIdCapaGeografica = $idCapaGeografica;
		$consultaSQL = "SELECT * FROM public.capas_geograficas 
			WHERE id_capa_geografica = $this->intIdCapaGeografica";
		$respuesta = $this->select($consultaSQL);
		return $respuesta;
	}

	public function agregar(string $nombre, string $alias, int $idTematica, int $tipo, int $visible, int $idUsuario){
		$this->strNombre= $nombre;
		$this->strAlias= $alias;
		$this->intIdTematica= $idTematica;
		$this->intTipo = $tipo;
		$this->bolVisible= $visible;
		$this->intIdUsuarioCrea= $idUsuario;

		$consultaSQL = "INSERT INTO public.capas_geograficas
			(nombre, alias, id_tematica, tipo, visible, id_usuario_crea, id_usuario_modifica) 
			VALUES (?, ?, ?, ?, ?, ?, ?);";
		$parametros = array(
			$this->strNombre,
			$this->strAlias,
			$this->intIdTematica,
			$this->intTipo,
			$this->bolVisible,
			$this->intIdUsuarioCrea,
			$this->intIdUsuarioCrea);

		$respuesta = $this->insert($consultaSQL, $parametros);
		return $respuesta;
	}

	public function actualizar(int $idCapaGeografica, string $nombre, string $alias, int $idTematica, int $tipo, int $visible, int $idUsuario){
		$this->intIdCapaGeografica= $idCapaGeografica;
		$this->strNombre= $nombre;
		$this->strAlias= $alias;
		$this->intIdTematica= $idTematica;
		$this->intTipo = $tipo;
		$this->bolVisible= $visible;
		$this->intIdUsuarioModifica= $idUsuario;

		$consultaSQL = "UPDATE public.capas_geograficas 
			SET nombre=?, alias=?, id_tematica=?, tipo=?, visible=?, id_usuario_modifica=?, fecha_modifica=? 
			WHERE id_capa_geografica=?;";
		$parametros = array(
			$this->strNombre,
			$this->strAlias,
			$this->intIdTematica,
			$this->intTipo,
			$this->bolVisible,
			$this->intIdUsuarioModifica,
			date("Y-m-d H:i:s"),
			$this->intIdCapaGeografica);
		$respuesta = $this->update($consultaSQL,$parametros);

		return $respuesta;
	}

	public function eliminar(int $idCapaGeografica){
		$this->intIdCapaGeografica = $idCapaGeografica;

		$consultaSQL = "DELETE FROM public.capas_geograficas WHERE id_capa_geografica = $this->intIdCapaGeografica";
		$respuesta = $this->delete($consultaSQL);
		return $respuesta;
	}

	public function getCantidadTotal(){
		$consultaSQL = "SELECT COUNT(*) as total_registro FROM public.capas_geograficas WHERE habilitado = True;";
		$respuesta = $this->select($consultaSQL);
		return $respuesta;
	}

	public function getPagina($desde, $porPagina){
		$consultaSQL = "SELECT 
			a.id_capa_geografica, 
			a.nombre, 
			a.alias, 
			b.id_tematica, 
			b.descripcion_tematica, 
			CASE WHEN a.tipo = 1 THEN 'Vector' ELSE 'Raster' END AS tipo,
			CASE WHEN a.visible THEN '🟢' ELSE '⚫' END AS visible,
			a.orden
			FROM public.capas_geograficas a
			JOIN (SELECT 
					h.id_tematica, 
					COALESCE(p.nombre || ' > ', '') || h.nombre AS descripcion_tematica
				FROM public.tematica h
				LEFT JOIN public.tematica p ON h.id_padre = p.id_tematica
				WHERE h.habilitado = true AND h.id_padre != 0
				) b ON a.id_tematica = b.id_tematica
			WHERE a.habilitado = true
			ORDER BY a.orden DESC LIMIT '$porPagina' OFFSET '$desde';";
		$respuesta =$this->select_all($consultaSQL);
		return $respuesta;
	}

	public function actualizarOrden(array $datos)
	{
		$this->arrDatos = $datos;
		$consultaSQL ="UPDATE public.capas_geograficas SET orden = :orden WHERE id_capa_geografica = :id";

		$respuesta = $this->update_all($consultaSQL, $this->arrDatos);
		return $respuesta;
	}
}
?>