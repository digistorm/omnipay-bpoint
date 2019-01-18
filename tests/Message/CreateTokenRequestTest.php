<?php
/**
 * Created by PhpStorm.
 * User: pedro
 * Date: 18/06/17
 * Time: 21:30
 */

namespace Omnipay\Bpoint\Message;

use Omnipay\Tests\TestCase;

class CreateTokenRequestTest extends TestCase
{
    /**
     * @var CreateTokenRequest $request
     */
    private $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = new CreateTokenRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testEndpoint()
    {
        $this->assertSame('https://www.bpoint.com.au/webapi/v3/dvtokens', $this->request->getEndpoint());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage You must pass a 'card' parameter.
     */
    public function testGetDataInvalid()
    {
        $this->request->setCard(null);

        $this->request->getData();
    }

    public function testGetDataWithCard()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);

        $data = $this->request->getData();

        $expiryDate = sprintf('%02d', $card['expiryMonth']) . substr($card['expiryYear'], -2);
        $name = $card['firstName'] . ' ' . $card['lastName'];

        $this->assertSame($card['number'],  $data['DVTokenReq']['CardDetails']['CardNumber']);
        $this->assertSame($expiryDate,      $data['DVTokenReq']['CardDetails']['ExpiryDate']);
        $this->assertSame($card['cvv'],     $data['DVTokenReq']['CardDetails']['Cvn']);
        $this->assertSame($name,            $data['DVTokenReq']['CardDetails']['CardHolderName']);
    }

    public function testResponseFailure()
    {
        $this->markTestIncomplete('Need to get failure response');

        $this->setMockHttpResponse('CreateTokenFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());

        $this->assertNull($response->getTransactionReference());
    }

    public function testResponseSuccess()
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
