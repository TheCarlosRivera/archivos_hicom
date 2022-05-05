/////////////////////////////////////////////////
// VALIDACION DEL REGISTRO DE NUEVO PROVEEDOR ///
/////////////////////////////////////////////////

const formulario = document.getElementById("registro_proveedor");
const inputs = document.querySelectorAll("#registro_proveedor .input");

const expresiones =
{
	exp_nombre: /^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]{5,20}$/,	
	exp_empresa_dir: /^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9(-.,*)\s]{5,50}$/,	
	exp_rubro: /^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9(-.,*)\s]{5,500}$/,
	exp_web: /^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9-._]{1,500}$/,
	exp_telefono: /^[0-9]{9,11}$/,
	exp_correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
}

const campos = 
{
	nombre: false,
	empresa: false,
	direccion: false,
	web: true,
	rubro: false,
	telf_1: true,
	telf_2: true,
	telf_3: true,
	correo: true
}


const validarFormulario = (e) =>
{
	switch(e.target.name)
	{
		//NOMBRE
		case "nombre":
			validarCampos(expresiones.exp_nombre, e.target, 'nombre');
		break;

		case "empresa":
			validarCampos(expresiones.exp_empresa_dir, e.target, 'empresa');
		break;

		case "direccion":
			validarCampos(expresiones.exp_empresa_dir, e.target, 'direccion');
		break;

		case "rubro":
			validarCampos(expresiones.exp_rubro, e.target, 'rubro');
		break;

		case "web":
			validarWeb(expresiones.exp_web, e.target, 'web');
		break;

		case "telf_1":
			validarCampos(expresiones.exp_telefono, e.target, 'telf_1');
		break

		case "telf_2":
			validarTelf(expresiones.exp_telefono, e.target, 'telf_2');
		break

		case "telf_3":
			validarTelf(expresiones.exp_telefono, e.target, 'telf_3');
		break

		case "correo":
			validarCampos(expresiones.exp_correo, e.target, 'correo');
		break

	}
}

const validarCampos = (expresion, input, grupo) =>
{
	if(expresion.test(input.value))
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('error');
		document.getElementById(`grupo__${grupo}`).classList.add('correcto');
		document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-times-circle');
		document.querySelector(`#grupo__${grupo} i`).classList.add('fa-check-circle');
		campos[grupo] = true;
	}
	else
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('correcto');
		document.getElementById(`grupo__${grupo}`).classList.add('error');
		document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-check-circle');
		document.querySelector(`#grupo__${grupo} i`).classList.add('fa-times-circle');	
		campos[grupo] = false;		
	}
}

const validarTelf = (expresion, input, grupo) =>
{
	if(formulario.telf_2.value !== "" || formulario.telf_3.value !== "")
	{
		if(expresion.test(input.value))
		{
			document.getElementById(`grupo__${grupo}`).classList.remove('error');
			document.getElementById(`grupo__${grupo}`).classList.add('correcto');
			document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-times-circle');
			document.querySelector(`#grupo__${grupo} i`).classList.add('fa-check-circle');
			campos[grupo] = true;
		}
		else
		{
			document.getElementById(`grupo__${grupo}`).classList.remove('correcto');
			document.getElementById(`grupo__${grupo}`).classList.add('error');
			document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-check-circle');
			document.querySelector(`#grupo__${grupo} i`).classList.add('fa-times-circle');	
			campos[grupo] = false;		
		}		
	}
	else
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('error');		
		campos[grupo] = true;
	}

}

const validarWeb = (expresion, input, grupo) =>
{
	if(formulario.web.value !== "")
	{
		if(expresion.test(input.value))
		{
			document.getElementById(`grupo__${grupo}`).classList.remove('error');
			document.getElementById(`grupo__${grupo}`).classList.add('correcto');
			document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-times-circle');
			document.querySelector(`#grupo__${grupo} i`).classList.add('fa-check-circle');
			campos[grupo] = true;
		}
		else
		{
			document.getElementById(`grupo__${grupo}`).classList.remove('correcto');
			document.getElementById(`grupo__${grupo}`).classList.add('error');
			document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-check-circle');
			document.querySelector(`#grupo__${grupo} i`).classList.add('fa-times-circle');	
			campos[grupo] = false;		
		}		
	}
	else
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('error');		
		campos[grupo] = true;
	}

}

inputs.forEach((input) => 
{
	input.addEventListener("keyup", validarFormulario);
	input.addEventListener("blur", validarFormulario);
});

formulario.addEventListener("submit", (e) =>
{
	e.preventDefault();

		if (campos.nombre && campos.empresa && campos.direccion && campos.rubro && campos.web && campos.telf_2 && campos.telf_3) 
		{

			if(formulario.telf_1.value !== "" || formulario.correo.value !== "")
			{

			formulario.enviar.disabled = true;
			formulario.enviar.classList.add("inactivo");

			var datos = new FormData($('#registro_proveedor')[0]);
			$.ajax({
				data: datos,
				type: "POST",
				url: "procesos/validaciones.php",
				contentType: false,
				processData: false,
				success: function (resultado)
				{
					$("#resul").html(resultado);
				}

			});
			formulario.reset();
			document.querySelectorAll('.box__input').forEach((icono) =>
			{
				icono.classList.remove('correcto');
			});
			formulario.enviar.disabled = false;
			formulario.enviar.classList.remove("inactivo");

			}
			else
			{
				document.getElementById('mensaje_error_proceso').innerHTML='Debe ingresar un teléfono principal o correo electrónico';
						$("#alert_error_proceso").addClass("mostrar");
						setTimeout(() =>
						{
							$("#alert_error_proceso").removeClass("mostrar");			
						}, 7500);			
			}
	
		}
		else
		{

			document.getElementById('alert_mensaje').innerHTML='Debe completar los campos.';
			$("#alert_error_form").addClass("mostrar");
			setTimeout(() =>
			{
				$("#alert_error_form").removeClass("mostrar");			
			}, 5000);		
		}

})




/////////////////////////////////////////////////
// VALIDACION DE ACTUALIZACION DE PROVEEDOR /////
/////////////////////////////////////////////////

const formulario_two = document.getElementById("form_act_proveedor");
const inputs_two = document.querySelectorAll("#form_act_proveedor .input");

const expresiones_two =
{
	exp_nombre: /^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]{5,20}$/,	
	exp_empresa_dir: /^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9(-.,*)\s]{5,50}$/,	
	exp_rubro: /^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9(-.,*)\s]{5,500}$/,
	exp_web: /^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9-._]{1,500}$/,
	exp_telefono: /^[0-9]{9,11}$/,
	exp_correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
}

const campos_two = 
{
	nombre2: false,
	empresa2: false,
	direccion2: false,
	rubro2: false,
	web_two: true,
	telf_1_two: false,
	telf_2_two: true,
	telf_3_two: true,
	correo2: false
}

const validarFormulario_two = (e) =>
{
	switch(e.target.name)
	{
		//NOMBRE
		case "nombre2":
			validarCampos_two(expresiones_two.exp_nombre, e.target, 'nombre_two');
		break;

		case "empresa2":
			validarCampos_two(expresiones_two.exp_empresa_dir, e.target, 'empresa_two');
		break;

		case "direccion2":
			validarCampos_two(expresiones_two.exp_empresa_dir, e.target, 'direccion_two');
		break;

		case "rubro2":
			validarCampos_two(expresiones_two.exp_rubro, e.target, 'rubro_two');
		break;

		case "web_two":
			validarCampos_two(expresiones_two.exp_web, e.target, 'web_two');
		break;

		case "telf_1_two":
			validarCampos_two(expresiones_two.exp_telefono, e.target, 'telf_1_two');
		break

		case "telf_2_two":
			validarTelf_two(expresiones_two.exp_telefono, e.target, 'telf_2_two');
		break

		case "telf_3_two":
			validarTelf_two(expresiones_two.exp_telefono, e.target, 'telf_3_two');
		break

		case "correo2":
			validarCampos_two(expresiones_two.exp_correo, e.target, 'correo_two');
		break

	}
}

const validarCampos_two = (expresion_two, input_two, grupo_two) =>
{
	if(expresion_two.test(input_two.value))
	{
		document.getElementById(`grupo__${grupo_two}`).classList.remove('error');
		document.getElementById(`grupo__${grupo_two}`).classList.add('correcto');
		document.querySelector(`#grupo__${grupo_two} i`).classList.remove('fa-times-circle');
		document.querySelector(`#grupo__${grupo_two} i`).classList.add('fa-check-circle');
		campos_two[grupo_two] = true;
	}
	else
	{
		document.getElementById(`grupo__${grupo_two}`).classList.remove('correcto');
		document.getElementById(`grupo__${grupo_two}`).classList.add('error');
		document.querySelector(`#grupo__${grupo_two} i`).classList.remove('fa-check-circle');
		document.querySelector(`#grupo__${grupo_two} i`).classList.add('fa-times-circle');	
		campos_two[grupo_two] = false;		
	}
}

const validarTelf_two = (expresion_two, input_two, grupo_two) =>
{
	if(formulario_two.telf_2_two.value !== "" || formulario_two.telf_3_two.value !== "")
	{
		if(expresion_two.test(input_two.value))
		{
			document.getElementById(`grupo__${grupo_two}`).classList.remove('error');
			document.getElementById(`grupo__${grupo_two}`).classList.add('correcto');
			document.querySelector(`#grupo__${grupo_two} i`).classList.remove('fa-times-circle');
			document.querySelector(`#grupo__${grupo_two} i`).classList.add('fa-check-circle');
			campos_two[grupo_two] = true;
		}
		else
		{
			document.getElementById(`grupo__${grupo_two}`).classList.remove('correcto');
			document.getElementById(`grupo__${grupo_two}`).classList.add('error');
			document.querySelector(`#grupo__${grupo_two} i`).classList.remove('fa-check-circle');
			document.querySelector(`#grupo__${grupo_two} i`).classList.add('fa-times-circle');	
			campos_two[grupo_two] = false;		
		}		
	}
	else
	{
		document.getElementById(`grupo__${grupo_two}`).classList.remove('error');		
		campos_two[grupo_two] = true;
	}

}

inputs_two.forEach((input_two) => 
{
	input_two.addEventListener("keyup", validarFormulario_two);
	input_two.addEventListener("blur", validarFormulario_two);
});

formulario_two.addEventListener("submit", (e) =>
{
	e.preventDefault();

	if (formulario_two.nombre2.value != "")
		{ campos_two.nombre2 = true}

	if (formulario_two.empresa2.value != "")
		{ campos_two.empresa2 = true}	

	if (formulario_two.direccion2.value != "")
		{ campos_two.direccion2 = true}

	if (formulario_two.rubro2.value != "")
		{ campos_two.rubro2 = true}

	if (formulario_two.web_two.value != "")
		{ campos_two.web_two = true}

	if (formulario_two.telf_1_two.value != "")
		{ campos_two.telf_1_two = true}

	if (formulario_two.correo2.value != "")
		{ campos_two.correo2 = true}

	if (campos_two.nombre2 && campos_two.empresa2 && campos_two.direccion2 && campos_two.rubro2 && campos_two.telf_2_two && campos_two.telf_3_two) 
	{

			if(formulario_two.telf_1_two.value !== "" || formulario_two.correo2.value !== "")
			{

		formulario_two.enviar.disabled = true;
		formulario_two.enviar.classList.add("inactivo");

		var datos = new FormData($('#form_act_proveedor')[0]);
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			contentType: false,
			processData: false,
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}

		});
		formulario_two.reset();
		document.querySelectorAll('.box__input').forEach((icono) =>
		{
			icono.classList.remove('correcto');
		});
		formulario_two.enviar.disabled = false;
		formulario_two.enviar.classList.remove("inactivo");

			}
			else
			{
				document.getElementById('mensaje_error_proceso').innerHTML='Debe ingresar un teléfono principal o correo electrónico';
						$("#alert_error_proceso").addClass("mostrar");
						setTimeout(() =>
						{
							$("#alert_error_proceso").removeClass("mostrar");			
						}, 7500);			
			}

	}
	else
	{
		
		document.getElementById('alert_mensaje').innerHTML='Debe completar los campos.';
		$("#alert_error_form").addClass("mostrar");
		setTimeout(() =>
		{
			$("#alert_error_form").removeClass("mostrar");			
		}, 5000);		
	}

});

/////////////////////////////////////////////////
// MOSTRAR ACTUALIZAR PROVEEDOR /////////////////
/////////////////////////////////////////////////

var cerrar_act = document.getElementById("cerrar_act");
cerrar_act.onclick = function()
{
	$("#act_proveedor").removeClass(" mostrar");
}

function actualizar_proveedor(e)
{
		var datos = { "id_edit": e };
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}

		});

	$("#act_proveedor").addClass(" mostrar");
}

/////////////////////////////////////////////////
// MOSTRAR ELIMINAR PROVEEDOR ///////////////////
/////////////////////////////////////////////////

var cerrar_elim = document.getElementById("cerrar_elim");
cerrar_elim.onclick = function()
{
	$("#elim_proveedor").removeClass(" mostrar");
}

function eliminar_proveedor(e)
{
		var datos = { "id_elim": e };
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}

		});

	$("#elim_proveedor").addClass(" mostrar");	
}

/////////////////////////////////////////////////
// ELIMINAR PROVEEDOR ///////////////////////////
/////////////////////////////////////////////////

var elim = document.getElementById("eliminar_proveedor");

elim.addEventListener("submit", (e) =>
{
		e.preventDefault();
		var id = $("#id_elim_proveedor").val();
		var datos = {"elim_proveedor": id};	
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}

		});	
})


/////////////////////////////////////////////////
// ORDENAR RESULTADO PROVEEDORES ////////////////
/////////////////////////////////////////////////

const order = document.querySelectorAll(".order");
const enviar_order = (e) =>
{
		var buscador = document.getElementById("buscador");
		document.querySelectorAll('.order').forEach((e) =>
		{
			e.classList.remove('activo');
		});
		e.target.classList.add("activo");

		if (buscador.value.length >= 1)
		{
			var datos = 
			{ 
				"order": e.target.value,
				"valor": $("#buscador").val() 
			};			
		}
		else
		{
			var datos = 
			{ 
				"order": e.target.value,
				"valor": "" 
			};				
		}

		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			success: function (resultado)
			{
				$("#resul_busqueda").html(resultado);
			}
		});		
}

order.forEach((e) => 
{
	e.addEventListener("click", enviar_order);
});


const reporte = document.getElementById("reporte_proveedores");
reporte.addEventListener("submit", (e) =>
{
		e.preventDefault();
		var order = $("#valor_order").val();
		var busq = $("#valor_busq").val();
		window.open ('sites/reportes.php?order='+order+'&busq='+busq, "_newtab" );  

})