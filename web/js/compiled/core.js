var $flashbags = $('.flashbag');

$flashbags.on('click', function() {
	$(this).hide(1000);
});
$('.add-com').hide();

$('article').on("click", ".open-com-tab", function(e)
{
	if ($(this).parent(".com-tab-container").find(".add-com").is(":hidden"))
	{
   		$(this).parent(".com-tab-container").find(".add-com").slideDown("slow");
	} else 
	{
		$(this).parent(".com-tab-container").find(".add-com").slideUp("slow");
	}
	
})

$('article').on("submit", '.submit-com', function(e) {
	e.preventDefault();
	var pb      = e.currentTarget.id;
	var action  = e.currentTarget.action;
	var donnees = 'content=' + $(this).children("#com-text").val();
	console.log("pb :" + pb + ", action : " + action + ", donnees : " + donnees);

	$(".new-com").show();

	$.ajax
	({
		url : action,
		type : 'GET',
		data : donnees,
		dataType : 'html',
		success : function(com, statut) 
		{
			$(".new-com").hide();
			$(".coms#" + pb).append(com);
			$('.submit-com#' + pb + " textarea#com-text").val('');
		},
		error : function(resultat, statut, erreur) 
		{
			console.log(com);
		}
	});
});
$("article").on("click", ".com-links a",function(e) {
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
//Script d'apparition de la zone de texte

$("article").on("click", ".com-links a",function(e)
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

$("article").on("submit", "form.modif", function(e) { 

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
/*$('#add-vehicule').on('click', function(e)
	{	
		e.preventDefault();

		var url = $(this).attr('href');

		$.ajax({
			url: url,
			type: 'POST',
			success: function(reponse, statut)
			{
				$('#block_UMH').append(reponse);
			}
		})
	}
);*/
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

$("#block_page").on("submit", "form", function(e) {
	e.preventDefault();

	var nomVehicule = $("")

	$.ajax({
		url : Routing.generate("samu_gestion_vm_problemeAdd", {"typePb" : "pbvehicule"}),
		type: 'POST',
		data: $(this).serialize(),
		success: function(reponse, statut)
		{
			//récupérer le nom du vehicule de la réponse avec data()
			$("#block_page").append("<div id=reponse>" + reponse + "</div>");
			var vehiculeReponse = $("#reponse .probleme_view_block").data('vehicule');

			//trouver la div du même véhicule et ajouter le contenu
			$("#reponse .probleme_view_block").insertAfter("#groupe-" + vehiculeReponse);
			$("#reponse").remove();
			$('.add-com').hide();

			closePopup();			
		}
	});
});

function closePopup() {
	$("#background-add-pb").remove();
}
$(".sortable").sortable({
	cursor: "move",
	containment: "#block-UMH",
	placeholder: "ui-state-highlight"}
);
