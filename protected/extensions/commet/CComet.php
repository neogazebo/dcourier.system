<?php

class CComet extends CWidget
{
	private $_timeOutScript;
	public $url = array();
	public $timeOut = 3000; // 3 seconds
	public $success = 'alert("a")';
	public $type = 'post';
	public $data;

	public function init()
	{
		$token=array(Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken);
		if (is_null($this->data))
		{
			$this->data = CJavaScript::encode($token);
		}
		else if (is_array($this->data))
		{
			$this->data=array_merge($token, $this->data);
			$this->data=CJavaScript::encode($this->data);
		}
		else
		{
			$this->data =$this->data;
		}

		if (is_array($this->url))
		{
			$this->url = CHtml::normalizeUrl($this->url);
		}

		$asset = Yii::app()->assetManager->publish(__DIR__ . '//assets');
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile($asset . '/smartupdater.js', CClientScript::POS_END);
		$cs->registerScript($this->id . __CLASS__, $this->timeOutScript, CClientScript::POS_END);
	}

	public function getTimeOutScript($autoGenerate = true)
	{

		if ($this->_timeOutScript !== null)
			return $this->_timeOutScript;
		else if ($autoGenerate)
			return $this->_timeOutScript = <<<EOD
$("#{$this->id}").smartupdater({
    url : '{$this->url}',
		type:'{$this->type}',
		data: $this->data,
    minTimeout: {$this->timeOut}
    }, {$this->success}
);
EOD;
	}

	public function setTimeOutScript($value)
	{
		$this->_timeOutScript = $value;
	}

	public function run()
	{
		echo CHtml::openTag('div', array('id' => $this->id));
		echo CHtml::closeTag('div');
	}
}

?>