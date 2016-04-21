/*$(".probleme_view_title").mouseenter(function(event) {

	var id = $(this).parent('.probleme_view').attr('id');
	var element = $(this);
	console.log(id);
	$.ajax({
		url: Routing.generate('samu_gestion_vm_isProblemNew', {'id': id }),
		succes: function(reponse, statut)
		{
			element.find('.pvt_id').empty().append(reponse);
		}
	})
});*/