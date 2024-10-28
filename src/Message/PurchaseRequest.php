<?php

declare(strict_types=1);

namespace Omnipay\Bpoint\Message;

use Omnipay\Bpoint\Traits\CommonParametersTrait;
use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Bpoint Purchase Request.
 */
class PurchaseRequest extends AbstractRequest
{
    use CommonParametersTrait;

    /**
     * Get request data array to process a purchase.
     * @throws InvalidCreditCardException
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('amount', 'currency');

        if (!$this->getParameter('card')) {
            throw new InvalidRequestException('You must pass a "card" parameter.');
        }

        /* @var $card CreditCard */
        $card = $this->getParameter('card');
        $card->validate();

        $data = [];

        $payload = [
            'TestMode' => $this->getTestMode(),
        ];

        $payload['Action'] = 'payment';
        $payload['Amount'] = $this->getAmountInteger();
        if ($this->getAmountSurcharge()) {
            $payload['AmountSurcharge'] = $this->getAmountSurcharge();
        }
        $payload['Currency'] = $this->getCurrency();
        if ($this->getDescription()) {
            $payload['MerchantReference'] = $this->filter($this->getDescription());
        }
        $payload['Crn1'] = $this->filter($this->getCrn1());
        if ($this->getCrn2()) {
            $payload['Crn2'] = $this->filter($this->getCrn2());
        }
        if ($this->getCrn3()) {
            $payload['Crn3'] = $this->filter($this->getCrn3());
        }
        $payload['StoreCard'] = false;
        $payload['SubType'] = 'single';
        $payload['Type'] = 'internet';
        $payload['CardDetails'] = [
            'CardHolderName' => $card->getBillingName(),
            'CardNumber' => $card->getNumber(),
            'Cvn' => $card->getCvv(),
            'ExpiryDate' => $card->getExpiryDate('my'),
        ];

        if ($this->getBillerCode()) {
            $payload['BillerCode'] = $this->filter($this->getBillerCode());
        }

        $data['TxnReq'] = $payload;

        return $data;
    }

    public function getEndpoint(): string
    {
        return parent::getEndpointBase() . '/txns';
    }

    public function getAmountSurcharge(): ?string
    {
        return $this->getParameter('amountSurcharge');
    }

    public function setAmountSurcharge(string $value): AbstractRequest
    {
        return $this->setParameter('amountSurcharge', $value);
    }

    protected function createResponse(mixed $data): PurchaseResponse
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
