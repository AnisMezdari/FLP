$( ".boutonUploadEvenement" ).click(function() {
	var lien = $("#lienAjoutPhotoEvenement").val();
	var idEvenement = $("#idEvenemnt").val();
	var data = new FormData();
	var fileId = new File([], idEvenement);
	jQuery.each(jQuery('#filesToUploadEvenement')[0].files, function(i, file) {
	    data.append('file-'+i, file);
	});
	data.append('idEvenement' ,fileId);
	jQuery.ajax({
	    url: lien,
	    data:  data,
	    cache: false,
	    name : "test",
	    contentType: false,
	    processData: false,
	    type: 'POST',
	    success: function(data){
	        for(var i = 0 ; i < data.urlImage.length ; i++)
	        {
	        	var urlSuppression = $("#lienSuppressionPhotonu2Evenement").val();
	        	$(".conteneurImagesEvenement").
		        append('<div class = "conteneurImageEvenement imgEvenement-'+ data.id[i+1] +'" >'+
      			'<img src = "' + data.urlImage[i] + '" alt = "photo événement" class  = "imageEvenement" />'+
    		  	'<span  id= "' + data.id[i+1] + '" class="glyphicon glyphicon-remove fa-lg croixSuppressionEvenement" onclick = "suppressionJsPhotoEvenement(this)" > </span>'+
      			'<input type="hidden" value="' + urlSuppression + '" id =  "lienSuppressionPhotonu2Evenement" />'+
      			'</div>');
	        }
	    },
	    error: function (data) {
        	console.log(data);
        	// status 500
    	}
	})

});



$( ".croixSuppressionEvenement" ).click(function() {
	// console.log($(this).attr("id"));
	var element = $(this).attr("id");
	var lien = $("#lienSuppressionPhotonu2Evenement").val();
	var idPhoto = element;
	jQuery.ajax({
	    url: lien,
	    data: JSON.stringify({"idPhoto": idPhoto}),
	    contentType: "application/json",
	    type: 'POST',
	    success: function(data){
	    	$(".imgEvenement-" + idPhoto).remove();
	    },
	    error: function (data) {
        	console.log(data.responseText);
        	// status 500
    	}
	})

}); 

function suppressionJsPhotoEvenement(object){
	var element = object.id;
	var lien = $("#lienSuppressionPhotonu2Evenement").val();
	var idPhoto = element;
	jQuery.ajax({
	    url: lien,
	    data: JSON.stringify({"idPhoto": idPhoto}),
	    contentType: "application/json",
	    type: 'POST',
	    success: function(data){
	    	console.log(data);
	    	$(".imgEvenement-" + idPhoto).remove();
	    },
	    error: function (data) {
        	console.log(data.responseText);
        	// status 500
    	}
	})
}

$( ".croixSuppressionEvent" ).click(function() {
	// console.log($(this).attr("id"));
	var element = $(this).attr("id");
	var lien = $("#lienSuppressionEvenement").val();
	var idEvenement = element;
	jQuery.ajax({
	    url: lien,
	    data: JSON.stringify({"idEvenement": idEvenement}),
	    contentType: "application/json",
	    type: 'POST',
	    success: function(data){
	    	console.log(data);
	    	$("#evenement-" + idEvenement).remove();
	    },
	    error: function (data) {
        	console.log(data.responseText);
        	// status 500
    	}
	})

}); 

