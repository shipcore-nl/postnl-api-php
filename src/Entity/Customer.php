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

namespace ThirtyBees\PostNL\Entity;

use Sabre\Xml\Writer;
use ThirtyBees\PostNL\Service\BarcodeService;
use ThirtyBees\PostNL\Service\ConfirmingService;
use ThirtyBees\PostNL\Service\LabellingService;

/**
 * Class Customer
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method string getCustomerNumber()
 * @method string getCustomerCode()
 * @method string getCollectionLocation()
 * @method string getContactPerson()
 * @method string getEmail()
 * @method string getName()
 * @method string getAddress()
 *
 * @method Customer setCustomerNumber(string $customerNr)
 * @method Customer setCustomerCode(string $customerCode)
 * @method Customer setCollectionLocation(string $collectionLocation)
 * @method Customer setContactPerson(string $contactPerson)
 * @method Customer setEmail(string $email)
 * @method Customer setName(string $name)
 * @method Customer setAddress(Address $address)
 */
class Customer extends AbstractEntity
{
    /** @var string[] $defaultProperties */
    public static $defaultProperties = [
        'Address'            => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'CollectionLocation' => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'ContactPerson'      => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'CustomerCode'       => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'CustomerNumber'     => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'Email'              => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'Name'               => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var Address $Address */
    protected $Address = null;
    /** @var string $CollectionLocation */
    protected $CollectionLocation = null;
    /** @var string $ContactPerson */
    protected $ContactPerson = null;
    /** @var string $CustomerCode */
    protected $CustomerCode = null;
    /** @var string $CustomerNumber */
    protected $CustomerNumber = null;
    /** @var string $Email */
    protected $Email = null;
    /** @var string $Name */
    protected $Name = null;
    // @codingStandardsIgnoreEnd

    /**
     * @param string  $customerNr
     * @param string  $customerCode
     * @param string  $collectionLocation
     * @param string  $contactPerson
     * @param string  $email
     * @param string  $name
     * @param Address $address
     */
    public function __construct(
        $customerNr,
        $customerCode,
        $collectionLocation,
        $contactPerson = null,
        $email = null,
        $name = null,
        Address $address = null
    ) {
        parent::__construct();

        $this->setCustomerNumber($customerNr);
        $this->setCustomerCode($customerCode);
        $this->setCollectionLocation($collectionLocation);
        $this->setContactPerson($contactPerson);
        $this->setEmail($email);
        $this->setName($name);
        $this->setAddress($address);
    }

    public function xmlSerialize(Writer $writer)
    {
        parent::xmlSerialize($writer);
    }
}
