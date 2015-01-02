<?php

namespace Somkiatprogrammer\Gateway;

class BraintreeTest extends AbstractGatewayTest {
	public function testPurchase() {
		try {
			include './vendor/braintree/braintree_php/lib/Braintree.php';
			
			\Braintree_Configuration::environment ( 'sandbox' );
			\Braintree_Configuration::merchantId ( '1234' );
			\Braintree_Configuration::publicKey ( '1234' );
			\Braintree_Configuration::privateKey ( '1234' );
			
			$resultPayment = \Braintree_Transaction::sale ( array (
					'amount' => '10.00',
					'creditCard' => array (
							'number' => '4444333322221111',
							'expirationDate' => '12/2020',
							'cardholderName' => 'test test',
							'cvv' => '123' 
					),
					'customer' => array (
							'firstName' => 'test',
							'lastName' => 'test' 
					) 
			) );
			
			$result = array ();
			$result ['success'] = false;
			
			if ($resultPayment->success) {
				$result ['success'] = true;
				$result ['tran_id'] = $resultPayment->transaction->id;
				$result ['amount'] = $resultPayment->transaction->amount;
				$result ['currency'] = $resultPayment->transaction->currencyIsoCode;
				$result ['create_time'] = $resultPayment->transaction->createdAt->getTimestamp ();
				$result ['shipping_name'] = 'test' . ' ' . 'test';
				
				return $result;
			} else if ($resultPayment->transaction) {
				$message = "Error processing transaction: ";
				$message .= "code: " . $result->transaction->processorResponseCode;
				$message .= "text: " . $result->transaction->processorResponseText;
				return $message;
			} else {
				$errors = $resultPayment->errors->deepAll ();
				$message = '';
				foreach ( $errors as $error ) {
					$message .= $error->__get ( 'message' ) . "<br />";
				}
				return $message;
			}
		} catch ( \Exception $e ) {
			return $e->getMessage ();
		}
	}
}
?>