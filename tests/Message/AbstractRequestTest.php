<?php

namespace Omnipay\Bpoint\Message;

use Mockery;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = Mockery::mock('\Omnipay\Bpoint\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }

    public function testUsername()
    {
        $this->assertSame($this->request, $this->request->setUsername('abc123'));
        $this->assertSame('abc123', $this->request->getUsername());
    }

    public function testPassword()
    {
        $this->assertSame($this->request, $this->request->setPassword('abc123'));
        $this->assertSame('abc123', $this->request->getPassword());
    }

    public function testMerchantNumber()
    {
        $this->assertSame($this->request, $this->request->setMerchantNumber('abc123'));
        $this->assertSame('abc123', $this->request->getMerchantNumber());
    }
}
