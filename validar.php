<?php

date_default_timezone_set('America/Lima');

// include SMTP Email Validation Class 
require_once('./class/smtp_validateEmail.class.php');

function VerificarDireccionCorreo($direccion1)
{
   $Sintaxis='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
   if(preg_match($Sintaxis,$direccion1))
      return "SI";
   else
     return "NO";
}

function VerificarDominioDireccionCorreo($direccion2){

	$domain = explode('@', $direccion2);
	if(empty($domain[1])){
		return "NO";
	}

    if (checkdnsrr($domain[1])) 
      return "SI";  
    else
      return "NO";  
}

function VerificarUsuarioDireccionCorreo($direccion3){

	// the email to validate 
	$email = $direccion3; 
	// an optional sender 
	$sender = 'check@ncprogreso.com'; 
	// instantiate the class 
	$SMTP_Validator = new SMTP_validateEmail(); 
	// turn on debugging if you want to view the SMTP transaction 
	$SMTP_Validator->debug = true; 
	// do the validation 
	$results = $SMTP_Validator->validate(array($email), $sender); 
	// view results 

	if ($results[$email]) {
		return "SI";
	} else { 
		return "NO";
	}
}

//recibiendo ruta
$path = "./".$_POST['ruta'];

$dir = opendir($path);
$files = array();
while ($current = readdir($dir)){
    if( $current != "." && $current != "..") {
        if(is_dir($path.$current)) {
            showFiles($path.$current.'/');
        } else {
            $files[] = $current; //aqui va el listado de archivos
        }
    }
}

/*inicializando variables*/
$EmailsPositivos=0;
$EmailsNegativos=0;
$EmailsTotales=0;
$listado = "";
$linea_temporal_true = "";
$linea_temporal_false = "";
$nuevo_archivo1 = "";
$nuevo_archivo2 = "";
$rpta_consulta = "*-----*";

for($i=0; $i<count( $files ); $i++){
	
	/*abriendo archivo*/
	$file = fopen($_POST['ruta'].$files[$i], "r");
	
	/*capturamos hora inicial*/
	$hora_inicial = date("H:i:s a");
	
	/*recorriendo las lineas*/
	while(!feof($file)) {
		$linea = fgets($file);
		if (trim($linea) != ''){
			$EmailsTotales = $EmailsTotales + 1;
			
			/*Validando Estructura*/
			$estructura = VerificarDireccionCorreo(trim($linea));
			if ($estructura == 'SI'){
				
				/*Validando Dominio*/
				$dominio = VerificarDominioDireccionCorreo(trim($linea));
				if ($dominio == 'SI'){
					
					/*Validando Usuario*/
					$usuario = VerificarUsuarioDireccionCorreo(trim($linea));
					if ($usuario == 'SI'){
						$EmailsPositivos = $EmailsPositivos + 1;
						$linea_temporal_true = $linea_temporal_true.trim($linea)."|SI|SI|SI|".date("Y-m-d H:i:s a")."\n";
					} else {
						$EmailsNegativos = $EmailsNegativos + 1;
						$linea_temporal_false = $linea_temporal_false.trim($linea)."|".$estructura."|".$dominio."|NO|".date("Y-m-d H:i:s a")."\n";	
					}
				} else {
					$EmailsNegativos = $EmailsNegativos + 1;
					$linea_temporal_false = $linea_temporal_false.trim($linea)."|".$estructura."|NO|NO|".date("Y-m-d H:i:s a")."\n";
				}
			} else {
				$EmailsNegativos = $EmailsNegativos + 1;
				$linea_temporal_false = $linea_temporal_false.trim($linea)."|NO|NO|NO|".date("Y-m-d H:i:s a")."\n";
			}
		}
	}

	/*capturamos hora inicial*/
	$hora_final = date("H:i:s a");

	/*cerrando archivo abierto*/
	fclose($file);

	/*Creando archivo true*/
	$nuevo_archivo1 = substr($files[$i], 0, -4)."_Validacion_True_".date("Y-m-d").".txt";
	$file2 = fopen("resultados_files/".$nuevo_archivo1, "w");

	/*Escribiendo contenido*/
	fwrite($file2, "CORREO|ESTRUCTURA|DOMINIO|USUARIO|FECHA"."\n".$linea_temporal_true);

	/*Cerrando archivo creado*/
	fclose($file2);

	/*Creando archivo false*/
	$nuevo_archivo2 = substr($files[$i], 0, -4)."_Validacion_False_".date("Y-m-d").".txt";	
	$file3 = fopen("resultados_files/".$nuevo_archivo2, "w");

	/*Escribiendo contenido*/
	fwrite($file3, "CORREO|ESTRUCTURA|DOMINIO|USUARIO|FECHA"."\n".$linea_temporal_false);

	/*Cerrando archivo creado*/
	fclose($file3);
	
	/*armando resumen*/
	$listado = $listado.'<h5>Resumen del Archivo: '.$files[$i]."</h5>"; 
	$listado = $listado."<table class='table'>";
	$listado = $listado."<tr><td>Hora Ininio:</td><td>".$hora_inicial."</td></tr>";
	$listado = $listado."<tr><td>Hora Fin:</td><td>".$hora_final."</td></tr>";
	$listado = $listado."<tr class='info'><td>Emails Procesados :</td><td align='right'><strong>".$EmailsTotales."</strong></td></tr>";
	$listado = $listado."<tr class='success'><td>Emails Correctos :</td><td align='right'>".$EmailsPositivos."</td></tr>";
	$listado = $listado."<tr class='danger'><td>Emails Incorrectos :</td><td align='right'>".$EmailsNegativos."</td></tr>";
	$listado = $listado."</table>";	
}

echo $rpta_consulta."//".$listado."//".$nuevo_archivo1."//".$nuevo_archivo2;
?>