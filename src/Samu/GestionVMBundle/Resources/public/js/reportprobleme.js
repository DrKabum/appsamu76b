$("#report-pb").on("click", function(e) {
	e.preventDefault();

	$.ajax({
		url: Routing.generate('samu_gestion_vm_problemeReport', {'typePb': 'pbvehicule'}),
		type: 'GET',
		success: function(reponse, status)
		{
			$('#block_page').append(reponse);
		}
	});
});

//fermeture du popup
$("#block_page").on("click", "#popup-close", function(e) {

	e.preventDefault();
	closePopup();
})

$("#block_page").on("submit", ".report-pb form", function(e) {
	e.preventDefault();

	$.ajax({
		url : Routing.generate("samu_gestion_vm_problemeReport", {"typePb" : "pbvehicule"}),
		type: 'POST',
		data: $(this).serialize(),
		success: function(reponse, statut)
		{
			window.location.href = Routing.generate('samu_gestion_vm_indexNonValide');
		}
	});
});

function closePopup() {
	$("#background-add-pb").remove();
}