<?php

declare(strict_types=1);

/**
 * Bpoint Gateway.
 */

namespace Omnipay\Bpoint;

use Omnipay\Bpoint\Message\CreateTokenRequest;
use Omnipay\Bpoint\Message\PurchaseRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;

/**
 * Bpoint Gateway.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the Bpoint Gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('Bpoint');
 *   $gateway->setUsername('usernameValue');
 *   $gateway->setPassword('passwordValue');
 *   $gateway->setMerchantId('merchantIdValue');
 *
 *   // Tokenize a card
 *   $response = $gateway->createToken([
 *       'card' => new CreditCard([...]),
 *       'crn1' => '12345',
 *       'crn2' => '',
 *       'crn3' => null,
 *   ])->send();
 *
 *   // Charge using a token
 *   $gateway->purchase([
 *       'card' => new CreditCard([
 *           'number' => $response->getToken(),
 *           ...
 *       ]),
 *       'amount' => '50.00',
 *       'currency' => 'AUD',
 *       'description' => 'Merchant Reference',
 *       'crn1' => '12345',
 *       'crn2' => '',
 *       'crn3' => null,
 *   ])->send();
 *
 * </code>
 *
 * @method RequestInterface authorize(array $options = []) (Optional method)
 * Authorize an amount on the customers card
 * @method RequestInterface completeAuthorize(array $options = []) (Optional method)
 * Handle return from off-site gateways after authorization
 * @method RequestInterface capture(array $options = []) (Optional method)
 * Capture an amount you have previously authorized
 * @method RequestInterface completePurchase(array $options = []) (Optional method)
 * Handle return from off-site gateways after purchase
 * @method RequestInterface refund(array $options = []) (Optional method)
 * Refund an already processed transaction
 * @method RequestInterface void(array $options = []) (Optional method)
 * Generally can only be called up to 24 hours after submitting a transaction
 * @method RequestInterface createCard(array $options = []) (Optional method)
 * The returned response object includes a cardReference, which can be used for future transactions
 * @method RequestInterface updateCard(array $options = []) (Optional method)
 * Update a stored card
 * @method RequestInterface deleteCard(array $options = []) (Optional method)
 * Delete a stored card
 */
class Gateway extends AbstractGateway
{
    public function getName(): string
    {
        return 'Bpoint';
    }

    /**
     * Get the gateway parameters.
     */
    public function getDefaultParameters(): array
    {
        return ['endpointBase' => 'https://www.bpoint.com.au/webapi/v3', 'username' => '', 'password' => '', 'merchantNumber' => ''];
    }

    public function getEndpointBase(): string
    {
        return $this->getParameter('endpointBase');
    }

    public function setEndpointBase(string $value): Gateway
    {
        return $this->setParameter('endpointBase', $value);
    }

    public function getUsername(): string
    {
        return $this->getParameter('username');
    }

    public function setUsername(string $value): Gateway
    {
        return $this->setParameter('username', $value);
    }

    public function getMerchantNumber(): string
    {
        return $this->getParameter('merchantNumber');
    }

    public function setMerchantNumber(string $value): Gateway
    {
        return $this->setParameter('merchantNumber', $value);
    }

    public function getPassword(): string
    {
        return $this->getParameter('password');
    }

    public function setPassword(string $value): Gateway
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Purchase request.
     */
    public function purchase(array $parameters = []): PurchaseRequest|AbstractRequest
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     * Create token request.
     */
    public function createToken(array $parameters = []): CreateTokenRequest|AbstractRequest
    {
        return $this->createRequest(CreateTokenRequest::class, $parameters);
    }
}
