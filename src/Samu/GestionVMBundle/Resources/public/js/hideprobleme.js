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