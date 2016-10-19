$( "#boutonAjouterCategoriePortfolio" ).click(function() {

	$(".conteneurFormulairePortfolioModif").append("<div class = 'conteneurInputPortfolioModif'>"+
					"<input type = 'text' name = 'newCategorie'"+
					"class = 'form-control inputPortfolioModif'/>"+
				"</div>");

});