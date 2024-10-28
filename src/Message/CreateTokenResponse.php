<?php

declare(strict_types=1);

namespace Omnipay\Bpoint\Message;

class CreateTokenResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        if (!parent::isSuccessful()) {
            return false;
        }

        // Ensure the response contains a DV Token
        return (isset($this->data['DVTokenResp']['DVToken']) && $this->data['DVTokenResp']['DVToken']);
    }
}
