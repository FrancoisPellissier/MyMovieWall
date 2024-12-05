// Ajouter un film dans la base
$(function() {
	$('.addFilm').click(function(event) {
	  
	  $( "#results" ).replaceWith('<p>La fiche est en cours de création... Vous serez redirigé quand elle sera prête.</p>');
	   event.preventDefault();
	  
	  $.ajax(this.href, {
	      success: function(data) {
	        window.location = 'film/'+data;
	      }
	   });
	});
});