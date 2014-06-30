<?php
/**
 * Description of InvoiceForm
 *
 * @author febri
 */
class InvoiceForm extends CFormModel
{
	public $method;
	public $trans_month;
	public $to_create;
	public $cid;
	public $trans_id;
	public $listcustom = array('all' => 'All Transaction','not_all' => 'By Month','custom' => 'Custom');
	
	public function rules()
	{
		return array(
			array('method','required'),
			array('trans_month','requiredOnByMonth'),
			array('to_create','hasTransaction'),
			array('cid,trans_id','safe')
		);
	}
	
	public function requiredOnByMonth($attribute,$param)
	{
		if(!$this->hasErrors())
		{
			if($this->method == 'not_all' && ($this->trans_month == '' || empty($this->trans_month)))
				$this->addError ('trans_month', 'Bulan Transaksi Belum dipilih');
		}
	}
	
	public function hasTransaction($attribute,$param)
	{
		if(!$this->hasErrors())
		{
			if($this->method == 'all')
				$hasTr = Transaction::model()->findAll('customer_id=:cid and invoice_id is null',array(':cid' => $this->cid));
			else if($this->method == 'not_all')
				$hasTr = Transaction::model()->findAll('customer_id=:cid and invoice_id is null and MONTH(FROM_UNIXTIME(created)) = :month',array(':cid' => $this->cid,':month' => $this->trans_month));
			else if($this->method == 'custom')
				$hasTr = $this->trans_id;
			if(count($hasTr) == 0)
				$this->addError ('', 'Invoice cannot created because there\'s no transaction available ');
		}
	}
	
	public function listMonth()
	{
		$list = array();
		for($i = 1;$i <= 12 ;$i++)
		{
			$list[$i] = date('F',  mktime(0, 0, 0, $i+1, 0, 0, 0));
		}
		return $list;
	}
}

?>
