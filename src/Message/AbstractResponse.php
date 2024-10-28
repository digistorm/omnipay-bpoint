<?php

declare(strict_types=1);

/**
 * Bpoint Response.
 */

namespace Omnipay\Bpoint\Message;

use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Bpoint Response.
 *
 * This is the response class for all Bpoint requests.
 *
 * @see \Omnipay\Bpoint\Gateway
 */
abstract class AbstractResponse extends CommonAbstractResponse
{
    public function __construct(RequestInterface $request, mixed $data)
    {
        parent::__construct($request, $data);

        $arrayData = json_decode((string) $data, true);

        if (is_array($arrayData)) {
            $this->data = $arrayData;
        } else {
            if (preg_match('/^<!doctype html/i', (string) $data) && preg_match('/<title>([^<]+)<\/title>/i', (string) $data, $matches)) {
                $errorString = $matches[1];
            } else {
                $errorString = substr((string) $data, 0, 255);
            }
            $this->data = [
                'ErrorString' => $errorString,
                'ErrorCode' => 500,
            ];
        }
    }

    /**
     * Is the transaction successful?
     */
    public function isSuccessful(): bool
    {
        if (!isset($this->data['APIResponse']) || !isset($this->data['APIResponse']['ResponseCode'])) {
            return false;
        }

        return $this->data['APIResponse']['ResponseCode'] == 0;
    }

    /**
     * Get a token, for createToken requests.
     */
    public function getToken(): ?string
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
     */
    public function getMessage(): ?string
    {
        if (!$this->isSuccessful() && isset($this->data['APIResponse']) && isset($this->data['APIResponse']['ResponseText'])) {
            return $this->data['APIResponse']['ResponseText'];
        }

        return $this->data['ErrorString'] ?? null;
    }

    /**
     * Get the error message from the response.
     *
     * Returns null if the request was successful.
     */
    public function getCode(): ?string
    {
        if (!$this->isSuccessful() && isset($this->data['APIResponse']) && isset($this->data['APIResponse']['ResponseCode'])) {
            return $this->data['APIResponse']['ResponseCode'];
        }

        return $this->data['ErrorCode'] ?? null;
    }
}
