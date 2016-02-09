//Script d'apparition de la zone de texte

$(".coms").on("click", "a",function(e)
{
	e.preventDefault();
	var id = $(this).parents(".combox").children(".combody").prop('id');

	if($(this).is(":contains('Modifier')")) 
	{
		if(!$(this).parents(".combox").find("form.modif").length)
		{
			//Si le formulaire n'existe pas, je le crée
			var content = $("#" + id).html();
			var action  = $(this).attr('href');

			$("#" + id).html('');
			$("#" + id).append('<form class="modif" id="modif-' + id + '" method="post" action="' + action + '"><input type="text" name="edit-com" id="content"/><input type="submit" value="Modifier le commentaire" /></form>');
			$("#" + id + " input#content").val(content);
		
		//Si le formulaire existe déjà, c'est qu'on veut annuler
		} else {
			var content = $("#" + id + " input#content").val();

			$("#modif-" + id).remove();
			$("#" + id).append(content);
		}
	}
});

//Script AJAX de modification du commentaire

$(".coms").on("submit", "form.modif", function(e) { 

	e.preventDefault();

	console.log($(this));

	var action = $(this).parents(".combox").find(".Modifier").attr('href');
	var newContent = $(this).children("input#content").val();

	$.ajax({
		url: action,
		type: 'POST',
		data: {modif:newContent},
		success: function(reponse, statut)
		{
			$('form.modif').parents('.combody').append(newContent);
			$('form.modif').detach();
		}
	});
});