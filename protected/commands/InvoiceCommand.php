<?php

/**
 * Description of InvoiceCommand
 *
 * @author febri
 */
class InvoiceCommand extends CConsoleCommand
{
	public function __construct()
	{
	}
	
	public function actionIndex()
	{
		new self;
		print_r($this->getCustomers());
		echo "\n";
	}
	
	public function getCustomers()
	{
		return $customers_id = Customer::getCustomerId();
	}
}

?>
