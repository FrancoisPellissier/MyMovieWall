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

function onChangeFamilleLevel(cur) {
   
    var next = cur + 1;
   
    for(j = next;j <= 7; j++) {
        $("select#famille_id_"+j).html('');
        }
       
   
    if(cur < 7) {
    $.post("include/ajax/articles_famille.php", {
        niveau: next,
        valeur_select: $("select#famille_id_"+cur+" option:selected").attr('value')
        }, function (data) {
        $("select#famille_id_"+next).html(data);  
        });
    }
}
