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

// Ajouter un film dans la base
$(function() {
	$('.addFilm').click(function(event) {
	   event.preventDefault();
	  
	  $.ajax(this.href, {
	      success: function(data) {
	        window.location = 'http://localhost/movie/film/'+data;
	      }
	   });
	});
});
