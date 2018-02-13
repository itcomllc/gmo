<?php
namespace GMO\Payment\Exceptions;

use GMO\Payment\Consts;

class GmoException extends \Exception
{
    protected $info = null;
	
	public function __construct($error, \Exception $previous = null){
		$this->info	= empty($error['ErrInfo'])?null:$error['ErrInfo'];
		$code		= empty($error['ErrCode'])?0:substr($error['ErrCode'], 1);
		$message = Consts::getErrorMessage($this->info);
		
		parent::__construct($message, $code, $previous);
	}
	
	final public function getInfo(){
		return $this->info;
	}
}
