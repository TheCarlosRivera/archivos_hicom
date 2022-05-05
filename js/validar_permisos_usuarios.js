  /* mostrar permisos usuarios*/

  document.getElementById("cerrar_permisos").addEventListener("click", () =>
  {
  	$("#permiso_usuario").removeClass("mostrar");  	
  })

  function permisos_usuario(e)
  {

		var datos = { "id_usuario_permiso": e };
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}

		});

		setTimeout(function(){
			validarCheck();
  		$("#permiso_usuario").addClass(" mostrar");
  	},500)
  }


 	const formCheck = document.getElementById("form_permisos");
	const inputCheck = document.querySelectorAll("#form_permisos input"); 

  const validarCheck = () =>
  {

		for (var i = 0; i < inputCheck.length; i++) 
		{
			if(inputCheck[i].type == "checkbox")
			{
				inputCheck[i].parentElement.children[0].checked = true;
				if (inputCheck[i].value == "si")
				{	
				inputCheck[i].parentElement.children[2].className = inputCheck[i].parentElement.children[2].className.replace("fa-toggle-off", "fa-toggle-on");
				}
				else
				{	
				inputCheck[i].parentElement.children[2].className = inputCheck[i].parentElement.children[2].className.replace("fa-toggle-on", "fa-toggle-off");
				}
			}
		}			
  }

const validarInfo = (e) =>
{
	if(e.target.value == "si")
	{
		$("#"+e.target.name).val("no");
	}
	else
	{
		$("#"+e.target.name).val("si");		
	}
	validarCheck();
}


inputCheck.forEach((input) => {
	input.addEventListener("click", validarInfo);	
});


formCheck.addEventListener("submit", (e) => {
	
	e.preventDefault();

		formCheck.enviar.disabled = true;
		formCheck.enviar.classList.add('inactivo');

		var datos = new FormData($("#form_permisos")[0]);

		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			contentType: false,
			processData: false,
			beforesend: function()
			{

			},
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}
		});//fin ajax	
});

function estatus_usuario(e)
{
		var datos = { "id_estatus_user": e };
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}

		});
}

function historial_usuario(e)
{
		var datos = { "id_usuario_history": e };
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}

		});

		setTimeout(function(){
  		$("#historial_user").addClass(" mostrar");
  	},500)	
}