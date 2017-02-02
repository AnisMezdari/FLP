$( ".boutonUploadOneUser" ).click(function() {
	var lien = $("#lienAjoutPhotoOneUser").val();
	var idOneUser = $("#idOneUser").val();
	var data = new FormData();
	var fileId = new File([], idOneUser);
	jQuery.each(jQuery('#filesToUploadOneUser')[0].files, function(i, file) {
	    data.append('file-'+i, file);
	});
	data.append('idOneUser' ,fileId);
	jQuery.ajax({
	    url: lien,
	    data:  data,
	    cache: false,
	    name : "test",
	    contentType: false,
	    processData: false,
	    type: 'POST',
	    success: function(data){
				console.log(data);
	        for(var i = 0 ; i < data.urlImage.length ; i++)
	        {
	        	var urlSuppression = $("#lienSuppressionPhotonu2OneUser").val();
	        	$(".conteneurImagesOneUser").
		        append('<div class = "conteneurImageOneUser imgOneUser-'+ data.id[i+1] +'" >'+
      			'<img src = "' + data.urlImage[i] + '" alt = "photo événement" class  = "imageOneUser" />'+
    		  	'<span  id= "' + data.id[i+1] + '" class="glyphicon glyphicon-remove fa-lg croixSuppressionOneUser" onclick = "suppressionJsPhotoOneUser(this)" > </span>'+
      			'<input type="hidden" value="' + urlSuppression + '" id =  "lienSuppressionPhotonu2OneUser" />'+
      			'</div>');
	        }
	    },
	    error: function (data) {
        	console.log(data);
        	// status 500
    	}
	})

});



$( ".croixSuppressionOneUser" ).click(function() {
	// console.log($(this).attr("id"));
	var element = $(this).attr("id");
	var lien = $("#lienSuppressionPhotonu2OneUser").val();
	var idPhoto = element;
	jQuery.ajax({
	    url: lien,
	    data: JSON.stringify({"idImageUser": idPhoto}),
	    contentType: "application/json",
	    type: 'POST',
	    success: function(data){
	    	$(".imgOneUser-" + idPhoto).remove();
	    },
	    error: function (data) {
        	console.log(data.responseText);
        	// status 500
    	}
	})

});

function suppressionJsPhotoOneUser(object){
	var element = object.id;
	var lien = $("#lienSuppressionPhotonu2OneUser").val();
	var idPhoto = element;
	jQuery.ajax({
	    url: lien,
	    data: JSON.stringify({"idImageUser": idPhoto}),
	    contentType: "application/json",
	    type: 'POST',
	    success: function(data){
	    	console.log(data);
	    	$(".imgOneUser-" + idPhoto).remove();
	    },
	    error: function (data) {
        	console.log(data.responseText);
        	// status 500
    	}
	})
}

$( ".conteneurBoutonSupprimer" ).click(function() {
	console.log($(this).attr("id"));
	var element = $(this).attr("id");
	var lien = $("#lienSuppressionUser").val();
	var idOneUser = element;
	jQuery.ajax({
	    url: lien,
	    data: JSON.stringify({"idOneUser": idOneUser}),
	    contentType: "application/json",
	    type: 'POST',
	    success: function(data){
	    	console.log(data);
	    	$("#user-" + idOneUser).remove();
	    },
	    error: function (data) {
        	console.log(data.responseText);
        	// status 500
    	}
	})

});
