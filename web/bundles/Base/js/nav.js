
// Permet le hover de la nav coté admin ( le carré noir )
$(function(){

	$( ".navFLP" ).hover(function() {
		$( ".active" ).removeClass("active");
	});

	$( ".navFLP" ).mouseover(function() {
		$( ".active" ).removeClass("active");
		$(this).addClass("active");
	});

});