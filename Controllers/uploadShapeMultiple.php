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

		// if ($file['tmp_name']<>0){		
		// 	$arr[] = array('result'  => 0,
	    //                    'mensaje' => $file['error'] . " this"
	    //           );		
	    //     echo '' . json_encode($arr) . '';
		// 	return;
		// }		

				
		$resultGeneral;		

		$tmpfile = $file['tmp_name'];
		$fileparts = explode('.', $file['name']);
		$fileExtension = $fileparts[count($fileparts) - 1];


		if ($fileExtension<>"zip"){		
			$arr[] = array('result'  => 0,
	                       'mensaje' => "Archivo no valido, solo admite formato *.zip"
	              );		
	        echo '' . json_encode($arr) . '';
			return;
		}



							
		$nombre =   "up". date("YmdHis");  
		$saveto  ="../tempostorage/". $nombre.".zip";

		if (!move_uploaded_file($tmpfile, $saveto) ){
			$arr[] = array('result'  => 0,
	                       'mensaje' => "Error en move_uploaded_file"
	              );		
	        echo '' . json_encode($arr) . '';
			return;
		} 

			
					
		$zip = new ZipArchive;
		$res = $zip->open($saveto);

		if ($res === FALSE) {
			$arr[] = array('result'  => 0,
	                       'mensaje' => "Error open zip"
	              );		
	        echo '' . json_encode($arr) . '';
			return;
		}


					
	    $zip->extractTo("../tempostorage/". $nombre."/");
	    $zip->close();					    				   
	    $Totreg=0;


	    $files = glob("../tempostorage/". $nombre."/*");
		$files = array_combine($files, array_map("filemtime", $files));
		arsort($files);

		$latest_file = key($files);

		//$arrFiles = scandir("../tempostorage/". $nombre."/");


		$arrFiles = array();
		$iterator = new FilesystemIterator("../tempostorage/". $nombre);
		 
		foreach($iterator as $entry) {
		    $arrFiles[] = $entry->getFilename();
		}


		//$rut = obtener_estructura_directorios ("../tempostorage/". $nombre);

		obtener_estructura_directorios2("../tempostorage/". $nombre);

		foreach ($GLOBALS["final"] as $value) {
	  
		  $fileparts = explode('/',  $value);
		  if ($value<>""){
		  	// echo $value. " - ". count($fileparts)."<br>";
		  	// $arrDatos[]["nro"] = count($fileparts);
		  	$laRuta = $value;
		  }
			
		}


		if ($laRuta === "" || $laRuta === null) {
			$arr[] = array('result'  => 0,
	                       'mensaje' => "No existe archivo shape"
	              );		
	        echo '' . json_encode($arr) . '';
			return;
		}		

										
	    //@$Shapefile = new ShapefileReader("C:/ms4w/Apache/htdocs/geobosque/tempostorage/". $nombre."/shape_multi_areas.shp");
	    @$Shapefile = new ShapefileReader($laRuta);
	   	if (!$Shapefile) {
			$arr[] = array('result'  => 0,
	                       'mensaje' => "No se encontro shapeFile"
	              );		
	        echo '' . json_encode($arr) . '';
			return;
		}


	    $tipoShape= $Shapefile->getShapeType();

	    if ($tipoShape<>5 && $tipoShape<>1 && $tipoShape<>3){
	    	$arr[] = array('result'  => 0,
	                       'mensaje' => "Tipo de geometria no admitido"
	              );		
	        echo '' . json_encode($arr) . '';
			return;
	    }


	    



	    $arrayCamposShape= $Shapefile->getFieldsNames();

	    $fiel_codigo = array_search('CODIGO', $arrayCamposShape);
	    $fiel_nombre = array_search('NOMBRE', $arrayCamposShape);

	    // if ($fiel_codigo===false){
	    // 	$arr[] = array('result'  => 0,
	    //                    'mensaje' => "No se encotro el campo requerido[codigo]."
	    //                    ,'arrayCamposShape' => $arrayCamposShape
	    //           );		
	    //     echo '' . json_encode($arr) . '';
		// 	return;
	    // }


	    // if ($fiel_nombre===false){
	    // 	$arr[] = array('result'  => 0,
	    //                    'mensaje' => "No se encotro el campo requerido[nombre]"
	    //           );		
	    //     echo '' . json_encode($arr) . '';
		// 	return;
	    // }



	    //validar tipo geom multi
	    $tipos  = array();	    	    
	    $boleanMultis=1;
	    // Read all the records
	    while ($Geometry2 = $Shapefile->fetchRecord()) {	
	    	$tipo_geom =  json_decode ($Geometry2->getGeoJSON());

	    	if ($tipo_geom->{'type'}==="MultiPolygon"){

	    		$boleanMultis=99;
	    	}
	       	
	    }

	    

			    	

    	$cart  = array();	 

    	$Shapefile->setCurrentRecord(1);   	    
	    
	    // Read all the records
	    while ($Geometry = $Shapefile->fetchRecord()) {	

	    	$tipo_geom2 =  json_decode ($Geometry->getGeoJSON());

	    	//if ($tipo_geom2->{'type'} === "Polygon"){

	    		$arrAtrib = array();

	    		foreach ($arrayCamposShape as $valor) {

	    			array_push($arrAtrib, $Geometry->getData($valor));
				}


	    		$cart[] =  array('the_geom'	=> $Geometry->getGeoJSON()
           					,'atributos' 	=> $arrAtrib           					
	        	);	
		        
		       	$Totreg+=1;

	    		
	    	//}			        				        

	       	
	    }




	    if ($Totreg>1000){
	    	$arr[] = array('result'  => 0,
	                       'mensaje' => "Excede el numero de areas permitidas"
	              );		
	        echo '' . json_encode($arr) . '';
			return;
	    }




	    
	    //$resultGeneral=1;
	    $resultGeneral=$boleanMultis;

	    $mesnajeFin= "ok";

	    if ($boleanMultis===99){
	    	$mesnajeFin= "Existen tipos multipoligonos  en el shapeFile, los cuales no se han cargado. Revise antes de grabar!";
	    }

			    
			


		$arr[] = array('result'  => $resultGeneral,
			           'mensaje' => $mesnajeFin,                   	                   
	                   'totReg'  =>	$Totreg,	                   
	                   'geometrias' => $cart	,
	                   'tipo' => $tipoShape,
	                   'fileExtension' => $fileExtension,
	                   'latest_file' => $latest_file,
	                   'arrFiles' => $arrFiles ,
	                   'rut' => $laRuta,
	                   "camposShape" => $arrayCamposShape  
	                   ,"tipos" => $tipo_geom->{'type'}           
	                );

		echo '' . json_encode($arr) . '';
			
	   

}


function obtener_estructura_directorios2($ruta){
	
    // Se comprueba que realmente sea la ruta de un directorio
    if (is_dir($ruta)){
        // Abre un gestor de directorios para la ruta indicada
        $gestor = opendir($ruta);
        //echo "<ul>";
        

        // Recorre todos los elementos del directorio
        while (($archivo = readdir($gestor)) !== false)  {
                
            $ruta_completa = $ruta . "/" . $archivo;
            

            // Se muestran todos los archivos y carpetas excepto "." y ".."
            if ($archivo != "." && $archivo != "..") {
            	//echo "ruta_completa : ". $ruta_completa. "<br>";
            	//$final[]= $ruta_completa;
            	$cadena = "hola mundo";

				if (substr($ruta_completa,-3)==="shp" && $archivo<>""){
					$GLOBALS["final"][]=$ruta_completa;
				}
            	
                // Si es un directorio se recorre recursivamente

                if (is_dir($ruta_completa)) {
                	//$contador++;
                    //echo "<li>" . $contador  ." - ". $archivo . "</li>";
                    
                    obtener_estructura_directorios2($ruta_completa);
                } else {
                	//$contador++;
                    //echo "<li>" . $contador  ." - ". $archivo . "</li>";
                    //$final[]= $archivo;
                    //echo $final;
                    
                }
            }
        }
        
        // Cierra el gestor de directorios
        closedir($gestor);
        //echo "</ul>";
        
    } else {
        //echo "No es una ruta de directorio valida<br/>";
        $GLOBALS["final"]="";
        
    }

    
}	


function obtener_estructura_directorios($ruta){
	$ruttt="";
    // Se comprueba que realmente sea la ruta de un directorio
    if (is_dir($ruta)){
        // Abre un gestor de directorios para la ruta indicada
        $gestor = opendir($ruta);
        //echo "<ul>";
        $ruttt .="<ul>";

        // Recorre todos los elementos del directorio
        while (($archivo = readdir($gestor)) !== false)  {
                
            $ruta_completa = $ruta . "/" . $archivo;

            // Se muestran todos los archivos y carpetas excepto "." y ".."
            if ($archivo != "." && $archivo != "..") {
                // Si es un directorio se recorre recursivamente
                if (is_dir($ruta_completa)) {
                    //echo "<li>" . $archivo . "</li>";
                    $ruttt .="<li>" . $archivo . "</li>";
                    obtener_estructura_directorios($ruta_completa);
                } else {
                    //echo "<li>" . $archivo . "</li>";
                    $ruttt .="<li>" . $archivo . "</li>";
                }
            }
        }
        
        // Cierra el gestor de directorios
        closedir($gestor);
        //echo "</ul>";
        $ruttt .="</ul>";
    } else {
        //echo "No es una ruta de directorio valida<br/>";
        $ruttt .="No es una ruta de directorio valida<br/>";
    }

    return $ruttt;
}
    

function procesar($file){

	if ($file['error']<>4 ){

		$mensajeProc;
		$resultReadShape;
		$resultGeneral;

		if ($file['tmp_name']==0){

			$tmpfile = $file['tmp_name'];
			$fileparts = explode('.', $file['name']);
			$fileExtension = $fileparts[count($fileparts) - 1];
						
			$nombre =   "up". date("YmdHis");  
			$saveto  ="../tempostorage/". $nombre.".zip"; 			
			
			if (move_uploaded_file($tmpfile, $saveto) ){
				
				$zip = new ZipArchive;

				$res = $zip->open($saveto);
				if ($res === TRUE) {
				    $zip->extractTo("../tempostorage/". $nombre."/");
				    $zip->close();
				    				   
				    $Totreg=0;
					//leyendo el shapefile					
				    $Shapefile = new ShapefileReader("C:/ms4w/Apache/htdocs/geobosque/tempostorage/". $nombre."/shape_multi_areas.shp");

				    $tipoShape= $Shapefile->getShapeType();

				    if ($tipoShape<>5){

				    	$resultGeneral=0;	
						$mensajeProc = "Tipo de geometria no admitido"; 	

				    }else{


				    	//if 

				    	$cart  = array();
					    //$casos = array();
					    $TotDesestimados=0;
					    
					    // Read all the records
					    while ($Geometry = $Shapefile->fetchRecord()) {				        				        

					       	$cart[] =  array('the_geom'	=> $Geometry->getGeoJSON(),
				           					'nombre' 	=> $Geometry->getData('NOMBRE') 
		                	);	
					        
					       	$Totreg+=1;
					    }
					    
					    $resultGeneral=1;

				    }
					
				    

				}else{
					
					$resultGeneral=0;	
					$mensajeProc = "Error open zip"; 			   
				}

			}else{				
				$resultGeneral=0; 
				$mensajeProc = "Error en move_uploaded_file";               
			}

		}else{
						
			$resultGeneral=0;
			$mensajeProc = $file['error'];
		}


		//leer el excel
      	$ultletra="";

		


		$arr[] = array('result'  => $resultGeneral,
			           'mensaje' => $mensajeProc,                   	                   
	                   'totReg'  =>	$Totreg,	                   
	                   'poligonos' => $cart	,
	                   'tipo' => $tipoShape                   
	                );

		echo '' . json_encode($arr) . '';

			// $arr[] = array('result'  => 1,
		  	//                      'mensaje' => "cargop"
		  	//             );		
		  	//       echo '' . json_encode($arr) . '';

		

    }else{ 

		$arr[] = array('result'  => 0,
                       'mensaje' => "No se cargo archivo"
              );		
        echo '' . json_encode($arr) . '';
	}

}

    









?>




