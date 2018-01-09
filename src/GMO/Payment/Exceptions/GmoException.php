<?php
namespace GMO\Payment\Exceptions;

use GMO\Payment\Consts;

class GmoException extends \Exception
{
    protected $info = null;
	
	public function __construct($error, Exception $previous = null){
		$this->info	= empty($error['info'])?null:$error['info'];
		$code		= empty($error['code'])?0:substr($error['code'], 1);
		$message = Consts::getErrorMessage($this->info);
		
		parent::__construct($message, $code, $previous);
	}
	
	final public function getInfo(){
		return $this->info;
	}
}
