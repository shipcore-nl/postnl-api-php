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

namespace ThirtyBees\PostNL\Entity\SOAP;

use Sabre\Xml\Writer;
use ThirtyBees\PostNL\Entity\AbstractEntity;
use ThirtyBees\PostNL\Service\BarcodeService;
use ThirtyBees\PostNL\Service\ConfirmingService;
use ThirtyBees\PostNL\Service\LabellingService;

/**
 * Class UsernameToken
 *
 * @package ThirtyBees\PostNL\Entity\SOAP
 *
 * @method string getUsername()
 * @method string getPassword()
 *
 * @method UsernameToken setUsername(string $username)
 * @method UsernameToken setPassword(string $password)
 */
class UsernameToken extends AbstractEntity
{
    /** @var string[] $defaultProperties */
    public static $defaultProperties = [
        'Username' => [
            'Barcode'    => Security::SECURITY_NAMESPACE,
            'Confirming' => Security::SECURITY_NAMESPACE,
            'Labelling'  => Security::SECURITY_NAMESPACE,
        ],
        'Password' => [
            'Barcode'    => Security::SECURITY_NAMESPACE,
            'Confirming' => Security::SECURITY_NAMESPACE,
            'Labelling'  => Security::SECURITY_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var string $Username */
    public $Username;
    /** @var string $Password */
    public $Password;
    /**
     * Indicates whether this token has been created for the legacy API
     *
     * @var bool $legacy
     */
    protected $legacy;
    // @codingStandardsIgnoreEnd

    /**
     * UsernameToken constructor.
     *
     * @param string $username
     * @param string $password Plaintext password
     * @param bool   $legacy   Whether this token is created for the legacy API
     */
    public function __construct($username, $password, $legacy = false)
    {
        parent::__construct();

        $this->setUsername($username);
        $this->setPassword($password);
        $this->legacy = $legacy;
    }

    /**
     * Return a serializable array for the XMLWriter
     *
     * @param Writer $writer
     *
     * @return void
     */
    public function xmlSerialize(Writer $writer)
    {
        $xml = [];
        foreach (static::$defaultProperties as $propertyName => $namespace) {
            $namespace = isset(static::$defaultProperties[$propertyName][$writer->service]) ? static::$defaultProperties[$propertyName][$writer->service] : '';
            if (!is_null($this->{$propertyName})) {
                if ($this->legacy && $propertyName === 'Password') {
                    $xml[$namespace ? "{{$namespace}}{$propertyName}" : $propertyName] = sha1($this->{$propertyName});
                } else {
                    $xml[$namespace ? "{{$namespace}}{$propertyName}" : $propertyName] = $this->{$propertyName};
                }
            }
        }
        // Auto extending this object with other properties is not supported with SOAP

        $writer->write($xml);
    }
}
