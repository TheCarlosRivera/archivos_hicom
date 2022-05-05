/**********************************************************/
/* buscador  **********************************************/
/**********************************************************/


$("#buscador").on('keyup', function() { 

    var buscador = document.getElementById("buscador");
    if(buscador.value != "")
    {
        var datos =
        {
            "buscar": $("#buscador").val(),
            "id": "id_proveedor",
        }
        
        $.ajax({
        url: "procesos/validaciones.php",
        type: 'POST',
        data: datos,
        success: function (resultado){
          $("#resul_busqueda").html(resultado);
        }     
        })
    }
    else
    {
        var campo =
        {
            "id": "id_proveedor",
            "buscar": "null"
        }

        $.ajax({
        url: "procesos/validaciones.php",
        type: 'POST',
        data: campo,
        success: function (resultado){
          $("#resul_busqueda").html(resultado);
        }     
        }) 
    }

})