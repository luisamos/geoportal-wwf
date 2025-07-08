<?php
class Visor2Model extends PostgreSQL
{
    public function __construct(){
        parent::__construct();
    }

    public function Listar(){
        $consultaSQL = "SELECT * FROM public.v_listar_tematica_capas_servicios_geograficos ORDER BY id_tematica;";
        $respuesta = $this->select_all($consultaSQL);
        return $respuesta;
    }

    public function Listar2(){
        $consultaSQL ="SELECT
                padre.id_tematica AS id_padre,
                padre.nombre AS nombre_padre,
                padre.visible AS visible_padre,
                padre.orden AS orden_padre,
                hijo.id_tematica AS id_hijo,
                hijo.nombre AS nombre_hijo,
                hijo.visible AS visible_hijo,
                hijo.orden AS orden_hijo,
                c.id AS id,
                c.servicio,
                c.tipo,
                c.nombre,
                c.alias,
                c.visible,
                c.orden,
                c.url
            FROM public.tematica padre
            LEFT JOIN public.tematica hijo ON padre.id_tematica = hijo.id_padre
            LEFT JOIN public.v_listar_tematica_capas_servicios_geograficos c ON hijo.id_tematica = c.id_tematica
            WHERE padre.id_padre = 0 AND
            padre.habilitado = True AND
            hijo.habilitado = True AND
            id IS NOT NULL
            ORDER BY padre.orden, hijo.orden, c.orden;";
        $respuesta = $this->select_all($consultaSQL);
        return $respuesta;
    }
}
?>