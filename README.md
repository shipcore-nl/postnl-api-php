# PostNL REST API PHP Bindings

## Example
Creating a label

```php
<?php

use ThirtyBees\PostNL\Entity\Dimension;
use ThirtyBees\PostNL\Entity\Shipment;
use ThirtyBees\PostNL\PostNL;
use ThirtyBees\PostNL\Entity\Address;
use ThirtyBees\PostNL\Entity\Customer;
use ThirtyBees\PostNL\Request\LabelRequest;
use ThirtyBees\PostNL\Service\BarcodeService;
use ThirtyBees\PostNL\Service\LabellingService;

require_once __DIR__.'/vendor/autoload.php';

PostNL::setApiKey('9s8adf7as8f6gasf6sdf6asfsfaw4f');
PostNL::setSandbox(true);
PostNL::setCustomer(
    Customer::create()
        ->setCollectionLocation('123456')
        ->setCustomerCode('DEVC')
        ->setCustomerNumber('11223344')
        ->setContactPerson('Lesley')
        ->setAddress(Address::create([
            'AddressType' => '02',
            'City'        => 'Hoofddorp',
            'CompanyName' => 'PostNL',
            'Countrycode' => 'NL',
            'HouseNr'     => '42',
            'Street'      => 'Siriusdreef',
            'Zipcode'     => '2132WT',
        ]))
        ->setEmail('michael@thirtybees.com')
        ->setName('Michael')
);

$labelRequest = new LabelRequest([
    Shipment::create()
        ->setAddresses([
            Address::create([
                'AddressType' => '01',
                'City'        => 'Utrecht',
                'Countrycode' => 'NL',
                'FirstName'   => 'Peter',
                'HouseNr'     => '9',
                'HouseNrExt'  => 'a bis',
                'Name'        => 'de Ruijter',
                'Street'      => 'Bilderdijkstraat',
                'Zipcode'     => '3521VA',
            ]),
        ])
        ->setBarcode(BarcodeService::generateBarcode())
        ->setDimension(new Dimension('2000'))
        ->setProductCodeDelivery('3085'),
]);

$label = LabellingService::generateLabel($labelRequest);

var_dump($label);die();
```
