<?php
    // Configuración de entorno
    define("IS_DEV", true);
    // Zona horaria
    date_default_timezone_set("America/Lima");

    if (IS_DEV) {
        if (PHP_OS_FAMILY === "Windows") {
            define("BASE_URL", "http://127.0.0.5/"); // Local
        }
        else{
            define("BASE_URL", "http://app5.local/"); // Local
        }
        define("DB_HOST", "localhost");
        define("DB_NAME", "geoportalwwf");
        define("DB_USER", "postgres");
        define("DB_PASSWORD", "123456");
        define("DB_PORT", "5432");

        define("GEOSERVER_URL", "http://localhost:8080/geoserver/rest");
        define("GEOSERVER_USER", "admin");
        define("GEOSERVER_PASS", "geoserver");
    } else {
        define("BASE_URL", "http://geoportal.wwfperu.org/"); // Producción
        define("DB_HOST", "172.20.76.4");
        define("DB_NAME", "geoportalwwf");
        define("DB_USER", "postgres");
        define("DB_PASSWORD", "123456789");
        define("DB_PORT", "5432");

        define("GEOSERVER_URL", "http://172.20.75.11/geoserver/rest");
        define("GEOSERVER_USER", "admin");
        define("GEOSERVER_PASS", "geoserver");
    }
    define("DB_CHARSET", "utf8");

    if (PHP_OS_FAMILY === "Windows") {
        define("DIR_TMP_ZIP", "C:/Apps/www/geoportal/Assets/files/uploads/tmp/");
        define("DIR_TMP_SHP", "C:/Apps/www/geoportal/Assets/files/uploads/shp/");
        define("DIR_TMP_RASTER", "C:/Apps/www/geoportal/Assets/files/uploads/raster/");
    }
    else if(PHP_OS_FAMILY === "Darwin")
    {
        define("DIR_TMP_ZIP", "/private/apps/www/geoportal/Assets/files/uploads/tmp/");
        define("DIR_TMP_SHP", "/private/apps/www/geoportal//Assets/files/uploads/shp/");
        define("DIR_TMP_RASTER", "/private/apps/www/geoportal//Assets/files/uploads/raster/");
    }
    else{
        define("DIR_TMP_ZIP", "/var/www/html/Assets/files/uploads/tmp/");
        define("DIR_TMP_SHP", "/var/www/html/Assets/files/uploads/shp/");
        define("DIR_TMP_RASTER", "/var/www/http/Assets/files/uploads/raster/");
    }

    // Configuración de bitácora
    define("BITAC_ESQUEMA", "informacion_base");
    define("BITAC_TABLA", "reservas_indigenas_piaci");
    define("BITAC_ATRIBUTOS", "id, nombre, categoria, estado, pueblo_ind");

    // Configuración de correo
    define("NOMBRE_REMITENTE", "Geoportal WWF");
    define("EMAIL_REMITENTE", "informes@geoportal.wwf.org");
    define("NOMBRE_EMPRESA", "Geoportal WWF");
    define("WEB_EMPRESA", "www.geoportal.wwf.org");

    // Información de la empresa
    define("DIRECCION", "Lima - Perú");
    define("TELEMPRESA", "(+01) 9999999");
    define("WHATSAPP", "+51979435811");
    define("EMAIL_EMPRESA", "empresa@geoportal.wwf.org");
    define("EMAIL_PEDIDOS", "pedidos@geoportal.wwf.org");
    define("EMAIL_CONTACTO", "contacto@geoportal.wwf.org");

    // Configuración de encriptación
    define("KEY", "geoportalwwf");
    define("METHODENCRIPT", "AES-128-ECB");

    // Configuración de módulos
    define("MPERSONAS", 7);
    define("MUSUARIOS", 8);
    define("MCAPAS", 9);
    define("MPLANOTECA", 10);
    define("MARCHIVOS", 11);
    define("MPORTADAS", 12);
    define("MSERVICIOSGEOGRAFICOS", 13);
    define("MNOTICIAS", 14);
    //define("MTEMATICA",15)

    // Configuración de páginas
    define("PINICIO", 1);
    define("PERROR", 9);

    // Configuración de roles
    define("RADMINISTRADOR", 1);
    define("RCLIENTES", 2);

    // Configuración de registros
    define("REG_XPORPAGINA", 10);
    define("REG_XBUSCAR", 10);
?>