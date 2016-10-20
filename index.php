<!doctype html>
<html lang="es">
	<head> 
		<meta charset="UTF-8">		
		<title>Validacion de Correos</title>
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<script src="Jquery/jquery-3.1.1.js"></script> <!-- Integramos jQuery-->
		<script src="js/script.js"></script> <!-- Integramos nuestro script que contendra nuestras funciones Javascript-->
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="bootstrap/js/bootstrap.file-input.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#mensage').hide();
				$('#mensage2').hide();
				$('#procesar').hide();
				$('#link_descarga').hide();
				$('div.progress').hide();
				$('input[type=file]').bootstrapFileInput();
				$('#nuevo').hide();
				$('#titulo').css("padding","5px");
				$('#titulo').css("margin-bottom","10px");
				$('#titulo').css("margin-top","-10px");
			});
		</script>
	</head>
	<body>
	<br>
	<div class="container col-sm-10">
		<div id="titulo" class="alert alert-success" role="alert" align="center">
			<h3>Validador de Emails</h3> 
		</div>
		<table class="table table-bordered" bgcolor="#c0c5ce">
		<tr>
			<td>
				<input id="archivos" type="file" title="Seleccione Archivo" class="btn btn-info btn-xs">
			</td>
			<td align="center">
				<button type="button" id="enviar" class="btn btn-primary btn-xs">Subir Archivo</button>
				<button type="button" id="nuevo"  class="btn btn-danger btn-xs">Reiniciar</button>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="col-sm-8"><div class="alert alert-info" id="mensage"> ... </div></td>
			<!-- <td>&nbsp;</td> -->
		</tr>
		<tr>
			<td>
				<div class="progress"> 
					<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
						<label id="avance"></label>
					</div> 
				</div>
				<div id="mensage2" class="alert alert-warning" role="alert"> ... </div>
				</td>
			<td align="center" valign="center">
				<button type="button" id="procesar" class="btn btn-success btn-sm">Validar Archivos</button>
				<div id="mensage3" class="alert alert-warning" role="alert"> ... </div>
				<div id="link_descarga" class="alert alert-warning" role="alert"></div>
			</td>
		</tr>
		</table>
	</div>
	</body>
</html>