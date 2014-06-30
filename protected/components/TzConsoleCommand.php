<?php

class TzConsoleCommand extends CConsoleCommand
{
	public function printf()
	{
		echo call_user_func_array('sprintf', func_get_args()) . "\n";
	}
}