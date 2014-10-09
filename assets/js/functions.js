// Ajouter un film dans sa vidéotheque
$(function() {
	$('.addBiblio').click(function(event) {
	   event.preventDefault();
	  
	  $.ajax('http://localhost/movie/film/18/addBiblio/'+this.id, {
	      success: function() {
	         this.removeClass('btn-default').removeClass('active').addClass('btn-success');
	      }
	   });
	});
});
