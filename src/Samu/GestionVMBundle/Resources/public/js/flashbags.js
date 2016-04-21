var $flashbags = $('.flashbag');

$flashbags.on('click', function() {
	$(this).slideUp(1000);
});

$flashbags.delay(2000).slideUp(1000);