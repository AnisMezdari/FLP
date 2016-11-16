var i = 0;
$( "#boutonAjouterCategoriePortfolio" ).click(function() {
	if(i == 0){
		$(".conteneurFormulairePortfolioModif").append("<div class = 'conteneurInputPortfolioModif'>"+
				"<input type = 'text' name = 'newCategorie' value = 'Nouvelle catégorie' "+
				"class = 'form-control inputPortfolioModif'/>"+
				"<span  id= '{{categorie.id}}' class='glyphicon glyphicon-remove fa-lg croixSuppressionPortfolioCategorie' > </span>"+
				"<input type = 'hidden' value = '{{path('portfolio_platform_supressionCategorie')}}' id = 'lienSuppressionPortfolioCategorie' />"+
			"</div>");
		i++;
	}


});

$( ".croixSuppressionPortfolioCategorie" ).click(function() {
	// console.log($(this).attr("id"));
	var element = $(this).attr("id");
	var lien = $("#lienSuppressionPortfolioCategorie").val();
	var idPortfolioCategorie = element;
	jQuery.ajax({
	    url: lien,
	    data: JSON.stringify({"idPortfolioCategorie": idPortfolioCategorie}),
	    contentType: "application/json",
	    type: 'POST',
	    success: function(data){
	    	console.log(data);
	    	$("#portfolioCategorie-" + idPortfolioCategorie).remove();
	    },
	    error: function (data) {
        	console.log(data.responseText);
        	// status 500
    	}
	})

}); 