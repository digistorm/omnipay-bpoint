<?php


namespace Omnipay\Bpoint\Message;

use Omnipay\Bpoint\Traits\CommonParametersTrait;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 *
 */
class CreateTokenRequest extends AbstractRequest
{
    use CommonParametersTrait;

    /**
     * Get request data array to create a token.
     *
     * @return array
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function getData()
    {
        $data = [];

        if (!$this->getParameter('card')) {
            throw new InvalidRequestException("You must pass a 'card' parameter.");
        }

        /* @var $card \OmniPay\Common\CreditCard */
        $card = $this->getParameter('card');
        $card->validate();

        $payload = [
            'TestMode' => $this->getTestMode(),
        ];

        $payload["BankAccountDetails"] = null;
        $payload["CardDetails"] = [
            "CardHolderName" => $card->getBillingName(),
            "CardNumber" => $card->getNumber(),
            "Cvn" => $card->getCvv(),
            "ExpiryDate" => $card->getExpiryDate('my'),
        ];
        $payload["AcceptBADirectDebitTC"] = true;
        $payload["EmailAddress"] = null;
        $payload["Crn1"] = $this->getCrn1();
        $payload["Crn2"] = $this->getCrn2();
        $payload["Crn3"] = $this->getCrn3();

        $data["DVTokenReq"] = $payload;

        return $data;
    }

    /**
     * @inheritdoc
     *
     * @return string The endpoint for the create token request.
     */
    public function getEndpoint()
    {
        return $this->endpoint . '/dvtokens';
    }
}
