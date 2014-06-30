<table class="tp" style="width: 50%;">
	<tr>
		<th>Action</th>
		<th>Service Name</th>
		<th>Area</th>
		<th>Price</th>
	</tr>
	<?php foreach ($services as $service):?>
	<tr class="area<?php echo $service['area_id'] ?> body">
		<td><input type="radio" name="Shipment[service_id]" value="<?php echo $service['type_id'] ?>" /></td>
		<td><?php echo $service['type'] ?></td>
		<td><?php  echo $service['area']== 'default' ? '-' : $service['area'] ?></td>
		<td><?php echo $service['price'] ?></td>
	</tr>
	<?php endforeach; ?>
</table>