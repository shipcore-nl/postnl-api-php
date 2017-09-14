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

use ThirtyBees\PostNL\Entity\Customer;
use ThirtyBees\PostNL\HttpClient\ClientInterface;
use ThirtyBees\PostNL\HttpClient\CurlClient;
use ThirtyBees\PostNL\HttpClient\GuzzleClient;

/**
 * Class PostNL
 *
 * @package ThirtyBees\PostNL
 */
class PostNL
{
    /**
     * Verify SSL certificate of the PostNL REST API
     *
     * @var bool $verifySslCerts
     */
    public static $verifySslCerts = true;
    /**
     * The PostNL REST API key to be used for requests.
     *
     * @var string $apiKey
     */
    protected static $apiKey;
    /**
     * The PostNL Customer to be used for requests.
     *
     * @var Customer $customer
     */
    protected static $customer;
    /**
     * Sandbox mode
     *
     * @var bool $sandbox
     */
    protected static $sandbox = false;
    /** @var ClientInterface $httpClient */
    protected static $httpClient;

    /**
     * Set API Key
     *
     * @param string $key
     */
    public static function setApiKey($key)
    {
        static::$apiKey = $key;
    }

    /**
     * Get API Key
     *
     * @return string
     */
    public static function getApiKey()
    {
        return static::$apiKey;
    }

    /**
     * Set PostNL Customer
     *
     * @param Customer $customer
     */
    public static function setCustomer(Customer $customer)
    {
        static::$customer = $customer;
    }

    /**
     * Get PostNL Customer
     *
     * @return Customer
     */
    public static function getCustomer()
    {
        return static::$customer;
    }

    /**
     * Set sandbox mode
     *
     * @param string $sandbox
     */
    public static function setSandbox($sandbox)
    {
        static::$sandbox = (bool) $sandbox;
    }

    /**
     * Get sandbox mode
     *
     * @return string
     */
    public static function getSandbox()
    {
        return static::$sandbox;
    }

    /**
     * Set the HttpClient
     *
     * @param ClientInterface $client
     */
    public static function setHttpClient(ClientInterface $client)
    {
        static::$httpClient = $client;
    }

    /**
     * HttpClient singleton
     *
     * Automatically load Guzzle when available
     *
     * @return ClientInterface
     */
    public static function getHttpClient()
    {
        if (!static::$httpClient) {
            if (class_exists('\\GuzzleHttp\\ClientInterface')
                && version_compare(\GuzzleHttp\ClientInterface::VERSION, '6.0.0', '>=')) {
                static::$httpClient = GuzzleClient::instance();
            } else {
                static::$httpClient = CurlClient::instance();
            }
        }

        return static::$httpClient;
    }
}
