 <?php
 
 function comprobar_carpetas(){
	 if (!file_exists("uploads")) {
        mkdir("uploads", 0777, true);
    }
	else{
		chmod("uploads", 0777);
	}
	
		 if (!file_exists("output")) {
        mkdir("output", 0777, true);
    }
	else{
		chmod("output", 0777);
	}
	
if (!file_exists("output/frames")) {
        mkdir("output/frames", 0777, true);
    }
	else{
		chmod("output/frames", 0777);
	}
 }
 
function sacarframes(){
 include_once ( str_replace('\\','/',dirname(__FILE__) ) ."/GIFDecoder.class.php" );
	
		$animation = 'output/picture.gif';
		$gifDecoder = new GIFDecoder ( fread ( fopen ( $animation, "rb" ), filesize ( $animation ) ) );
		$i = 1;
		
		foreach ( $gifDecoder -> GIFGetFrames ( ) as $frame ) {
			
			if ( $i < 10 ) {
				fwrite ( fopen ( "output/frames/frame0$i.png" , "wb" ), $frame );
			}
			
			else {
				fwrite ( fopen ( "output/frames/frame$i.png" , "wb" ), $frame );
			}
			
			$i++;
		}
return --$i;
}

function agregar_zip($dir, $zip) {
  //verificamos si $dir es un directorio
  if (is_dir($dir)) {
    //abrimos el directorio y lo asignamos a $da
    if ($da = opendir($dir)) {
      //leemos del directorio hasta que termine
      while (($archivo = readdir($da)) !== false) {
        /*Si es un directorio imprimimos la ruta
         * y llamamos recursivamente esta función
         * para que verifique dentro del nuevo directorio
         * por mas directorios o archivos
         */
        if (is_dir($dir . $archivo) && $archivo != "." && $archivo != "..") {

          agregar_zip($dir . $archivo . "/", $zip);

          /*si encuentra un archivo imprimimos la ruta donde se encuentra
           * y agregamos el archivo al zip junto con su ruta 
           */
        } elseif (is_file($dir . $archivo) && $archivo != "." && $archivo != "..") {
          echo "Agregando archivo: $dir$archivo <br/>";
          $zip->addFile($dir . $archivo, $dir . $archivo);
        }
      }
      //cerramos el directorio abierto en el momento
      closedir($da);
    }
  }
}

function crear_zip(){
	$zip = new ZipArchive();

/*directorio a comprimir
 * la barra inclinada al final es importante
 * la ruta debe ser relativa no absoluta
 */
$dir = 'output/';

//ruta donde guardar los archivos zip, ya debe existir
$rutaFinal = "./";

if(!file_exists($rutaFinal)){
  mkdir($rutaFinal);
}

$archivoZip = date("Y") . "_" . date("d") . "_" . date("m") . "_" . date("H") . "-" . date("i") . "-" . date("s").'.zip';

if ($zip->open($archivoZip, ZIPARCHIVE::CREATE) === true) {
  agregar_zip($dir, $zip);
  $zip->close();

  //Muevo el archivo a una ruta
  //donde no se mezcle los zip con los demas archivos
  rename($archivoZip, "$rutaFinal/$archivoZip");

  //Hasta aqui el archivo zip ya esta creado
  //Verifico si el archivo ha sido creado
  if (file_exists($rutaFinal. "/" . $archivoZip)) {
	  return $archivoZip;

  } 
}
}

	?>