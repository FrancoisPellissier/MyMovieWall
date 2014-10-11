// Ajouter un film dans sa vidéotheque
$(function() {
	$('.addBiblio').click(function(event) {
		event.preventDefault();

		$(this).children().removeClass('btn-default').addClass('btn-success');
		$(this).removeClass('addBiblio').addClass('delBiblio');
		// this.href = this.href.replace('addBiblio', 'delBiblio');
		$.ajax(this.href);
	});
});