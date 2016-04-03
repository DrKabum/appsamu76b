//Affichage du popup

$("#add-pb").on("click", function(e) {

	e.preventDefault();
	console.log("Pour ouvrir, ça marche hein... !");

	var action = $(this).attr('href');

	//demander le formulaire au controlleur
	$.ajax({
		url: action,
		type: 'GET',
		success: function(reponse, statut) 
		{
			$("#block_page").append(reponse);
		}
	});
});

//fermeture du popup
$("#block_page").on("click", "#popup-close", function(e) {

	e.preventDefault();
	$("#background-add-pb").remove();
})