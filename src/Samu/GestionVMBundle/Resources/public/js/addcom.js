$('.com-nojs').hide();
$('.add-com').show();

$('.submit-com').submit(
	$('.new-com').show();
	$.ajax({
		method : this.attr('method'),
		url    : this.attr('action'),
		data   : $('this > input[name=com]').val(),
		succes : function(data) {
			$('.new-com').hide();
			
		}
	});