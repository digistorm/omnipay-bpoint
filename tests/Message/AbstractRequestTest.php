<?php

declare(strict_types=1);

namespace Omnipay\Bpoint\Message;

use Mockery;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    public $request;

    public function setUp(): void
    {
        $this->request = Mockery::mock(AbstractRequest::class)->makePartial();
        $this->request->initialize();
    }

    public function testUsername(): void
    {
        $this->assertSame($this->request, $this->request->setUsername('abc123'));
        $this->assertSame('abc123', $this->request->getUsername());
    }

    public function testPassword(): void
    {
        $this->assertSame($this->request, $this->request->setPassword('abc123'));
        $this->assertSame('abc123', $this->request->getPassword());
    }

    public function testMerchantNumber(): void
    {
        $this->assertSame($this->request, $this->request->setMerchantNumber('abc123'));
        $this->assertSame('abc123', $this->request->getMerchantNumber());
    }

    public function testFilterPreservesAllowedSpecialCharacters(): void
    {
        $input = 'Enrolment Application F (210-49202-6420---210|GST)';
        $method = new \ReflectionMethod(AbstractRequest::class, 'filter');
        $method->setAccessible(true);
        $result = $method->invoke($this->request, $input);
        $this->assertSame($input, $result);
    }
}
