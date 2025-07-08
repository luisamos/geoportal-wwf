<<<<<<< HEAD
--
-- TABLE: public.tematica
--
-- FECHA CREACION: 01-05-2025
-- FECHA MODIFICA: 20-06-2025
--
-- DROP TABLE IF EXISTS public.tematica;
CREATE TABLE IF NOT EXISTS public.tematica
(
    id_tematica serial,
    nombre character varying(60),
	visible boolean DEFAULT False,
	habilitado boolean DEFAULT True,
	orden integer DEFAULT 0,
	id_padre integer DEFAULT 0,
    id_usuario_crea integer,
    fecha_crea timestamp WITHOUT time ZONE DEFAULT now(),
	id_usuario_modifica integer,
    fecha_modifica timestamp WITHOUT time ZONE DEFAULT now(),    
    CONSTRAINT tematica_pkey PRIMARY KEY (id_tematica)
);
--
-- CATEGORIA
--
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Paisajes de Intervensión', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Información Base', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Entorno físico ambiental', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Entorno social', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Áreas de conservación', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Derechos de uso', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Presiones y amanazas ambientales', True, 1, NOW(), 1, NOW());

--
-- SUBCATEGORIA
--
INSERT INTO public.tematica(nombre, id_padre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Unidades Administrativas',2, True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, id_padre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Transportes',2, True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, id_padre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Hidrografía',2, True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, id_padre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Títulos Habilitantes',6, True, 1, NOW(), 1, NOW());

SELECT * FROM public.tematica;


SELECT id_tematica, nombre, orden FROM public.tematica
WHERE habilitado = True
AND id_padre = 0
ORDER BY orden ASC;

SELECT id_tematica, nombre, orden FROM public.tematica
WHERE habilitado = True
AND id_padre = 2
ORDER BY orden ASC;

SELECT 
	padre.id_tematica as id_padre,
	padre.nombre as nombre_padre,
	padre.visible as visible_padre,
	padre.orden as orden_padre,
	hijo.id_tematica as id_hijo,
	hijo.nombre as nombre_hijo,
	hijo.visible as visible_hijo,
	hijo.orden as orden_hijo,
	c.id_capa_geografica,
	--c.servicio,
	c.tipo,
	c.nombre,
	c.alias,
	c.visible,
	c.orden
	--c.url
FROM public.tematica padre
LEFT JOIN public.tematica hijo ON padre.id_tematica = hijo.id_padre
LEFT JOIN public.capas_geograficas c ON hijo.id_tematica = c.id_tematica
WHERE padre.id_padre = 0
ORDER BY padre.orden, hijo.orden, c.orden;

=======
--
-- TABLE: public.tematica
--
-- FECHA CREACION: 01-05-2025
-- FECHA MODIFICA: 20-06-2025
--
-- DROP TABLE IF EXISTS public.tematica;
CREATE TABLE IF NOT EXISTS public.tematica
(
    id_tematica serial,
    nombre character varying(60),
	visible boolean DEFAULT False,
	habilitado boolean DEFAULT True,
	orden integer DEFAULT 0,
	id_padre integer DEFAULT 0,
    id_usuario_crea integer,
    fecha_crea timestamp WITHOUT time ZONE DEFAULT now(),
	id_usuario_modifica integer,
    fecha_modifica timestamp WITHOUT time ZONE DEFAULT now(),    
    CONSTRAINT tematica_pkey PRIMARY KEY (id_tematica)
);
--
-- CATEGORIA
--
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Paisajes de Intervensión', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Información Base', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Entorno físico ambiental', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Entorno social', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Áreas de conservación', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Derechos de uso', True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Presiones y amanazas ambientales', True, 1, NOW(), 1, NOW());

--
-- SUBCATEGORIA
--
INSERT INTO public.tematica(nombre, id_padre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Unidades Administrativas',2, True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, id_padre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Transportes',2, True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, id_padre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Hidrografía',2, True, 1, NOW(), 1, NOW());
INSERT INTO public.tematica(nombre, id_padre, visible, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica)
	VALUES ('Títulos Habilitantes',6, True, 1, NOW(), 1, NOW());

SELECT * FROM public.tematica;


SELECT id_tematica, nombre, orden FROM public.tematica
WHERE habilitado = True
AND id_padre = 0
ORDER BY orden ASC;

SELECT id_tematica, nombre, orden FROM public.tematica
WHERE habilitado = True
AND id_padre = 2
ORDER BY orden ASC;

SELECT 
	padre.id_tematica as id_padre,
	padre.nombre as nombre_padre,
	padre.visible as visible_padre,
	padre.orden as orden_padre,
	hijo.id_tematica as id_hijo,
	hijo.nombre as nombre_hijo,
	hijo.visible as visible_hijo,
	hijo.orden as orden_hijo,
	c.id_capa_geografica,
	--c.servicio,
	c.tipo,
	c.nombre,
	c.alias,
	c.visible,
	c.orden
	--c.url
FROM public.tematica padre
LEFT JOIN public.tematica hijo ON padre.id_tematica = hijo.id_padre
LEFT JOIN public.capas_geograficas c ON hijo.id_tematica = c.id_tematica
WHERE padre.id_padre = 0
ORDER BY padre.orden, hijo.orden, c.orden;

>>>>>>> c4032f0 (v.1.2.9 | 07/07/2025 00:50)
