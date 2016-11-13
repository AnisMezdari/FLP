var i = 0;
$( "#boutonAjouterCategoriePortfolio" ).click(function() {
	if(i == 0){
		$(".conteneurFormulairePortfolioModif").append("<div class = 'conteneurInputPortfolioModif'>"+
				"<input type = 'text' name = 'newCategorie'"+
				"class = 'form-control inputPortfolioModif'/>"+
			"</div>");
		i++;
	}


});