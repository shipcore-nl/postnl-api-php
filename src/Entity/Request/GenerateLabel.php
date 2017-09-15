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

namespace ThirtyBees\PostNL\Entity\Request;

use Sabre\Xml\Writer;
use ThirtyBees\PostNL\Entity\AbstractEntity;
use ThirtyBees\PostNL\Entity\Customer;
use ThirtyBees\PostNL\Entity\Message\LabellingMessage;
use ThirtyBees\PostNL\Entity\Message\Message;
use ThirtyBees\PostNL\Entity\Shipment;
use ThirtyBees\PostNL\PostNL;
use ThirtyBees\PostNL\Service\BarcodeService;
use ThirtyBees\PostNL\Service\ConfirmingService;
use ThirtyBees\PostNL\Service\LabellingService;

/**
 * Class GenerateLabel
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method Customer   getCustomer()
 * @method Message    getMessage()
 * @method Shipment[] getShipments()
 *
 * @method GenerateLabel setCustomer(Customer $customer)
 * @method GenerateLabel setMessage(Message $message)
 * @method GenerateLabel setShipments(Shipment[] $shipments)
 */
class GenerateLabel extends AbstractEntity
{
    /**
     * Default properties and namespaces for the SOAP API
     *
     * @var array $defaultProperties
     */
    public static $defaultProperties = [
        'Customer'  => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'Message'   => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'Shipments' => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var Customer $Customer */
    public $Customer;
    /** @var Message $Message */
    public $Message;
    /** @var Shipment[] $Shipments */
    public $Shipments;
    // @codingStandardsIgnoreEnd

    /**
     * LabelRequest constructor.
     *
     * @param Shipment[]       $shipments
     * @param LabellingMessage $message
     * @param Customer         $customer
     */
    public function __construct(array $shipments, LabellingMessage $message = null, Customer $customer = null)
    {
        parent::__construct();

        $this->setShipments($shipments);
        $this->setMessage($message ?: new LabellingMessage());
        $this->setCustomer($customer ?: PostNL::getCustomer());
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
            if ($propertyName === 'Shipments') {
                $shipments = [];
                foreach ($this->Shipments as $shipment) {
                    $shipments[] = ["{{$namespace}}Shipment" => $shipment];
                }
                $xml["{{$namespace}}Shipments"] = $shipments;
            } elseif (!is_null($this->{$propertyName})) {
                $xml[$namespace ? "{{$namespace}}{$propertyName}" : $propertyName] = $this->{$propertyName};
            }
        }
        // Auto extending this object with other properties is not supported with SOAP
        $writer->write($xml);
    }
}
