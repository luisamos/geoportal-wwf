<?php
	//Retorna la url del proyecto
	function base_url()
	{
		return BASE_URL;
	}
    //Retorna la url de Assets
    function media()
    {
        return BASE_URL."Assets";
    }
    function headerAdmin($data="")
    {
        $view_header = "Views/Template/header_admin.php";
        require_once ($view_header);
    }
    function header_body_Admin($data="")
    {
        $view_header = "Views/Template/header_body_admin.php";
        require_once ($view_header);
    }
    function footerAdmin($data="")
    {
        $view_footer = "Views/Template/footer_admin.php";
        require_once ($view_footer);
    }
    function headerPublic($data="")
    {
        $view_header = "Views/Template/header_public.php";
        require_once ($view_header);
    }
    function header_body_Public($data="")
    {
        $view_header = "Views/Template/header_body_public.php";
        require_once ($view_header);
    }
    function footerPublic($data="")
    {
        $view_footer = "Views/Template/footer_public.php";
        require_once ($view_footer);
    }
	//Muestra informaci�n formateada
	function dep($data)
    {
        $format  = print_r('<pre>');
        $format .= print_r($data);
        $format .= print_r('</pre>');
        return $format;
    }
    function getModal(string $nameModal, $data)
    {
        $view_modal = "Views/Template/Modals/{$nameModal}.php";
        require_once $view_modal;
    }
    function getFile(string $url, $data)
    {
        ob_start();
        require_once("Views/{$url}.php");
        $file = ob_get_clean();
        return $file;
    }
    function getPermisos(int $id_modulo){
        require_once ("Models/PermisosModel.php");
        $objPermisos = new PermisosModel();
        if (!empty($_SESSION['userData'])) {
            $id_rol = $_SESSION['userData']['id_rol'];
            $arrPermisos = $objPermisos->permisosModulo($id_rol);
            $permisos = '';
            $permisosMod = '';
            if(count($arrPermisos) > 0 ){
                $permisos = $arrPermisos;
                $permisosMod = isset($arrPermisos[$id_modulo]) ? $arrPermisos[$id_modulo] : "";
            }
            $_SESSION['permisos'] = $permisos;
            $_SESSION['permisosMod'] = $permisosMod;
        }
    }

    function sessionUser(int $idpersona){
        require_once ("Models/IngresoModel.php");
        $objLogin = new IngresoModel();
        $request = $objLogin->sessionLogin($idpersona);
        return $request;
    }

    function uploadImage(array $data, string $name){
        $url_temp = $data['tmp_name'];
        $destino    = '/var/www/html/Assets/images/uploads/'.$name;

        // Verificar si el directorio de destino existe
        if (!file_exists('/var/www/html/Assets/files/uploads')) {
            return "El directorio de destino no existe";
        }

        // Intentar mover el archivo al directorio de destino
        if (move_uploaded_file($url_temp, $destino)) {
            return true; // �xito al mover el archivo
        }
        else {
            return "Error al mover la imagen al directorio de destino";
        }
    }

    function uploadFile(array $data, string $name){
        $url_temp = $data['tmp_name'];
        $destino   = '/var/www/html/Assets/files/uploads/'.$name;

        // Verificar si el directorio de destino existe
        if (!file_exists('/var/www/html/Assets/files/uploads')) {
            return "El directorio de destino no existe";
        }

        // Intentar mover el archivo al directorio de destino
        if (move_uploaded_file($url_temp, $destino)) {
            return true; // �xito al mover el archivo
        }
        else {
            return "Error al mover el archivo al directorio de destino";
        }
    }

    function deleteFile(string $name){
        unlink('Assets/images/uploads/'.$name);
    }

    function deleteFile2(string $name){
        unlink('Assets/files/uploads/'.$name);
    }

    //Elimina exceso de espacios entre palabras
    function strClean($strCadena){
        $string = preg_replace(['/\s+/','/^\s|\s$/'],[' ',''], $strCadena);
        $string = trim($string); //Elimina espacios en blanco al inicio y al final
        $string = stripslashes($string); // Elimina las \ invertidas
        $string = str_ireplace("<script>","",$string);
        $string = str_ireplace("</script>","",$string);
        $string = str_ireplace("<script src>","",$string);
        $string = str_ireplace("<script type=>","",$string);
        $string = str_ireplace("SELECT * FROM","",$string);
        $string = str_ireplace("DELETE FROM","",$string);
        $string = str_ireplace("INSERT INTO","",$string);
        $string = str_ireplace("SELECT COUNT(*) FROM","",$string);
        $string = str_ireplace("DROP TABLE","",$string);
        $string = str_ireplace("OR '1'='1","",$string);
        $string = str_ireplace('OR "1"="1"',"",$string);
        $string = str_ireplace('OR �1�=�1�',"",$string);
        $string = str_ireplace("is NULL; --","",$string);
        $string = str_ireplace("is NULL; --","",$string);
        $string = str_ireplace("LIKE '","",$string);
        $string = str_ireplace('LIKE "',"",$string);
        $string = str_ireplace("LIKE �","",$string);
        $string = str_ireplace("OR 'a'='a","",$string);
        $string = str_ireplace('OR "a"="a',"",$string);
        $string = str_ireplace("OR �a�=�a","",$string);
        $string = str_ireplace("OR �a�=�a","",$string);
        $string = str_ireplace("--","",$string);
        $string = str_ireplace("^","",$string);
        $string = str_ireplace("[","",$string);
        $string = str_ireplace("]","",$string);
        $string = str_ireplace("==","",$string);
        return $string;
    }

    function clear_cadena(string $cadena){
        //Reemplazamos la A y a
        $cadena = str_replace(
        array('�', '�', '�', '�', '�', '�', '�', '�', '�'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $cadena
        );
        //Reemplazamos la E y e
        $cadena = str_replace(
        array('�', '�', '�', '�', '�', '�', '�', '�'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $cadena );
        //Reemplazamos la I y i
        $cadena = str_replace(
        array('�', '�', '�', '�', '�', '�', '�', '�'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena );
        //Reemplazamos la O y o
        $cadena = str_replace(
        array('�', '�', '�', '�', '�', '�', '�', '�'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena );
        //Reemplazamos la U y u
        $cadena = str_replace(
        array('�', '�', '�', '�', '�', '�', '�', '�'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena );
        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
        array('�', '�', '�', '�',',','.',';',':'),
        array('N', 'n', 'C', 'c','','','',''),
        $cadena
        );
        return $cadena;
    }

    //Genera una contrase�a de 10 caracteres
	function passGenerator($length = 10){
        $pass = "";
        $longitudPass=$length;
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $longitudCadena=strlen($cadena);

        for($i=1; $i<=$longitudPass; $i++)
        {
            $pos = rand(0,$longitudCadena-1);
            $pass .= substr($cadena,$pos,1);
        }
        return $pass;
    }

    // funcion para almacenar los meses del a�o en un array
    function Meses(){
        $meses = array( "Enero",
                        "Febrero",
                        "Marzo",
                        "Abril",
                        "Mayo",
                        "Junio",
                        "Julio",
                        "Agosto",
                        "Septiembre",
                        "Octubre",
                        "Novimebre",
                        "Diciembre");
        return $meses;
    }

    function getPageRout(string $ruta){
        require_once("Libraries/Core/PostgreSQL.php");
        $con = new PostgreSQL();
        $sql = "SELECT * FROM modulo WHERE modulo_ruta = '$ruta' AND modulo_estado != 0 ";
        $request = $con->select($sql);
        return $request;
    }

    function viewPage(int $idpagina){
        require_once("Libraries/Core/PostgreSQL.php");
        $con = new PostgreSQL();
        $sql = "SELECT * FROM modulo WHERE id_modulo = $idpagina ";
        $request = $con->select($sql);
        if( ($request['modulo_estado'] == 2 AND isset($_SESSION['permisosMod']) AND $_SESSION['permisosMod']['u'] == true) OR $request['modulo_estado'] == 1){
            return true;
        }else{
            return false;
        }
    }
 ?>