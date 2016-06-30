var $flashbags = $('.flashbag');

$flashbags.on('click', function() {
	$(this).slideUp(1000);
});

$flashbags.delay(2000).slideUp(1000);
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

$("#block_page").on("submit", ".add-pb form", function(e) {
	e.preventDefault();

	$.ajax({
		url : Routing.generate("samu_gestion_vm_problemeAdd", {"typePb" : "pbvehicule"}),
		type: 'POST',
		data: $(this).serialize(),
		success: function(reponse, statut)
		{
			window.location.href = Routing.generate('samu_gestion_vm_index');			
		}
	});
});

function closePopup() {
	$("#background-add-pb").remove();
}
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
$('.probleme_hidable').hide();

$('article').on("click", ".probleme_view_title .pvt_title a", function(e)
{
	e.preventDefault();

	if ($(e.target).parent('.pvt_title').parent('.probleme_view_title').parent(".probleme_view").find(".probleme_hidable").is(":hidden"))
	{
   		$(e.target).parent('.pvt_title').parent('.probleme_view_title').parent(".probleme_view").find(".probleme_hidable").slideDown("slow");
	} 
		else 
	{
		$(e.target).parent('.pvt_title').parent('.probleme_view_title').parent(".probleme_view").find(".probleme_hidable").slideUp("slow");
	}
});
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
			window.location.href = Routing.generate('samu_gestion_vm_index', {'action': 'valider'});
		}
	});
});

function closePopup() {
	$("#background-add-pb").remove();
}