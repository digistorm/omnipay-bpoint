<?php

namespace Omnipay\Bpoint\Message;

class CreateTokenResponse extends AbstractResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        if (!parent::isSuccessful()) {
            return false;
        }

        // Ensure the response contains a DV Token
        return (isset($this->data['DVTokenResp']['DVToken']) && $this->data['DVTokenResp']['DVToken']);
    }
}