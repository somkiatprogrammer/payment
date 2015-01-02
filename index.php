<?php
require_once "config.ini.php";
require_once "db.php";
require_once "vendor/autoload.php";

use Somkiatprogrammer\Payment;

try {
	$message = '';
	if (isset ( $_POST ['card_type'] )) {
		if ($_POST ['card_type'] == 'amex' && $_POST ['currency'] != 'USD') {
			$message = '<div style="color:red">Amex card supports only USD currency.</div>';
		} elseif (($_POST ['currency'] == 'USD') || ($_POST ['currency'] == 'EUR') || ($_POST ['currency'] == 'AUD')) {
			$payment = new Somkiatprogrammer\Payment ( 'Paypal' );
			$payment->setId ( $config ['paypal'] );
		} else {
			$payment = new Somkiatprogrammer\Payment ( 'Braintree' );
			$payment->setId ( $config ['braintree'] [$_POST ['currency']] );
		}
		
		if (isset ( $payment )) {
			$result = $payment->setParam ( $_POST )->purchase ();
			if (isset ( $result ['success'] ) && (false !== $result ['success'])) {
				$message = '<div style="color:blue">Success.</div>';
				unset ( $result ['success'] );
				$db = new DB ( $config ['db'] ['server'], $config ['db'] ['username'], $config ['db'] ['password'], $config ['db'] ['dbname'] );
				if (false === ($result = $db->save ( $result ))) {
					$message = '<div style="color:red">' . $result . '</div>';
				}
			} else {
				$message = '<div style="color:red">Error.</div>';
			}
		}
	}
} catch ( Exception $e ) {
	$message = '<div style="color:red">' . $e->getMessage () . '</div>';
}

$card_lists = Somkiatprogrammer\Payment::getCardLists ();
$currency_lists = Somkiatprogrammer\Payment::getCurrencyLists ();

require_once "views/index.phtml";