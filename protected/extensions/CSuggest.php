<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CSuggest
 *
 * @author Cahyo - Technolyze
 */
class CSuggest extends CWidget
{
	public $value;
	public $returnId = true;
	public $model;
	public $attribute;
	public $source = '';
	public $sourceUrl;
	public $options = array();
	public $htmlOptions = array();
	public $autoCompleteHtmlOptions = array();
	public $form;

	public function init()
	{
		$this->options = array_merge($this->options, array(
			'autoFocus' => true,
			'showAnim' => 'fold',
				));

		$this->autoCompleteHtmlOptions = array_merge($this->autoCompleteHtmlOptions, array(
			'name' => $this->returnId ? str_replace('[', '_disabled[', CHtml::activeName($this->model, $this->attribute)) : CHtml::activeName($this->model, $this->attribute),
			'id' => CHtml::activeId($this->model, $this->attribute),
			'value' => $this->value,
				));
		if ($this->returnId)
		{
			$this->options = array_merge($this->options, array(
				'select' => "js:function(event,ui){
								$('input#'+$(this).attr('id')+'[type=hidden]').attr('value',ui.item.id);
								return true;
						}"
					));
		}
	}

	public function run()
	{
		$model = $this->model;
		$attribute = $this->attribute;
		$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
			'model' => $this->model,
			'attribute' => $this->attribute,
			$this->sourceUrl ? 'sourceUrl' : 'source' =>
			$this->sourceUrl ? $this->sourceUrl : $this->source,
			'options' => $this->options,
			'htmlOptions' => $this->autoCompleteHtmlOptions,
		));
		if ($this->returnId)
			echo $this->form->hiddenField($this->model, $this->attribute, $this->htmlOptions);
	}

	public static function renderCallBack($from, $select, $criteria = array())
	{
		$suggest = new self;
		if (preg_match('{(\w+)}', $select))
			$suggest->getSuggest($from, $select, $criteria);
	}

	public function getSuggest($from, $select, $criteria)
	{
		$string = str_replace(array('{', '}'), array('', $delimiter = '_____'), $select);
		$arrayselect = explode($delimiter, $string);
		$this->renderJSON($from, $arrayselect, $criteria);
	}

	public function renderJSON($from, $arrayselect, $criteria)
	{
		if (is_array($arrayselect))
		{
			$return = array();
			$select = substr_replace(implode(', ', $arrayselect), '', -2);
			$select1 = $arrayselect[0];
			$qtxt = "SELECT {$select} FROM {$from} WHERE {$select1} LIKE :{$select1}";
			$wheres = array();
			if ($criteria != array())
			{
				foreach ($criteria as $parcol => $value)
				{
					foreach ($value as $parenttbl => $vcol)
					{
						foreach ($vcol as $col => $val)
						{
							
						}
					}
				}
				$pcommand = Yii::app()->db->createCommand()
						->select("id")
						->from($parenttbl)
						->where(array('and', "$col=:$col"), array(":$col" => $val))
						->queryAll();
				foreach ($pcommand as $key => $b)
				{
					foreach ($b as $c => $d)
					{
						$wheres[] = " AND $parcol=$d";
					}
				}
				if ($wheres != array())
					$qtxt.=implode(' ', $wheres);
			}
			$command = Yii::app()->db->createCommand($qtxt);
			$command->bindValue(":{$select1}", '' . $_GET['term'] . '%', PDO::PARAM_STR);
			$res = $command->queryAll();
			foreach ($res as $v)
			{
				if (isset($v['type']))
				{
					$v[$select1] = uc_first($v['type']) . "." . $v[$select1];
				}
				$v['value'] = $v[$select1];
				$return[] = $v;
			}
			echo CJSON::encode($return);
			Yii::app()->end();
		}
		else
			return false;
	}
}

?>
