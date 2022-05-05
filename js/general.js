$(document).ready(function() {

	//efectos del loader
	setTimeout(function(){
		$("#contentLoader").addClass("ocultar");
	}, 1300);

/**********************************************/
/* boton arriba *******************************/
/**********************************************/
		
	$('.arriba').click(function(){
		$('body, html').animate({
			scrollTop: '0px'
		}, 300);
	});
 
	$(window).scroll(function(){
		if( $(this).scrollTop() > 0 ){
			$('.arriba').slideDown(300);
		} else {
			$('.arriba').slideUp(300);
		}
	});

/**********************************************/
/* deshabilitando la tecla enter **************/
/**********************************************/

    $("form").keypress(function(e) {
        if (e.which == 13) {
            return false;
        }
    });

})//fin de carga de documento
