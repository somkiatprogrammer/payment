<?php

namespace Somkiatprogrammer\Gateway;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\BillingInfo;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Invoice;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;

class Paypal extends AbstractGateway {
	public function purchase() {
		try {
			$apiContext = new ApiContext ( new OAuthTokenCredential ( $this->clientId ['client_id'], $this->clientId ['client_secret'] ) );
			
			$card_holder_name = explode ( ' ', $this->parameters ['card_holder_name'], 2 );
			$card_expire = explode ( '/', $this->parameters ['card_expire'], 2 );
			
			$card = new CreditCard ();
			$card->setNumber ( $this->parameters ['card_number'] );
			$card->setType ( $this->parameters ['card_type'] );
			$card->setExpire_month ( $card_expire [0] );
			$card->setExpire_year ( $card_expire [1] );
			$card->setCvv2 ( $this->parameters ['card_ccv'] );
			$card->setFirst_name ( $card_holder_name [0] );
			$card->setLast_name ( $card_holder_name [1] );
			
			$fi = new FundingInstrument ();
			$fi->setCredit_card ( $card );
			
			$payer = new Payer ();
			$payer->setPayment_method ( 'credit_card' );
			$payer->setFunding_instruments ( array (
					$fi 
			) );
			
			$amount = new Amount ();
			$amount->setCurrency ( $this->parameters ['currency'] );
			$amount->setTotal ( $this->parameters ['price'] );
			
			$transaction = new Transaction ();
			$transaction->setAmount ( $amount );
			
			$payment = new Payment ();
			$payment->setIntent ( 'sale' );
			$payment->setPayer ( $payer );
			$payment->setTransactions ( array (
					$transaction 
			) );
			
			$resultPayment = $payment->create ( $apiContext );
			$result = array ();
			$result ['success'] = false;
			
			if (isset ( $resultPayment->transactions [0]->related_resources [0]->sale->state )) {
				if ($resultPayment->transactions [0]->related_resources [0]->sale->state == 'completed') {
					$result ['success'] = true;
					$result ['tran_id'] = $resultPayment->transactions [0]->related_resources [0]->sale->id;
					$result ['amount'] = $resultPayment->transactions [0]->related_resources [0]->sale->amount->total;
					$result ['currency'] = $resultPayment->transactions [0]->related_resources [0]->sale->amount->currency;
					$result ['create_time'] = strtotime ( $resultPayment->transactions [0]->related_resources [0]->sale->create_time );
					$result ['shipping_name'] = $this->parameters ['firstname'] . ' ' . $this->parameters ['lastname'];
				}
			}
			
			return $result;
		} catch ( \Exception $e ) {
			throw new \Exception ( $e->getMessage () );
		}
	}
}
?>