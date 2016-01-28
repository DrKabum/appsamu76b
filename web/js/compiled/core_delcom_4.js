$("div.combox li a").click(e) {
	e.preventDefault();
	var target = e.target;
	console.log(target.parent("combox").attr('id'));

	/*if(target.html().is(":contain('Supprimer')")) 
	{
		var action = target.href;

		$.ajax({
			url: action,
			type: 'GET',
			success: function(statut)
			{
				
			}
		});
	}*/
}