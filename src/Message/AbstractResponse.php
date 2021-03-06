<?php

/**
 * Bpoint Response.
 */
namespace Omnipay\Bpoint\Message;

use Omnipay\Common\Message\RequestInterface;

/**
 * Bpoint Response.
 *
 * This is the response class for all Bpoint requests.
 *
 * @see \Omnipay\Bpoint\Gateway
 */
abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $arrayData = json_decode($data, true);

        if (is_array($arrayData)) {
            $this->data = $arrayData;
        } else {
            if (preg_match('/^\<!doctype html/i', $data) && preg_match('/\<title>([^<]+)\<\/title>/i', $data, $matches)) {
                $errorString = $matches[1];
            } else {
                $errorString = substr($data, 0, 255);
            }
            $this->data = [
                'ErrorString' => $errorString,
                'ErrorCode' => 500,
            ];
        }
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

        return $this->data['APIResponse']['ResponseCode'] == 0;
    }

    /**
     * Get a token, for createToken requests.
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

        if (isset($this->data['ErrorString'])) {
            return $this->data['ErrorString'];
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

        if (isset($this->data['ErrorCode'])) {
            return $this->data['ErrorCode'];
        }

        return null;
    }
}
