<?php

namespace Somkiatprogrammer\Gateway;

class Braintree extends AbstractGateway {
	public function purchase() {
		try {
			include './vendor/braintree/braintree_php/lib/Braintree.php';
			
			\Braintree_Configuration::environment ( 'sandbox' );
			\Braintree_Configuration::merchantId ( $this->clientId ['merchant_id'] );
			\Braintree_Configuration::publicKey ( $this->clientId ['public_key'] );
			\Braintree_Configuration::privateKey ( $this->clientId ['private_key'] );
			
			$resultPayment = \Braintree_Transaction::sale ( array (
					'amount' => $this->parameters ['price'],
					'creditCard' => array (
							'number' => $this->parameters ['card_number'],
							'expirationDate' => $this->parameters ['card_expire'],
							'cardholderName' => $this->parameters ['card_holder_name'],
							'cvv' => $this->parameters ['card_ccv'] 
					),
					'customer' => array (
							'firstName' => $this->parameters ['firstname'],
							'lastName' => $this->parameters ['lastname'] 
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
				$result ['shipping_name'] = $this->parameters ['firstname'] . ' ' . $this->parameters ['lastname'];
				
				return $result;
			} else if ($resultPayment->transaction) {
				$message = "Error processing transaction: ";
				$message .= "code: " . $resultPayment->transaction->processorResponseCode;
				$message .= "text: " . $resultPayment->transaction->processorResponseText;
				throw new \Exception ( $message );
			} else {
				$errors = $resultPayment->errors->deepAll ();
				$message = '';
				foreach ( $errors as $error ) {
					$message .= $error->__get ( 'message' ) . "<br />";
				}
				throw new \Exception ( $message );
			}
		} catch ( \Exception $e ) {
			throw new \Exception ( $e->getMessage () );
		}
	}
}
?>