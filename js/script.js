//Iniciamos nuestra función jquery.
$(function(){
	$('#enviar').click(SubirFotos); //Capturamos el evento click sobre el boton con el id=enviar	y ejecutamos la función seleccionado.
	$('#procesar').click(ValidarArchivo); //Capturamos el evento click sobre el boton con el id=procesar	y ejecutamos la función seleccionado.
	$('#nuevo').click(ReiniciarTodo); //Capturamos el evento click sobre el boton con el id=nuevo	y ejecutamos la función seleccionado.
});

function SubirFotos(){	
	var archivos = document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
	var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput
	//Creamos una instancia del Objeto FormDara.
	var archivos = new FormData();
	/* Como son multiples archivos creamos un ciclo for que recorra la el arreglo de los archivos seleccionados en el input
	Este y añadimos cada elemento al formulario FormData en forma de arreglo, utilizando la variable i (autoincremental) como 
	indice para cada archivo, si no hacemos esto, los valores del arreglo se sobre escriben*/
	for(i=0; i<archivo.length; i++){
	archivos.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice direfente
	}

	/*Ejecutamos la función ajax de jQuery*/		
	$.ajax({
		url:'subir.php', //Url a donde la enviaremos
		type:'POST', //Metodo que usaremos
		contentType:false, //Debe estar en false para que pase el objeto sin procesar
		data:archivos, //Le pasamos el objeto que creamos con los archivos
		processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
		cache:false //Para que el formulario no guarde cache
	}).done(function(msg){//Escuchamos la respuesta y capturamos el mensaje msg
		MensajeFinal(msg)
	});
}

function MensajeFinal(msg){
	$('#mensage').css("margin-bottom","1px");
	$('#mensage').html(msg);//A el div con la clase msg, le insertamos el mensaje en formato  thml
	$('#mensage').show('slow');//Mostramos el div.
	$('#procesar').show();//Mostramos el boton.
	$('div.progress').css("margin-bottom","3px");
	$('div.progress').show();
	$('#nuevo').show();
	$('#enviar').hide();
}

function ValidarArchivo(){
	var x = "correos_files/";
	var parametros = {
            "ruta" : x
    };	
	$.ajax({
		type: "POST",
		url: "validar.php",
		data: parametros,
		async: true,
		xhr: function () {
			var xhr = new window.XMLHttpRequest();
			//Upload Progress
			xhr.upload.addEventListener("progress", function (evt) {
				if (evt.lengthComputable) {
					var percentComplete = (evt.loaded / evt.total) * 100; 
					$('div.progress > div.progress-bar').css({ "width": percentComplete + "%" }); 
					$("#avance").html(percentComplete + "%");
				} 
			}, false);
		 
			//Download progress
			xhr.addEventListener("progress", function (evt) {
				if (evt.lengthComputable) { 
					var percentComplete = (evt.loaded / evt.total) *100;
					$("div.progress > div.progress-bar").css({ "width": percentComplete + "%" });
					$("#avance").html(percentComplete + "%");
				} 
			}, false);
			return xhr;
		},
		beforeSend: function () {
			$('#mensage2').show('slow');
            $("#mensage2").html("Procesando, espere por favor...");
        },
   		success: function(data){
   			$('#procesar').hide();
   			var result=data.split('//');
   			$('#mensage2').css("padding","5px");
       		$('#mensage2').css("margin-bottom","3px");
   			$('#mensage2').css("font-size","12px");
       		$("#mensage2").html(result[0]);
       		$('#mensage3').css("font-size","8px");
       		$("#mensage3").html(result[1]);

       		var enlace_final = "<a href='./resultados_files/"+ result[2] +"' download  >Descargar   Validos</a>";
       		var enlace_final2 = "<a href='./resultados_files/"+ result[3] +"' download >Descargar Invalidos</a>";
       		$('#link_descarga').html(enlace_final+'<br>'+enlace_final2);
       		$('#link_descarga').show();//Mostramos el boton.
   		}
	});

	return false;
}

function ReiniciarTodo(){
	 $('#mensage').html("..."); //limpiamos el objeto div.
	 $('#mensage').hide('slow'); // Ocultamos el div.
	 $('#avance').html("..."); // Ocultamos el div.
	 $('div.progress').hide();
	 $('#mensage2').html("...");
	 $('#mensage2').hide();
	 $('#link_descarga').html("...");
	 $('#link_descarga').hide();
	 $('#procesar').hide();
	 $("#archivos").val("");
	 $("span.file-input-name").hide();
	 $('#nuevo').hide();
	 $('#enviar').show();
}