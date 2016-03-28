// Ajouter un film dans sa vidéotheque
$(function() {
	$("#viewdate").datepicker({
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
		regional: 'fr',
		closeText: 'Fermer',
	    prevText: 'Précédent',
	    nextText: 'Suivant',
	    currentText: 'Aujourd\'hui',
	    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
	    monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
	    dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
	    dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
	    dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
	    weekHeader: 'Sem.',
	});
});


function changeTrailer(url) {
	$('#trailer').html(url);
	event.preventDefault();
}

function addWish(movieid, type) {
	  $.ajax({
	     url : 'film/'+movieid+'/addWish/'+type,
	  }).done(function() {

	  	$('#'+type).attr('onClick', 'delWish('+movieid+', \''+type+'\')');
	  	$('#'+type).removeClass('btn-default').addClass('btn-success');
	  	});
	}

function delWish(movieid, type) {
	  $.ajax({
	     url : 'film/'+movieid+'/delWish/'+type,
	  }).done(function() {

	  	$('#'+type).attr('onClick', 'addWish('+movieid+', \''+type+'\')');
	  	$('#'+type).removeClass('btn-success').addClass('btn-default');
	  	});
	}
	
function addBiblio(movieid, type) {
	$.ajax({
		url : 'film/'+movieid+'/addBiblio/'+type,
	}).done(function() {
		$('#'+type).attr('onClick', 'delBiblio('+movieid+', \''+type+'\')');
		$('#'+type).removeClass('btn-default').addClass('btn-success');
	});
}

function delBiblio(movieid, type) {
	$.ajax({
		url : 'film/'+movieid+'/delBiblio/'+type,
	}).done(function() {
		$('#'+type).attr('onClick', 'addBiblio('+movieid+', \''+type+'\')');
		$('#'+type).removeClass('btn-success').addClass('btn-default');
	});
}

function rate(movieid, rate) {
	$.ajax({
		url : 'film/'+movieid+'/rate/'+rate,
	}).done(function() {
		var text = 'Note : ';
		for(var i = 1; i <= 5; i++) {

		    if(i > rate)
		        text += '<span class="glyphicon glyphicon-star-empty" onClick="rate('+movieid+', '+i+')" title="Noter '+i+'/5"></span>';
		    else
		        text += '<span class="glyphicon glyphicon-star" onClick="rate('+movieid+', '+i+')" title="Noter '+i+'/5"></span>';
		}
		$('#rateFilm').html(text);
	});
}
