$('#block_page').on('click', '.pvt_title a', function(e) {
	e.preventDefault();

	if ($(this).parents('.probleme_view_title').children('.pvt_id').children('img').hasClass('new-pb'))
	{	
		var divId = $(this).parents('.probleme_view').attr('id');
		var problemId = divId.replace(/pb-/, '');

		console.log(problemId);

		$.ajax({
			url: Routing.generate('samu_gestion_vm_isProblemNew', {'id' : problemId}),
			success: function(reponse, status)
			{
				$('#pb-' + problemId).find('.pvt_id').empty().append(reponse);
			}
		});
	}
});