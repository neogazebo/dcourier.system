<div class="row" id="prid">
		<?php echo CHtml::activeLabel($customer_shipping_profile,'product_service_id'); ?>
		<?php echo CHtml::activeDropDownList($customer_shipping_profile,'product_service_id', CHtml::listData(ProductService::model()->findAllByAttributes(array('product_id' => $product_id)), 'id', 'name'),array('prompt' => '')); ?>
</div>