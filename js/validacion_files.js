
function validar_file(){
    var archivo = document.getElementById('archivo');
    var producto = archivo.value;
    var extension = /(.jpg|.jpeg|.png)$/i;
    var img_size = archivo.files[0].size;
    var size = "1000000";
    var mensaje = "";
    var labelImg = document.getElementById("labelImg");

    document.getElementById('info_img').innerHTML = '';
    $("#labelImg").removeClass();   

    if(!extension.exec(producto))
    {
       //document.getElementById('info_img').innerHTML = 'El formato de la imagen es incorrecto';
       	mensaje = "Opps, el formato es incorrecto";
				labelImg.innerHTML = mensaje;
		    $("#labelImg").removeClass(); 
    		$("#labelImg").addClass(" fondo-red");
    		//document.getElementById('mostrar_img').innerHTML = '<img src="../img/error.png"/>';
    }
    else if (img_size >  size) 
    {
       //document.getElementById('info_img').innerHTML = 'La imagen supera el tamaño máximo';
       	mensaje = "Opps, el archivo pesa demasiado";
				labelImg.innerHTML = mensaje;
		    $("#labelImg").removeClass(); 
    		$("#labelImg").addClass(" fondo-red");
    		//document.getElementById('mostrar_img').innerHTML = '<img src="../img/error.png"/>';
    }
    else{
        //Image preview
        if (archivo.files && archivo.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                //document.getElementById('mostrar_img').innerHTML = '<img src="'+e.target.result+'"/>';
            };
            reader.readAsDataURL(archivo.files[0]);
            mensaje = "Genial! archivo seleccionado";
       			labelImg.innerHTML = mensaje;
				    $("#labelImg").removeClass();
            $("#labelImg").addClass(" fondo-green");
        }
    }
}

/*
		if(elementos.archivo.value == "")
		{
	    document.getElementById('info_img').innerHTML = 'Debe seleccionar la imagen';
	   	$("#labelImg").removeClass(" ColorGris");
	   	$("#labelImg").addClass(" ColorRed");

	   	return false;
	  }

		if(!exp_extension.exec(elementos.archivo.value) && elementos.archivo.files.length >= 1)
		{
       document.getElementById('info_img').innerHTML = 'El formato de la imagen es incorrecto';
		   	$("#labelImg").removeClass(" ColorGris");
    		$("#labelImg").addClass(" ColorRed");
    		document.getElementById('mostrar_img').innerHTML = '<img src="../img/error.png"/>';
    		return false;		
		}

		if(elementos.archivo.files.length >= 1)
		{
	    var img = document.getElementById('archivo');
	    var size = img.files[0].size;
			if (size >  1000000) 
	    {
	      document.getElementById('info_img').innerHTML = 'La imagen supera el tamaño máximo';
		   	$("#labelImg").removeClass(" ColorGris");
	    	$("#labelImg").addClass(" ColorRed");
	    	document.getElementById('mostrar_img').innerHTML = '<img src="../img/error.png"/>';

	    	return false;
	    }
		}		
*/