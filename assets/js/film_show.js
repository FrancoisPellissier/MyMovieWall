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

	$('.addWish').click(function(event) {
		event.preventDefault();
		
		$.ajax(this.href);
		$(this).children().removeClass('btn-default').addClass('btn-success');
		$(this).removeClass('addWish').addClass('delWish');
		$(this).attr("href", this.href.replace('addWish', 'delWish'))
		
		return false;
	});

	$('.delWish').click(function(event) {
		event.preventDefault();
		
		$.ajax(this.href);
		$(this).children().removeClass('btn-success').addClass('btn-default');
		$(this).removeClass('delWish').addClass('addWish');
		$(this).attr("href", this.href.replace('delWish', 'addWish'))
		
		return false;
	});
});


function changeTrailer(url) {
	$('#trailer').html(url);
	event.preventDefault();
}