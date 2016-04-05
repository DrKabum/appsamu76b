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
	closePopup();
})

$("#block_page").on("submit", "form", function(e) {
	e.preventDefault();

	var nomVehicule = $("")

	$.ajax({
		url : Routing.generate("samu_gestion_vm_problemeAdd", {"typePb" : "pbvehicule"}),
		type: 'POST',
		data: $(this).serialize(),
		success: function(reponse, statut)
		{
			//récupérer le nom du vehicule de la réponse avec data()
			$("#block_page").append("<div id=reponse>" + reponse + "</div>");
			var vehiculeReponse = $("#reponse .probleme_view_block").data('vehicule');

			//trouver la div du même véhicule et ajouter le contenu
			$("#reponse .probleme_view_block").insertAfter("#groupe-" + vehiculeReponse);
			$("#reponse").remove();
			$('.add-com').hide();

			closePopup();			
		}
	});
});

function closePopup() {
	$("#background-add-pb").remove();
}