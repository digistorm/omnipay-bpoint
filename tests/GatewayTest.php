<?php

namespace Omnipay\Bpoint;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\GatewayTestCase;

/**
 * @property Gateway gateway
 */
class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setTestMode(true);
    }

    /**
     *
     */
    public function testCreateToken()
    {
        $request = $this->gateway->createToken([
            'card' => new CreditCard([
                'firstName' => 'John',
                'lastName' => 'Doe',
                'number' => '424242424242',
                'expiryMonth' => '03',
                'expiryYear' => '2020',
                'cvv' => '123',
            ]),
            'crn1' => '12345',
            'crn2' => '',
            'crn3' => null,
        ]);

        $this->assertInstanceOf('Omnipay\Bpoint\Message\CreateTokenRequest', $request);
        $data = $request->getData();

        $expectedCardDetails = [
            'CardHolderName' => 'John Doe',
            'CardNumber' => '424242424242',
            'Cvn' => '123',
            'ExpiryDate' => '0320',
        ];

        $this->assertEquals(true,                   $data['DVTokenReq']['TestMode']);
        $this->assertEquals(null,                   $data['DVTokenReq']['BankAccountDetails']);
        $this->assertEquals($expectedCardDetails,   $data['DVTokenReq']['CardDetails']);
        $this->assertEquals(true,                   $data['DVTokenReq']['AcceptBADirectDebitTC']);
        $this->assertEquals(null,                   $data['DVTokenReq']['EmailAddress']);
        $this->assertEquals('12345',                $data['DVTokenReq']['Crn1']);
        $this->assertEquals('',                     $data['DVTokenReq']['Crn2']);
        $this->assertEquals(null,                   $data['DVTokenReq']['Crn3']);
    }

    /**
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testPurchase()
    {
        $request = $this->gateway->purchase([
            'amount' => '10.00',
            'currency' => 'AUD',
            'description' => 'Here is a description that is over 50 characters long. It will get truncated to 50 characters.',
            'card' => new CreditCard([
                'firstName' => 'John',
                'lastName' => 'Doe',
                'number' => '5999999789012346',
                'expiryMonth' => '03',
                'expiryYear' => '2020',
                'cvv' => '123',
            ]),
            'crn1' => '12345',
            'crn2' => '',
            'crn3' => null,
        ]);

        $this->assertInstanceOf('Omnipay\Bpoint\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());

        $data = $request->getData();

        $expectedCardDetails = [
            'CardHolderName' => 'John Doe',
            'CardNumber' => '5999999789012346',
            'Cvn' => '123',
            'ExpiryDate' => '0320',
        ];

        $this->assertEquals(true, $data['TxnReq']['TestMode']);
        $this->assertEquals('payment', $data['TxnReq']['Action']);
        $this->assertEquals(1000, $data['TxnReq']['Amount']);
        $this->assertEquals(null, $data['TxnReq']['AmountOriginal']);
        $this->assertEquals(null, $data['TxnReq']['AmountSurcharge']);
        $this->assertEquals($expectedCardDetails,   $data['TxnReq']['CardDetails']);
        $this->assertEquals('AUD', $data['TxnReq']['Currency']);
        $this->assertEquals(null, $data['TxnReq']['Customer']);
        $this->assertEquals('Here is a description that is over 50 characters l', $data['TxnReq']['MerchantReference']);
        $this->assertEquals(null, $data['TxnReq']['Order']);
        $this->assertEquals(null, $data['TxnReq']['OriginalTxnNumber']);
        $this->assertEquals(null, $data['TxnReq']['StoreCard']);
        $this->assertEquals('single', $data['TxnReq']['SubType']);
        $this->assertEquals(null, $data['TxnReq']['TokenisationMode']);
        $this->assertEquals('internet', $data['TxnReq']['Type']);
        $this->assertEquals(null, $data['TxnReq']['FraudScreeningRequest']);
        $this->assertEquals(null, $data['TxnReq']['StatementDescriptor']);

    }
}
