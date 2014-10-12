// Ajouter un film dans sa vidéotheque
$(function() {
	$('.addBiblio').click(function(event) {
		event.preventDefault();
		
		$.ajax(this.href);
		$(this).children().removeClass('btn-default').addClass('btn-success');
		$(this).removeClass('addBiblio').addClass('delBiblio');
		$(this).attr("href", this.href.replace('addBiblio', 'delBiblio'))
		
		return false;
	});

	$('.delBiblio').click(function(event) {
		event.preventDefault();
		
		$.ajax(this.href);
		$(this).children().removeClass('btn-success').addClass('btn-default');
		$(this).removeClass('delBiblio').addClass('addBiblio');
		$(this).attr("href", this.href.replace('delBiblio', 'addBiblio'))
		
		return false;
	});
});