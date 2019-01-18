<?php

/**
 * Bpoint Gateway.
 */
namespace Omnipay\Bpoint;

use Omnipay\Common\AbstractGateway;
use Omnipay\Bpoint\Message\CreateTokenRequest;

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
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())         (Optional method)
 *         Authorize an amount on the customers card
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array()) (Optional method)
 *         Handle return from off-site gateways after authorization
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())           (Optional method)
 *         Capture an amount you have previously authorized
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())  (Optional method)
 *         Handle return from off-site gateways after purchase
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())            (Optional method)
 *         Refund an already processed transaction
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())              (Optional method)
 *         Generally can only be called up to 24 hours after submitting a transaction
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())        (Optional method)
 *         The returned response object includes a cardReference, which can be used for future transactions
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())        (Optional method)
 *         Update a stored card
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())        (Optional method)
 *         Delete a stored card
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Bpoint';
    }

    /**
     * Get the gateway parameters.
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
            'merchantNumber' => '',
        );
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
     * @return \Omnipay\Bpoint\Gateway
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
     * @return \Omnipay\Bpoint\Gateway
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
     * @return \Omnipay\Bpoint\Gateway
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Purchase request.
     *
     *
     *
     * @param array $parameters
     *
     * @return \Omnipay\Bpoint\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Bpoint\Message\PurchaseRequest', $parameters);
    }

    /**
     *
     *
     * @param array $parameters parameters to be passed in to the TokenRequest.
     *
     * @return CreateTokenRequest|\Omnipay\Common\Message\AbstractRequest The create token request.
     */
    public function createToken(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Bpoint\Message\CreateTokenRequest', $parameters);
    }
}
