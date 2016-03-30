//Script d'apparition de la zone de texte

$(".coms").on("click", "a",function(e)
{
	e.preventDefault();
	var id = $(this).parents(".combox").children(".combody").prop('id');

	if($(this).is(":contains('Modifier')") || $(this).is(":contains('Annuler')"))
	{
		//Si le formulaire de ce commentaire n'existe pas déjà
		if(!$(this).parents(".combox").find("form.modif").length)
		{
			//On enlève les autres modifications en cours
			var modifEnCours = $(".modif");

			if(modifEnCours.length)
			{
				modifEnCours.each(function()
				{
					var id          = $(this).parents(".combox").prop('id');
					var idForm      = $(this).parents(".combox").children(".combody").prop('id');
					var thisCombody = $('#' + id).children('.combody');

					$(this).parents(".combox").children(".com-links").children(".Modifier").html('Modifier');

					$(".loader").clone().appendTo(thisCombody);
					thisCombody.children(".loader").show();

					$.ajax({
						url: "/app_dev.php/GestionVM/comment/view/" + id,
						type: 'GET',
						success: function(reponse, statut)
						{
							thisCombody.children(".loader").remove();
							$("#modif-" + idForm).remove();
							$("#" + id).children('.combody').html('');
							$("#" + id).children('.combody').prepend(reponse);
						}
					});
				});
			}

			//on crée le formulaire
			var content = $("#" + id).html();
			var action  = $(this).attr('href');

			$(this).html('Annuler');
			$("#" + id).html('');
			$("#" + id).append('<form class="modif" id="modif-' + id + '" method="post" action="' + action + '"><textarea name="edit-com" id="content"></textarea><input type="submit" value="Modifier le commentaire" /></form>');
			$("#" + id + " textarea#content").val(content);
		
		//Si le formulaire existe déjà, c'est qu'on veut annuler
		} else {
			var id          = $(this).parents(".combox").prop('id');
			var idForm      = $(this).parents(".combox").children(".combody").prop('id');
			var thisCombody = $('#' + id).children('.combody');

			$(this).html('Modifier');

			$(".loader").clone().appendTo(thisCombody);
			thisCombody.children(".loader").show();
			
			$.ajax({
				url: "/app_dev.php/GestionVM/comment/view/" + id,
				type: 'GET',
				success: function(reponse, statut)
				{
					thisCombody.children(".loader").remove();
					$("#modif-" + idForm).remove();
					$("#" + id).children('.combody').html('');
					$("#" + id).children('.combody').prepend(reponse);
				}
			});
		}
	}
});

//Script AJAX de modification du commentaire

$(".coms").on("submit", "form.modif", function(e) { 

	e.preventDefault();

	var action = $(this).parents(".combox").find(".Modifier").attr('href');
	var newContent = $(this).children("textarea#content").val();

	$.ajax({
		url: action,
		type: 'POST',
		data: {modif:newContent},
		success: function(reponse, statut)
		{
			console.log('fonction Ajax d\'édition');
			console.log($('form.modif').parents('.combody'))
			$('form.modif').parents('.combody').html('');
			$('form.modif').parents('.combody').html(newContent);
		}
	});
});