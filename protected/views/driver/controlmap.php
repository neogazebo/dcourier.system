<div class="form">
<?php echo CHtml::beginForm(); ?>
 
    <div class="row">
        <?php echo CHtml::label('Method', 'method')?>
        <?php echo CHtml::dropDownList('method','' ,array('all'=>'All', 'one'=>'By user')) ?>
    </div>
	
		<div class="row">
        <?php echo CHtml::label('User ID', 'user_id')?>
        <?php echo CHtml::textField('user_id') ?>
    </div>

    <div class="row submit">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>
 
<?php echo CHtml::endForm(); ?>
</div><!-- form -->