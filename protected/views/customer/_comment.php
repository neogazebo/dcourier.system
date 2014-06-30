<div class="comment">
	<p style="line-height: 17px;padding: 0"><strong style="font-weight: bold"><?php echo ucfirst($comment->user->username) ?> </strong> <?php echo $comment->comment ?></p>
	<span style="font-size: 80%"><?php  echo Yii::app()->dateFormatter->formatDateTime($comment->created,'medium','short') ?></span>
</div>