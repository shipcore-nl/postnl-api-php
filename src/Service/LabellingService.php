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

use Elasticsearch\Endpoints\Indices\Upgrade\Post;
use ThirtyBees\PostNL\Entity\SOAP\Security;
use ThirtyBees\PostNL\Exception\CifException;
use ThirtyBees\PostNL\PostNL;
use ThirtyBees\PostNL\Entity\Request\GenerateLabel;
use ThirtyBees\PostNL\Util\PostNLXmlService;

/**
 * Class LabellingService
 *
 * @package ThirtyBees\PostNL\Service
 */
class LabellingService extends AbstractService
{
    // API Version
    const VERSION = '2.1';

    // Endpoints
    const LIVE_ENDPOINT = 'https://api.postnl.nl/shipment/v2_1/label';
    const SANDBOX_ENDPOINT = 'https://api-sandbox.postnl.nl/shipment/v2_1/label';
    const LEGACY_SANDBOX_ENDPOINT = 'https://testservice.postnl.com/CIF_SB/LabellingWebService/2_1/LabellingWebService.svc';
    const LEGACY_LIVE_ENDPOINT = 'https://service.postnl.com/CIF_SB/LabellingWebService/2_1/LabellingWebService.svc';

    // SOAP API
    const SOAP_ACTION = 'http://postnl.nl/cif/services/LabellingWebService/ILabellingWebService/GenerateLabel';
    const ENVELOPE_NAMESPACE = 'http://schemas.xmlsoap.org/soap/envelope/';
    const SERVICES_NAMESPACE = 'http://postnl.nl/cif/services/LabellingWebService/';
    const DOMAIN_NAMESPACE = 'http://postnl.nl/cif/domain/LabellingWebService/';

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
     * @param GenerateLabel $request
     * @param bool          $confirm Should the label immediately be confirmed (pre-alerted)?
     *
     * @return string
     */
    public static function generateLabel(GenerateLabel $request, $confirm = false)
    {
        // TODO: make it try with the other method if one fails
        return (PostNL::getCurrentMode() === PostNL::MODE_REST
            ? static::generateLabelREST($request, $confirm)
            : static::generateLabelSOAP($request, $confirm));
    }

    /**
     * Generate a single barcode via REST
     *
     * @param GenerateLabel $request
     * @param bool          $confirm
     *
     * @return string
     */
    protected static function generateLabelREST(GenerateLabel $request, $confirm = false)
    {
        $client = PostNL::getHttpClient();
        $apiKey = PostNL::getRestApiKey();

        $result = $client->request(
            'POST',
            PostNL::getSandbox() ? static::SANDBOX_ENDPOINT : static::LIVE_ENDPOINT,
            [
                "apikey: $apiKey",
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            [
                'confirm' => $confirm,
            ],
            json_encode($request)
        );

        // FIXME
        return $result[0];
    }

    /**
     * Generate a single label via SOAP
     *
     * @param GenerateLabel $generateLabel
     * @param bool          $confirm
     *
     * @return string
     */
    protected static function generateLabelSOAP(GenerateLabel $generateLabel, $confirm = false)
    {
        $soapAction = static::SOAP_ACTION;
        $xmlService = new PostNLXmlService();
        foreach (static::$namespaces as $namespace => $prefix) {
            $xmlService->namespaceMap[$namespace] = $prefix;
        }
        $xmlService->setService('Labelling');

        $request = $xmlService->write(
            '{'.static::ENVELOPE_NAMESPACE.'}Envelope',
            [
                '{'.static::ENVELOPE_NAMESPACE.'}Header' => [
                    ['{'.Security::SECURITY_NAMESPACE.'}Security' => new Security(PostNL::getSoapUsernameToken())],
                ],
                '{'.static::ENVELOPE_NAMESPACE.'}Body'   => [
                    '{'.static::SERVICES_NAMESPACE.'}GenerateLabel' => $generateLabel,
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

        // FIXME: should not return the raw result
        return (string) $xml->xpath('//bar1:Content')[0][0];
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
