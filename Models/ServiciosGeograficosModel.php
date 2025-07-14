<?php
class ServiciosGeograficosModel extends PostgreSQL
{
	private $intIdServicioGeografico;
	private $intTipo;
	private $intIdTematica;
	private $strDireccionWeb;
	private $strCapa;
	private $strNombre;
	private $strAlias;
	private $bolVisible;
	private $intOrden;
	private $intIdUsuarioCrea;
	private $intIdUsuarioModifica;
	private $arrDatos;

	public function __construct()
	{
		parent::__construct();
	}

	public function getCantidadTotal(){
		$consulta_sql = "SELECT COUNT(*) as total_registro FROM public.servicios_geograficos;";
		$respuesta = $this->select($consulta_sql);
		return $respuesta;
	}

	public function getPagina($desde, $por_pagina){
		$consulta_sql = "SELECT a.id_servicio_geografico, CASE WHEN a.tipo=1 THEN 'OGC: WMS' ELSE 'ArcGIS Server' END AS tipo, a.direccion_web, a.capa, a.nombre, a.alias, 
			CASE WHEN a.visible THEN '🟢' ELSE '⚫' END AS visible, a.orden, b.id_tematica, b.descripcion_tematica
			FROM public.servicios_geograficos a
			JOIN (SELECT 
					h.id_tematica, 
					COALESCE(p.nombre || ' > ', '') || h.nombre AS descripcion_tematica
				FROM public.tematica h
				LEFT JOIN public.tematica p ON h.id_padre = p.id_tematica
				WHERE h.habilitado = true AND h.id_padre != 0
				) b ON a.id_tematica = b.id_tematica
			ORDER BY a.orden DESC LIMIT '$por_pagina' OFFSET '$desde';";

		$respuesta =$this->select_all($consulta_sql);
		return $respuesta;
	}

	public function listar(){
		$consultaSQL = "SELECT * FROM public.servicios_geograficos;";
		$respuesta = $this->select_all($consultaSQL);
		return $respuesta;
	}

	public function listarPor(int $idServicioGeografico){
		$this->intIdServicioGeografico = $idServicioGeografico;
		$consultaSQL = "SELECT a.id_servicio_geografico, a.tipo, b.id_tematica, b.nombre, a.direccion_web, a.capa, 
			a.nombre, a.alias, a.visible, a.orden
			FROM public.servicios_geograficos a
			JOIN public.tematica b
			ON a.id_tematica = b.id_tematica
			WHERE a.id_servicio_geografico = ?";
		$respuesta = $this->select_array($consultaSQL, array($this->intIdServicioGeografico));
		return $respuesta;
	}

	public function agregar(int $tipo, int $idTematica, string $direccionWeb, string $capa, string $nombre, string $alias, int $visible, int $idUsuarioCrea){

		$this->intTipo = $tipo;
		$this->intIdTematica= $idTematica;
		$this->strDireccionWeb = $direccionWeb;
		$this->strCapa= $capa;
		$this->strNombre = $nombre;
		$this->strAlias = $alias;
		$this->bolVisible = $visible;
		$this->intIdUsuarioCrea = $idUsuarioCrea;

		$consultaSQL = "INSERT INTO public.servicios_geograficos
			(tipo, id_tematica, direccion_web, capa, nombre, alias, visible, id_usuario_crea, id_usuario_modifica)
			VALUES(?,?,?,?,?,?,?,?,?)";
		$parametros = array(
			$this->intTipo,
			$this->intIdTematica,
			$this->strDireccionWeb,
			$this->strCapa,
			$this->strNombre,
			$this->strAlias,
			$this->bolVisible,
			$this->intIdUsuarioCrea,
			$this->intIdUsuarioCrea);
		$respuesta = $this->insert($consultaSQL, $parametros);
		return $respuesta;
	}

	public function actualizar(int $idServicioGeografico, int $tipo, int $idTematica, string $direccionWeb, string $capa, string $nombre,
		string $alias, int $visible, int $idUsuarioModifica){

		$this->intIdServicioGeografico = $idServicioGeografico;
		$this->intTipo = $tipo;
		$this->intIdTematica= $idTematica;
		$this->strDireccionWeb = $direccionWeb;
		$this->strCapa = $capa;
		$this->strNombre = $nombre;
		$this->strAlias = $alias;
		$this->bolVisible = $visible;
		$this->intIdUsuarioModifica = $idUsuarioModifica;

		$consultaSQL = "UPDATE public.servicios_geograficos SET 
			tipo = ?,
			id_tematica = ?,
			direccion_web = ?,
			capa = ?,
			nombre = ?,
			alias = ?,
			visible = ?,
			id_usuario_modifica = ?
			WHERE id_servicio_geografico = ?";

		$parametros = array(
			$this->intTipo,
			$this->intIdTematica,
			$this->strDireccionWeb,
			$this->strCapa,
			$this->strNombre,
			$this->strAlias,
			$this->bolVisible,
			$this->intIdUsuarioModifica,
			$this->intIdServicioGeografico
		);

		$respuesta = $this->update($consultaSQL, $parametros);
		return $respuesta;
	}

	public function eliminar(int $idServicioGeografico){
		$this->intIdServicioGeografico = $idServicioGeografico;
		$consultaSQL = "DELETE FROM public.servicios_geograficos WHERE id_servicio_geografico = $this->intIdServicioGeografico";
		$respuesta = $this->delete($consultaSQL);
		return $respuesta;
	}

	public function actualizarOrden(array $datos)
	{
		$this->arrDatos = $datos;
		$consultaSQL ="UPDATE public.servicios_geograficos SET orden = :orden WHERE id_servicio_geografico = :id";
		$respuesta = $this->update_all($consultaSQL, $this->arrDatos);
		return $respuesta;
	}
}
?>