<?php

declare(strict_types=1);

namespace Omnipay\Bpoint\Message;

use JetBrains\PhpStorm\NoReturn;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            ['amount' => '10.00', 'currency' => 'USD', 'card' => $this->getValidCard()]
        );
    }

    public function testSendSuccess(): void
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getMessage());
    }

    #[NoReturn]
    public function testSendError(): void
    {
        $this->markTestIncomplete('Need to get failure response');

        $this->setMockHttpResponse('PurchaseFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('ch_1IUAZQWFYrPooM', $response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('Your card was declined', $response->getMessage());
    }

    public function testBillerCode(): void
    {
        $this->assertSame($this->request, $this->request->setBillerCode('abc123'));
        $this->assertSame('abc123', $this->request->getBillerCode());
    }
}
