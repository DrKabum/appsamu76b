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
		success : function(html, statut) {
		/*$(this).parent().children(".new-com").append(html);*/
		alert(html);},
		error : function(resultat, statut, erreur) {
			alert(erreur);
		}
	});
});
