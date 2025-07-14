<<<<<<< HEAD
--
-- View: public.v_listar_tematica_capas_servicios
--
-- FECHA CREACION: 17-02-2025
-- FECHA MODIFICA: 20-06-2025
--
-- DROP VIEW public.v_listar_tematica;

CREATE OR REPLACE VIEW v_listar_tematica AS 
SELECT h.id_tematica, COALESCE(p.nombre || ' > ', '') || h.nombre AS nombre, h.visible
FROM public.tematica AS h LEFT JOIN public.tematica AS p ON h.id_padre = p.id_tematica
WHERE h.habilitado = true AND h.id_padre != 0 ORDER BY p.orden, h.orden;

-- DROP VIEW public.v_listar_tematica_capas_servicios_geograficos;

CREATE OR REPLACE VIEW public.v_listar_tematica_capas_servicios_geograficos
 AS
 SELECT 'C' || LPAD(a.id_capa_geografica::text, 4, '0') as id,
 	0 as servicio,
 	tipo,
    a.nombre,
    a.alias,
    a.visible,
    a.orden,
    a.id_tematica,
    ''::character varying AS url
   FROM capas_geograficas a
  WHERE a.habilitado = true
UNION
SELECT 'S' || LPAD(a.id_servicio_geografico::text, 4, '0') AS id_capa_geografica,
 	1 as servicio,
 	a.tipo,
    a.capa,
    a.alias,
    a.visible,
    a.orden,
    a.id_tematica,    
    a.direccion_web AS url
   FROM servicios_geograficos a;

SELECT * FROM public.v_listar_tematica_capas_servicios_geograficos
ORDER BY id;

SELECT * FROM v_listar_tematica_capas_servicios_geograficos;

SELECT 
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
ORDER BY padre.orden, hijo.orden, c.orden;

--DROP VIEW public.v_listar_capas_servicios_by;

CREATE OR REPLACE VIEW v_listar_capas_servicios_by AS
SELECT id_tematica, 0 AS servicio, id_capa_geografica  AS id, alias, orden
	FROM public.capas_geograficas
	WHERE habilitado = True
UNION
SELECT id_tematica, 1 AS servicio, id_servicio_geografico  AS id, alias, orden
	FROM public.servicios_geograficos;

SELECT * FROM v_listar_capas_servicios_by WHERE id_tematica = 15
=======
--
-- View: public.v_listar_tematica_capas_servicios
--
-- FECHA CREACION: 17-02-2025
-- FECHA MODIFICA: 20-06-2025
--
-- DROP VIEW public.v_listar_tematica;

CREATE OR REPLACE VIEW v_listar_tematica AS 
SELECT h.id_tematica, COALESCE(p.nombre || ' > ', '') || h.nombre AS nombre, h.visible
FROM public.tematica AS h LEFT JOIN public.tematica AS p ON h.id_padre = p.id_tematica
WHERE h.habilitado = true AND h.id_padre != 0 ORDER BY p.orden, h.orden;

-- DROP VIEW public.v_listar_tematica_capas_servicios_geograficos;

CREATE OR REPLACE VIEW public.v_listar_tematica_capas_servicios_geograficos
 AS
 SELECT 'C' || LPAD(a.id_capa_geografica::text, 4, '0') as id,
 	0 as servicio,
 	tipo,
    a.nombre,
    a.alias,
    a.visible,
    a.orden,
    a.id_tematica,
    ''::character varying AS url
   FROM capas_geograficas a
  WHERE a.habilitado = true
UNION
SELECT 'S' || LPAD(a.id_servicio_geografico::text, 4, '0') AS id_capa_geografica,
 	1 as servicio,
 	a.tipo,
    a.capa,
    a.alias,
    a.visible,
    a.orden,
    a.id_tematica,    
    a.direccion_web AS url
   FROM servicios_geograficos a;

SELECT * FROM public.v_listar_tematica_capas_servicios_geograficos
ORDER BY id;

SELECT * FROM v_listar_tematica_capas_servicios_geograficos;

SELECT 
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
ORDER BY padre.orden, hijo.orden, c.orden;

--DROP VIEW public.v_listar_capas_servicios_by;

CREATE OR REPLACE VIEW v_listar_capas_servicios_by AS
SELECT id_tematica, 0 AS servicio, id_capa_geografica  AS id, alias, orden
	FROM public.capas_geograficas
	WHERE habilitado = True
UNION
SELECT id_tematica, 1 AS servicio, id_servicio_geografico  AS id, alias, orden
	FROM public.servicios_geograficos;

SELECT * FROM v_listar_capas_servicios_by WHERE id_tematica = 15
>>>>>>> c4032f0 (v.1.2.9 | 07/07/2025 00:50)
ORDER BY orden ASC;