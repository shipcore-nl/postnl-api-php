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
 * Class Amount
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method string getAccountName()
 * @method string getAmountType()
 * @method string getBIC()
 * @method string getCurrency()
 * @method string getIBAN()
 * @method string getReference()
 * @method string getTransactionNumber()
 * @method string getValue()
 *
 * @method Amount setAccountName(string $accountName)
 * @method Amount setAmountType(string $amountType)
 * @method Amount setBIC(string $bic)
 * @method Amount setCurrency(string $currency)
 * @method Amount setIBAN(string $iban)
 * @method Amount setReference(string $reference)
 * @method Amount setTransactionNumber(string $transactionNr)
 * @method Amount setValue(string $value)
 */
class Amount
{
    /** @var string[] $defaultProperties */
    public static $defaultProperties = [
        'AccountName'       => [
            'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'AmountType'        => [
             'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'BIC'               => [
             'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'Currency'          => [
             'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'IBAN'              => [
             'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'Reference'         => [
             'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'TransactionNumber' => [
             'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
        'Value'             => [
             'Barcode'    => BarcodeService::DOMAIN_NAMESPACE,
            'Confirming' => ConfirmingService::DOMAIN_NAMESPACE,
            'Labelling'  => LabellingService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var string $AccountName */
    protected $AccountName = null;
    /** @var string $AmountType */
    protected $AmountType = null;
    /** @var string $BIC */
    protected $BIC = null;
    /** @var string $Currency */
    protected $Currency = null;
    /** @var string $IBAN */
    protected $IBAN = null;
    /** @var string $Reference */
    protected $Reference = null;
    /** @var string $TransactionNumber */
    protected $TransactionNumber = null;
    /** @var string $Value */
    protected $Value = null;
    // @codingStandardsIgnoreEnd

    /**
     * @param string $accountName
     * @param string $amountType
     * @param string $bic
     * @param string $currency
     * @param string $iban
     * @param string $reference
     * @param string $transactionNumber
     * @param string $value
     */
    public function __construct(
        $accountName,
        $amountType,
        $bic,
        $currency,
        $iban,
        $reference,
        $transactionNumber,
        $value
    ) {
        parent::__construct();

        $this->setAccountName($accountName);
        $this->setAmountType($amountType);
        $this->setBIC($bic);
        $this->setCurrency($currency);
        $this->setIBAN($iban);
        $this->setReference($reference);
        $this->setTransactionNumber($transactionNumber);
        $this->setValue($value);
    }
}
