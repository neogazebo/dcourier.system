<table class="tp" style="width: 50%;">
	<tr>
		<th>Action</th>
		<th>Company Name</th>
		<th>Service Name</th>
		<th>Price</th>
	</tr>
	<?php foreach ($services as $service):?>
	<tr>
		<?php $sm = ($service['service_id']==$model->service_id)?>
		<td><input type="radio" name="Shipment[service_id]" value="<?php echo $service['service_id'] ?>" <?php if($sm):?> checked="checked" <?php endif;?>/></td>
		<td><?php echo $service['company_name'] ?></td>
		<td><?php echo $service['service_name'] ?></td>
		<td><?php echo $service['price'] ?></td>
	</tr>
	<?php endforeach; ?>
</table>
