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

$("#block_page").on("submit", ".add-pb form", function(e) {
	e.preventDefault();

	$.ajax({
		url : Routing.generate("samu_gestion_vm_problemeAdd", {"typePb" : "pbvehicule"}),
		type: 'POST',
		data: $(this).serialize(),
		success: function(reponse, statut)
		{
			window.location.href = Routing.generate('samu_gestion_vm_index');			
		}
	});
});

function closePopup() {
	$("#background-add-pb").remove();
}