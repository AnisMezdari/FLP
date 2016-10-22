$( ".boutonUploadPortfolio" ).click(function() {
	console.log("bonjourrr");
	var lien = $("#lienAjoutPhoto").val();
	var idCategorieportfolio = $("#idCategoriePorfolio").val();
	var data = new FormData();
	var fileIdCate = new File([], idCategorieportfolio);
	jQuery.each(jQuery('#filesToUploadPortfolio')[0].files, function(i, file) {
	    data.append('file-'+i, file);
	});
	data.append('idCategorie' ,fileIdCate);
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
	        for(var i = 0 ; i < data.urlImage.length ; i++){
	        	var urlSuppression = $("#lienSuppressionPhotoporfolio").val();
	        	$(".conteneurImagesPortfolio").
		        append('<div class = "conteneurImagePortfolio" id= "img-' + data.id[i] + '"> '+
		          '<img class = "imagePortfolio" src = "' + data.urlImage[i] + '" alt = "Photo" />'+
		          '<span  id= "img-' + data.id[i] + '" class="glyphicon glyphicon-remove fa-lg croixSuppressionPortfolio"> </span>'+
		          '<input type="hidden" value="' + urlSuppression + '" id =  "lienSuppressionPhoto" />'+
		        '</div>');
	        }
	        
	    },
	    error: function (data) {
        	console.log(data);
        	// status 500
    	}
	})

});


$( ".croixSuppression" ).click(function() {
	// console.log($(this).attr("id"));
	var element = $(this).attr("id");

	console.log("c passe");

	var lien = $("#lienSuppressionPhoto").val();
	var idPhoto = element;
	console.log(lien);
	console.log(idPhoto);
	jQuery.ajax({
	    url: lien,
	    data: JSON.stringify({"idPhoto": idPhoto}),
	    contentType: "application/json",
	    type: 'POST',
	    success: function(data){
	    	$("#img-" + idPhoto).remove();
	    },
	    error: function (data) {
        	console.log(data.responseText);
        	// status 500
    	}
	})

});