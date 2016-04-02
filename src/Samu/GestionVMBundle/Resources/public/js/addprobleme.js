//Affichage du formulaire

$("#add-pb").on("click", function(e) {
		
		console.log("addprobleme.js activé");

		e.preventDefault();

		var action = $(this).attr('href');

		$.ajax({
			url: action,
			type: 'GET',
			success: function(reponse, statut) 
			{
				$("#block_page").append(reponse);
			}
		});
});

//Ajout effectif du formulaire

