<?php

namespace Somkiatprogrammer\Gateway;

abstract class AbstractGateway {
	protected $clientId;
	protected $parameters;
	public function setId($value) {
		$this->clientId = $value;
	}
	public function setParam($value) {
		$this->parameters = $value;
	}
	abstract function purchase();
}