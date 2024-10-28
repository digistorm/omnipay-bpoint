<?php

declare(strict_types=1);

namespace Omnipay\Bpoint\Traits;

use Omnipay\Bpoint\Message\AbstractRequest;

trait CommonParametersTrait
{
    public function getCrn1(): ?string
    {
        return $this->getParameter('crn1');
    }

    public function setCrn1(?string $value): AbstractRequest
    {
        return $this->setParameter('crn1', $value);
    }

    public function getCrn2(): ?string
    {
        return $this->getParameter('crn2');
    }

    public function setCrn2(?string $value): AbstractRequest
    {
        return $this->setParameter('crn2', $value);
    }

    public function getCrn3(): ?string
    {
        return $this->getParameter('crn3');
    }

    public function setCrn3(?string $value): AbstractRequest
    {
        return $this->setParameter('crn3', $value);
    }

    public function getBillerCode(): ?string
    {
        return $this->getParameter('billerCode');
    }

    public function setBillerCode(?string $value): AbstractRequest
    {
        return $this->setParameter('billerCode', $value);
    }
}
