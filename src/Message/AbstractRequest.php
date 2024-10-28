<?php

declare(strict_types=1);

/**
 * Bpoint Abstract Request.
 */

namespace Omnipay\Bpoint\Message;

use Money\Currency;
use Money\Money;
use Money\Number;
use Money\Parser\DecimalMoneyParser;
use Omnipay\Bpoint\Gateway;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

/**
 * Bpoint Abstract Request.
 *
 * This is the parent class for all Bpoint requests.
 *
 * Test modes:
 *
 * Bpoint accounts have test-mode API keys as well as live-mode
 * API keys. These keys can be active at the same time. Data
 * created with test-mode credentials will never hit the credit
 * card networks and will never cost anyone money.
 *
 * Unlike some gateways, there is no test mode endpoint separate
 * to the live mode endpoint, the Bpoint API endpoint is the same
 * for test and for live.
 *
 * Setting the testMode flag on this gateway has no effect.  To
 * use test mode just use your test mode API key.
 *
 * You can use any of the cards listed at https://bpoint.com/docs/testing
 * for testing.
 *
 * @see Gateway
 * @link https://bpoint.com/docs/api
 * @method getEndpoint()
 */
abstract class AbstractRequest extends CommonAbstractRequest
{
    /**
     * Live or Test Endpoint URL.
     */
    public function getEndpointBase(): ?string
    {
        return $this->getParameter('endpointBase');
    }

    public function setEndpointBase(?string $value): AbstractRequest
    {
        return $this->setParameter('endpointBase', $value);
    }

    public function getUsername(): ?string
    {
        return $this->getParameter('username');
    }

    public function setUsername(?string $value): AbstractRequest
    {
        return $this->setParameter('username', $value);
    }

    public function getMerchantNumber(): ?string
    {
        return $this->getParameter('merchantNumber');
    }

    public function setMerchantNumber(?string $value): AbstractRequest
    {
        return $this->setParameter('merchantNumber', $value);
    }

    public function getPassword(): ?string
    {
        return $this->getParameter('password');
    }

    public function setPassword(?string $value): AbstractRequest
    {
        return $this->setParameter('password', $value);
    }

    abstract protected function createResponse(mixed $data): ResponseInterface;

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function sendData(mixed $data): ResponseInterface
    {
        $authString = $this->getUsername() . '|' . $this->getMerchantNumber() . ':' . $this->getPassword();
        $headers = [
            'Authorization' => base64_encode($authString),
            'Content-Type' => 'application/json; charset=utf-8',
        ];
        $body = json_encode($data);
        $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers, $body ?: null);

        return $this->createResponse($httpResponse->getBody()->getContents());
    }

    /**
     * @throws InvalidRequestException
     */
    public function getMoney(string $parameterName = 'amount'): ?Money
    {
        $amount = $this->getParameter($parameterName);

        if ($amount instanceof Money) {
            return $amount;
        }

        if ($amount !== null) {
            $moneyParser = new DecimalMoneyParser($this->getCurrencies());
            $currencyCode = $this->getCurrency() ?: 'USD';
            $currency = new Currency($currencyCode);

            $number = Number::fromString($amount);

            // Check for rounding that may occur if too many significant decimal digits are supplied.
            $decimal_count = strlen($number->getFractionalPart());
            $subunit = $this->getCurrencies()->subunitFor($currency);
            if ($decimal_count > $subunit) {
                throw new InvalidRequestException('Amount precision is too high for currency.');
            }

            $money = $moneyParser->parse((string) $number, $currency);

            // Check for a negative amount.
            if (!$this->negativeAmountAllowed && $money->isNegative()) {
                throw new InvalidRequestException('A negative amount is not allowed.');
            }

            // Check for a zero amount.
            if (!$this->zeroAmountAllowed && $money->isZero()) {
                throw new InvalidRequestException('A zero amount is not allowed.');
            }

            return $money;
        }

        return null;
    }

    /**
     * Filter a string value, so it will not break the API request.
     */
    protected function filter(?string $string, int $maxLength = 50): string
    {
        return substr((string) preg_replace('/[^a-zA-Z0-9 \-]/', '', (string) $string), 0, $maxLength);
    }
}
