<?php

/**
 * Bpoint Response.
 */
namespace Omnipay\Bpoint\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Bpoint Response.
 *
 * This is the response class for all Bpoint requests.
 *
 * @see \Omnipay\Bpoint\Gateway
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->data = json_decode($this->data, true);
    }

    /**
     * Is the transaction successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        if (!isset($this->data['APIResponse']) || !isset($this->data['APIResponse']['ResponseCode'])) {
            return false;
        }

        return $this->data['APIResponse']['ResponseCode'] === 0;
    }

    /**
     * Get a token, for createCard requests.
     *
     * @return string|null
     */
    public function getToken()
    {
        if (!isset($this->data['DVTokenResp']) || !isset($this->data['DVTokenResp']['DVToken'])) {
            return null;
        }

        return $this->data['DVTokenResp']['DVToken'];
    }

    /**
     * Get the error message from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getMessage()
    {
        if (!$this->isSuccessful() && isset($this->data['APIResponse']) && isset($this->data['APIResponse']['ResponseText'])) {
            return $this->data['APIResponse']['ResponseText'];
        }

        return null;
    }

    /**
     * Get the error message from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getCode()
    {
        if (!$this->isSuccessful() && isset($this->data['APIResponse']) && isset($this->data['APIResponse']['ResponseCode'])) {
            return $this->data['APIResponse']['ResponseCode'];
        }

        return null;
    }
}
