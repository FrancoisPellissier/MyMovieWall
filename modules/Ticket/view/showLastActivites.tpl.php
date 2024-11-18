<?php
$Parsedown = new \library\Parsedown();

foreach($activites AS $comment) {
	?>
<div class="row">
	<div class="col-xs-8 col-sm-8 col-md-8">
		<div class="panel panel-default">
			<?php
			echo "\n\t\t\t".'<div class="panel-heading" id="'.$comment['commentid'].'">'.\library\Datetime::formatDateTime($comment['created_at'], 'd/m/Y', 'H:i:s').' : ' .$comment['realname'].($comment['firstcommentid'] == $comment['commentid'] ? ' a crée ' : ' a commenté').' le ticket <a href="ticket/'.$comment['ticketid'].'#'.$comment['commentid'].'">#'.$comment['ticketid'].' '.pun_htmlspecialchars($comment['ticketname']).'</a></div>';
			?>
			<div class="panel-body">
			<?php
			echo $Parsedown->text(pun_htmlspecialchars($comment['message']));
			?>
			</div>
		</div>
	</div>
</div>
<?php
}?>
