# Omnipay: BPoint

**BPoint driver for the Omnipay PHP payment processing library**

Currently only supports tokenized purchases with two available methods:

- createToken()
- purchase()

## Usage

```php
<?php
use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;

// Create a gateway for the Bpoint Gateway
// (routes to GatewayFactory::create)
$gateway = Omnipay::create('Bpoint');

$gateway->setTestMode(true);
$gateway->setUsername('usernameValue');
$gateway->setPassword('passwordValue');
$gateway->setMerchantId('merchantIdValue');

// Tokenize a card
$response = $gateway->createToken([
    'card' => new CreditCard([
        'number' => '4987654321098769',
        'cvv' => '987',
        'expiryMonth' => '03',
        'expiryYear' => '2026',
        'firstName' => 'John',
        'lastName' => 'Doe',
    ]),
    'crn1' => '12345',
    'crn2' => '',
    'crn3' => null,
])->send();

// Charge using a token
$gateway->purchase([
    'card' => new CreditCard([
        'number' => $response->getToken(),
        'cvv' => '987',
        'expiryMonth' => '03',
        'expiryYear' => '2026',
        'firstName' => 'John',
        'lastName' => 'Doe',
    ]),
    'amount' => '50.00',
    'currency' => 'AUD',
    'description' => 'Merchant Reference',
    'crn1' => '12345',
    'crn2' => '',
    'crn3' => null,
])->send();
```

