<?php

namespace Somkiatprogrammer;

class Payment {
	private $gateway;
	function __construct($gateway) {
		$gateway = 'Somkiatprogrammer\\Gateway\\' . $gateway;
		if (class_exists ( $gateway )) {
			$this->gateway = new $gateway ();
			return $this->gateway;
		} else {
			throw new \Exception ( 'The gateway does not support.' );
		}
	}
	public static function getCardLists() {
		return array (
				'amex',
				'visa',
				'mastercard' 
		);
	}
	public static function getCurrencyLists() {
		return array (
				'USD',
				'EUR',
				'THB',
				'HKD',
				'SGD',
				'AUD' 
		);
	}
	public function setParam($value) {
		$this->gateway->setParam ( $value );
		
		return $this->gateway;
	}
	public function setId($value) {
		$this->gateway->setId ( $value );
		
		return $this->gateway;
	}
}

?>