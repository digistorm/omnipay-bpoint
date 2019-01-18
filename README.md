# Omnipay: BPoint

**BPoint driver for the Omnipay PHP payment processing library**

Currently only supports tokenized purchases with two available methods:

- createToken()
- purchase()

## Usage

```php
<?php
// Create a gateway for the Bpoint Gateway
// (routes to GatewayFactory::create)
$gateway = Omnipay::create('Bpoint');
$gateway->setUsername('usernameValue');
$gateway->setPassword('passwordValue');
$gateway->setMerchantId('merchantIdValue');
// Tokenize a card
$response = $gateway->createToken([
    'card' => new CreditCard([...]),
    'crn1' => '12345',
    'crn2' => '',
    'crn3' => null,
])->send();
// Charge using a token
$gateway->purchase([
    'card' => new CreditCard([
        'number' => $response->getToken(),
        ...
    ]),
    'amount' => '50.00',
    'currency' => 'AUD',
    'description' => 'Merchant Reference',
    'crn1' => '12345',
    'crn2' => '',
    'crn3' => null,
])->send();
```