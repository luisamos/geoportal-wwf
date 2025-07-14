<<<<<<< HEAD
--
-- TABLE: public.servicios_geograficos
--
-- FECHA CREACION: 17-02-2025
-- FECHA MODIFICA: 19-05-2025
--
-- DROP TABLE IF EXISTS public.servicios_geograficos;

CREATE TABLE IF NOT EXISTS public.servicios_geograficos
(
    id_servicio_geografico serial,
	tipo integer,
	id_tematica integer,
    direccion_web character varying(1000),
	capa character varying(200),
    nombre character varying(200),
	alias character varying(200),
	visible boolean DEFAULT True,
	orden integer DEFAULT 0,
    id_usuario_crea integer,
    fecha_crea timestamp WITHOUT time ZONE DEFAULT now(),
	id_usuario_modifica integer,
    fecha_modifica timestamp WITHOUT time ZONE DEFAULT now(),
    CONSTRAINT servicios_geograficos_pkey PRIMARY KEY (id_servicio_geografico)
);

SELECT a.id_servicio_geografico, a.tipo, b.id_tematica, a.direccion_web, a.capa, a.nombre, a.alias, a.visible, a.orden 
FROM public.servicios_geograficos a
JOIN public.tematica b
ON a.id_tematica = b.id_tematica
WHERE a.id_servicio_geografico = 2;


=======
--
-- TABLE: public.servicios_geograficos
--
-- FECHA CREACION: 17-02-2025
-- FECHA MODIFICA: 19-05-2025
--
-- DROP TABLE IF EXISTS public.servicios_geograficos;

CREATE TABLE IF NOT EXISTS public.servicios_geograficos
(
    id_servicio_geografico serial,
	tipo integer,
	id_tematica integer,
    direccion_web character varying(1000),
	capa character varying(200),
    nombre character varying(200),
	alias character varying(200),
	visible boolean DEFAULT True,
	orden integer DEFAULT 0,
    id_usuario_crea integer,
    fecha_crea timestamp WITHOUT time ZONE DEFAULT now(),
	id_usuario_modifica integer,
    fecha_modifica timestamp WITHOUT time ZONE DEFAULT now(),
    CONSTRAINT servicios_geograficos_pkey PRIMARY KEY (id_servicio_geografico)
);

SELECT a.id_servicio_geografico, a.tipo, b.id_tematica, a.direccion_web, a.capa, a.nombre, a.alias, a.visible, a.orden 
FROM public.servicios_geograficos a
JOIN public.tematica b
ON a.id_tematica = b.id_tematica
WHERE a.id_servicio_geografico = 2;


>>>>>>> c4032f0 (v.1.2.9 | 07/07/2025 00:50)
