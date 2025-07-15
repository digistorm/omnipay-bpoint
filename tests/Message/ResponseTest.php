<?php

declare(strict_types=1);

namespace Omnipay\Bpoint\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseSuccess(): void
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new PurchaseResponse($this->getMockRequest(), (string) $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseFailure(): void
    {
        // This failure response is incomplete, currently mocked with the data we have
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new CreateTokenResponse($this->getMockRequest(), (string) $httpResponse->getBody());

        $code = $response->getCode();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals($code, 1);
    }
}
