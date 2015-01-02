<?php

namespace Somkiatprogrammer\Gateway;

abstract class AbstractGatewayTest extends \PHPUnit_Framework_TestCase {
	protected $clientId;
	protected $parameters;
	public function testSetId($value = array()) {
		$this->clientId = $value;
	}
	public function testSetParam($value = array()) {
		$this->parameters = $value;
	}
	abstract function testPurchase();
}