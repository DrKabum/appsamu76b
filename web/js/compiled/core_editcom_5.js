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
					var action      = Routing.generate('samu_gestion_vm_ajaxView', {id: idficator(id)});

					$(this).parents(".combox").children(".com-links").children(".Modifier").html('Modifier');

					$(".loader").clone().appendTo(thisCombody);
					thisCombody.children(".loader").show();

					$.ajax({
						url: action,
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
				url: Routing.generate('samu_gestion_vm_ajaxView', {id: idficator(id)}),
				type: 'GET',
				success: function(reponse, statut)
				{
					thisCombody.children(".loader").remove();
					$("#modif-" + idForm).remove();
					//$("#" + id).children('.combody').html('');
					$("#" + id).children('.combody').html(reponse);
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
			$('form.modif').parents('.combody').parents(".combox").children(".com-links").children(".Modifier").html('Modifier');
			$('form.modif').parents('.combody').html(newContent);
			$('form.modif').detach();
		}
	});
});

function idficator(id)
{
	var re = /com/gi;
	var str = id;
	var nouvelleStr = str.replace(re, "");

	return nouvelleStr;
}