<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2017 Thirty Development, LLC
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
 * is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author    Michael Dekker <michael@thirtybees.com>
 * @copyright 2017 Thirty Development, LLC
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace ThirtyBees\PostNL\Service;

use ThirtyBees\PostNL\Entity\Customer;
use ThirtyBees\PostNL\PostNL;

/**
 * Class BarcodeService
 *
 * @package ThirtyBees\PostNL\Service
 */
class BarcodeService extends AbstractService
{
    const VERSION = '1.1';
    const SANDBOX_ENDPOINT = 'https://api-sandbox.postnl.nl/shipment/v1_1/barcode';
    const LIVE_ENDPOINT = 'https://api.postnl.nl/shipment/v1_1/barcode';

    const SOAP_ACTION = 'http://postnl.nl/cif/services/BarcodeWebService/IBarcodeWebService/GenerateBarcode';
    const ENVELOPE_NAMESPACE = 'http://schemas.xmlsoap.org/soap/envelope/';
    const SERVICES_NAMESPACE = 'http://postnl.nl/cif/services/BarcodeWebService/';
    const DOMAIN_NAMESPACE = 'http://postnl.nl/cif/domain/BarcodeWebService/';

    /**
     * Generate a single barcode
     *
     * @param Customer|null $customer
     *
     * @return string Barcode
     */
    public static function generateBarcode($customer = null)
    {
        if (!$customer instanceof Customer) {
            $customer = PostNL::getCustomer();
        }

        $apiKey = PostNL::getApiKey();

        $result =  PostNL::getHttpClient()->request(
            'GET',
            PostNL::getSandbox() ? static::SANDBOX_ENDPOINT : static::LIVE_ENDPOINT,
            [
                "apikey: $apiKey",
            ],
            [
                'CustomerCode'   => $customer->getCustomerCode(),
                'CustomerNumber' => $customer->getCustomerNumber(),
                'Type'           => '3S',
                'Serie'          => '000000000-999999999',
            ]
        );

        // FIXME: Do some error checking here
        return json_decode($result[0], true)['Barcode'];
    }

    protected static function setNamespaces()
    {

    }
}
