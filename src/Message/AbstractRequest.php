<?php

/**
 * Bpoint Abstract Request.
 */

namespace Omnipay\Bpoint\Message;
use Money\Currency;
use Money\Money;
use Money\Number;
use Money\Parser\DecimalMoneyParser;
use Omnipay\Common\Exception\InvalidRequestException;

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
 * @see \Omnipay\Bpoint\Gateway
 * @link https://bpoint.com/docs/api
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Live or Test Endpoint URL.
     */
    public function getEndpointBase()
    {
        return $this->getParameter('endpointBase');
    }

    public function setEndpointBase($value)
    {
        return $this->setParameter('endpointBase', $value);
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * @param $value
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * @return string
     */
    public function getMerchantNumber()
    {
        return $this->getParameter('merchantNumber');
    }

    /**
     * @param $value
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setMerchantNumber($value)
    {
        return $this->setParameter('merchantNumber', $value);
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * @param $value
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    abstract protected function createResponse($data);

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $authString = $this->getUsername() . '|' . $this->getMerchantNumber() . ':' . $this->getPassword();
        $headers = [
            'Authorization' => base64_encode($authString),
            'Content-Type' => 'application/json; charset=utf-8',
        ];
        $body = json_encode($data);
        $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers, $body);

        return $this->createResponse($httpResponse->getBody()->getContents());
    }

    /**
     * @param string $parameterName
     *
     * @return mixed|\Money\Money|null
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getMoney($parameterName = 'amount')
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

            $money = $moneyParser->parse((string) $number, $currency->getCode());

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
    }

    /**
     * Filter a string value so it will not break the API request.
     *
     * @param     $string
     * @param int $maxLength
     *
     * @return bool|string
     */
    protected function filter($string, $maxLength = 50)
    {
        return substr(preg_replace('/[^a-zA-Z0-9 ]/', '', $string), 0, $maxLength);
    }
}
