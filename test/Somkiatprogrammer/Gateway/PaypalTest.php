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

class PaypalTest extends AbstractGatewayTest {
	public function testPurchase() {
		try {
			$apiContext = new ApiContext ( new OAuthTokenCredential ( '1234', '1234' ) );
			
			$card_holder_name = explode ( ' ', 'test test', 2 );
			$card_expire = explode ( '/', '12/2020', 2 );
			
			$card = new CreditCard ();
			$card->setNumber ( '4444333322221111' );
			$card->setType ( 'visa' );
			$card->setExpire_month ( $card_expire [0] );
			$card->setExpire_year ( $card_expire [1] );
			$card->setCvv2 ( '123' );
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
			$amount->setCurrency ( 'USD' );
			$amount->setTotal ( '10.00' );
			
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
					$result ['shipping_name'] = 'test' . ' ' . 'test';
				}
			}
			
			return $result;
		} catch ( \Exception $e ) {
			return $e->getMessage ();
		}
	}
}
?>