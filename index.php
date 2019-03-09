<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Subir archivos al servidor</title>
<style type="text/css" media="screen">
*{font-size:1em;
transform: scale(1.1);
}
input{
 width: 200px;
}
</style>
</head>
<body>
<div style="text-align:center;margin:auto;">
<form enctype='multipart/form-data' action='' method='post'>
<br/>
<input name='uploadedfile' type='file' id="imagen"><br/><br/>
<input type='submit' name="enviar" value='Subir archivo'>
</form>
</div>
<?php 
		include('funciones.php');
comprobar_carpetas();
$target_path = "uploads/";
$avatar=$_FILES['uploadedfile']['name'];
$nombre = substr($avatar, 0, -4);
	$extension= substr($avatar, -4);
	if(strlen($nombre)>15){
		$nombre=substr($avatar, 0, 15);
		$avatar=$nombre.$extension; 
	}

$imagen=basename($avatar);
$longitud=strlen($imagen);
$target_path .=$imagen;

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)){


	if(substr($imagen,$longitud-3,$longitud)!="gif"){
		unlink($target_path);
	}
	else{
$numero=1;
		chmod("uploads/", 0777);
		rename($target_path ,'output/picture.gif');
		chmod("output/picture.gif", 0777);
		rmdir('uploads');
		$numero=sacarframes();
		if($numero>1){
			date_default_timezone_set ('Europe/Madrid');
		$nombre_zip=crear_zip();
		
		
		for($x=1;$x<=$numero;$x++){
		if($x<10){
			unlink('output/frames/frame0'.$x.'.png');
		}
		else{
			unlink('output/frames/frame'.$x.'.png');
		}
	}
	unlink("output/picture.gif");
		
		
		}
		else{
			if (file_exists("output/frames/frame01.png")) {
        mkdir("output/frames/frame01.png", 0777, true);
    }
	
	
			
	}
	}

	rmdir('output/frames');
	rmdir('output');
	header("Content-disposition: attachment; filename=$nombre_zip");
	header("Content-type: MIME");
	readfile($nombre_zip);
	unlink($nombre_zip);
}

?>

</body>
</html>