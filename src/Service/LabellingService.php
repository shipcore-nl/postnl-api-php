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

use ThirtyBees\PostNL\Entity\SOAP\Security;
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
//    const SANDBOX_ENDPOINT = 'https://api-sandbox.postnl.nl/shipment/v2_1/label';
    const SANDBOX_ENDPOINT = 'https://testservice.postnl.com/CIF_SB/LabellingWebService/2_1/LabellingWebService.svc';

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
     * @return array
     */
    protected static function generateLabelREST(GenerateLabel $request, $confirm = false)
    {
        var_dump(json_encode($request));exit;

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
        file_put_contents(__DIR__.'/../../request.xml', $request);

        $result =  PostNL::getHttpClient()->request(
            'POST',
            PostNL::getSandbox() ? static::SANDBOX_ENDPOINT : static::LIVE_ENDPOINT,
            [
                "SOAPAction: \"$soapAction\"",
                'Content-Type: text/xml',
                'Accept: text/xml',
            ],
            [],
            $request
        );
        file_put_contents(__DIR__.'/../../response.xml', $result[0]);

        $xml = simplexml_load_string($result[0]);
        foreach (static::$namespaces as $namespace => $prefix) {
            $xml->registerXPathNamespace($prefix, $namespace);
        }

        // FIXME: should not return the raw result
        return (string) $xml->xpath('//bar1:Content')[0][0];
    }
}
