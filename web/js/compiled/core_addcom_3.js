$('.add-com').show();

$('.submit-com').submit(function(e) {
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
			$('.submit-com#' + pb + " input#com-text").val('');
		},
		error : function(resultat, statut, erreur) 
		{
			console.log(com);
		}
	});
});