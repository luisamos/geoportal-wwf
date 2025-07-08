--
-- PostgreSQL database dump
--

-- Dumped from database version 13.13
-- Dumped by pg_dump version 15.4

-- Started on 2025-06-18 11:01:05

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 6 (class 2615 OID 80960)
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO postgres;

--
-- TOC entry 1075 (class 1255 OID 82282)
-- Name: gestionar_esquema(text, text, text); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.gestionar_esquema(esquema_nombre text, accion text, esquema_actual text DEFAULT NULL::text) RETURNS TABLE(estado boolean, mensaje text)
    LANGUAGE plpgsql
    AS $_$
DECLARE
    esquema_existente BOOLEAN;
    esquema_actual_existente BOOLEAN;
    regex_valid TEXT := '^[a-z0-9_]{1,50}$';
    esquema_limpio TEXT;
    esquema_actual_limpio TEXT;
    palabras_reservadas TEXT[] := ARRAY['area', 'user', 'table', 'schema', 'index', 'select', 'insert', 'update', 'delete'];
BEGIN
    -- Limpiar y normalizar el nombre del esquema
    esquema_limpio := LOWER(REGEXP_REPLACE(REGEXP_REPLACE(TRIM(TRANSLATE(esquema_nombre, 'ÁÉÍÓÚÑáéíóúñ', 'AEIOUNaeioun')), '\s+', '_', 'g'), '_y_', '_', 'g'));
    esquema_actual_limpio := LOWER(REGEXP_REPLACE(REGEXP_REPLACE(TRIM(TRANSLATE(esquema_actual, 'ÁÉÍÓÚÑáéíóúñ', 'AEIOUNaeioun')), '\s+', '_', 'g'), '_y_', '_', 'g'));

    -- Validar el esquema limpio
    IF esquema_limpio = '' THEN
        RETURN QUERY SELECT FALSE, 'Error: El nombre del esquema no puede estar vacío después de la normalización.';
        RETURN;
    END IF;
    
    IF esquema_limpio = ANY(palabras_reservadas) THEN
        RETURN QUERY SELECT FALSE, 'Error: El nombre del esquema no puede ser una palabra reservada del sistema.';
        RETURN;
    END IF;
    
    IF LENGTH(esquema_limpio) > 50 THEN
        RETURN QUERY SELECT FALSE, 'Error: El nombre del esquema supera los 50 caracteres permitidos.';
        RETURN;
    END IF;
    
    IF esquema_limpio !~ regex_valid THEN
        RETURN QUERY SELECT FALSE, 'Error: El nombre del esquema contiene caracteres no permitidos.';
        RETURN;
    END IF;

    -- Verificar si el esquema ya existe
    SELECT EXISTS (SELECT 1 FROM information_schema.schemata WHERE schema_name = esquema_limpio)
    INTO esquema_existente;

    IF accion = 'agregar' THEN
        IF esquema_existente THEN
            RETURN QUERY SELECT FALSE, 'Error: El esquema ya existe. No se puede agregar.';
        ELSE
            EXECUTE format('CREATE SCHEMA %I', esquema_limpio);
            RETURN QUERY SELECT TRUE, 'Éxito: El esquema ha sido creado correctamente.';
        END IF;

    ELSIF accion = 'actualizar' THEN
        IF esquema_actual IS NULL THEN
            RETURN QUERY SELECT FALSE, 'Error: Debe proporcionar el esquema actual para actualizarlo.';
            RETURN;
        END IF;

        -- Verificar si el esquema actual existe
        SELECT EXISTS (SELECT 1 FROM information_schema.schemata WHERE schema_name = esquema_actual_limpio)
        INTO esquema_actual_existente;

        IF NOT esquema_actual_existente THEN
            RETURN QUERY SELECT FALSE, 'Error: El esquema actual no existe. No se puede actualizar.';
        END IF;

        -- Verificar si el nuevo nombre ya existe
        IF esquema_existente THEN
            RETURN QUERY SELECT FALSE, 'Error: El nombre del esquema nuevo ya existe. No se puede renombrar.';
        END IF;

        EXECUTE format('ALTER SCHEMA %I RENAME TO %I', esquema_actual_limpio, esquema_limpio);
        RETURN QUERY SELECT TRUE, 'Éxito: El esquema ha sido actualizado correctamente.';

    ELSE
        RETURN QUERY SELECT FALSE, 'Error: Acción no válida. Debe ser "agregar" o "actualizar".';
    END IF;
END;
$_$;


ALTER FUNCTION public.gestionar_esquema(esquema_nombre text, accion text, esquema_actual text) OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 215 (class 1259 OID 80961)
-- Name: archivo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.archivo (
    id_archivo integer NOT NULL,
    archiv_id_tipo smallint NOT NULL,
    archiv_codigo character varying(12) NOT NULL,
    archiv_nombre character varying(100) NOT NULL,
    archiv_archivo character varying(100) NOT NULL,
    archiv_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint,
    archiv_acceso smallint
);


ALTER TABLE public.archivo OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 80964)
-- Name: archivo_campo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.archivo_campo (
    id_archivo_campo integer NOT NULL,
    arccam_id_tipo smallint NOT NULL,
    arccam_nombre character varying(100) NOT NULL,
    arccam_archivo character varying(100) NOT NULL,
    arccam_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.archivo_campo OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 80967)
-- Name: archivo_campo_id_archivo_campo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.archivo_campo_id_archivo_campo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.archivo_campo_id_archivo_campo_seq OWNER TO postgres;

--
-- TOC entry 5145 (class 0 OID 0)
-- Dependencies: 217
-- Name: archivo_campo_id_archivo_campo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.archivo_campo_id_archivo_campo_seq OWNED BY public.archivo_campo.id_archivo_campo;


--
-- TOC entry 218 (class 1259 OID 80969)
-- Name: archivo_id_archivo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.archivo_id_archivo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.archivo_id_archivo_seq OWNER TO postgres;

--
-- TOC entry 5146 (class 0 OID 0)
-- Dependencies: 218
-- Name: archivo_id_archivo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.archivo_id_archivo_seq OWNED BY public.archivo.id_archivo;


--
-- TOC entry 219 (class 1259 OID 80971)
-- Name: bitacora; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.bitacora (
    id_bitacora integer NOT NULL,
    bitaco_id_proy integer NOT NULL,
    bitaco_id_elemento integer NOT NULL,
    bitaco_campo1 character varying(200),
    bitaco_campo2 character varying(500),
    bitaco_campo3 text,
    bitaco_estado smallint NOT NULL,
    bitaco_id_ref integer NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.bitacora OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 80977)
-- Name: bitacora_id_bitacora_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.bitacora_id_bitacora_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.bitacora_id_bitacora_seq OWNER TO postgres;

--
-- TOC entry 5147 (class 0 OID 0)
-- Dependencies: 220
-- Name: bitacora_id_bitacora_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.bitacora_id_bitacora_seq OWNED BY public.bitacora.id_bitacora;


--
-- TOC entry 290 (class 1259 OID 97059)
-- Name: capas_geograficas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.capas_geograficas (
    id_capa_geografica integer NOT NULL,
    nombre character varying(60),
    alias character varying(60),
    id_tematica integer,
    tipo integer,
    visible boolean DEFAULT true,
    habilitado boolean DEFAULT true,
    orden integer DEFAULT 0,
    nombres_columnas character varying(100)[],
    alias_columnas character varying(100)[],
    visible_columnas boolean[],
    id_usuario_crea integer,
    fecha_crea timestamp without time zone DEFAULT now(),
    id_usuario_modifica integer,
    fecha_modifica timestamp without time zone DEFAULT now()
);


ALTER TABLE public.capas_geograficas OWNER TO postgres;

--
-- TOC entry 252 (class 1259 OID 82295)
-- Name: seq_orden_capa_geografica1; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.seq_orden_capa_geografica1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_orden_capa_geografica1 OWNER TO postgres;

--
-- TOC entry 254 (class 1259 OID 82299)
-- Name: capas_geograficas1; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.capas_geograficas1 (
    id_capa_geografica integer NOT NULL,
    nombre character varying(60),
    alias character varying(60),
    id_tematica integer,
    tipo integer,
    visible boolean DEFAULT true,
    habilitado boolean DEFAULT true,
    orden integer DEFAULT nextval('public.seq_orden_capa_geografica1'::regclass),
    nombres_columnas character varying(100)[],
    alias_columnas character varying(100)[],
    visible_columnas boolean[],
    id_usuario_crea integer,
    fecha_crea timestamp without time zone DEFAULT now(),
    id_usuario_modifica integer,
    fecha_modifica timestamp without time zone DEFAULT now()
);


ALTER TABLE public.capas_geograficas1 OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 82297)
-- Name: capas_geograficas_id_capa_geografica_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.capas_geograficas_id_capa_geografica_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.capas_geograficas_id_capa_geografica_seq OWNER TO postgres;

--
-- TOC entry 5148 (class 0 OID 0)
-- Dependencies: 253
-- Name: capas_geograficas_id_capa_geografica_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.capas_geograficas_id_capa_geografica_seq OWNED BY public.capas_geograficas1.id_capa_geografica;


--
-- TOC entry 289 (class 1259 OID 97057)
-- Name: capas_geograficas_id_capa_geografica_seq1; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.capas_geograficas_id_capa_geografica_seq1
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.capas_geograficas_id_capa_geografica_seq1 OWNER TO postgres;

--
-- TOC entry 5149 (class 0 OID 0)
-- Dependencies: 289
-- Name: capas_geograficas_id_capa_geografica_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.capas_geograficas_id_capa_geografica_seq1 OWNED BY public.capas_geograficas.id_capa_geografica;


--
-- TOC entry 287 (class 1259 OID 83118)
-- Name: categoria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categoria (
    id_categoria integer NOT NULL,
    nombre character varying(60),
    visible boolean DEFAULT false,
    habilitado boolean DEFAULT true,
    orden integer DEFAULT 0,
    id_padre integer DEFAULT 0,
    id_usuario_crea integer,
    fecha_crea timestamp without time zone DEFAULT now(),
    id_usuario_modifica integer,
    fecha_modifica timestamp without time zone DEFAULT now()
);


ALTER TABLE public.categoria OWNER TO postgres;

--
-- TOC entry 286 (class 1259 OID 83116)
-- Name: categoria_id_categoria_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categoria_id_categoria_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categoria_id_categoria_seq OWNER TO postgres;

--
-- TOC entry 5150 (class 0 OID 0)
-- Dependencies: 286
-- Name: categoria_id_categoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categoria_id_categoria_seq OWNED BY public.categoria.id_categoria;


--
-- TOC entry 221 (class 1259 OID 81002)
-- Name: modulo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.modulo (
    id_modulo integer NOT NULL,
    modulo_titulo character varying(100) NOT NULL,
    modulo_descripcion character varying(200),
    modulo_ruta character varying(50),
    modulo_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.modulo OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 81005)
-- Name: modulo_id_modulo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.modulo_id_modulo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.modulo_id_modulo_seq OWNER TO postgres;

--
-- TOC entry 5151 (class 0 OID 0)
-- Dependencies: 222
-- Name: modulo_id_modulo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.modulo_id_modulo_seq OWNED BY public.modulo.id_modulo;


--
-- TOC entry 223 (class 1259 OID 81007)
-- Name: noticia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.noticia (
    id_noticia integer NOT NULL,
    notici_titulo character varying(100) NOT NULL,
    notici_imagen1 character varying(100),
    notici_imagen2 character varying(100),
    notici_descripcion text,
    notici_ruta character varying(300),
    notici_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint,
    notici_tipo smallint,
    notici_url character varying(400)
);


ALTER TABLE public.noticia OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 81013)
-- Name: noticia_id_noticia_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.noticia_id_noticia_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.noticia_id_noticia_seq OWNER TO postgres;

--
-- TOC entry 5152 (class 0 OID 0)
-- Dependencies: 224
-- Name: noticia_id_noticia_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.noticia_id_noticia_seq OWNED BY public.noticia.id_noticia;


--
-- TOC entry 225 (class 1259 OID 81015)
-- Name: paisaje; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.paisaje (
    id_paisaje integer NOT NULL,
    paisaj_paisaje character varying(200),
    paisaj_estrategia character varying(300) NOT NULL,
    paisaj_objetivo character varying(400) NOT NULL,
    paisaj_meta1 character varying(500) NOT NULL,
    paisaj_meta2 character varying(500) NOT NULL,
    paisaj_meta3 character varying(500),
    paisaj_meta4 character varying(500),
    paisaj_meta5 character varying(500),
    paisaj_indicador smallint NOT NULL,
    paisaj_estado smallint NOT NULL,
    paisaj_id_ref integer,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.paisaje OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 81021)
-- Name: paisaje_id_paisaje_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.paisaje_id_paisaje_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.paisaje_id_paisaje_seq OWNER TO postgres;

--
-- TOC entry 5153 (class 0 OID 0)
-- Dependencies: 226
-- Name: paisaje_id_paisaje_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.paisaje_id_paisaje_seq OWNED BY public.paisaje.id_paisaje;


--
-- TOC entry 227 (class 1259 OID 81023)
-- Name: permiso; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.permiso (
    id_permiso integer NOT NULL,
    permis_id_rol integer NOT NULL,
    permis_id_modulo integer NOT NULL,
    permis_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.permiso OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 81026)
-- Name: permiso_id_permiso_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.permiso_id_permiso_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.permiso_id_permiso_seq OWNER TO postgres;

--
-- TOC entry 5154 (class 0 OID 0)
-- Dependencies: 228
-- Name: permiso_id_permiso_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.permiso_id_permiso_seq OWNED BY public.permiso.id_permiso;


--
-- TOC entry 229 (class 1259 OID 81028)
-- Name: persona; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.persona (
    id_persona integer NOT NULL,
    person_num_documento character varying(8) NOT NULL,
    person_nombres character varying(50) NOT NULL,
    person_apellidos character varying(100) NOT NULL,
    person_celular character varying(9) NOT NULL,
    person_email character varying(100) NOT NULL,
    person_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.persona OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 81031)
-- Name: persona_id_persona_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.persona_id_persona_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.persona_id_persona_seq OWNER TO postgres;

--
-- TOC entry 5155 (class 0 OID 0)
-- Dependencies: 230
-- Name: persona_id_persona_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.persona_id_persona_seq OWNED BY public.persona.id_persona;


--
-- TOC entry 231 (class 1259 OID 81033)
-- Name: planoteca; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.planoteca (
    id_planoteca integer NOT NULL,
    planot_id_tipo smallint NOT NULL,
    planot_codigo character varying(12) NOT NULL,
    planot_nombre character varying(100) NOT NULL,
    planot_archivo character varying(100) NOT NULL,
    planot_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint,
    planot_img character varying(100),
    planot_tag character varying(200)
);


ALTER TABLE public.planoteca OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 81039)
-- Name: planoteca_id_planoteca_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.planoteca_id_planoteca_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.planoteca_id_planoteca_seq OWNER TO postgres;

--
-- TOC entry 5156 (class 0 OID 0)
-- Dependencies: 232
-- Name: planoteca_id_planoteca_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.planoteca_id_planoteca_seq OWNED BY public.planoteca.id_planoteca;


--
-- TOC entry 233 (class 1259 OID 81041)
-- Name: portada; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.portada (
    id_portada integer NOT NULL,
    portad_titulo character varying(100) NOT NULL,
    portad_imagen character varying(100) NOT NULL,
    portad_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.portada OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 81044)
-- Name: portada_id_portada_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.portada_id_portada_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.portada_id_portada_seq OWNER TO postgres;

--
-- TOC entry 5157 (class 0 OID 0)
-- Dependencies: 234
-- Name: portada_id_portada_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.portada_id_portada_seq OWNED BY public.portada.id_portada;


--
-- TOC entry 235 (class 1259 OID 81046)
-- Name: programa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.programa (
    id_programa integer NOT NULL,
    progra_nombre character varying(50) NOT NULL,
    progra_descripcion character varying(300) NOT NULL,
    progra_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.programa OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 81049)
-- Name: programa_id_programa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.programa_id_programa_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.programa_id_programa_seq OWNER TO postgres;

--
-- TOC entry 5158 (class 0 OID 0)
-- Dependencies: 236
-- Name: programa_id_programa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.programa_id_programa_seq OWNED BY public.programa.id_programa;


--
-- TOC entry 237 (class 1259 OID 81051)
-- Name: rol; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rol (
    id_rol integer NOT NULL,
    rol_nombre character varying(50) NOT NULL,
    rol_descripcion character varying(100) NOT NULL,
    rol_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.rol OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 81054)
-- Name: rol_id_rol_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rol_id_rol_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rol_id_rol_seq OWNER TO postgres;

--
-- TOC entry 5159 (class 0 OID 0)
-- Dependencies: 238
-- Name: rol_id_rol_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rol_id_rol_seq OWNED BY public.rol.id_rol;


--
-- TOC entry 278 (class 1259 OID 82563)
-- Name: seq_orden_servicio_geografico; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.seq_orden_servicio_geografico
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_orden_servicio_geografico OWNER TO postgres;

--
-- TOC entry 271 (class 1259 OID 82517)
-- Name: seq_orden_tematica1; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.seq_orden_tematica1
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.seq_orden_tematica1 OWNER TO postgres;

--
-- TOC entry 280 (class 1259 OID 82567)
-- Name: servicios_geograficos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.servicios_geograficos (
    id_servicio_geografico integer NOT NULL,
    tipo integer,
    id_tematica integer,
    direccion_web character varying(1000),
    capa character varying(200),
    nombre character varying(200),
    alias character varying(200),
    visible boolean DEFAULT true,
    orden integer DEFAULT nextval('public.seq_orden_servicio_geografico'::regclass),
    id_usuario_crea integer,
    fecha_crea timestamp without time zone DEFAULT now(),
    id_usuario_modifica integer,
    fecha_modifica timestamp without time zone DEFAULT now()
);


ALTER TABLE public.servicios_geograficos OWNER TO postgres;

--
-- TOC entry 279 (class 1259 OID 82565)
-- Name: servicios_geograficos_id_servicio_geografico_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.servicios_geograficos_id_servicio_geografico_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.servicios_geograficos_id_servicio_geografico_seq OWNER TO postgres;

--
-- TOC entry 5160 (class 0 OID 0)
-- Dependencies: 279
-- Name: servicios_geograficos_id_servicio_geografico_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.servicios_geograficos_id_servicio_geografico_seq OWNED BY public.servicios_geograficos.id_servicio_geografico;


--
-- TOC entry 273 (class 1259 OID 82521)
-- Name: tematica; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tematica (
    id_tematica integer NOT NULL,
    nombre_esquema character varying(60),
    descripcion_esquema character varying(60),
    visible boolean DEFAULT false,
    habilitado boolean DEFAULT true,
    orden_esquema integer DEFAULT nextval('public.seq_orden_tematica1'::regclass),
    id_usuario_crea integer,
    fecha_crea timestamp without time zone DEFAULT now(),
    id_usuario_modifica integer,
    fecha_modifica timestamp without time zone DEFAULT now()
);


ALTER TABLE public.tematica OWNER TO postgres;

--
-- TOC entry 272 (class 1259 OID 82519)
-- Name: tematica_id_tematica_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tematica_id_tematica_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tematica_id_tematica_seq OWNER TO postgres;

--
-- TOC entry 5161 (class 0 OID 0)
-- Dependencies: 272
-- Name: tematica_id_tematica_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tematica_id_tematica_seq OWNED BY public.tematica.id_tematica;


--
-- TOC entry 239 (class 1259 OID 81081)
-- Name: tipo_archivo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tipo_archivo (
    id_tipo_archivo integer NOT NULL,
    tiparc_nombre character varying(50) NOT NULL,
    tiparc_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.tipo_archivo OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 81084)
-- Name: tipo_archivo_id_tipo_archivo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tipo_archivo_id_tipo_archivo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tipo_archivo_id_tipo_archivo_seq OWNER TO postgres;

--
-- TOC entry 5162 (class 0 OID 0)
-- Dependencies: 240
-- Name: tipo_archivo_id_tipo_archivo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tipo_archivo_id_tipo_archivo_seq OWNED BY public.tipo_archivo.id_tipo_archivo;


--
-- TOC entry 241 (class 1259 OID 81086)
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuario (
    id_usuario integer NOT NULL,
    usuari_id_persona integer NOT NULL,
    usuari_nombre character varying(15) NOT NULL,
    usuari_clave character varying(75) NOT NULL,
    usuari_id_rol integer NOT NULL,
    usuari_estado smallint NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.usuario OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 81089)
-- Name: usuario_archivo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuario_archivo (
    id_usuario_archivo integer NOT NULL,
    usuarc_id_usuario integer NOT NULL,
    usuarc_id_archivo integer NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.usuario_archivo OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 81092)
-- Name: usuario_archivo_id_usuario_archivo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuario_archivo_id_usuario_archivo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuario_archivo_id_usuario_archivo_seq OWNER TO postgres;

--
-- TOC entry 5163 (class 0 OID 0)
-- Dependencies: 243
-- Name: usuario_archivo_id_usuario_archivo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuario_archivo_id_usuario_archivo_seq OWNED BY public.usuario_archivo.id_usuario_archivo;


--
-- TOC entry 244 (class 1259 OID 81094)
-- Name: usuario_capa; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuario_capa (
    id_usuario_capa integer NOT NULL,
    usucap_id_usuario integer NOT NULL,
    usucap_id_capa integer NOT NULL,
    created timestamp without time zone NOT NULL,
    created_by smallint NOT NULL,
    updated timestamp without time zone,
    updated_by smallint
);


ALTER TABLE public.usuario_capa OWNER TO postgres;

--
-- TOC entry 245 (class 1259 OID 81097)
-- Name: usuario_capa_id_usuario_capa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuario_capa_id_usuario_capa_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuario_capa_id_usuario_capa_seq OWNER TO postgres;

--
-- TOC entry 5164 (class 0 OID 0)
-- Dependencies: 245
-- Name: usuario_capa_id_usuario_capa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuario_capa_id_usuario_capa_seq OWNED BY public.usuario_capa.id_usuario_capa;


--
-- TOC entry 246 (class 1259 OID 81099)
-- Name: usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.usuario_id_usuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuario_id_usuario_seq OWNER TO postgres;

--
-- TOC entry 5165 (class 0 OID 0)
-- Dependencies: 246
-- Name: usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.usuario_id_usuario_seq OWNED BY public.usuario.id_usuario;


--
-- TOC entry 292 (class 1259 OID 97079)
-- Name: v_listar_capas_servicios_by; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.v_listar_capas_servicios_by AS
 SELECT capas_geograficas.id_tematica,
    0 AS servicio,
    capas_geograficas.id_capa_geografica AS id,
    capas_geograficas.alias,
    capas_geograficas.orden
   FROM public.capas_geograficas
  WHERE (capas_geograficas.habilitado = true)
UNION
 SELECT servicios_geograficos.id_tematica,
    1 AS servicio,
    servicios_geograficos.id_servicio_geografico AS id,
    servicios_geograficos.alias,
    servicios_geograficos.orden
   FROM public.servicios_geograficos;


ALTER TABLE public.v_listar_capas_servicios_by OWNER TO postgres;

--
-- TOC entry 288 (class 1259 OID 83213)
-- Name: v_listar_tematica; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.v_listar_tematica AS
 SELECT h.id_categoria,
    (COALESCE(((p.nombre)::text || ' > '::text), ''::text) || (h.nombre)::text) AS nombre,
    h.visible
   FROM (public.categoria h
     LEFT JOIN public.categoria p ON ((h.id_padre = p.id_categoria)))
  WHERE ((h.habilitado = true) AND (h.id_padre <> 0))
  ORDER BY p.orden, h.orden;


ALTER TABLE public.v_listar_tematica OWNER TO postgres;

--
-- TOC entry 281 (class 1259 OID 82581)
-- Name: v_listar_tematica_capas_servicios; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.v_listar_tematica_capas_servicios AS
 SELECT a.id_capa_geografica,
    a.nombre,
    a.alias,
    a.visible,
    a.orden,
    b.id_tematica,
    b.nombre_esquema,
    b.descripcion_esquema,
    b.visible AS visible_esquema,
    ''::character varying AS url_wms
   FROM (public.capas_geograficas1 a
     JOIN public.tematica b ON ((a.id_tematica = b.id_tematica)))
  WHERE (a.habilitado = true)
UNION
 SELECT a.id_servicio_geografico AS id_capa_geografica,
    a.nombre,
    a.alias,
    a.visible,
    a.orden,
    b.id_tematica,
    b.nombre_esquema,
    b.descripcion_esquema,
    b.visible AS visible_esquema,
    a.direccion_web AS url_wms
   FROM (public.servicios_geograficos a
     JOIN public.tematica b ON ((a.id_tematica = b.id_tematica)));


ALTER TABLE public.v_listar_tematica_capas_servicios OWNER TO postgres;

--
-- TOC entry 291 (class 1259 OID 97074)
-- Name: v_listar_tematica_capas_servicios_geograficos; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.v_listar_tematica_capas_servicios_geograficos AS
 SELECT ('C'::text || lpad((a.id_capa_geografica)::text, 4, '0'::text)) AS id,
    0 AS servicio,
    a.tipo,
    a.nombre,
    a.alias,
    a.visible,
    a.orden,
    a.id_tematica,
    ''::character varying AS url
   FROM public.capas_geograficas a
  WHERE (a.habilitado = true)
UNION
 SELECT ('S'::text || lpad((a.id_servicio_geografico)::text, 4, '0'::text)) AS id,
    1 AS servicio,
    a.tipo,
    a.capa AS nombre,
    a.alias,
    a.visible,
    a.orden,
    a.id_tematica,
    a.direccion_web AS url
   FROM public.servicios_geograficos a;


ALTER TABLE public.v_listar_tematica_capas_servicios_geograficos OWNER TO postgres;

--
-- TOC entry 4855 (class 2604 OID 81101)
-- Name: archivo id_archivo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archivo ALTER COLUMN id_archivo SET DEFAULT nextval('public.archivo_id_archivo_seq'::regclass);


--
-- TOC entry 4856 (class 2604 OID 81102)
-- Name: archivo_campo id_archivo_campo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archivo_campo ALTER COLUMN id_archivo_campo SET DEFAULT nextval('public.archivo_campo_id_archivo_campo_seq'::regclass);


--
-- TOC entry 4857 (class 2604 OID 81103)
-- Name: bitacora id_bitacora; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bitacora ALTER COLUMN id_bitacora SET DEFAULT nextval('public.bitacora_id_bitacora_seq'::regclass);


--
-- TOC entry 4895 (class 2604 OID 97062)
-- Name: capas_geograficas id_capa_geografica; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.capas_geograficas ALTER COLUMN id_capa_geografica SET DEFAULT nextval('public.capas_geograficas_id_capa_geografica_seq1'::regclass);


--
-- TOC entry 4871 (class 2604 OID 82302)
-- Name: capas_geograficas1 id_capa_geografica; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.capas_geograficas1 ALTER COLUMN id_capa_geografica SET DEFAULT nextval('public.capas_geograficas_id_capa_geografica_seq'::regclass);


--
-- TOC entry 4888 (class 2604 OID 83121)
-- Name: categoria id_categoria; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categoria ALTER COLUMN id_categoria SET DEFAULT nextval('public.categoria_id_categoria_seq'::regclass);


--
-- TOC entry 4858 (class 2604 OID 81106)
-- Name: modulo id_modulo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modulo ALTER COLUMN id_modulo SET DEFAULT nextval('public.modulo_id_modulo_seq'::regclass);


--
-- TOC entry 4859 (class 2604 OID 81107)
-- Name: noticia id_noticia; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.noticia ALTER COLUMN id_noticia SET DEFAULT nextval('public.noticia_id_noticia_seq'::regclass);


--
-- TOC entry 4860 (class 2604 OID 81108)
-- Name: paisaje id_paisaje; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paisaje ALTER COLUMN id_paisaje SET DEFAULT nextval('public.paisaje_id_paisaje_seq'::regclass);


--
-- TOC entry 4861 (class 2604 OID 81109)
-- Name: permiso id_permiso; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permiso ALTER COLUMN id_permiso SET DEFAULT nextval('public.permiso_id_permiso_seq'::regclass);


--
-- TOC entry 4862 (class 2604 OID 81110)
-- Name: persona id_persona; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.persona ALTER COLUMN id_persona SET DEFAULT nextval('public.persona_id_persona_seq'::regclass);


--
-- TOC entry 4863 (class 2604 OID 81111)
-- Name: planoteca id_planoteca; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.planoteca ALTER COLUMN id_planoteca SET DEFAULT nextval('public.planoteca_id_planoteca_seq'::regclass);


--
-- TOC entry 4864 (class 2604 OID 81112)
-- Name: portada id_portada; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.portada ALTER COLUMN id_portada SET DEFAULT nextval('public.portada_id_portada_seq'::regclass);


--
-- TOC entry 4865 (class 2604 OID 81113)
-- Name: programa id_programa; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.programa ALTER COLUMN id_programa SET DEFAULT nextval('public.programa_id_programa_seq'::regclass);


--
-- TOC entry 4866 (class 2604 OID 81114)
-- Name: rol id_rol; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol ALTER COLUMN id_rol SET DEFAULT nextval('public.rol_id_rol_seq'::regclass);


--
-- TOC entry 4883 (class 2604 OID 82570)
-- Name: servicios_geograficos id_servicio_geografico; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.servicios_geograficos ALTER COLUMN id_servicio_geografico SET DEFAULT nextval('public.servicios_geograficos_id_servicio_geografico_seq'::regclass);


--
-- TOC entry 4877 (class 2604 OID 82524)
-- Name: tematica id_tematica; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tematica ALTER COLUMN id_tematica SET DEFAULT nextval('public.tematica_id_tematica_seq'::regclass);


--
-- TOC entry 4867 (class 2604 OID 81117)
-- Name: tipo_archivo id_tipo_archivo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_archivo ALTER COLUMN id_tipo_archivo SET DEFAULT nextval('public.tipo_archivo_id_tipo_archivo_seq'::regclass);


--
-- TOC entry 4868 (class 2604 OID 81118)
-- Name: usuario id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario ALTER COLUMN id_usuario SET DEFAULT nextval('public.usuario_id_usuario_seq'::regclass);


--
-- TOC entry 4869 (class 2604 OID 81119)
-- Name: usuario_archivo id_usuario_archivo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario_archivo ALTER COLUMN id_usuario_archivo SET DEFAULT nextval('public.usuario_archivo_id_usuario_archivo_seq'::regclass);


--
-- TOC entry 4870 (class 2604 OID 81120)
-- Name: usuario_capa id_usuario_capa; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario_capa ALTER COLUMN id_usuario_capa SET DEFAULT nextval('public.usuario_capa_id_usuario_capa_seq'::regclass);


--
-- TOC entry 5094 (class 0 OID 80961)
-- Dependencies: 215
-- Data for Name: archivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.archivo (id_archivo, archiv_id_tipo, archiv_codigo, archiv_nombre, archiv_archivo, archiv_estado, created, created_by, updated, updated_by, archiv_acceso) FROM stdin;
2	1	PLN-001-2023	Plano Catastral Urbano Pangoa	archivos.pdf	1	2023-06-23 19:39:41	1	\N	\N	1
3	1	PLN-002-2023	ARCHIVO RESOLUCIONES 2 - PRIVADO	arx_26405bf6c6cfa2e19b7525dc09361a0b.pdf	1	2023-10-18 16:05:54	1	\N	\N	2
4	2	PLN-003-2023	ARCHIVO DE ACUERDOS - PRIVADO	arx_ebc3dca308a9151eea733bfbeae9d6df.pdf	1	2023-10-18 16:08:37	1	\N	\N	2
5	2	PLN-004-2023	ARCHIVO DE ACUERDOS - PUBLICO	arx_b0f3479948855d5a4c29f064558ce1c9.pdf	1	2023-10-18 16:09:11	1	\N	\N	1
6	3	PLN-005-2023	ARCHIVO DE PROYECTOS - PRIVADO	arx_archivo.pdf	1	2023-10-18 16:09:44	1	\N	\N	2
7	1	PLN-033-2024	Prueba 001 2024 Set	arx_archivo.pdf	1	2024-09-05 16:02:53	1	\N	\N	1
8	1	rs3333	rs 3333	arx_65f744e168b152ed7e298d6eed4f3737.pdf	1	2024-09-05 16:24:58	1	\N	\N	1
9	2	AC00345	ACUERDO 00345	arx_a145c00882cad57e0aab3998e8f0fe30.pdf	1	2024-09-05 16:27:19	1	\N	\N	1
\.


--
-- TOC entry 5095 (class 0 OID 80964)
-- Dependencies: 216
-- Data for Name: archivo_campo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.archivo_campo (id_archivo_campo, arccam_id_tipo, arccam_nombre, arccam_archivo, arccam_estado, created, created_by, updated, updated_by) FROM stdin;
1	7	FOTO 1	arx_aeb999f955a585c01f0389ac559deab8.jpg	1	2023-10-31 17:06:54	1	\N	\N
2	6	VIDEO 1	arx_c54c63e90e059bb9610d395498ca51d7.mp4	1	2023-10-31 17:09:26	1	\N	\N
\.


--
-- TOC entry 5098 (class 0 OID 80971)
-- Dependencies: 219
-- Data for Name: bitacora; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.bitacora (id_bitacora, bitaco_id_proy, bitaco_id_elemento, bitaco_campo1, bitaco_campo2, bitaco_campo3, bitaco_estado, bitaco_id_ref, created, created_by, updated, updated_by) FROM stdin;
1	1	1	campo1	campo2	campo3	1	1	2023-10-31 04:17:57	1	\N	\N
2	1	1	campo1	campo2	campo3	1	1	2023-10-31 10:52:10	1	\N	\N
3	1	1	campo1	campo2	campo3	1	1	2024-02-02 22:39:40	1	\N	\N
\.


--
-- TOC entry 5138 (class 0 OID 97059)
-- Dependencies: 290
-- Data for Name: capas_geograficas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.capas_geograficas (id_capa_geografica, nombre, alias, id_tematica, tipo, visible, habilitado, orden, nombres_columnas, alias_columnas, visible_columnas, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica) FROM stdin;
19	mineriailegal_ln	Minería ilegal ln	29	1	f	t	5	\N	\N	\N	1	2025-06-12 15:34:30.907647	1	2025-06-12 15:34:30.907647
20	lotespetroleo	Lotes de petróleo	29	1	f	t	6	\N	\N	\N	1	2025-06-12 16:20:45.314139	1	2025-06-12 16:20:45.314139
25	vianacional	Vía nacional	9	1	f	t	1	\N	\N	\N	1	2025-06-12 23:02:26.312323	1	2025-06-12 23:02:26.312323
24	viadepartamental	Vía departamental	9	1	f	t	2	\N	\N	\N	1	2025-06-12 22:59:15.655196	1	2025-06-12 22:59:15.655196
26	vialocal	Vía local	9	1	f	t	3	\N	\N	\N	1	2025-06-12 23:06:29.393219	1	2025-06-12 23:06:29.393219
2	amazonianorte	Amazonía Norte	23	1	t	t	1	\N	\N	\N	1	2025-06-11 21:23:19.525889	1	2025-06-11 21:23:19.525889
3	amazoniasur	Amazonía Sur	23	1	t	t	2	\N	\N	\N	1	2025-06-11 21:23:50.812311	1	2025-06-11 21:23:50.812311
4	pacifico	Pacífico	23	1	t	t	3	\N	\N	\N	1	2025-06-11 21:34:13.62813	1	2025-06-11 21:34:13.62813
8	limitedepartamental	Límite departamental	8	1	f	t	1	\N	\N	\N	1	2025-06-11 21:46:39.975775	1	2025-06-11 21:46:39.975775
10	limiteprovincial	Límite Provincial	8	1	f	t	2	\N	\N	\N	1	2025-06-11 21:50:49.158427	1	2025-06-11 21:50:49.158427
9	limitedistrital	Límite distrital	8	1	f	t	3	\N	\N	\N	1	2025-06-11 21:47:23.504179	1	2025-06-11 21:47:23.504179
5	capitaldepartamental	Capital departamental	8	1	f	t	4	\N	\N	\N	1	2025-06-11 21:44:30.156721	1	2025-06-11 21:44:30.156721
6	capitalprovincial	Capital provincial	8	1	f	t	5	\N	\N	\N	1	2025-06-11 21:45:03.799995	1	2025-06-11 21:45:03.799995
7	capitaldistrital	Capital distrital	8	1	f	t	6	\N	\N	\N	1	2025-06-11 21:45:43.672577	1	2025-06-11 21:45:43.672577
11	centropoblado	Centro poblado	8	1	f	t	7	\N	\N	\N	1	2025-06-11 22:02:34.38016	1	2025-06-11 22:02:34.38016
12	riointernacional	Río internacional	10	1	f	t	1	\N	\N	\N	1	2025-06-11 22:31:18.089877	1	2025-06-11 22:31:18.089877
14	riosnavegables	Ríos navegables	10	1	f	t	2	\N	\N	\N	1	2025-06-11 22:37:02.629701	1	2025-06-11 22:37:02.629701
13	riosyquebradas	Ríos y quebradas	10	1	f	t	3	\N	\N	\N	1	2025-06-11 22:34:56.466864	1	2025-06-11 22:34:56.466864
15	lagunas	Lagunas	10	1	f	t	0	\N	\N	\N	1	2025-06-11 22:45:27.714644	1	2025-06-11 22:45:27.714644
16	12062025095027	Cobertura y uso de suelo	25	2	f	t	0	\N	\N	\N	1	2025-06-12 14:50:32.215432	1	2025-06-12 14:50:32.215432
23	catastrominero17	Catastro minero 17S	29	1	f	t	1	\N	\N	\N	1	2025-06-12 22:18:54.973328	1	2025-06-12 22:18:54.973328
22	catastrominero18	Catastro minero 18S	29	1	f	t	2	\N	\N	\N	1	2025-06-12 22:12:28.079066	1	2025-06-12 22:12:28.079066
21	catastrominero19	Catastro minero 19S	29	1	f	t	3	\N	\N	\N	1	2025-06-12 21:55:40.526224	1	2025-06-12 21:55:40.526224
18	corredorminerosur	Corredor minero Sur	29	1	f	t	4	\N	\N	\N	1	2025-06-12 15:26:04.638209	1	2025-06-12 15:26:04.638209
\.


--
-- TOC entry 5128 (class 0 OID 82299)
-- Dependencies: 254
-- Data for Name: capas_geograficas1; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.capas_geograficas1 (id_capa_geografica, nombre, alias, id_tematica, tipo, visible, habilitado, orden, nombres_columnas, alias_columnas, visible_columnas, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica) FROM stdin;
2	tg_dpto_interes	Dpto. de interés	3	1	f	t	2	\N	\N	\N	1	2025-04-07 17:57:26.191705	1	2025-04-07 17:57:26.191705
4	gbff	Global Biodiversity	4	1	f	t	4	\N	\N	\N	1	2025-04-07 18:00:24.661202	1	2025-04-07 18:00:24.661202
5	nbs_areas_estudio	NbS áreas de estudio	2	1	f	t	5	\N	\N	\N	1	2025-04-07 18:03:30.137368	1	2025-04-07 18:03:30.137368
7	sardi_expedicion_rio_amazonas	SARDI expedición río Amazonas	1	1	f	t	7	\N	\N	\N	1	2025-04-07 18:04:54.976058	1	2025-04-07 18:04:54.976058
12	23042025124303	peru	4	2	f	t	12	\N	\N	\N	1	2025-04-23 17:43:07.146827	1	2025-04-23 17:43:07.146827
9	pacifico	Paisaje Pacífico	3	1	f	t	9	\N	\N	\N	1	2025-04-08 23:42:36.083091	1	2025-04-24 22:15:44
3	tg_lugares	Lugares de interés	3	1	f	t	3	\N	\N	\N	1	2025-04-07 17:58:08.591613	1	2025-04-24 22:15:56
16	tb_social	Ucayali	9	1	f	t	16	\N	\N	\N	1	2025-04-25 04:19:53.589554	1	2025-04-25 18:56:58
17	25042025190956	Cobertura 2020	1	2	f	t	17	\N	\N	\N	1	2025-04-26 00:10:01.527128	1	2025-05-07 08:54:31
\.


--
-- TOC entry 5136 (class 0 OID 83118)
-- Dependencies: 287
-- Data for Name: categoria; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categoria (id_categoria, nombre, visible, habilitado, orden, id_padre, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica) FROM stdin;
11	Títulos Habilitantes	t	t	0	6	1	2025-05-07 13:07:05.767615	1	2025-05-07 13:07:05.767615
1	Paisajes de Intervensión	t	f	2	0	1	2025-05-07 13:07:05.767615	1	2025-05-07 13:07:05.767615
5	Áreas de conservación	t	f	5	0	1	2025-05-07 13:07:05.767615	1	2025-05-07 13:07:05.767615
19	Paisajes	f	f	3	2	1	2025-05-20 16:22:15.019383	1	2025-05-20 12:34:58
6	Derechos de uso	t	f	6	0	1	2025-05-07 13:07:05.767615	1	2025-05-07 13:07:05.767615
24	Ambientales	f	f	0	20	1	2025-05-30 15:57:03.120131	1	2025-05-30 15:57:03.120131
29	Ambientales	f	t	0	20	1	2025-06-12 15:21:51.910712	1	2025-06-12 15:21:51.910712
8	Unidades Administrativas	f	t	1	2	1	2025-05-07 13:07:05.767615	1	2025-06-10 13:13:51
9	Transportes	f	t	2	2	1	2025-05-07 13:07:05.767615	1	2025-06-12 10:19:27
23	WWF-Perú	t	t	1	22	1	2025-05-29 16:26:37.44836	1	2025-05-29 16:26:37.44836
10	Hidrografía	f	t	3	2	1	2025-05-07 13:07:05.767615	1	2025-06-11 17:39:19
25	Cobertura	f	t	4	2	1	2025-06-09 16:45:25.096915	1	2025-06-09 16:45:25.096915
22	Paisajes	t	t	1	0	1	2025-05-29 16:25:29.382271	1	2025-05-29 16:25:29.382271
16	Amazonía Norte	f	t	2	0	1	2025-05-19 20:16:08.737961	1	2025-05-19 15:16:41
18	Pacífico	f	t	3	0	1	2025-05-19 20:17:25.420256	1	2025-06-10 13:14:32
17	Amazonía Sur	t	t	4	0	1	2025-05-19 20:16:32.770307	1	2025-05-19 20:16:32.770307
2	Información Base	f	t	5	0	1	2025-05-07 13:07:05.767615	1	2025-06-12 10:26:37
20	Amenazas 	f	t	6	0	1	2025-05-27 21:17:13.85218	1	2025-05-29 14:42:55
13	Interoperabilidad	f	t	7	0	1	2025-05-07 20:44:19.981905	1	2025-05-19 15:16:19
30	Áreas Conservación	f	t	0	0	1	2025-06-13 17:27:46.04478	1	2025-06-13 17:27:46.04478
31	Áreas Naturales Prot	t	t	0	30	1	2025-06-17 15:18:09.571083	1	2025-06-17 15:18:09.571083
12	Interroperabilidad	t	f	7	0	1	2025-05-07 20:25:50.877161	1	2025-05-07 15:26:04
3	Entorno físico ambiental	f	f	3	0	1	2025-05-07 13:07:05.767615	1	2025-05-29 11:10:17
7	Presiones y amanazas ambientales	t	f	7	0	1	2025-05-07 13:07:05.767615	1	2025-05-07 13:07:05.767615
15	Niño	t	t	1	13	1	2025-05-08 15:50:04.299881	1	2025-05-08 15:50:04.299881
4	Entorno social	t	f	4	0	1	2025-05-07 13:07:05.767615	1	2025-05-07 13:07:05.767615
14	TEMA2	t	t	0	3	1	2025-05-08 15:33:50.956462	1	2025-05-16 08:10:24
21	Bosques	f	f	0	20	1	2025-05-27 21:17:25.807673	1	2025-05-27 21:17:25.807673
26	ANA	f	t	0	13	1	2025-06-10 16:31:37.166227	1	2025-06-10 11:35:13
27	Serfor	f	t	0	13	1	2025-06-10 18:08:34.369403	1	2025-06-10 18:08:34.369403
28	Senamhi	f	t	0	13	1	2025-06-10 18:09:22.999179	1	2025-06-10 18:09:22.999179
\.


--
-- TOC entry 5100 (class 0 OID 81002)
-- Dependencies: 221
-- Data for Name: modulo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.modulo (id_modulo, modulo_titulo, modulo_descripcion, modulo_ruta, modulo_estado, created, created_by, updated, updated_by) FROM stdin;
6	VISOR	\N	visor	1	2023-06-13 00:00:00	1	\N	\N
5	APLICACIONES	\N	aplicaciones	1	2023-06-13 00:00:00	1	\N	\N
4	SERVICIOS GEOGRAFICOS	\N	servicios_geograficos	1	2023-06-13 00:00:00	1	\N	\N
2	PLANOTECA DIGITAL	\N	planoteca	1	2023-06-13 00:00:00	1	\N	\N
3	DESCARGA DE INFORMACION	\N	archivos	1	2023-06-13 00:00:00	1	\N	\N
7	PERSONAS	Modulo Personas	\N	1	2023-06-13 00:00:00	1	\N	\N
8	USUARIOS	Modulo Usuarios	\N	1	2023-06-13 00:00:00	1	\N	\N
9	CAPAS	Modulo Capas	\N	1	2023-06-13 00:00:00	1	\N	\N
10	PLANOTECA	Modulo Planoteca	\N	1	2023-06-13 00:00:00	1	\N	\N
11	ARCHIVOS	Modulo Archivos	\N	1	2023-06-13 00:00:00	1	\N	\N
12	PORTADAS	Modulo Portadas	\N	1	2023-06-13 00:00:00	1	\N	\N
13	SERVICIOS GEOGRAFICOS	Modulo Servicios Geograficos	\N	1	2023-06-13 00:00:00	1	\N	\N
14	NOTICIAS	Modulo Noticias	\N	1	2023-06-13 00:00:00	1	\N	\N
1	INICIO	\N	inicio	1	2023-06-13 00:00:00	1	\N	\N
\.


--
-- TOC entry 5102 (class 0 OID 81007)
-- Dependencies: 223
-- Data for Name: noticia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.noticia (id_noticia, notici_titulo, notici_imagen1, notici_imagen2, notici_descripcion, notici_ruta, notici_estado, created, created_by, updated, updated_by, notici_tipo, notici_url) FROM stdin;
3	TITULO DE NOTICIA 2	img1_noticia_87f9f9898f6020da8472b83f48262142.jpg	img2_noticia_87f9f9898f6020da8472b83f48262142.jpg	<p><strong>DESCRIPCION DE NOTICIA 2&nbsp;</strong></p><p>DESCRIPCION DE NOTICIA 2 DESC<i><strong>RIPCION DE N</strong></i>OTICIA 2 DESCRIPCION</p><p>DE NOTICIA 2 DESCRIPCION DE NOTICIA 2 DESCRIPCION DE NOTICIA 2 DESCRIPCION DE NOTICIA 2&nbsp;</p><p>DESCRIPCION DE NOTICIA 2 DESCRIPCION DE NOTICIA 2 DESCRIPCION DE NOTICIA 2 DESCRIPCION DE NOTICIA 2 DESCRIPCION DE NOTICIA 2 DESCRIPCION DE NOTICIA 2 DESCRIPCION DE NOTICIA 2</p>	titulo-de-noticia-2	1	2023-06-20 11:37:36	1	2023-10-03 22:10:54	1	1	
2	TITULO DE NOTICIA 1	img1_noticia_a8b4639e0c006a38c6e50e86b7a87d96.jpg	img2_noticia_a8b4639e0c006a38c6e50e86b7a87d96.jpg	<p>DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1 DESCRIPCION NOTICIA 1</p>	noticia-1	1	2023-06-20 11:01:52	1	2023-10-03 22:14:11	1	1	
5	“EL RÍO PUTUMAYO SE HA CONVERTIDO EN LA PISTA POR EXCELENCIA PARA EL TRANSPORTE DE COCA” | ENTREVIST	img1_noticia_ca61c0696f74f83f0d00a7634f7e3eb3.jpg	notici_imagen2.png		crimen-en-lince-pnp-activara-alerta-roja-para-detencion-internacional-de-abel-valdivia-montoya	1	2023-10-03 22:04:03	1	2023-10-28 21:02:33	1	2	https://elcomercio.pe/tecnologia/ecologia/el-rio-putumayo-se-ha-convertido-en-la-pista-por-excelencia-para-el-transporte-de-coca-entrevista-noticia/
7	NOTICIA NUEVA 31-10-2023	img1_noticia_9993aaaf2630dabf78d2eb4d4da84240.jpg	img2_noticia_9993aaaf2630dabf78d2eb4d4da84240.jpg	<p><i><strong>Descripcion ….</strong></i></p><p><i><strong>Descripcion ….</strong></i></p><p><i><strong>Descripcion ….</strong></i></p>	noticia-nueva-31-10-2023	1	2023-10-31 16:05:20	1	\N	\N	1	
6	TRAZAPP, LA APP QUE REVOLUCIONA LA PESQUERÍA ARTESANAL	img1_noticia_f819d68d22ae497ad71cf94405fb6fca.jpg	notici_imagen2.jpg		https//elcomerciope/tecnologia/ecologia/muro-fronterizo-entre-mexico-y-estados-unidos-pone-a-la-re	1	2023-10-28 21:13:22	1	2023-11-20 15:23:30	1	2	https://www.wwf.org.pe/informate/noticias/?uNewsID=385413
\.


--
-- TOC entry 5104 (class 0 OID 81015)
-- Dependencies: 225
-- Data for Name: paisaje; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.paisaje (id_paisaje, paisaj_paisaje, paisaj_estrategia, paisaj_objetivo, paisaj_meta1, paisaj_meta2, paisaj_meta3, paisaj_meta4, paisaj_meta5, paisaj_indicador, paisaj_estado, paisaj_id_ref, created, created_by, updated, updated_by) FROM stdin;
1	AMAZONIA	E1- Al 2030, se ha mantenido la conectividad ecológica en 20M de Ha, incluyendo cuencas y conservando especies prioritarias.	Al 2030, se ha mantenido la conectividad ecológica en 20M de Ha, incluyendo cuencas y conservando especies prioritarias.	Al 2027, 4M de Ha están bajo mecanismos de conservación nuevos o fortalecidos en corredores prioritarios	Al 2027, al menos 12M de Ha de 26 ANPs mantienen su estado de conservación	Al 2027, al menos dos cuencas prioritarias (Marañon y Madre de Dios) incorporan mecanismos para la conservación de la conectividad hídrica (caudal ecológico, planificación a escala de sistema o mecanismos de retribución de servicios ecosistemicos, entre otros).	Al 2027, al menos 5000 ha restauradas en corredores prioritarios.	Al 2027, se están implementando mecanismos de conservación para reducir las amenazas (relacionadas a conflicto o tráfico) de especies prioritarias.	45	1	\N	2023-09-21 02:46:05	1	2023-10-04 00:14:56	1
2	Amazonia	E2 - Pueblos indígenas y Comunidades locales empoderadas para la conservación de sus recursos y territorios	Al 2030, se han conservado al menos 1.5M de Ha de territorios indígenas y de comunidades locales	Al 2027, al menos 1.3M ha de territorios indígenas con seguridad territorial.	Al 2027, 200,000 Ha de territorios indígenas y comunidades locales implementan acciones para el control y vigilancia de sus territorios.	Al 2027, al menos 8 organizaciones de los pueblos indígenas o comunidades locales han mejorado sus capacidades para la conservación y manejo de sus territorios y recursos.	\N	\N	0	1	1	2023-09-21 16:49:18	1	2023-09-21 21:25:14	1
3	Amazonia	E3 - Cadenas productivas sostenibles, vinculadas a mercados y mecanismos financieros	Al 2030, al menos 5 cadenas productivas se desarrollan libres de deforestación y amenazas a la fauna, articuladas a mercados y financiamiento.	Al 2027, se han implementado medidas efectivas para reducir la deforestación en al menos tres cadenas (ganadería, madera, castaña) productivas en los paisajes priorizados.	Al 2027, al menos dos cadenas productivas (pesquerías continentales y ganadería) implementan prácticas para reducir conflicto con especies prioritarias (jaguar y delfines)	Al 2027, al menos 50 emprendimientos indígenas y productores implementan prácticas sostenibles que les permite aumentar sus ventas en al menos un 30%.	Al 2027, instituciones financieras e inversionistas canalizan al menos $10M en cadenas productivas sostenibles que cumplen con criterios ambientales, sociales y de gobernanza (ASG)	\N	0	1	1	2023-09-21 21:28:00	1	\N	\N
4	Amazonia	E4 - Gestión pública participativa y efectiva	Al 2030, se ha contribuido a la reducción de emisiones de gases de efecto invernadero y conservación de la biodiversidad mediante el fortalecimiento de políticas y la participación ciudadana.	Al 2027, al menos 2 municipalidades implementan planes locales de cambio climático.	Al 2027, el 10% de la matríz energética nacional proviene de energías renovables.	Al 2027, al menos 5 políticas ambientales (relacionadas a las estrategias de conservación de WWF) son implementadas con la activa participación de la sociedad civil con apoyo de WWF.	\N	\N	0	1	3	2023-09-21 21:45:28	1	\N	\N
5	Amazonia	E5 - Amazonía y Pacífico libres de contaminación	Al 2030, se ha reducido la contaminación por plásticos, petróleo y mercurio en la Amazonía y Pacífico Peruano	Al 2027, se han implementado modelos de gestión integral de residuos (plásticos y oleosos), basados en principios de conomía circular en desembarcaderos pesqueros artesanales y embarcaciones.	Al 2027, se ha aprobado un instrumento internacional legalmente vinculante para combatir la contaminación por plásticos con el liderazgo de Perú y su implementación nacional	\N	\N	\N	0	1	4	2023-09-21 22:15:47	1	2023-09-22 00:15:15	1
\.


--
-- TOC entry 5106 (class 0 OID 81023)
-- Dependencies: 227
-- Data for Name: permiso; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permiso (id_permiso, permis_id_rol, permis_id_modulo, permis_estado, created, created_by, updated, updated_by) FROM stdin;
1	1	7	1	2023-06-13 00:00:00	1	\N	\N
2	1	8	1	2023-06-13 00:00:00	1	\N	\N
3	1	9	1	2023-06-13 00:00:00	1	\N	\N
4	1	10	1	2023-06-13 00:00:00	1	\N	\N
5	1	11	1	2023-06-13 00:00:00	1	\N	\N
6	1	12	1	2023-06-13 00:00:00	1	\N	\N
7	1	13	1	2023-06-13 00:00:00	1	\N	\N
8	1	14	1	2023-06-13 00:00:00	1	\N	\N
42	3	7	0	2023-10-31 16:48:16	1	\N	\N
43	3	8	0	2023-10-31 16:48:16	1	\N	\N
44	3	9	0	2023-10-31 16:48:16	1	\N	\N
45	3	10	0	2023-10-31 16:48:16	1	\N	\N
46	3	11	0	2023-10-31 16:48:16	1	\N	\N
47	3	12	0	2023-10-31 16:48:16	1	\N	\N
48	3	13	0	2023-10-31 16:48:16	1	\N	\N
49	3	14	1	2023-10-31 16:48:16	1	\N	\N
50	2	7	1	2025-04-05 23:14:35	1	\N	\N
51	2	8	0	2025-04-05 23:14:35	1	\N	\N
52	2	9	0	2025-04-05 23:14:35	1	\N	\N
53	2	10	0	2025-04-05 23:14:35	1	\N	\N
54	2	11	0	2025-04-05 23:14:35	1	\N	\N
55	2	12	0	2025-04-05 23:14:35	1	\N	\N
56	2	13	0	2025-04-05 23:14:35	1	\N	\N
57	2	14	0	2025-04-05 23:14:35	1	\N	\N
\.


--
-- TOC entry 5108 (class 0 OID 81028)
-- Dependencies: 229
-- Data for Name: persona; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.persona (id_persona, person_num_documento, person_nombres, person_apellidos, person_celular, person_email, person_estado, created, created_by, updated, updated_by) FROM stdin;
2	11111111	Roberto	Arbañil Segura	999988888	roberto@gmail.com	0	2023-06-19 07:34:09	1	\N	\N
3	12345678	Juan	Perez Perez	123456789	juanpp@gmail.com	1	2023-07-31 19:13:50	1	\N	\N
4	70926836	Ivan	Barreto Sandoval	345345	ivan@hotmail.com	1	2023-07-31 20:42:54	1	2023-07-31 20:43:46	1
5	41414273	Roberto	Arbañil	921691720	robertoarbanil@gmail.com	1	2023-10-22 21:09:56	1	\N	\N
7	22222222	Juan	Perez 2	222222222	juanp2@gmail.com	1	2024-03-11 17:51:05	1	\N	\N
1	70926839	Edgar	Barreto Sandoval	992282970	edgarbs1003@gmail.com	1	2023-06-13 00:00:00	1	2024-03-12 22:37:39	1
8	73638075	Aldair	Sotelo Belen	941997559	aldair.sotelo-pract@wwfperu.org	1	2024-03-13 17:38:39	1	\N	\N
6	45895845	Leonel	Canchari	78532548	leonel@wwf.org	0	2023-10-23 16:43:06	1	\N	\N
9	47217416	Leonel	Canchari	990236988	leonel.canchari@wwfperu.org	1	2024-03-13 17:41:58	1	2024-12-04 13:54:29	9
10	12121212	Oliver	Liao	999999999	oliver.liao@wwfperu.org	1	2025-04-11 10:32:51	1	\N	\N
11	44683161	Jorge	Campos	969389958	rafael.campos@wwfus.org	1	2025-05-29 10:04:07	1	\N	\N
\.


--
-- TOC entry 5110 (class 0 OID 81033)
-- Dependencies: 231
-- Data for Name: planoteca; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.planoteca (id_planoteca, planot_id_tipo, planot_codigo, planot_nombre, planot_archivo, planot_estado, created, created_by, updated, updated_by, planot_img, planot_tag) FROM stdin;
17	3	PLN-024-2023	Plano Catastral urbano Pangoa 24	archivo1 - copia (11).pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
14	3	PLN-021-2023	Plano Catastral urbano Pangoa 21	archivo1 - copia (8).pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
16	3	PLN-023-2023	Plano Catastral urbano Pangoa 23	archivo1 - copia (10).pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
15	3	PLN-022-2023	Plano Catastral urbano Pangoa 22	archivo1 - copia (9).pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
5	2	PLN-011-2023	Plano Catastral urbano Pangoa 11	archivo1 - copia (4).pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
6	2	PLN-012-2023	Plano Catastral urbano Pangoa 12	archivo1 - copia (5).pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
8	2	PLN-014-2023	Plano Catastral urbano Pangoa 14 - 31-10-2023	archivo1 - copia (7).pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
7	2	PLN-013-2023	Plano Catastral urbano Pangoa 13	archivo1 - copia (6).pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
1	1	PLN-001-2023	Plano Catastral urbano Pangoa 1	archivo1.pdf\n	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
2	1	PLN-002-2023	Plano Catastral urbano Pangoa 2	archivo1 - copia.pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
3	1	PLN-003-2023	Plano Catastral urbano Pangoa 3	archivo1 - copia (2).pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
4	1	PLN-004-2023	Plano Catastral urbano Pangoa 4	archivo1 - copia (3).pdf	0	2023-06-23 19:39:41	1	\N	\N	\N	\N
23	1	ABC-01	PLANO PRUEBA	pla_1d3ac69b9a2f6565e0e01c8c00410d79.pdf	0	2023-10-28 16:39:31	1	\N	\N	\N	\N
18	1	PLN-005-2023	Plano Catastral urbano Pangoa 5	archivo1 - copia (12).pdf	0	2023-06-23 19:39:41	1	2023-11-08 16:07:06	1	plano_img_ad6c6da3396fa8506a3fabb442df57a6.jpg	peru,bosque,seco
39	3	AN-B-47-21	20_Tierras que se habitan	pla_b5f431b5237b4247bd29bf775871b972.pdf	0	2024-04-17 14:43:49	9	\N	\N	plano_img.jpg	a
44	7	R	Paisajes de intervención	pla_415ae43fe6f644df6b74db52da81bb4d.pdf	1	2024-11-11 19:44:41	9	2024-11-11 19:45:46	9	plano_img_aa8a4e36948dd45a811ff0e346126fc1.png	Reporte anual
25	7	pl002	G- LÍNEA ESTRATEGIA 2 - COMUNIDADES NATIVAS	pla_archivo.pdf	0	2023-11-20 15:15:07	1	\N	\N	plano_img.jpg	G- LÍNEA ESTRATEGIA 2 - COMUNIDADES NATIVAS
26	7	PL003	G- LÍNEA ESTRATEGIA 3 - CONCESIONES	pla_archivo.pdf	0	2023-11-20 15:15:37	1	\N	\N	plano_img.jpg	G- LÍNEA ESTRATEGIA 3 - CONCESIONES
27	7	PL004	G- MAPA DE LOTES PETROLEROS Y ANP	pla_archivo.pdf	0	2023-11-20 15:16:08	1	\N	\N	plano_img.jpg	G- MAPA DE LOTES PETROLEROS Y ANP
28	7	PL005	G-LOCALIZACIÓN PAISAJES DE INTERVECIÓN	pla_archivo.pdf	0	2023-11-20 15:16:38	1	\N	\N	plano_img.jpg	G-LOCALIZACIÓN PAISAJES DE INTERVECIÓN
38	7	PRUEBA	prueba archivo	pla_22adf53abc7ba949aa14544e3a506b37.pdf	0	2024-03-13 14:28:43	1	2024-03-18 14:30:54	9	plano_img_9d762aa744b756ecbcbb02b3436950fd.png	prueba
29	6	PL001-AMASUR	PAS-CONCETRACIÓN DE PÉRDIDA DE BOSQUE EN MDD	pla_archivo.pdf	0	2023-11-20 15:17:31	1	\N	\N	plano_img.jpg	PAS-CONCETRACIÓN DE PÉRDIDA DE BOSQUE EN MDD
41	1	LKKJHK	JKJHKJHK	pla_d1a80fcbb8af432bfddb53dd6b9a8823.pdf	0	2024-09-05 16:36:39	1	\N	\N	plano_img.jpg	KJKJHKJH
24	7	PL001	G- LÍNEA ESTRATEGIA 1 - ANP	pla_archivo.pdf	0	2023-11-20 15:14:12	1	2023-11-20 15:14:35	1	plano_img.jpg	G- LÍNEA ESTRATEGIA 1 - ANP
43	7	PDP	PDB	pla_fa46c18ede0c1886f32181e4c92d2663.pdf	0	2024-11-11 14:27:43	9	\N	\N	plano_img.jpg	PDP
40	7	NA-V-46-1	Presentación de Coexistencia Humano - Vida Silvestre	pla_6c9a784bf07baf4901f16cd52a9fce48.pdf	0	2024-04-17 14:46:53	9	\N	\N	plano_img.jpg	a
42	7	ABC	Paisajes de intervención	pla_archivo.pdf	0	2024-11-11 14:24:37	9	\N	\N	plano_img_0526116b34b11d1bc0b2a598151184ee.png	ABCD
45	7	a	Intervención Amazonía Norte	pla_f24406581fdb6923a53c069babd06f74.pdf	1	2024-11-11 21:58:28	9	2024-11-11 22:01:57	9	plano_img_33cf52fd78dc848951d7b61ba96a580c.png	Reporte anual 2023
46	7	s	Intervención Amazonía Sur	pla_db9ca4746daa15677fc998f6e55f1a18.pdf	0	2024-11-11 22:08:49	9	2024-11-12 14:18:02	9	plano_img_5b267a66d238acf09e12bf63881658bb.png	Reporte anual 2023
48	7	aqw	Intervención Amazonía Sur	pla_2c6d6e2612f8966a1e6e2148500af864.rar	1	2024-11-12 14:20:32	9	2024-11-15 18:38:12	9	plano_img_a3d77d9fa455761438feeae673d5d616.png	Reporte anual 2023
50	7	jjjll	expedicion	pla_archivo.pdf	1	2024-11-25 17:16:35	1	2024-11-25 17:19:25	1	plano_img.jpg	https://app.powerbi.com/links/NWfotSkc_g?ctid=68a878b0-8b0b-4459-ac6c-2082f0e189db&pbi_source=linkShare&bookmarkGuid=d35d7a68-29d1-4ef1-adaa-3f21e243eead
47	7	asd	Intervención Paisaje Pacífico	pla_8faaf451cd0af521a20a70b92179cf92.pdf	0	2024-11-11 22:19:01	9	2024-11-11 22:31:17	9	plano_img_f2f43d5cca1f45b764c96181eb714b8b.png	Reporte anual 2023
49	7	fgh	Intervención Paisaje Pacífico	pla_01f55033d4c6ec601ff4ab43d2c93c22.pdf	1	2024-11-12 14:56:15	9	2024-11-12 14:59:38	9	plano_img_4291e8370d1107e4eb38a9aaedb2d952.png	Reporte anual 2023
\.


--
-- TOC entry 5112 (class 0 OID 81041)
-- Dependencies: 233
-- Data for Name: portada; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.portada (id_portada, portad_titulo, portad_imagen, portad_estado, created, created_by, updated, updated_by) FROM stdin;
5	CORDILLERA AZUL	img_b267b546ef5abbf8d50075dc2ce427ec.jpg	1	2023-06-19 07:50:49	1	\N	\N
6	PARQUE NACIONAL	img_9c1acdff028144d4cfeb6b154ab0d00f.jpg	0	2023-06-19 07:51:35	1	\N	\N
2	AMAZONAS	img_b254dceaf006c47540648549b5f340df.jpg	0	2023-06-13 00:00:00	1	\N	\N
1	SAN MARTIN	img_9b6583641ea71ab352ba4ecb542bb3d1.jpg	0	2023-06-13 00:00:00	1	\N	\N
3	AMAZONÍA SUR	img_12a1cc92c57911450a33165a661b63a4.jpg	1	2025-05-29 11:33:57	1	2025-06-12 10:15:23	1
7	BOSQUES	img_1de2fda4e6e8f80c5996a300c2c6ad47.jpg	1	2023-06-23 21:08:33	1	2025-06-12 11:32:45	1
\.


--
-- TOC entry 5114 (class 0 OID 81046)
-- Dependencies: 235
-- Data for Name: programa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.programa (id_programa, progra_nombre, progra_descripcion, progra_estado, created, created_by, updated, updated_by) FROM stdin;
6	Amazonía Sur	Cartografía	1	2023-09-20 16:38:22	1	2024-11-11 19:56:09	9
2	Amazonía Norte	Cartografía	1	2023-06-23 19:39:41	1	2024-11-11 19:57:09	9
7	Comunicaciones	Cartografía	1	2023-10-28 16:40:23	1	2024-11-11 20:07:54	9
1	Transversal	Cartografía	1	2023-06-23 19:39:41	1	2024-11-11 20:21:09	9
3	Pacífico	Cartografía	1	2023-06-23 19:39:41	1	2024-11-11 20:34:10	9
\.


--
-- TOC entry 5116 (class 0 OID 81051)
-- Dependencies: 237
-- Data for Name: rol; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rol (id_rol, rol_nombre, rol_descripcion, rol_estado, created, created_by, updated, updated_by) FROM stdin;
2	Publico	Usuario publico	1	2023-06-13 00:00:00	1	\N	\N
1	Administrador	Acceso a todo el sistema	1	2023-06-13 00:00:00	1	\N	\N
3	Relaciones Publicas	Registro de Noticias	1	2023-06-13 00:00:00	1	\N	\N
\.


--
-- TOC entry 5134 (class 0 OID 82567)
-- Dependencies: 280
-- Data for Name: servicios_geograficos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.servicios_geograficos (id_servicio_geografico, tipo, id_tematica, direccion_web, capa, nombre, alias, visible, orden, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica) FROM stdin;
2	1	26	https://geosnirh.ana.gob.pe/server/services/ws_UnidadesHidro/MapServer/WMSServer	0	UnidadesHidrograficas	Unidades hidrográficas	f	2	1	2025-04-23 13:22:59.753878	1	2025-04-23 13:22:59.753878
6	1	27	https://geo.serfor.gob.pe/geoservicios/services/Servicios_OGC/Modalidad_Acceso/MapServer/WMSServer?	0	Concesiones_Forestales	Concesiones forestales	f	6	1	2025-06-10 19:31:21.506426	1	2025-06-10 19:31:21.506426
7	1	27	https://geo.serfor.gob.pe/geoservicios/services/Servicios_OGC/Zonificacion_Forestal/MapServer/WMSServer?	0	Zonificacion Forestal	Zonificación forestal	f	7	1	2025-06-10 19:35:23.622562	1	2025-06-10 19:35:23.622562
10	1	29	https://geo.socioambiental.org/raisg/services/PilotoGarimpo/garimpo_ilegal/MapServer/WMSServer	8	Minería: ilegal	Mineria ilegal	f	10	1	2025-06-17 16:18:12.408296	1	2025-06-17 16:18:12.408296
\.


--
-- TOC entry 4854 (class 0 OID 81529)
-- Dependencies: 248
-- Data for Name: spatial_ref_sys; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.spatial_ref_sys (srid, auth_name, auth_srid, srtext, proj4text) FROM stdin;
\.


--
-- TOC entry 5131 (class 0 OID 82521)
-- Dependencies: 273
-- Data for Name: tematica; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tematica (id_tematica, nombre_esquema, descripcion_esquema, visible, habilitado, orden_esquema, id_usuario_crea, fecha_crea, id_usuario_modifica, fecha_modifica) FROM stdin;
7	informacion_base	informacion_base	f	t	7	1	2025-04-08 16:28:28.954606	1	2025-04-08 16:28:28.954606
10	areas_de_conservacion	areas_de_conservacion	f	t	10	1	2025-04-08 16:28:28.954606	1	2025-04-08 16:28:28.954606
11	derechos_de_uso	derechos_de_uso	f	t	11	1	2025-04-08 16:28:28.954606	1	2025-04-08 16:28:28.954606
12	presiones_amenazas_ambientales	presiones_amenazas_ambientales	f	t	12	1	2025-04-08 16:28:28.954606	1	2025-04-08 16:28:28.954606
8	entorno_fisico_ambiental	entorno_fisico_ambiental	f	t	8	1	2025-04-08 16:28:28.954606	1	2025-04-08 18:30:11
14	Amenazas	Amenazas en los paisajes	t	f	14	1	2025-04-08 17:37:07.837163	1	2025-04-08 17:37:07.837163
15	Interoperabilidad	Interoperabilidad	f	t	15	1	2025-04-08 23:35:44.969211	1	2025-04-08 18:47:47
13	Amenazas	Amenazas en los paisajes	f	t	13	1	2025-04-08 17:32:56.860639	1	2025-04-08 18:47:55
6	paisaje_intervencion_wwf_peru	paisaje_intervencion_wwf_peru	f	t	6	1	2025-04-08 16:28:28.954606	1	2025-04-08 18:48:05
3	pacifico	Pacífico	f	t	3	1	2025-04-08 16:28:28.954606	1	2025-04-11 11:19:52
1	amazonia_norte	Amazonía Norte	f	t	1	1	2025-04-08 16:28:28.954606	1	2025-04-11 11:20:14
2	amazonia_sur	Amazonía Sur	f	t	2	1	2025-04-08 16:28:28.954606	1	2025-04-11 11:20:21
5	pmel	pmel	f	t	5	1	2025-04-08 16:28:28.954606	1	2025-04-24 22:16:36
16	base	Base	f	t	16	1	2025-04-11 14:36:45.175822	1	2025-04-24 22:16:47
9	entorno_social	Entorno Social	f	t	9	1	2025-04-08 16:28:28.954606	1	2025-04-24 23:23:39
4	nacional	Nacional	f	t	4	1	2025-04-08 16:28:28.954606	1	2025-04-25 18:56:24
\.


--
-- TOC entry 5118 (class 0 OID 81081)
-- Dependencies: 239
-- Data for Name: tipo_archivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tipo_archivo (id_tipo_archivo, tiparc_nombre, tiparc_estado, created, created_by, updated, updated_by) FROM stdin;
1	RESOLUCIONES	1	2023-06-23 19:39:41	1	\N	\N
2	ACUERDOS	1	2023-06-23 19:39:41	1	\N	\N
3	PROYECTOS	1	2023-06-23 19:39:41	1	\N	\N
4	OFICIOS, OTROS...	1	2023-06-23 19:39:41	1	2023-09-20 22:25:27	1
6	VIDEOS	1	2023-10-31 17:06:02	1	\N	\N
7	FOTOS	1	2023-10-31 17:06:14	1	\N	\N
\.


--
-- TOC entry 5120 (class 0 OID 81086)
-- Dependencies: 241
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuario (id_usuario, usuari_id_persona, usuari_nombre, usuari_clave, usuari_id_rol, usuari_estado, created, created_by, updated, updated_by) FROM stdin;
3	5	rarbanil	a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3	3	1	2023-10-22 21:10:33	1	\N	\N
5	1	usuario	a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3	3	1	2023-10-31 16:50:55	1	\N	\N
6	1	edgarbs	a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3	1	0	2024-02-02 20:30:22	1	2024-02-02 20:32:58	1
2	4	ivanbs	a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3	3	1	2023-07-31 23:04:52	1	2024-03-11 17:46:24	1
7	3	juanp	a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3	2	1	2024-03-11 17:48:27	1	\N	\N
1	1	admin	a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3	1	1	2023-06-13 00:00:00	1	2024-03-12 22:37:55	1
4	6	leonel	a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3	3	0	2023-10-23 16:43:39	1	\N	\N
10	10	oliver	a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3	1	1	2025-04-11 10:33:24	1	2025-04-11 12:21:06	1
9	8	aldair	a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3	1	1	2024-03-13 17:39:21	1	2025-04-11 12:21:34	1
8	7	alexander	a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3	1	1	2024-03-11 17:52:50	1	2025-04-11 12:21:56	1
11	11	rafael.campos	8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92	1	1	2025-05-29 10:05:31	1	2025-05-29 11:05:52	1
\.


--
-- TOC entry 5121 (class 0 OID 81089)
-- Dependencies: 242
-- Data for Name: usuario_archivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuario_archivo (id_usuario_archivo, usuarc_id_usuario, usuarc_id_archivo, created, created_by, updated, updated_by) FROM stdin;
1	1	3	2023-10-18 16:10:19	1	\N	\N
2	1	4	2023-10-19 15:31:27	1	\N	\N
\.


--
-- TOC entry 5123 (class 0 OID 81094)
-- Dependencies: 244
-- Data for Name: usuario_capa; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.usuario_capa (id_usuario_capa, usucap_id_usuario, usucap_id_capa, created, created_by, updated, updated_by) FROM stdin;
\.


--
-- TOC entry 5166 (class 0 OID 0)
-- Dependencies: 217
-- Name: archivo_campo_id_archivo_campo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.archivo_campo_id_archivo_campo_seq', 2, true);


--
-- TOC entry 5167 (class 0 OID 0)
-- Dependencies: 218
-- Name: archivo_id_archivo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.archivo_id_archivo_seq', 9, true);


--
-- TOC entry 5168 (class 0 OID 0)
-- Dependencies: 220
-- Name: bitacora_id_bitacora_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.bitacora_id_bitacora_seq', 3, true);


--
-- TOC entry 5169 (class 0 OID 0)
-- Dependencies: 253
-- Name: capas_geograficas_id_capa_geografica_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.capas_geograficas_id_capa_geografica_seq', 17, true);


--
-- TOC entry 5170 (class 0 OID 0)
-- Dependencies: 289
-- Name: capas_geograficas_id_capa_geografica_seq1; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.capas_geograficas_id_capa_geografica_seq1', 26, true);


--
-- TOC entry 5171 (class 0 OID 0)
-- Dependencies: 286
-- Name: categoria_id_categoria_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categoria_id_categoria_seq', 31, true);


--
-- TOC entry 5172 (class 0 OID 0)
-- Dependencies: 222
-- Name: modulo_id_modulo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.modulo_id_modulo_seq', 16, true);


--
-- TOC entry 5173 (class 0 OID 0)
-- Dependencies: 224
-- Name: noticia_id_noticia_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.noticia_id_noticia_seq', 7, true);


--
-- TOC entry 5174 (class 0 OID 0)
-- Dependencies: 226
-- Name: paisaje_id_paisaje_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.paisaje_id_paisaje_seq', 5, true);


--
-- TOC entry 5175 (class 0 OID 0)
-- Dependencies: 228
-- Name: permiso_id_permiso_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permiso_id_permiso_seq', 57, true);


--
-- TOC entry 5176 (class 0 OID 0)
-- Dependencies: 230
-- Name: persona_id_persona_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.persona_id_persona_seq', 11, true);


--
-- TOC entry 5177 (class 0 OID 0)
-- Dependencies: 232
-- Name: planoteca_id_planoteca_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.planoteca_id_planoteca_seq', 50, true);


--
-- TOC entry 5178 (class 0 OID 0)
-- Dependencies: 234
-- Name: portada_id_portada_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.portada_id_portada_seq', 3, true);


--
-- TOC entry 5179 (class 0 OID 0)
-- Dependencies: 236
-- Name: programa_id_programa_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.programa_id_programa_seq', 7, true);


--
-- TOC entry 5180 (class 0 OID 0)
-- Dependencies: 238
-- Name: rol_id_rol_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rol_id_rol_seq', 4, true);


--
-- TOC entry 5181 (class 0 OID 0)
-- Dependencies: 252
-- Name: seq_orden_capa_geografica1; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_orden_capa_geografica1', 17, true);


--
-- TOC entry 5182 (class 0 OID 0)
-- Dependencies: 278
-- Name: seq_orden_servicio_geografico; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_orden_servicio_geografico', 10, true);


--
-- TOC entry 5183 (class 0 OID 0)
-- Dependencies: 271
-- Name: seq_orden_tematica1; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.seq_orden_tematica1', 16, true);


--
-- TOC entry 5184 (class 0 OID 0)
-- Dependencies: 279
-- Name: servicios_geograficos_id_servicio_geografico_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.servicios_geograficos_id_servicio_geografico_seq', 10, true);


--
-- TOC entry 5185 (class 0 OID 0)
-- Dependencies: 272
-- Name: tematica_id_tematica_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tematica_id_tematica_seq', 16, true);


--
-- TOC entry 5186 (class 0 OID 0)
-- Dependencies: 240
-- Name: tipo_archivo_id_tipo_archivo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tipo_archivo_id_tipo_archivo_seq', 7, true);


--
-- TOC entry 5187 (class 0 OID 0)
-- Dependencies: 243
-- Name: usuario_archivo_id_usuario_archivo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuario_archivo_id_usuario_archivo_seq', 2, true);


--
-- TOC entry 5188 (class 0 OID 0)
-- Dependencies: 245
-- Name: usuario_capa_id_usuario_capa_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuario_capa_id_usuario_capa_seq', 2, true);


--
-- TOC entry 5189 (class 0 OID 0)
-- Dependencies: 246
-- Name: usuario_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.usuario_id_usuario_seq', 11, true);


--
-- TOC entry 4905 (class 2606 OID 81122)
-- Name: archivo_campo archivo_campo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archivo_campo
    ADD CONSTRAINT archivo_campo_pkey PRIMARY KEY (id_archivo_campo);


--
-- TOC entry 4903 (class 2606 OID 81124)
-- Name: archivo archivo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archivo
    ADD CONSTRAINT archivo_pkey PRIMARY KEY (id_archivo);


--
-- TOC entry 4907 (class 2606 OID 81126)
-- Name: bitacora bitacora_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bitacora
    ADD CONSTRAINT bitacora_pkey PRIMARY KEY (id_bitacora);


--
-- TOC entry 4937 (class 2606 OID 82312)
-- Name: capas_geograficas1 capas_geograficas1_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.capas_geograficas1
    ADD CONSTRAINT capas_geograficas1_pkey PRIMARY KEY (id_capa_geografica);


--
-- TOC entry 4945 (class 2606 OID 97072)
-- Name: capas_geograficas capas_geograficas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.capas_geograficas
    ADD CONSTRAINT capas_geograficas_pkey PRIMARY KEY (id_capa_geografica);


--
-- TOC entry 4943 (class 2606 OID 83129)
-- Name: categoria categoria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categoria
    ADD CONSTRAINT categoria_pkey PRIMARY KEY (id_categoria);


--
-- TOC entry 4909 (class 2606 OID 81132)
-- Name: modulo modulo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modulo
    ADD CONSTRAINT modulo_pkey PRIMARY KEY (id_modulo);


--
-- TOC entry 4911 (class 2606 OID 81134)
-- Name: noticia noticia_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.noticia
    ADD CONSTRAINT noticia_pkey PRIMARY KEY (id_noticia);


--
-- TOC entry 4913 (class 2606 OID 81136)
-- Name: paisaje paisaje_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paisaje
    ADD CONSTRAINT paisaje_pkey PRIMARY KEY (id_paisaje);


--
-- TOC entry 4915 (class 2606 OID 81138)
-- Name: permiso permiso_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permiso
    ADD CONSTRAINT permiso_pkey PRIMARY KEY (id_permiso);


--
-- TOC entry 4917 (class 2606 OID 81140)
-- Name: persona persona_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.persona
    ADD CONSTRAINT persona_pkey PRIMARY KEY (id_persona);


--
-- TOC entry 4919 (class 2606 OID 81142)
-- Name: planoteca planoteca_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.planoteca
    ADD CONSTRAINT planoteca_pkey PRIMARY KEY (id_planoteca);


--
-- TOC entry 4921 (class 2606 OID 81144)
-- Name: portada portada_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.portada
    ADD CONSTRAINT portada_pkey PRIMARY KEY (id_portada);


--
-- TOC entry 4923 (class 2606 OID 81146)
-- Name: programa programa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.programa
    ADD CONSTRAINT programa_pkey PRIMARY KEY (id_programa);


--
-- TOC entry 4925 (class 2606 OID 81148)
-- Name: rol rol_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rol
    ADD CONSTRAINT rol_pkey PRIMARY KEY (id_rol);


--
-- TOC entry 4941 (class 2606 OID 82579)
-- Name: servicios_geograficos servicios_geograficos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.servicios_geograficos
    ADD CONSTRAINT servicios_geograficos_pkey PRIMARY KEY (id_servicio_geografico);


--
-- TOC entry 4939 (class 2606 OID 82531)
-- Name: tematica tematica_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tematica
    ADD CONSTRAINT tematica_pkey PRIMARY KEY (id_tematica);


--
-- TOC entry 4927 (class 2606 OID 81154)
-- Name: tipo_archivo tipo_archivo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tipo_archivo
    ADD CONSTRAINT tipo_archivo_pkey PRIMARY KEY (id_tipo_archivo);


--
-- TOC entry 4931 (class 2606 OID 81156)
-- Name: usuario_archivo usuario_archivo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario_archivo
    ADD CONSTRAINT usuario_archivo_pkey PRIMARY KEY (id_usuario_archivo);


--
-- TOC entry 4933 (class 2606 OID 81158)
-- Name: usuario_capa usuario_capa_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario_capa
    ADD CONSTRAINT usuario_capa_pkey PRIMARY KEY (id_usuario_capa);


--
-- TOC entry 4929 (class 2606 OID 81160)
-- Name: usuario usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id_usuario);


--
-- TOC entry 4947 (class 2606 OID 81161)
-- Name: archivo_campo fk_archivo_campo_tipo_archivo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archivo_campo
    ADD CONSTRAINT fk_archivo_campo_tipo_archivo FOREIGN KEY (arccam_id_tipo) REFERENCES public.tipo_archivo(id_tipo_archivo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4946 (class 2606 OID 81166)
-- Name: archivo fk_archivo_tipo_archivo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archivo
    ADD CONSTRAINT fk_archivo_tipo_archivo FOREIGN KEY (archiv_id_tipo) REFERENCES public.tipo_archivo(id_tipo_archivo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4950 (class 2606 OID 81171)
-- Name: usuario fk_asignacion_usuario_rol; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT fk_asignacion_usuario_rol FOREIGN KEY (usuari_id_rol) REFERENCES public.rol(id_rol) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4948 (class 2606 OID 81181)
-- Name: permiso fk_permiso_modulo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permiso
    ADD CONSTRAINT fk_permiso_modulo FOREIGN KEY (permis_id_modulo) REFERENCES public.modulo(id_modulo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4949 (class 2606 OID 81186)
-- Name: permiso fk_permiso_rol; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permiso
    ADD CONSTRAINT fk_permiso_rol FOREIGN KEY (permis_id_rol) REFERENCES public.rol(id_rol) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4952 (class 2606 OID 81191)
-- Name: usuario_archivo fk_usuario_archivo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario_archivo
    ADD CONSTRAINT fk_usuario_archivo FOREIGN KEY (usuarc_id_archivo) REFERENCES public.archivo(id_archivo) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4951 (class 2606 OID 81201)
-- Name: usuario fk_usuario_persona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT fk_usuario_persona FOREIGN KEY (usuari_id_persona) REFERENCES public.persona(id_persona) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4953 (class 2606 OID 81206)
-- Name: usuario_archivo fk_usuario_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario_archivo
    ADD CONSTRAINT fk_usuario_usuario FOREIGN KEY (usuarc_id_usuario) REFERENCES public.usuario(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 4954 (class 2606 OID 81211)
-- Name: usuario_capa fk_usuario_usuario; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuario_capa
    ADD CONSTRAINT fk_usuario_usuario FOREIGN KEY (usucap_id_usuario) REFERENCES public.usuario(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 5144 (class 0 OID 0)
-- Dependencies: 6
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE USAGE ON SCHEMA public FROM PUBLIC;


-- Completed on 2025-06-18 11:01:22

--
-- PostgreSQL database dump complete
--

