<?php

declare(strict_types=1);

namespace Omnipay\Bpoint;

use Carbon\Carbon;
use Omnipay\Bpoint\Message\CreateTokenRequest;
use Omnipay\Bpoint\Message\PurchaseRequest;
use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\GatewayTestCase;

/**
 * @property Gateway gateway
 */
class GatewayTest extends GatewayTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setTestMode(true);
    }

    public function testCreateToken(): void
    {
        $year = Carbon::now()->addYear();
        $request = $this->gateway->createToken([
            'card' => new CreditCard([
                'firstName' => 'John',
                'lastName' => 'Doe',
                'number' => '424242424242',
                'expiryMonth' => '03',
                'expiryYear' => $year->format('Y'),
                'cvv' => '123',
            ]),
            'crn1' => '12345',
            'crn2' => '',
            'crn3' => null,
        ]);

        $this->assertInstanceOf(CreateTokenRequest::class, $request);
        $data = $request->getData();

        $expectedCardDetails = [
            'CardHolderName' => 'John Doe',
            'CardNumber' => '424242424242',
            'Cvn' => '123',
            'ExpiryDate' => '03' . $year->format('y'),
        ];

        $this->assertEquals(true, $data['DVTokenReq']['TestMode']);
        $this->assertEquals(null, $data['DVTokenReq']['BankAccountDetails']);
        $this->assertEquals($expectedCardDetails, $data['DVTokenReq']['CardDetails']);
        $this->assertEquals(true, $data['DVTokenReq']['AcceptBADirectDebitTC']);
        $this->assertEquals(null, $data['DVTokenReq']['EmailAddress']);
        $this->assertEquals('12345', $data['DVTokenReq']['Crn1']);
        $this->assertEquals('', $data['DVTokenReq']['Crn2']);
        $this->assertEquals(null, $data['DVTokenReq']['Crn3']);
    }

    /**
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function testPurchase(): void
    {
        $year = Carbon::now()->addYear();
        $request = $this->gateway->purchase([
            'amount' => '10.00',
            'currency' => 'AUD',
            'description' => 'Here is a description that is over 50 characters long. It will get truncated to 50 characters.',
            'card' => new CreditCard([
                'firstName' => 'John',
                'lastName' => 'Doe',
                'number' => '5999999789012346',
                'expiryMonth' => '03',
                'expiryYear' => $year->format('Y'),
                'cvv' => '123',
            ]),
            'crn1' => '12345',
            'crn2' => '',
            'crn3' => null,
        ]);

        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());

        $data = $request->getData();

        $expectedCardDetails = [
            'CardHolderName' => 'John Doe',
            'CardNumber' => '5999999789012346',
            'Cvn' => '123',
            'ExpiryDate' => '03' . $year->format('y'),
        ];

        $this->assertEquals(true, $data['TxnReq']['TestMode']);
        $this->assertEquals('payment', $data['TxnReq']['Action']);
        $this->assertEquals(1000, $data['TxnReq']['Amount']);
        $this->assertEquals($expectedCardDetails, $data['TxnReq']['CardDetails']);
        $this->assertEquals('AUD', $data['TxnReq']['Currency']);
        $this->assertEquals('Here is a description that is over 50 characters l', $data['TxnReq']['MerchantReference']);
        $this->assertEquals(null, $data['TxnReq']['StoreCard']);
        $this->assertEquals('single', $data['TxnReq']['SubType']);
        $this->assertEquals('internet', $data['TxnReq']['Type']);
    }
}
