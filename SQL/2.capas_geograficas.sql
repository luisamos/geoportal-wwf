<<<<<<< HEAD
--
-- TABLE: public.capas_geograficas
--
-- FECHA CREACION: 17-02-2025
-- FECHA MODIFICA: 20-06-2025
--
-- DROP VIEW v_listar_tematica_capas_servicios_geograficos;
-- DROP VIEW v_listar_capas_servicios_by;
-- DROP TABLE IF EXISTS public.capas_geograficas;

CREATE TABLE IF NOT EXISTS public.capas_geograficas
(
    id_capa_geografica serial,
    nombre character varying(60),
    alias character varying(60),
	id_tematica integer,
    tipo integer, -- 1: Vector 2: Raster
	visible boolean DEFAULT True,
	habilitado boolean DEFAULT True,
	orden integer DEFAULT 0,
	nombres_columnas character varying(100)[],
	alias_columnas character varying(100)[],
	visible_columnas boolean[],
	--acceso integer,
    id_usuario_crea integer,
    fecha_crea timestamp WITHOUT time ZONE DEFAULT now(),
	id_usuario_modifica integer,
    fecha_modifica timestamp WITHOUT time ZONE DEFAULT now(),    
    CONSTRAINT capas_geograficas_pkey PRIMARY KEY (id_capa_geografica)
);

SELECT id_capa_geografica, a.nombre, a.alias, b.id_tematica, b.descripcion, 
CASE
	WHEN a.tipo=1 THEN 'Vector'
	ELSE 'Raster'
END AS tipo,
CASE
	WHEN a.visible THEN '🟢'
	ELSE '⚫'
END AS visible,
a.orden
FROM public.capas_geograficas a
JOIN (SELECT h.id_tematica, COALESCE(p.nombre || ' > ', '') || h.nombre AS descripcion
			FROM public.tematica AS h LEFT JOIN public.tematica AS p ON h.id_padre = p.id_tematica
			WHERE h.habilitado = true AND h.id_padre != 0) b
ON a.id_tematica=b.id_tematica
WHERE a.habilitado = True
ORDER BY orden;

=======
--
-- TABLE: public.capas_geograficas
--
-- FECHA CREACION: 17-02-2025
-- FECHA MODIFICA: 20-06-2025
--
-- DROP VIEW v_listar_tematica_capas_servicios_geograficos;
-- DROP VIEW v_listar_capas_servicios_by;
-- DROP TABLE IF EXISTS public.capas_geograficas;

CREATE TABLE IF NOT EXISTS public.capas_geograficas
(
    id_capa_geografica serial,
    nombre character varying(60),
    alias character varying(60),
	id_tematica integer,
    tipo integer, -- 1: Vector 2: Raster
	visible boolean DEFAULT True,
	habilitado boolean DEFAULT True,
	orden integer DEFAULT 0,
	nombres_columnas character varying(100)[],
	alias_columnas character varying(100)[],
	visible_columnas boolean[],
	--acceso integer,
    id_usuario_crea integer,
    fecha_crea timestamp WITHOUT time ZONE DEFAULT now(),
	id_usuario_modifica integer,
    fecha_modifica timestamp WITHOUT time ZONE DEFAULT now(),    
    CONSTRAINT capas_geograficas_pkey PRIMARY KEY (id_capa_geografica)
);

SELECT id_capa_geografica, a.nombre, a.alias, b.id_tematica, b.descripcion, 
CASE
	WHEN a.tipo=1 THEN 'Vector'
	ELSE 'Raster'
END AS tipo,
CASE
	WHEN a.visible THEN '🟢'
	ELSE '⚫'
END AS visible,
a.orden
FROM public.capas_geograficas a
JOIN (SELECT h.id_tematica, COALESCE(p.nombre || ' > ', '') || h.nombre AS descripcion
			FROM public.tematica AS h LEFT JOIN public.tematica AS p ON h.id_padre = p.id_tematica
			WHERE h.habilitado = true AND h.id_padre != 0) b
ON a.id_tematica=b.id_tematica
WHERE a.habilitado = True
ORDER BY orden;

>>>>>>> c4032f0 (v.1.2.9 | 07/07/2025 00:50)
