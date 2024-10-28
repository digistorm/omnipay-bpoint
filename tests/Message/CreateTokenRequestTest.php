<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 18/06/17
 * Time: 21:30
 */

namespace Omnipay\Bpoint\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class CreateTokenRequestTest extends TestCase
{
    private CreateTokenRequest $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new CreateTokenRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setEndpointBase('http://example.com');
    }

    public function testEndpoint(): void
    {
        $this->assertSame('http://example.com/dvtokens', $this->request->getEndpoint());
    }

    public function testGetDataInvalid(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('You must pass a "card" parameter.');
        $this->request->setCard(null);

        $this->request->getData();
    }

    public function testGetDataWithCard(): void
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);

        $data = $this->request->getData();

        $expiryDate = sprintf('%02d', $card['expiryMonth']) . substr((string) $card['expiryYear'], -2);
        $name = $card['firstName'] . ' ' . $card['lastName'];

        $this->assertSame($card['number'], $data['DVTokenReq']['CardDetails']['CardNumber']);
        $this->assertSame($expiryDate, $data['DVTokenReq']['CardDetails']['ExpiryDate']);
        $this->assertSame($card['cvv'], $data['DVTokenReq']['CardDetails']['Cvn']);
        $this->assertSame($name, $data['DVTokenReq']['CardDetails']['CardHolderName']);
    }

    public function testResponseFailure(): void
    {
        $this->markTestIncomplete('Need to get failure response');

        $this->setMockHttpResponse('CreateTokenFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());

        $this->assertNull($response->getTransactionReference());
    }

    public function testResponseSuccess(): void
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);

        $this->setMockHttpResponse('CreateTokenSuccess.txt');
        $response = $this->request->send();

        $data = $response->getData();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());

        $this->assertSame('5999999789012346', $data['DVTokenResp']['DVToken']);
    }
}
