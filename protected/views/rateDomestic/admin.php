<div class="form">
	<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'FRatePrice-Form',
		'enableClientValidation' => true,
		'enableAjaxValidation' => true,
			));

	?>

	<h4 class="ui-box-header ui-corner-all">Rate Price</h4>

	<div class="row"><?php echo $form->label($model, 'companyService'); ?></div>
	<div class="row"><?php
	echo $form->dropDownList($model, 'companyService', CHtml::listData(RateCompanyService::model()->findAll(), 'id', 'name', 'company.name'), array(
		'prompt' => '-- Choose Service --',
	));

	?>
    <div class="row"><?php echo $form->error($model, 'companyService'); ?></div>
	</div>

	<div class="row"><?php echo $form->label($model, 'origin'); ?></div>
	<div class="row"><?php
		echo $form->dropDownList($model, 'origin', CHtml::listData(Origins::model()->findAll(), 'id', 'name'), array(
			'prompt' => '-- Choose Origin --',
		));

	?>
	</div>
	<div class="row"><?php echo $form->error($model, 'origin'); ?></div>

	<div class="row">
		<?php echo $form->label($model, 'to'); ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'model' => $model,
			'name' => 'to',
			'sourceUrl' => array('ratePrice/ratePriceAutoComplete'),
			'options' => array(
				'select' => 'js:function(event,ui){
							$("#FRatePrice_to").val(ui.item.did);
							$("#FRatePrice_zone_id").val(ui.item.zid);
							$("#FRatePrice_district_id").val(ui.item.did);
							$("#FRatePrice_mode").val(ui.item.mode);
              return true;
           }'
			),
				)
		);

		?>
		<?php echo $form->error($model, 'to'); ?>
	</div>
	<?php echo $form->hiddenField($model, 'to'); ?>
	<?php echo $form->hiddenField($model, 'zone_id'); ?>
	<?php echo $form->hiddenField($model, 'district_id'); ?>
	<?php echo $form->hiddenField($model, 'mode'); ?>

	<div class="row buttons"><?php
	echo CHtml::ajaxButton('View Table', array('submitOrigin'), array(
		'data' => 'js:$(this).parents("form").serialize()',
		'type' => 'POST',
		'dataType' => 'json',
		'success' => 'function(r){if(r.success){window.location.href = r.message;}
            else {
             $.each(r.message,function(k,v){
                $("div#"+k+"_em_").html(v).attr("style","");
                $("select#"+k).parents("div.row").addClass("error");
                return true;
              })
            }
         }'
	));

	?></div>
	<?php $this->endWidget(); ?>
	<?php
	if ($csid != '' && $oid != '')
	{
		$tokenName = Yii::app()->request->csrfTokenName;
		$token = Yii::app()->request->csrfToken;

		/**
		 * ajax for validate reteprice 
		 */
		$ajax = CHtml::ajax(array(
					'url' => array('validateRatePrice'),
					'dataType' => 'json',
					'type' => 'post',
					'data' => 'js:$("input."+$(this).attr("class")).serialize()+"&' . $tokenName . '=' . $token . '&service_id= ' . $csid . '&origin_id= ' . $oid . '"',
					'success' => 'function(r){
                        var targetClass=$("input.RatePrice_"+r.classid).parents("span.row");
                        var targetErrorClass=targetClass.children("span.RatePrice_"+r.classid);

                        if(r.success){
                                targetClass.removeClass("error");
                                targetClass.addClass("success");
                                targetErrorClass.html(" ");
                        }
                        else if(!r.success){
                                targetClass.removeClass("success");
                                targetClass.addClass("error");
                                $.each(r.message, function(selector,value){
                                    $("span.RatePrice_"+r.classid+"#"+selector).html(value)
                                });
                        }
                    }',
				));

		$cs = Yii::app()->clientScript;
		$script = <<<SCRIPT
    $('body').undelegate('input[rel=RatePriceTextField]','change').delegate('input[rel=RatePriceTextField]','change',function(){ $ajax });
SCRIPT;
		$cs->registerScript('validateGrid', $script);
		$cs->registerCoreScript('jquery.ui');

		/**
		 * begin form for set rate price 
		 */
		$form = $this->beginWidget('CActiveForm', array(
			'id' => 'grid-data-form'
				));

		?>

		<?php
		/**
		 * this is hidden field for string service_id and origin_id 
		 */
		echo CHtml::hiddenField('service_id', $csid, array('id' => 'hd_service_id'));
		echo CHtml::hiddenField('origin_id', $oid, array('id' => 'hd_origin_id'));

		?>
		<?php if ($mode == 'district'): ?>
			<div class="grid">
				<?php
				$this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider' => $district,
					'htmlOptions' => array('class' => 'hastable'),
					'columns' => array(
						array(
							'name' => 'Propinsi',
							'value' => '$data->province->name',
						),
						array(
							'name' => 'Kota / Kabupaten',
							'value' => '$data->name',
						),
						array(
							'value' => '$data->getRatePriceTextField("first_kg",' . $csid . ',' . $oid . ',$data->id)',
							'type' => 'raw',
							'header' => RateDomestic::model()->getAttributeLabel("first_kg"),
						),
						array(
							'value' => '$data->getRatePriceTextField("next_kg",' . $csid . ',' . $oid . ',$data->id)',
							'type' => 'raw',
							'header' => RateDomestic::model()->getAttributeLabel("next_kg"),
						),
						array(
							'value' => '$data->getRatePriceTextField("transit_time",' . $csid . ',' . $oid . ',$data->id)',
							'type' => 'raw',
							'header' => RateDomestic::model()->getAttributeLabel("transit_time"),
						),
						array(
							'class' => 'CButtonColumn',
							'header' => 'Opsi',
							'template' => '{delete}',
							'buttons' => array(
								'delete' => array(
									'url' => 'Yii::app()->controller->createUrl("/ratePrice/delete/".$data->getRatePriceTextField("id",' . $csid . ',' . $oid . ',$data->id,true))'
								),
							)
						)
					)
				));

				?>
			</div>
		<?php endif; ?>

		<br />
		<div class="grid">
			<?php
			$this->widget('zii.widgets.grid.CGridView', array(
				'dataProvider' => $zones,
				'htmlOptions' => array('class' => 'hastable'),
				'columns' => array(
					array(
						'name' => 'Propinsi',
						'value' => '$data->district->province->name',
					),
					array(
						'name' => 'Kota/Kabupaten',
						'value' => '$data->district->Name',
					),
					array(
						'header' => 'Kecamatan',
						'value' => '$data->name',
						'name' => 'name',
					),
					array(
						'value' => '$data->getRatePriceTextField("first_kg",' . $csid . ',' . $oid . ',$data->id,$data->district_id)',
						'type' => 'raw',
						'header' => RateDomestic::model()->getAttributeLabel("first_kg"),
					),
					array(
						'value' => '$data->getRatePriceTextField("next_kg",' . $csid . ',' . $oid . ',$data->id,$data->district_id)',
						'type' => 'raw',
						'header' => RateDomestic::model()->getAttributeLabel("next_kg"),
					),
					array(
						'value' => '$data->getRatePriceTextField("transit_time",' . $csid . ',' . $oid . ',$data->id,$data->district_id)',
						'type' => 'raw',
						'header' => RateDomestic::model()->getAttributeLabel("transit_time"),
					),
					array(
						'class' => 'CButtonColumn',
						'header' => 'Opsi',
						'template' => '{copytoall}&nbsp;&nbsp;{delete}',
						'buttons' => array(
							'delete' => array(
								'url' => 'Yii::app()->controller->createUrl("/ratePrice/delete/".$data->getRatePriceTextField("id",' . $csid . ',' . $oid . ',$data->id,$data->district_id,true))'
							),
							'copytoall' => array(
								'label' => 'Salin ke semua',
								'imageUrl' => Yii::app()->theme->baseUrl . '/images/icons/copytoall.png',
								'click' => "js:function(){
																var thisRow=$(this).parents('tr');
																var firstkgInputValue=thisRow.find('input[type=text]:eq(0)');
																var nextkgInputValue=thisRow.find('input[type=text]:eq(1)');
																var transitTimeInputValue=thisRow.find('input[type=text]:eq(2)');
																var serviceTypeValue=thisRow.find('input[type=text]:eq(3)');
																
																var firstkg=$('table.items tr:has(input[type=text][name*=first])').find('input[type=text]:eq(0)');
																var nextkg=$('table.items tr:has(input[type=text][name*=first])').find('input[type=text]:eq(1)');
																var transitTime=$('table.items tr:has(input[type=text][name*=first])').find('input[type=text]:eq(2)');
																if(firstkgInputValue.parents('span').attr('class')=='row success'){
																		firstkg.val(firstkgInputValue.val());};
																if(nextkgInputValue.parents('span').attr('class')=='row success'){nextkg.val(nextkgInputValue.val())};
																if(transitTimeInputValue.parents('span').attr('class')=='row success'){
																	transitTime.val(transitTimeInputValue.val());
																	$('table.items span.row:has(input[name*=RateDomestic])').addClass('success');
																};
																return false;
														}"
							),
						)
					)
				)
			));

			?>
		</div>
		<?php
		echo CHtml::ajaxButton('Simpan', $this->createUrl('submitGrid'), array(
			'data' => 'js:$("span.row.success input").serialize()+"&' . $tokenName . '=' . $token . '&service_id=' . $csid . '&origin_id=' . $oid . '"',
			'dataType' => 'json',
			'type' => 'post',
			'beforeSend' => 'function(){jQuery("<div id=notifsave> Sedang menyimpan data </div>").dialog({"modal":true,"closeOnEscape":false,"beforeClose":function(event,ui){$("span.row.success").removeClass("success");}})}',
			'success' => 'setTimeout(function(r){jQuery("div#notifsave").dialog("close");$.fn.yiiGridView.update("yw0");},800)'
				), array(
			'id' => 'save',
			'name' => 'save')
		);

		?>
		<?php
		$this->endWidget();
	}

	?>
</div>