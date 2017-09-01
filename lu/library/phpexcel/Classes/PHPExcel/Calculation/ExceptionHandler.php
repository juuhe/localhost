<?php

class PHPExcel_Calculation_ExceptionHandler
{
	public function __construct()
	{
		set_error_handler(array('PHPExcel_Calculation_Exception', 'errorHandlerCallback'), 32767);
	}

	public function __destruct()
	{
		restore_error_handler();
	}
}


?>
