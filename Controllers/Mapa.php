<?php
require_once("../Config/Config.php");
require_once("../Libraries/Core/Conexion.php");
require_once("../Libraries/Core/PostgreSQL.php");


$datosPost=$_POST["datos"];

if (isset($datosPost)) {
	
	//FORMATEAMOS EL OBJETO JSON
	//$data  = json_decode(str_replace ('\"','"',  $datosPost ), true);
	 
	//CAPTURAMOS LA VARIABLE DEL NIVEL DE CONSULTA
    //$Credencial = $data["key"];
    
	// $Archivos = New VisorModel();

	// $Resultado = $Archivos->getLayerServices();

	//echo '' . json_encode($Resultado) . '';

	$dert = getLayerServices();

	$arr[] = array('result'  => 1,
	                'data' => $dert
                  );		
	 echo '' . json_encode($arr) . '';





	 
}else{	

	 //DEVOLVER OBJETO CON ERRORES
	 $arr[] = array('result'  => 0,
	                'mensaje' => "Error en post datos"
                  );		
	 echo '' . json_encode($arr) . '';
	 
} 


function getLayerServices(){
	$connPOst = new PostgreSQL();
			
			$sql = "select id_capa,capa_nombre, capa_url,capa_estado, case when capa_id_padre is null then 0 else capa_id_padre end , layers, capa_descripcion  from capa where capa_acceso=1";
					$request = $connPOst->select_all($sql);
					return $request;

}


 ?>