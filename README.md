# Credo Payments

This package is for communicating with credocentral.com and credodemo.com API

## Getting Started

Install the package using composer.

Run `composer require ezeasorekene/credo-payments`

### Define your api keys

Define your `$apiKeys` variable as an array with the public and private keys. You should get the keys from environment variables. Also define `CREDO_MODE` environment key with parameters `DEMO | LIVE`

```php
    $apiKeys = [
        'publicKey' => getenv("CREDO_MODE") == 'LIVE' ? getenv("CREDO_LIVE_PUBLIC_KEY") : getenv("CREDO_TEST_PUBLIC_KEY"),
        'secretKey' => getenv("CREDO_MODE") == 'LIVE' ? getenv("CREDO_LIVE_SECRET_KEY") : getenv("CREDO_TEST_SECRET_KEY"),
    ];
```

### Instantiating Transaction

```php
   $credo = new CredoPay( $apiKeys, getenv("CREDO_MODE") );
```

Set data to post using array. Please note that the `serviceCode` is an optional field. It is used when you want to split payments between different accounts

```php
        $credodata = [
            'reference' => uniqid(),
            'amount' => 20000, //in kobo
            'bearer' => 0,
            'callbackUrl' => 'http://example.com/callback/credo',
            'currency' => 'NGN',
            'customerFirstName' => 'Credo',
            'customerLastName' => 'Package',
            'customerPhoneNumber' => '234080111111111',
            'email' => 'credotest@gmail.com',
            'channels' => array('card', 'bank'),
            'serviceCode' => 'XXXXXXXXXXX',
            "metadata" => [
                "customFields" => [
                    [
                        'display_name' => 'Custom Field Display 1',
                        'variable_name' => 'Custom Field Variable 1',
                        'value' => 'Custom Field Value 1'
                    ]
                ]
            ],
        ];
```

Response is a JSON encoded object. So to decode, perform a `json_decode`

```php
    $response = json_decode($credo->initialize($credodata));
```

### Verifying Transaction

```php
    $credo = new CredoPay( $apiKeys, getenv("CREDO_MODE") );
    $verify = json_decode($credo->verify($reference));

```
