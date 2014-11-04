// Ajouter un film dans la base
$(function() {
	$('.addFilm').click(function(event) {
	   event.preventDefault();
	  
	  $.ajax(this.href, {
	      success: function(data) {
	        window.location = 'film/'+data;
	      }
	   });
	});
});