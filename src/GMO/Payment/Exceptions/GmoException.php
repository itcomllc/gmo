<?php
namespace GMO\Payment\Exceptions;

use GMO\Payment\Consts;

class GmoException extends \Exception
{
    protected $info = null;
	
	public function __construct($error, \Exception $previous = null){
		$this->info	= empty($error['ErrInfo'])?null:$error['ErrInfo'];
		$code		= preg_replace('/[^0-9]/', '', $error['ErrCode']);
		$code		= empty($code)?0:$code;
		$message = Consts::getErrorMessage($this->info);
		
		parent::__construct($message, $code, $previous);
	}
	
	final public function getInfo(){
		return $this->info;
	}
}
