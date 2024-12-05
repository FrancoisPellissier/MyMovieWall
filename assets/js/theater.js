// Ajouter un film dans sa vidéotheque

$(function() {
	$('.addTheater').click(function(event) {
		event.preventDefault();
		$.ajax(this.href);
		
		return false;
	});
}
