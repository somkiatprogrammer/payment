<?php

namespace Somkiatprogrammer;

class PaymentTest extends \PHPUnit_Framework_TestCase {
	private $gateway;
	function __construct($gateway = 'PaypalTest') {
		$gateway = 'Somkiatprogrammer\\Gateway\\' . $gateway;
		if (class_exists ( $gateway )) {
			$this->gateway = new $gateway ();
			return $this->gateway;
		} else {
			throw new \Exception ( 'The gateway does not support.' );
		}
	}
	public static function testGetCardLists() {
		return array (
				'amex',
				'visa',
				'mastercard' 
		);
	}
	public static function testGetCurrencyLists() {
		return array (
				'USD',
				'EUR',
				'THB',
				'HKD',
				'SGD',
				'AUD' 
		);
	}
	public function testSetParam($value = array()) {
		$this->gateway->testSetParam ( $value );
		
		return $this->gateway;
	}
	public function testSetId($value = array()) {
		$this->gateway->testSetId ( $value );
		
		return $this->gateway;
	}
}

?>