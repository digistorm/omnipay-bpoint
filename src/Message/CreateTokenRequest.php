<?php

declare(strict_types=1);

namespace Omnipay\Bpoint\Message;

use Omnipay\Bpoint\Traits\CommonParametersTrait;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;

class CreateTokenRequest extends AbstractRequest
{
    use CommonParametersTrait;

    /**
     * Get request data array to create a token.
     * @throws InvalidRequestException
     * @throws InvalidCreditCardException
     */
    public function getData(): array
    {
        $data = [];

        if (!$this->getParameter('card')) {
            throw new InvalidRequestException('You must pass a "card" parameter.');
        }

        /* @var $card \OmniPay\Common\CreditCard */
        $card = $this->getParameter('card');
        $card->validate();

        $payload = [
            'TestMode' => $this->getTestMode(),
        ];

        $payload['BankAccountDetails'] = null;
        $payload['CardDetails'] = [
            'CardHolderName' => $card->getBillingName(),
            'CardNumber' => $card->getNumber(),
            'Cvn' => $card->getCvv(),
            'ExpiryDate' => $card->getExpiryDate('my'),
        ];
        $payload['AcceptBADirectDebitTC'] = true;
        $payload['EmailAddress'] = null;
        $payload['Crn1'] = $this->getCrn1();
        $payload['Crn2'] = $this->getCrn2();
        $payload['Crn3'] = $this->getCrn3();

        if ($this->getBillerCode()) {
            $payload['BillerCode'] = $this->filter($this->getBillerCode());
        }

        $data['DVTokenReq'] = $payload;

        return $data;
    }

    public function getEndpoint(): string
    {
        return parent::getEndpointBase() . '/dvtokens';
    }

    protected function createResponse(mixed $data): CreateTokenResponse
    {
        return $this->response = new CreateTokenResponse($this, $data);
    }
}
