<div class="form">
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<form method="get" action="<?php echo $this->createUrl('service/checkawb') ?>">

	<div class="row">
		<label>AWB:</label>
		<input type="text" name="awb"/>
		<input type="hidden" name="r" value="service/checkawb">
		<input type="submit"/>
	</div>
   

	</form>

</div><!-- form -->