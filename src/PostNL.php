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

namespace ThirtyBees\PostNL;

use ThirtyBees\PostNL\Entity\Barcode;
use ThirtyBees\PostNL\Entity\Customer;
use ThirtyBees\PostNL\Entity\Label;
use ThirtyBees\PostNL\Entity\Message\LabellingMessage;
use ThirtyBees\PostNL\Entity\Request\Confirming;
use ThirtyBees\PostNL\Entity\Request\GenerateBarcode;
use ThirtyBees\PostNL\Entity\Request\GenerateLabel;
use ThirtyBees\PostNL\Entity\Shipment;
use ThirtyBees\PostNL\Entity\SOAP\UsernameToken;
use ThirtyBees\PostNL\HttpClient\ClientInterface;
use ThirtyBees\PostNL\HttpClient\CurlClient;
use ThirtyBees\PostNL\HttpClient\GuzzleClient;
use ThirtyBees\PostNL\Service\BarcodeService;
use ThirtyBees\PostNL\Service\ConfirmingService;
use ThirtyBees\PostNL\Service\LabellingService;

/**
 * Class PostNL
 *
 * @package ThirtyBees\PostNL
 */
class PostNL
{
    // New REST API
    const MODE_REST = 1;
    // New SOAP API
    const MODE_SOAP = 2;
    // First pick the new REST API, if communication fails, pick the new SOAP API
    const MODE_REST_THEN_SOAP = 3;
    // First pick the new SOAP API, if communication fails, pick the new REST API
    const MODE_SOAP_THEN_REST = 4;
    // Old SOAP API
    const MODE_LEGACY = 5;

    /**
     * Verify SSL certificate of the PostNL REST API
     *
     * @var bool $verifySslCerts
     */
    public $verifySslCerts = true;

    /**
     * The PostNL REST API key or SOAP username/password to be used for requests.
     *
     * In case of REST the API key is the `Password` property of the `UsernameToken`
     * In case of SOAP this has to be a `UsernameToken` object, with the following requirements:
     *   - When using the legacy API, the username has to be given.
     *     The password has to be plain text.
     *   - When using the newer API (launched August 2017), do not pass a username (`null`)
     *     And pass the plaintext password.
     *
     * @var string $apiKey
     */
    protected $token;

    /**
     * The PostNL Customer to be used for requests.
     *
     * @var Customer $customer
     */
    protected $customer;

    /**
     * Sandbox mode
     *
     * @var bool $sandbox
     */
    protected $sandbox = false;

    /** @var ClientInterface $httpClient */
    protected $httpClient;

    /**
     * Set the preferred mode
     *
     * @var int $preferredMode
     */
    protected $preferredMode;

    /**
     * This is the current mode
     *
     * @var int $currentMode
     */
    protected $currentMode;

    /** @var BarcodeService $barcodeService */
    protected $barcodeService;
    /** @var LabellingService $labellingService */
    protected $labellingService;
    /** @var ConfirmingService $confirmingService */
    protected $confirmingService;

    /**
     * PostNL constructor.
     *
     * @param Customer             $customer
     * @param UsernameToken|string $token
     * @param bool                 $sandbox
     * @param int                  $preferredMode Set the preferred connection strategy.
     *                                            Valid options are:
     *                                            - `MODE_REST`: New REST API
     *                                            - `MODE_SOAP`: New SOAP API
     *                                            - `MODE_REST_THEN_SOAP`: First try the REST API
     *                                                                     if that fails, SOAP
     *                                            - `MODE_SOAP_THEN_REST`: First try the SOAP API
     *                                                                     if that fails, REST
     *                                            - `MODE_LEGACY`: Use the legacy API (the plug can
     *                                                             be pulled at any time)
     *
     * @param int                  $currentMode   Sets the current mode
     *                                            (`MODE_REST, `MODE_SOAP`, `MODE_LEGACY`)
     */
    public function __construct(
        Customer $customer,
        $token,
        $sandbox,
        $preferredMode = self::MODE_REST_THEN_SOAP,
        $currentMode = self::MODE_REST
    ) {
        $this->setCustomer($customer);
        $this->setToken($token);
        $this->setSandbox($sandbox);
        $this->setPreferredMode($preferredMode);
        $this->setCurrentMode($currentMode);
    }

    /**
     * Set the token
     *
     * @param string|UsernameToken $token
     *
     * @return bool
     */
    public function setToken($token)
    {
        if ($token instanceof UsernameToken) {
            $this->token = $token;

            return true;
        } elseif (is_string($token)) {
            $this->token = new UsernameToken(null, $token);

            return true;
        }

        return false;
    }

    /**
     * Get REST API Key
     *
     * @return bool|string
     */
    public function getRestApiKey()
    {
        if ($this->token instanceof UsernameToken) {
            return $this->token->getPassword();
        }

        return false;
    }

    /**
     * Get UsernameToken object (for SOAP)
     *
     * @return bool|UsernameToken
     */
    public function getToken()
    {
        if ($this->token instanceof UsernameToken) {
            return $this->token;
        }

        return false;
    }

    /**
     * Get PostNL Customer
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set PostNL Customer
     *
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get sandbox mode
     *
     * @return bool
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     * Set sandbox mode
     *
     * @param bool $sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = (bool) $sandbox;
    }

    /**
     * Get preferred mode
     *
     * @return int
     */
    public function getPreferredMode()
    {
        return $this->preferredMode;
    }

    /**
     * Set preferred mode
     *
     * @param int $preferred
     *
     * @return bool Indicates whether the preferred mode has been successfully changed
     */
    public function setPreferredMode($preferred)
    {
        if (!in_array($preferred, [
            static::MODE_REST,
            static::MODE_SOAP,
            static::MODE_REST_THEN_SOAP,
            static::MODE_SOAP_THEN_REST,
        ])) {
            return false;
        }

        $this->preferredMode = (int) $preferred;

        return true;
    }

    /**
     * Get the current mode
     *
     * @return int
     */
    public function getCurrentMode()
    {
        return $this->currentMode;
    }

    /**
     * Set sandbox mode
     *
     * @param int $current
     *
     * @return bool Indicates whether the current mode has been successfully changed
     */
    public function setCurrentMode($current)
    {
        if (!in_array($current, [
            static::MODE_REST,
            static::MODE_SOAP,
            static::MODE_LEGACY,
        ])) {
            return false;
        }

        $this->currentMode = (int) $current;

        return true;
    }

    /**
     * HttpClient
     *
     * Automatically load Guzzle when available
     *
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        if (!$this->httpClient) {
            if (class_exists('\\GuzzleHttp\\ClientInterface')
                && version_compare(
                    \GuzzleHttp\ClientInterface::VERSION,
                    '6.0.0',
                    '>='
                )) {
                $this->httpClient = GuzzleClient::instance();
            } else {
                $this->httpClient = CurlClient::instance();
            }
        }

        return $this->httpClient;
    }

    /**
     * Set the HttpClient
     *
     * @param ClientInterface $client
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Barcode client
     *
     * Automatically load the barcode service
     *
     * @return BarcodeService
     */
    public function getBarcodeService()
    {
        if (!$this->barcodeService) {
            $this->barcodeService = new BarcodeService($this);
        }

        return $this->barcodeService;
    }

    /**
     * Set the barcode service
     *
     * @param BarcodeService $service
     */
    public function setBarcodeService(BarcodeService $service)
    {
        $this->barcodeService = $service;
    }

    /**
     * Labelling service
     *
     * Automatically load the labelling service
     *
     * @return LabellingService
     */
    public function getLabellingService()
    {
        if (!$this->labellingService) {
            $this->labellingService = new LabellingService($this);
        }

        return $this->labellingService;
    }

    /**
     * Set the labelling service
     *
     * @param LabellingService $service
     */
    public function setLabellingService(LabellingService $service)
    {
        $this->labellingService = $service;
    }

    /**
     * Confirming service
     *
     * Automatically load the barcode service
     *
     * @return ConfirmingService
     */
    public function getConfirmingService()
    {
        if (!$this->confirmingService) {
            $this->confirmingService = new ConfirmingService($this);
        }

        return $this->confirmingService;
    }

    /**
     * Set the confirming service
     *
     * @param ConfirmingService $service
     */
    public function setConfirmingService(ConfirmingService $service)
    {
        $this->confirmingService = $service;
    }

    /**
     * Generate a single barcode
     *
     * @param string $type
     * @param string $range
     * @param string $serie
     *
     * @return string
     */
    public function generateBarcode($type, $range, $serie = '000000000-999999999')
    {
        return $this->getBarcodeService()->generateBarcode(new GenerateBarcode(new Barcode($type, $range, $serie), $this->customer));
    }

    /**
     * @param Shipment $shipment
     * @param string   $printertype
     * @param bool     $confirm
     * @param int      $format
     * @param int      $offset
     *
     * @return string
     */
    public function generateLabel($shipment, $printertype = 'GraphicFile|PDF', $confirm = false, $format = Label::FORMAT_A4, $offset = 0)
    {
        return $this->getLabellingService()->generateLabel(new GenerateLabel([$shipment], new LabellingMessage($printertype), $this->customer), $confirm);
    }

    /**
     * @param Shipment $shipment
     *
     * @return bool
     */
    public function confirm($shipment)
    {
        return $this->getConfirmingService()->confirm(new Confirming([$shipment], $this->customer));
    }
}
