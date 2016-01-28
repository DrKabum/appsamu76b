$(".coms").on("click", "a",function(e) {
	e.preventDefault();
	var id = $(this).parents(".combox").prop('id');

	if($(this).is(":contains('Supprimer')")) 
	{
		if(confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?'))
		{
			var action = $(this).attr('href');

			$.ajax
			({
				url: action,
				type: 'GET',
				success: function(statut)
				{
					$("#" + id).hide();
				},
				error: function()
				{
					prompt('Oups, il y a eu un problème... Veuillez rafraîchir la page.');
				}
			});
		}
	}
});