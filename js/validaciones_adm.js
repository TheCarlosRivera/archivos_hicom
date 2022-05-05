	//cerrar session
	var cerrar_session = document.getElementById("cerrar_session");
	cerrar_session.onclick = function()
	{
		var valor = "cerrar_session";
		var datos =	{ "cerrar_session": valor, };

		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}
		});//fin ajax		
	}


function crear()
{
	$("#nuevo").addClass(" mostrar");
	setTimeout(function(){
		$("#nuevo").addClass(" gris");
	}, 500)

	document.getElementsByTagName("html")[0].style.overflow = "hidden";
}

function cerrar_nuevo()
{
	$("#nuevo").removeClass(" mostrar");
	$("#nuevo").removeClass(" gris");
	document.getElementsByTagName("html")[0].style.overflow = "auto";
}

$(document).keyup(function(e) {  
 if(e.which== 27) { 
  cerrar_nuevo(); 
} 

});