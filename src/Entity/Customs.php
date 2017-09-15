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

use ThirtyBees\PostNL\Service\BarcodeService;
use ThirtyBees\PostNL\Service\ConfirmingService;
use ThirtyBees\PostNL\Service\LabellingService;

/**
 * Class Customs
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method string    getCertificate()
 * @method string    getCertificateNr()
 * @method Content[] getContent()
 * @method string    getCurrency()
 * @method string    getHandleAsNonDeliverable()
 * @method string    getInvoice()
 * @method string    getInvoiceNr()
 * @method string    getLicense()
 * @method string    getLicenseNr()
 * @method string    getShipmentType()
 *
 * @method Customs setCertificate(string $certificate)
 * @method Customs setCertificateNr(string $certificateNr)
 * @method Customs setContent(Content[] $content)
 * @method Customs setCurrency(string $currency)
 * @method Customs setHandleAsNonDeliverable(string $nonDeliverable)
 * @method Customs setInvoice(string $invoice)
 * @method Customs setInvoiceNr(string $invoiceNr)
 * @method Customs setLicense(string $license)
 * @method Customs setLicenseNr(string $licenseNr)
 * @method Customs setShipmentType(string $shipmentType)
 */
class Customs extends AbstractEntity
{
    /** @var string[] $defaultProperties */
    public static $defaultProperties = [
        'Certificate'            => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'CertificateNr'          => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'Content'                => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'Currency'               => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'HandleAsNonDeliverable' => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'Invoice'                => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'InvoiceNr'              => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'License'                => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'LicenseNr'              => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'ShipmentType'           => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var string $Certificate */
    protected $Certificate = null;
    /** @var string $CertificateNr */
    protected $CertificateNr = null;
    /** @var Content[] $Content */
    protected $Content = null;
    /** @var string $Currency */
    protected $Currency = null;
    /** @var string $HandleAsNonDeliverable */
    protected $HandleAsNonDeliverable = null;
    /** @var string $Invoice */
    protected $Invoice = null;
    /** @var string $InvoiceNr */
    protected $InvoiceNr = null;
    /** @var string $License */
    protected $License = null;
    /** @var string $LicenseNr */
    protected $LicenseNr = null;
    /** @var string $ShipmentType */
    protected $ShipmentType = null;
    // @codingStandardsIgnoreEnd

    /**
     * @param string    $certificate
     * @param string    $certificateNr
     * @param Content[] $content
     * @param string    $currency
     * @param string    $handleAsNonDeliverable
     * @param string    $invoice
     * @param string    $invoiceNr
     * @param string    $license
     * @param string    $licenseNr
     * @param string    $shipmentType
     */
    public function __construct(
        $certificate,
        $certificateNr,
        array $content,
        $currency,
        $handleAsNonDeliverable,
        $invoice,
        $invoiceNr,
        $license,
        $licenseNr,
        $shipmentType
    ) {
        parent::__construct();

        $this->setCertificate($certificate);
        $this->setCertificateNr($certificateNr);
        $this->setContent($content);
        $this->setCurrency($currency);
        $this->setHandleAsNonDeliverable($handleAsNonDeliverable);
        $this->setInvoice($invoice);
        $this->setInvoiceNr($invoiceNr);
        $this->setLicense($license);
        $this->setLicenseNr($licenseNr);
        $this->setShipmentType($shipmentType);
    }
}
