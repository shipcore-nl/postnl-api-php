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

use ThirtyBees\PostNL\Entity\Request\GenerateBarcode;
use ThirtyBees\PostNL\Entity\SOAP\Security;
use ThirtyBees\PostNL\Exception\CifException;
use ThirtyBees\PostNL\PostNL;
use ThirtyBees\PostNL\Util\PostNLXmlService;

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
    const LEGACY_SANDBOX_ENDPOINT = 'https://testservice.postnl.com/CIF_SB/BarcodeWebService/1_1/BarcodeWebService.svc';
    const LEGACY_LIVE_ENDPOINT = 'https://service.postnl.com/CIF_SB/BarcodeWebService/1_1/BarcodeWebService.svc';

    const SOAP_ACTION = 'http://postnl.nl/cif/services/BarcodeWebService/IBarcodeWebService/GenerateBarcode';
    const ENVELOPE_NAMESPACE = 'http://schemas.xmlsoap.org/soap/envelope/';
    const SERVICES_NAMESPACE = 'http://postnl.nl/cif/services/BarcodeWebService/';
    const DOMAIN_NAMESPACE = 'http://postnl.nl/cif/domain/BarcodeWebService/';

    /**
     * Namespaces uses for the SOAP version of this service
     *
     * @var array $namespaces
     */
    public static $namespaces = [
        self::ENVELOPE_NAMESPACE     => 'SOAP-ENV',
        self::SERVICES_NAMESPACE     => 'bar',
        self::DOMAIN_NAMESPACE       => 'bar1',
        Security::SECURITY_NAMESPACE => 'wsse',
        self::XML_SCHEMA_NAMESPACE   => 'i',
        self::COMMON_NAMESPACE       => 'ns0',
    ];

    /**
     * Generate a single barcode
     *
     * @param GenerateBarcode $generateBarcode
     *
     * @return string Barcode
     */
    public static function generateBarcode(GenerateBarcode $generateBarcode)
    {
        // TODO: make it try with the other method if one fails
        return (PostNL::getCurrentMode() === PostNL::MODE_REST
            ? static::generateBarcodeREST($generateBarcode)
            : static::generateBarcodeSOAP($generateBarcode));
    }

    /**
     * Generate a single barcode
     *
     * @param GenerateBarcode $generateBarcode
     *
     * @return string Barcode
     */
    public static function generateBarcodeREST(GenerateBarcode $generateBarcode)
    {
        $apiKey = PostNL::getRestApiKey();

        $result =  PostNL::getHttpClient()->request(
            'GET',
            PostNL::getSandbox() ? static::SANDBOX_ENDPOINT : static::LIVE_ENDPOINT,
            [
                'Content-Type: application/json; charset=utf-8',
                'Accept: application/json',
                "apikey: $apiKey",
            ],
            [
                'CustomerCode'   => $generateBarcode->getCustomer()->getCustomerCode(),
                'CustomerNumber' => $generateBarcode->getCustomer()->getCustomerNumber(),
                'Type'           => $generateBarcode->getBarcode()->getType(),
                'Serie'          => $generateBarcode->getBarcode()->getSerie(),
            ]
        );

        return json_decode($result[0], true)['Barcode'];
    }

    /**
     * Generate a single barcode
     *
     * @param GenerateBarcode $generateBarcode
     *
     * @return string Barcode
     */
    public static function generateBarcodeSOAP(GenerateBarcode $generateBarcode)
    {
        $soapAction = static::SOAP_ACTION;
        $xmlService = new PostNLXmlService();
        foreach (static::$namespaces as $namespace => $prefix) {
            $xmlService->namespaceMap[$namespace] = $prefix;
        }

        $xmlService->setService('Barcode');

        $request = $xmlService->write(
            '{'.static::ENVELOPE_NAMESPACE.'}Envelope',
            [
                '{'.static::ENVELOPE_NAMESPACE.'}Header' => [
                    ['{'.Security::SECURITY_NAMESPACE.'}Security' => new Security(PostNL::getSoapUsernameToken())],
                ],
                '{'.static::ENVELOPE_NAMESPACE.'}Body'   => [
                    '{'.static::SERVICES_NAMESPACE.'}GenerateBarcode' => $generateBarcode,
                ],
            ]
        );

        $endpoint = PostNL::getSandbox()
            ? (PostNL::getCurrentMode() === PostNL::MODE_LEGACY ? static::LEGACY_SANDBOX_ENDPOINT : static::SANDBOX_ENDPOINT)
            : (PostNL::getCurrentMode() === PostNL::MODE_LEGACY ? static::LEGACY_LIVE_ENDPOINT : static::LIVE_ENDPOINT);

        $result =  PostNL::getHttpClient()->request(
            'POST',
            $endpoint,
            [
                "SOAPAction: \"$soapAction\"",
                'Content-Type: text/xml',
                'Accept: text/xml',
            ],
            [],
            $request
        );

        $xml = simplexml_load_string($result[0]);
        static::registerNamespaces($xml);
        static::validateSOAPResponse($xml);

        return (string) $xml->xpath('//bar:GenerateBarcodeResponse/bar1:Barcode')[0][0];
    }

    /**
     * Register namespaces
     *
     * @param \SimpleXMLElement $element
     */
    protected static function registerNamespaces(\SimpleXMLElement $element)
    {
        foreach (static::$namespaces as $namespace => $prefix) {
            $element->registerXPathNamespace($prefix, $namespace);
        }
    }

    /**
     * @param \SimpleXMLElement $xml
     *
     * @return bool
     * @throws CifException
     */
    protected static function validateSOAPResponse(\SimpleXMLElement $xml)
    {
        // Detect errors
        $cifErrors = $xml->xpath('//ns0:CifException/ns0:Errors/ns0:ExceptionData');
        if (count($cifErrors)) {
            $exceptionData = [];
            foreach ($cifErrors as $error) {
                /** @var \SimpleXMLElement $error */
                static::registerNamespaces($error);
                $exceptionData[] = [
                    'description' => (string) $error->xpath('//ns0:Description')[0],
                    'message'     => (string) $error->xpath('//ns0:ErrorMsg')[0],
                    'code'        => (int) $error->xpath('//ns0:ErrorNumber')[0],
                ];
            }
            throw new CifException($exceptionData);
        }

        return true;
    }
}
