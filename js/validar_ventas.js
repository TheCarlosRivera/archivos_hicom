
const expresiones =
{
	exp_numero: /^[0-9]{1,}$/
}

  var fecha = new Date();
  var dayFull = fecha.getDate();
  var monthFull = fecha.getMonth();    
  var yearFull = fecha.getFullYear();

	if(dayFull<10) {
	    dayFull='0'+dayFull;
	} 

  var mesNum = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12',];
  var mes = mesNum[monthFull];
	var hoy = yearFull+'/'+mes+'/'+dayFull;

/////////////////////////////////////////////////
// REGISTRO DE NUEVA VENTA //////////////////////
/////////////////////////////////////////////////

const form_registro_venta = document.getElementById("registro_venta");
const input_regis = document.querySelectorAll("#registro_venta .input");

const campos_regis = 
{
	efectivo: false,
	moneda10: false,
	moneda50: false,
	moneda100: false,
	moneda500: false,
	pagos: false,
	tarjetas: false,
	ventas: false,
	fecha: false
}

function validarDiaCerrado()
{
	var diaCerrado = document.getElementById("diaCerrado");
	setTimeout(function(){
		if(diaCerrado.checked)
		 {
		 	diaCerrado.value = "0";
		 	document.querySelector("#icon_check").classList.remove("fa-square");
		 	document.querySelector("#icon_check").classList.add("fa-check-square");
		 	document.querySelector("#box__check").classList.add("paleta-azul");

			campos_regis.efectivo = true; 
			campos_regis.moneda10 = true; 
			campos_regis.moneda50 = true; 
			campos_regis.moneda100 = true; 
			campos_regis.moneda500 = true; 
			campos_regis.pagos = true; 
			campos_regis.tarjetas = true; 
			campos_regis.ventas = true; 

		document.querySelectorAll('.input').forEach((input) =>
		{
			input.disabled = true;
			input.classList.add('inactivo');
		});

		 }
		 else
		 {
		 	diaCerrado.value = "1";
		 	document.querySelector("#icon_check").classList.remove("fa-check-square");
		 	document.querySelector("#icon_check").classList.add("fa-square");
		 	document.querySelector("#box__check").classList.remove("paleta-azul");

			campos_regis.efectivo = false; 
			campos_regis.moneda10 = false; 
			campos_regis.moneda50 = false; 
			campos_regis.moneda100 = false; 
			campos_regis.moneda500 = false; 
			campos_regis.pagos = false; 
			campos_regis.tarjetas = false; 
			campos_regis.ventas = false; 

		document.querySelectorAll('.input').forEach((input) =>
		{
			input.disabled = false;
			input.classList.remove('inactivo');
		});

		 }
	},100);
}

const validarFormRegis = (e) =>
{
	switch(e.target.name)
	{
		case "efectivo":
			validarCamposRegis(expresiones.exp_numero, e.target, 'efectivo');

			efectivo_contado(e.target.value);
		break;

		case "moneda10":
			validarCamposRegis(expresiones.exp_numero, e.target, 'moneda10');

		break;

		case "moneda50":
			validarCamposRegis(expresiones.exp_numero, e.target, 'moneda50');

		break;

		case "moneda100":
			validarCamposRegis(expresiones.exp_numero, e.target, 'moneda100');

		break;

		case "moneda500":
			validarCamposRegis(expresiones.exp_numero, e.target, 'moneda500');

		break;

		case "pagos":
			validarCamposRegis(expresiones.exp_numero, e.target, 'pagos');

			pagos_rut(e.target.value);
		break;

		case "tarjetas":
			validarCamposRegis(expresiones.exp_numero, e.target, 'tarjetas');
			
			restarComision(form_registro_venta.tarjetas.value);
		break;

		case "ventas":
			validarCamposRegis(expresiones.exp_numero, e.target, 'ventas');

			restanteVenta(form_registro_venta.ventas.value);
		break;

	}
}

const validarCamposRegis = (expresion, input, grupo) =>
{
	if (expresion.test(input.value))
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('error');
		document.getElementById(`grupo__${grupo}`).classList.add('correcto');
		document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-times-circle');
		document.querySelector(`#grupo__${grupo} i`).classList.add('fa-check-circle');
		campos_regis[grupo] = true;
	}
	else
	{
		document.getElementById(`grupo__${grupo}`).classList.remove('correcto');
		document.getElementById(`grupo__${grupo}`).classList.add('error');
		document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-check-circle');
		document.querySelector(`#grupo__${grupo} i`).classList.add('fa-times-circle');	
		campos_regis[grupo] = false;			
	}
}

const contentValues = () =>
{
	restarMonedas(
	form_registro_venta.moneda10.value,
	form_registro_venta.moneda50.value,
	form_registro_venta.moneda100.value,
	form_registro_venta.moneda500.value,
	form_registro_venta.valor_moneda.value
	);
}

//conversion de monedas
const conversionMonedas = (e) =>
{
	switch(e.target.name)
	{
		case "moneda10":
			var valor = parseInt(e.target.value)*10;
			if(parseInt(valor)){$(e.target).val(valor);}			
			contentValues();
		break;

		case "moneda50":
			var valor = parseInt(e.target.value)*50;
			if(parseInt(valor)){$(e.target).val(valor);}
			contentValues();
		break;

		case "moneda100":
			var valor = parseInt(e.target.value)*100;
			if(parseInt(valor)){$(e.target).val(valor);}
			contentValues();
		break;

		case "moneda500":
			var valor = parseInt(e.target.value)*500;
			if(parseInt(valor)){$(e.target).val(valor);}
			contentValues();
		break;

	}
}

const efectivo_contado = (efectivo) =>
{

	if(parseInt(efectivo) >= 0)
	{
  	efect_final = Number(efectivo).toLocaleString("de-DE");
		$("#efectivof").html("Efectivo: $ "+efect_final);
		$("#valor_efect").val(efectivo);
	}
}

//restando las monedas del sistema de venta
const restarMonedas = (m10, m50, m100, m500, rest_monedas) =>
{		
		var monedas = parseInt(m10)+parseInt(m50)+parseInt(m100)+parseInt(m500);

		if(monedas >= 0)
		{
			var restante_monedas = parseInt(monedas)-parseInt(rest_monedas);
			var monedas_final = Number(restante_monedas).toLocaleString("de-DE");
			if(restante_monedas >= 1)
			{
				$("#monedas").html("Monedas: $ "+monedas_final);
				$("#valor_monedas").val(restante_monedas);
			}
			else
			{
				$("#monedas").html("Monedas: $ "+monedas_final);
				$("#valor_monedas").val("0");				
			}

			total_contado();
		}
/*
		var venta = parseInt(efectivo) + parseInt(monedas);

		var total_venta = venta - parseInt(rest_monedas);  
		if(parseInt(venta) >= 0)
		{

  	efect_final = Number(total_venta).toLocaleString("de-DE");
  	efect = Number(venta).toLocaleString("de-DE");

		$("#efectivof").html("Efectivo: $ "+efect+" ($ "+efect_final+")");
		$("#valor_efect").val(total_venta);
	}
*/
}

const pagos_rut = (pagos) =>
{
	valor_pagos = Number(pagos).toLocaleString("de-DE");
	$("#pago_rut").html("Pago rut / transferencia: $ "+valor_pagos);
	$("#valor_pago_rut").val(pagos);		

	total_contado();
}

const restarComision = (tarjeta) =>
{ 
	var porcentaje = form_registro_venta.valor_comision.value;
	
	var comision = parseInt(tarjeta) * porcentaje / 100;
	var valor = parseInt(tarjeta) - parseInt(comision);

  tarj_final = Number(valor).toLocaleString("de-DE");
  tarj = Number(tarjeta).toLocaleString("de-DE");

	if(parseInt(tarjeta) >= 0)
	{
		$("#tarjetaf").html("Débito / crédito: $ "+tarj+" ($ "+tarj_final+")");
		$("#valor_tarjetaf").val(valor);
	}
	var valor_monedas = form_registro_venta.valor_monedas.value;
	total_contado();
/*
	var valor_efect = form_registro_venta.valor_efect.value;

	var contado = parseInt(tarjeta) + parseInt(valor_efect);

  contado_final = Number(contado).toLocaleString("de-DE");
	
	if(parseInt(contado))
	{
		$("#contado").html("Contado: $ "+contado_final);
		$("#valor_contado").val(contado);	
	}
*/
}

const total_contado = () =>
{
	var contado = parseInt(form_registro_venta.valor_efect.value)  + parseInt(form_registro_venta.pagos.value) + parseInt(form_registro_venta.tarjetas.value) + parseInt(form_registro_venta.monedas.value);

  contado_final = Number(contado).toLocaleString("de-DE");

	if(parseInt(contado))
	{
		$("#contado").html("Contado: $ "+contado_final);
		$("#valor_contado").val(contado);	
	}	
}

const restanteVenta = (venta) =>
{
	var valor_contado = form_registro_venta.valor_contado.value;
	var suma = parseInt(venta) - parseInt(valor_contado);

	resul = Number(suma).toLocaleString("de-DE");

	if(parseInt(venta) >= 0)
	{

		if(parseInt(venta) < 99999)
		{
			$("#color_ident").val("paleta-roja");			
		}
		
		if(parseInt(venta) >= 100000)
		{
			$("#color_ident").val("paleta-gris");			
		}

		if(parseInt(venta) >= 199999)
		{
			$("#color_ident").val("paleta-verde");			
		}

		if(parseInt(venta) >= 299999)
		{
			$("#color_ident").val("paleta-azul");			
		}
		
		if(parseInt(venta) >= 399999)
		{
			$("#color_ident").val("paleta-morada");			
		}

		if(suma < 0 || suma > 0)
		{
			$("#restante").removeClass(" color-green");
			$("#restante").addClass(" color-red");	
		}
		else
		{
			$("#restante").removeClass(" color-red");
			$("#restante").addClass(" color-green");	
		}

		$("#restante").html("Diferencia: $ "+resul);
		$("#valor_restante").val(suma);	
	}

}

const validarFecha = () =>
{

		var fecha = form_registro_venta.fecha.value;
		var datos = { "fecha_venta": fecha };
		$.ajax({
			data: datos,
			type: "POST",
			url: "procesos/validaciones.php",
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}

		});	

	if(form_registro_venta.error_venta.value == "error")
	{
		document.querySelector("#campo_fecha_venta p").innerHTML='Existe una venta registrada en esta fecha';
		document.querySelector("#campo_fecha_venta").classList.remove('correcto');
		document.querySelector("#campo_fecha_venta").classList.add('error');
	}
	else
	{
		document.querySelector("#campo_fecha_venta").classList.add('correcto');
		document.querySelector("#campo_fecha_venta").classList.remove('error');	
	}

	if(Date.parse(form_registro_venta.fecha.value) > Date.parse(hoy))
	{			
		campos_regis.fecha = false;	
		document.querySelector("#campo_fecha_venta p").innerHTML='La fecha seleccionada es mayor a la actual';
		document.querySelector("#campo_fecha_venta").classList.remove('correcto');
		document.querySelector("#campo_fecha_venta").classList.add('error');
	}
	else
	{
		campos_regis.fecha = true;			
		document.querySelector("#campo_fecha_venta").classList.remove('error');
		document.querySelector("#campo_fecha_venta").classList.add('correcto');
	}

}

form_registro_venta.fecha.addEventListener("blur", validarFecha);

input_regis.forEach((input) =>
{
	input.addEventListener("keyup", validarFormRegis);
	input.addEventListener("blur", validarFormRegis);	
	input.addEventListener("blur", conversionMonedas);
});

form_registro_venta.addEventListener("submit", (e) =>
{
	e.preventDefault();

	if(form_registro_venta.fecha.value !== "") 
	{
		validarFecha();			
	}
	
	if(campos_regis.efectivo && campos_regis.moneda10 && campos_regis.moneda50 && campos_regis.moneda100 && campos_regis.moneda500 && campos_regis.pagos && campos_regis.tarjetas && campos_regis.ventas && campos_regis.fecha && form_registro_venta.error_venta.value == "")
	{
		
		form_registro_venta.enviar.disabled = true;
		form_registro_venta.enviar.classList.add('inactivo');

		var datos = new FormData($('#registro_venta')[0]);

		$.ajax({
			data: datos,
			type: 'POST',
			url: 'procesos/validaciones.php',
			contentType: false,
			processData: false,
			success: function (resultado)
			{
				$("#resul").html(resultado);
			}
		});

		form_registro_venta.reset();
		document.querySelectorAll('.box__input').forEach((icono) =>
		{
			icono.classList.remove('correcto');
		});

		$("#monedas").html("Monedas:");
		$("#efectivof").html("Efectivo:");
		$("#tarjetaf").html("Débito / crédito:");
		$("#contado").html("Contado:");
		$("#restante").html("Diferencia:");

		form_registro_venta.enviar.disabled = false;
		form_registro_venta.enviar.classList.remove("inactivo");		

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


const reporte = document.getElementById("reporte_ventas");
reporte.addEventListener("submit", (e) =>
{
		e.preventDefault();
		var tipo = $("#valor_tipo").val();
		var fecha = $("#valor_fecha").val();
		window.open ('sites/reportes.php?tipo='+tipo+'&fecha='+fecha, "_newtab" );  

})