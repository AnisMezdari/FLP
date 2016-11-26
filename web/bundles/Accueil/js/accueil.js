$( "#boutonUpload" ).click(function() {

	var lien = $("#lienAjoutPhoto").val();
	var data = new FormData();
	jQuery.each(jQuery('#filesToUpload')[0].files, function(i, file) {
	    data.append('file-'+i, file);
	});

	jQuery.ajax({
	    url: lien,
	    data: data,
	    cache: false,
	    name : "test",
	    contentType: false,
	    processData: false,
	    type: 'POST',
	    success: function(data){
	        console.log(data);
	        for(var i = 0 ; i < data.urlImage.length ; i++){
	        	var urlSuppression = $("#lienSuppressionPhotonu2").val();
	        	$(".conteneurImage").
		        append('<div class = "conteneurUneImage" id= "img-' + data.id[i+1] + '"> '+
		          '<img id = "' + data.id[i+1] + '" class = "imageAccueil" src = "' + data.urlImage[i] + '" alt = "img" />'+
		          '<span  id= "' + data.id[i+1] + '" class="glyphicon glyphicon-remove fa-lg croixSuppression" onclick = "croixSuppression(this)"> </span>'+
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
	console.log($(this).attr("id"));
	var element = $(this).attr("id");

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

function croixSuppression(object){

	console.log(object.id);
	var element = object.id;

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

}
// DRAG AND DROP


// function allowDrop(ev) {
//     ev.preventDefault();
// }

// function drag(ev) {
//     ev.dataTransfer.setData("text", ev.target.id);
// }

// function drop(ev) {
//     ev.preventDefault();
//     var data = ev.dataTransfer.getData("text");
//     console.log(data);
//     var images = $("#fileList").children();

//     var i;
// 	var idPrecedentZ = data;
// 	var res = idPrecedentZ.split("-");
// 	var idPrecedent = res[1];
// 	var idSuivant = ev.target.id;
// 	var estSurLaBonneDiv = false;
//     for (i=0; i < images.length ; i=i+2){
//     	if(images[i].id == idSuivant){
//     		// console.log(images[i].id + " " + idSuivant);
//     		estSurLaBonneDiv = true;
//     		var imageSupprimer = images[i];
//     		console.log(data.id);
//     		$("#imgg-"+images[i].id).replaceWith(document.getElementById(data.id));
//     	}
//     	if(estSurLaBonneDiv){
//     		if(images[i].id == idPrecedent){
//     			estSurLaBonneDiv = false;
//     		}else{
// 				var pivot = images[i+2];
// 				console.log(imageSupprimer);
// 	    		$("#imgg-"+images[i+2].id).replaceWith(imageSupprimer);
// 	    		imageSupprimer = pivot;
//     		}
    		
//     	}
//     }
        
// }
