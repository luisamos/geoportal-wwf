<?php
	session_start();

	 // Register autoloader
	require_once('php-shapefile/src/Shapefile/ShapefileAutoloader.php');
	Shapefile\ShapefileAutoloader::register();

	// Import classes
	use Shapefile\Shapefile;
	use Shapefile\ShapefileException;
	use Shapefile\ShapefileReader;
	use Shapefile\ShapefileWriter;
	use Shapefile\Geometry\Point;
	use Shapefile\Geometry\MultiPolygon;


	$final[] ="";
	$laRuta="";
	
	$file = isset($_FILES['file-upload']) ? $_FILES['file-upload'] : NULL;


	procesar2($file);


	function procesar2($file){


		if ($file['error']===4 ){
			$arr[] = array('result'  => 0,
	                       'mensaje' => "No se cargo archivo"
	              );		
	        echo '' . json_encode($arr) . '';
			return;
		}


				
		$resultGeneral="";		

		$tmpfile = $file['tmp_name'];
		$fileparts = explode('.', $file['name']);
		$fileExtension = $fileparts[count($fileparts) - 1];


		if ($fileExtension<>"kml"){		
			$arr[] = array('result'  => 0,
	                       'mensaje' => "Archivo no valido, solo admite formato *.zip"
	              );		
	        echo '' . json_encode($arr) . '';
			return;
		}

							
		$nombre =   "up". date("YmdHis");  
		$saveto  ="../tempostorage/". $nombre.".kml";

		if (!move_uploaded_file($tmpfile, $saveto) ){
			$arr[] = array('result'  => 0,
	                       'mensaje' => "Error en move_uploaded_file"
	              );		
	        echo '' . json_encode($arr) . '';
			return;
		} 


		$arr[] = array('result'  => $resultGeneral,			          
	                   'fileExtension' => $fileExtension,
	                   'nombre' => $nombre	                     
	                );

		echo '' . json_encode($arr) . '';
			
	   

}





    









?>




