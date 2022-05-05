  var mostrar = document.getElementById("mostrarSelect");
  var ocultar = document.getElementById("options");

  mostrar.onclick = function()
  {
    $("#mostrarSelect").addClass(" ocultar");
    $("#box__select").addClass(" abrir");
  }

  ocultar.onclick = function()
  {
    $("#mostrarSelect").removeClass(" ocultar");
    $("#box__select").removeClass(" abrir");
  }

  var mostrar_two = document.getElementById("mostrarSelect_two");
  var ocultar_two = document.getElementById("options_two");

  mostrar_two.onclick = function()
  {
    $("#mostrarSelect_two").addClass(" ocultar");
    $("#box__select_two").addClass(" abrir");
  }

  ocultar_two.onclick = function()
  {
    $("#mostrarSelect_two").removeClass(" ocultar");
    $("#box__select_two").removeClass(" abrir");
  }

		document.querySelectorAll('.select_proveedor').forEach((input) =>
		{
			input.addEventListener("click", (e) =>{
				if(e.target.value == "1")
				{
 					$("#grupo__otro_proveedor").removeClass("ocultar");
				}
				else
				{
 					$("#grupo__otro_proveedor").addClass("ocultar");
				}
			})
		});


const expresiones =
{
  exp_numero: /^[0-9]{1,}$/,
  exp_nombres: /^[a-zA-Z\s]{1,150}$/,
  exp_texto: /^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü0-9(-.,*)\s]{1,500}$/
}

//////////////////////////////
// validando egresos
//////////////////////////////

const formulario_movimiento = document.getElementById("registro_egreso");
const input_movimiento = document.querySelectorAll("#registro_egreso .input");

const campos_movimiento =
{
  otro_proveedor: true,
  efectivo: false,
  monedas: false,
  comentario: true
}

const validarFormMovimiento = (e) =>
{
  switch(e.target.name)
  {
    case "otro_proveedor":
      validarCamposMovimiento(expresiones.exp_nombres, e.target, "otro_proveedor");
    break;

    case "efectivo":
      validarCamposMovimiento(expresiones.exp_numero, e.target, "efectivo");
    break;

    case "monedas":
      validarCamposMovimiento(expresiones.exp_numero, e.target, "monedas");
    break;

    case "comentario":
      validarComentario(expresiones.exp_texto, e.target, "comentario");
    break;

  }
}

const validarCamposMovimiento = (expresion, input, grupo) =>
{
  if (expresion.test(input.value))
  {
    document.getElementById(`grupo__${grupo}`).classList.remove('error');
    document.getElementById(`grupo__${grupo}`).classList.add('correcto');
    document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-times-circle');
    document.querySelector(`#grupo__${grupo} i`).classList.add('fa-check-circle');
    campos_movimiento[grupo] = true;
  }
  else
  {
    document.getElementById(`grupo__${grupo}`).classList.remove('correcto');
    document.getElementById(`grupo__${grupo}`).classList.add('error');
    document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-check-circle');
    document.querySelector(`#grupo__${grupo} i`).classList.add('fa-times-circle');  
    campos_movimiento[grupo] = false;      
  }  
}

const validarSelects = () =>
{
  if (formulario_movimiento.proveedor.value == "1" && formulario_movimiento.otro_proveedor.value == "")
  {
    campos_movimiento.otro_proveedor = false;
  }
  else{
    campos_movimiento.otro_proveedor = true;
  }

  if (formulario_movimiento.tipo_accion.value == "")
  {
    document.getElementById('alert_mensaje').innerHTML='Seleccione tipo movimiento.';
    $("#alert_error_form").addClass("mostrar");
    setTimeout(() =>
    {
      $("#alert_error_form").removeClass("mostrar");      
    }, 5000);
    return false;
  }
  else if (formulario_movimiento.proveedor.value == "")
  {
    document.getElementById('alert_mensaje').innerHTML='Seleccione proveedor.';
    $("#alert_error_form").addClass("mostrar");
    setTimeout(() =>
    {
      $("#alert_error_form").removeClass("mostrar");      
    }, 5000);
    return false;
  }
  else
  {
    return true;
  }  

}

const validarComentario = (expresion, input, grupo) =>
{
  if (expresion.test(input.value) && input.value.length >= 1 || input.value.length <= 0)
  {
    document.getElementById(`grupo__${grupo}`).classList.remove('error');
    document.getElementById(`grupo__${grupo}`).classList.add('correcto');
    document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-times-circle');
    document.querySelector(`#grupo__${grupo} i`).classList.add('fa-check-circle');
    campos_movimiento[grupo] = true;
  }
  else
  {
    document.getElementById(`grupo__${grupo}`).classList.remove('correcto');
    document.getElementById(`grupo__${grupo}`).classList.add('error');
    document.querySelector(`#grupo__${grupo} i`).classList.remove('fa-check-circle');
    document.querySelector(`#grupo__${grupo} i`).classList.add('fa-times-circle');  
    campos_movimiento[grupo] = false;      
  }  

}


const validarSumaEfecMonedas = (e) =>
{
  switch(e.target.name)
  {
    case "efectivo":
      sumaEfecMonedas(e.target.value, formulario_movimiento.monedas.value);
    break;

    case "monedas":
      sumaEfecMonedas(formulario_movimiento.efectivo.value, e.target.value);      
    break;

  }
}

const sumaEfecMonedas = (efectivo, monedas) =>
{
  var total = parseInt(efectivo)+parseInt(monedas);
  if(parseInt(total))
  {
    $("#title_total_egreso").html("Total: $ "+Number(total).toLocaleString("de-DE"));
    $("#total_egreso").val(total); 
  }
}

input_movimiento.forEach((input) =>
{
  input.addEventListener("keyup", validarFormMovimiento);
  input.addEventListener("blur", validarFormMovimiento); 

  input.addEventListener("keyup", validarSumaEfecMonedas);
  input.addEventListener("blur", validarSumaEfecMonedas);  
});

formulario_movimiento.addEventListener("submit", (e) => 
{
  e.preventDefault(); 

  validarSelects();

  if(validarSelects())
  {
    if(campos_movimiento.otro_proveedor && campos_movimiento.efectivo && campos_movimiento.monedas)
    {
      formulario_movimiento.enviar.disabled = true;
      formulario_movimiento.enviar.classList.add('inactivo');   

      var datos = new FormData($('#registro_egreso')[0]);

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

      formulario_movimiento.reset();
      document.querySelectorAll('.box__input').forEach((icono) =>
      {
        icono.classList.remove('correcto');
      });  

      formulario_movimiento.enviar.disabled = false;
      formulario_movimiento.enviar.classList.remove('inactivo');

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
  }







})