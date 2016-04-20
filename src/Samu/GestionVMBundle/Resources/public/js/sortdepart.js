$(".sortable").sortable({
	cursor: "move",
	containment: "#block-UMH",
	placeholder: "ui-state-highlight",
	update: function(event, ui) 
	{
		$.ajax({
			url: Routing.generate("samu_gestion_vm_sortOrdreDepart"),
			data: $(this).sortable('serialize')
		});
	}
});