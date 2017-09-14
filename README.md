# PostNL REST API PHP Bindings

## Example
Creating a label

```php
PostNL::setApiKey('9s8adf7as8f6gasf6sdf6asfsfaw4f');
PostNL::setSandbox(true);
PostNL::setCustomer(
    Customer::create()
        ->setCollectionLocation('123456')
        ->setCustomerCode('DEVC')
        ->setCustomerNumber('11223344')
        ->setContactPerson('Dave')
        ->setAddress(Address::create([
            'AddressType' => '02',
            'City'        => 'Hoofddorp',
            'CompanyName' => 'PostNL',
            'Countrycode' => 'NL',
            'HouseNr'     => '42',
            'Street'      => 'Siriusdreef',
            'Zipcode'     => '2132WT',
        ]))
        ->setEmail('postnl@mijnpresta.nl')
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
