<?php

/**
 * Description of CFCheckBoxColumn
 *
 * @author febri
 */
class CFCheckBoxColumn extends CCheckBoxColumn
{
	public $customer_id;
	public $forAttribute;
	
	public function init()
	{
		if(isset($this->checkBoxHtmlOptions['name']))
			$name=$this->checkBoxHtmlOptions['name'];
		else
		{
			$name=$this->id;
			if(substr($name,-2)!=='[]')
				$name.='[]';
			$this->checkBoxHtmlOptions['name']=$name;
		}
		$name=strtr($name,array('['=>"\\[",']'=>"\\]"));

		if($this->selectableRows===null)
		{
			if(isset($this->checkBoxHtmlOptions['class']))
				$this->checkBoxHtmlOptions['class'].=' select-on-check';
			else
				$this->checkBoxHtmlOptions['class']='select-on-check';
			return;
		}

		$cball=$cbcode='';
		if($this->selectableRows==0)
		{
			//.. read only
			$cbcode="return false;";
		}
		elseif($this->selectableRows==1)
		{
			//.. only one can be checked, uncheck all other
			$cbcode="$(\"input:not(#\"+this.id+\")[name='$name']\").prop('checked',false);";
		}
		else
		{
			//.. process check/uncheck all
			$cball=<<<CBALL
$('#{$this->id}_all').live('click',function() {
	var checked=this.checked;
	$(".{$this->id}").each(function() {this.checked=checked;});
});

CBALL;
			$cbcode="$('#{$this->id}_all').prop('checked', $(\"input[name='$name']\").length==$(\"input[name='$name']:checked\").length);";
		}

		$js=$cball;
		$js.=<<<EOD
$("input[name='$name']").live('click', function() {
	$cbcode
});
EOD;
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id,$js);
	}

	protected function renderHeaderCellContent()
	{
		if($this->selectableRows===null && $this->grid->selectableRows>1)
			echo CHtml::checkBox($this->id.'_all',false,array('class'=>'select-on-check-all'));
		else if($this->selectableRows>1)
			echo trim($this->header)!=='' ? $this->header.' '.CHtml::checkBox($this->id.'_all',false) : $this->grid->blankDisplay;
		else
			parent::renderHeaderCellContent();
	}
	
	protected function renderDataCellContent($row, $data)
	{
		$model = CustomerDiscount::model()->findByAttributes(array(
			'customer_id' => $this->customer_id,
			'service_id' => $data->id,
				));

		if (!($model instanceof CustomerDiscount))
			$model = new CustomerDiscount;
		
		$options = $this->checkBoxHtmlOptions;
		$options['name'] = str_replace('CustomerDiscount', 'CustomerDiscount[' . $row . ']', CHtml::activeName($model, $this->forAttribute));
		
		$options['class'] = $this->id;

		echo CHtml::activeCheckBox($model, $this->forAttribute, $options);
	}
}

?>