<?php

namespace Omnipay\Bpoint\Message;

use Omnipay\Bpoint\Traits\CommonParametersTrait;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Bpoint Purchase Request.
 */
class PurchaseRequest extends AbstractRequest
{
    use CommonParametersTrait;

    /**
     * Get request data array to process a purchase.
     *
     * @return array|mixed
     *
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('amount', 'currency');

        if (!$this->getParameter('card')) {
            throw new InvalidRequestException('You must pass a "card" parameter.');
        }

        /* @var $card \OmniPay\Common\CreditCard */
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

        // Currently unsupported optional params
//        $payload['AmountOriginal'] = null;
//        $payload['Customer'] = null;
//        $payload['EmailAddress'] = null;
//        $payload['FraudScreeningRequest'] = null;
//        $payload['Order'] = null;
//        $payload['OriginalTxnNumber'] = null;
//        $payload['StatementDescriptor'] = null;
//        $payload['TokenisationMode'] = null;

        $data['TxnReq'] = $payload;

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return parent::getEndpointBase() . '/txns';
    }

    /**
     * @return integer
     */
    public function getAmountSurcharge()
    {
        return $this->getParameter('amountSurcharge');
    }

    /**
     * @param string $value
     *
     * @return \Omnipay\Bpoint\Message\AbstractRequest provides a fluent interface.
     */
    public function setAmountSurcharge($value)
    {
        return $this->setParameter('amountSurcharge', $value);
    }

    /**
     * @param       $data
     *
     * @return \Omnipay\Bpoint\Message\PurchaseResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
