//listes UMH connectées
$('#block-UMH').sortable({
	connectWith: "#block-UMH-desarme",
	placeholder: "ui-state-highlight"
});

$('#block-UMH-desarme').sortable({
	connectWith: "#block-UMH",
	placeholder: "ui-state-highlight"
});

//listes VML connectées
$('#block-VML').sortable({
	connectWith: "#block-VML-desarme",
	placeholder: "ui-state-highlight"
});

$('#block-VML-desarme').sortable({
	connectWith: "#block-VML",
	placeholder: "ui-state-highlight"
});

//Evenement entrainant la mise à jour ajax de la BDD
$("#block-UMH, #block-VML, #block-UMH-desarme, #block-VML-desarme").on("sortupdate", function(){
	$.ajax({
		url: Routing.generate("samu_gestion_vm_sortOrdreDepart"),
		data: $(this).sortable('serialize')
	});
});

//Changer les class et id quand on a changé de liste entre opé et pas opé
$("#block-UMH, #block-VML").on('sortremove', function(event, ui) {
	var id = ui.item.attr('id');
	var newId = id.replace(/vehiculeid-/, 'vehiculedownid-');

	ui.item.attr('id', newId);
	ui.item.find('.nom-vehicule').removeClass('vehicule-op').addClass('vehicule-hs');
});

$('#block-UMH-desarme, #block-VML-desarme').on('sortremove', function(event,ui) {
	var id = ui.item.attr('id');
	var newId = id.replace(/vehiculedownid-/, 'vehiculeid-');

	ui.item.attr('id', newId);
	ui.item.find('.nom-vehicule').removeClass('vehicule-hs').addClass('vehicule-op');
});