$('.add-com').show();

$('.submit-com').submit(function(e) {
	e.preventDefault();
	var action  = $(this).attr('action');
	var donnees = 'content=' + $(this).children("#com-text").val();

	$.ajax({
		url : action,
		type : 'GET',
		data : donnees,
		dataType : 'html',
		success : function(com, statut) 
		{
			/*$(this).append(com);
			/*$(this).children("#com-text").val('');*/
			console.log("Element : " + $(this).prop());
			console.log(com);
		},
		error : function(resultat, statut, erreur) 
		{
			console.log(com);
		}
	});
});

/*trouver comment trouver le bon élément qui est undefined pour le moment avec cette fonction...*/
