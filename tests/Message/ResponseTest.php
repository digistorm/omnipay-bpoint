<?php

namespace Omnipay\Bpoint\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new Response($this->getMockRequest(), (string) $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $this->markTestIncomplete('Need to get failure response');

        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new Response($this->getMockRequest(), (string) $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('ch_1IUAZQWFYrPooM', $response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('Your card was declined', $response->getMessage());
        $this->assertNull($response->getSource());
    }
}
