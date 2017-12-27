<?php
namespace GMO\Payment\Exceptions;

use GMO\Payment\Consts;

class GmoException extends \Exception
{
    protected $info = null;
	protected $result = null;
	
	public function __construct($result, Exception $previous = null){
		$this->result = $result;
		
		$this->info	= empty($result['ErrInfo'])?null:$result['ErrInfo'];
		$code		= empty($result['ErrCode'])?0:substr($result['ErrCode'], 1);
		$message = Consts::getErrorMessage($this->info);
		
		parent::__construct($message, $code, $previous);
	}
	
	final public function getInfo(){
		return $this->info;
	}
	
	final public function getResult(){
		return $this->result;
	}
}
