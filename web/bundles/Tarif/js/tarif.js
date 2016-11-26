$( ".croixSuppressionTarif" ).click(function() {
	console.log($(this).attr("id"));
	var element = $(this).attr("id");

	var lien = $("#lienSupressionTarif").val();
	var idTarif = element;
	console.log(lien);
	console.log(idTarif);
	jQuery.ajax({
	    url: lien,
	    data: JSON.stringify({"idTarif": idTarif}),
	    contentType: "application/json",
	    type: 'POST',
	    success: function(data){
	    	$("#img-" + idTarif).remove();
	    },
	    error: function (data) {
        	console.log(data.responseText);
        	// status 500
    	}
	})

});