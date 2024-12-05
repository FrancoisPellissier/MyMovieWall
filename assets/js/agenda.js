$(function() {
	$('.addWish').click(function(event) {
		event.preventDefault();
		
		$.ajax(this.href);
		$(this).replaceWith('<img src="img/icons/play.png" class="toview" />');
		/*
		$(this).removeClass('addWish');
		$(this).attr("href", this.href.replace('addWish', 'delWish'))
		*/
		return false;
	});
});
