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

/**
 * Class Location
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method string   getPostalcode()
 * @method string   getCoordinates()
 * @method string   getCity()
 * @method string   getStreet()
 * @method string   getHouseNr()
 * @method string   getHouseNrExt()
 * @method string   getAllowSundaySorting()
 * @method string   getDeliveryDate()
 * @method string[] getDeliveryOptions()
 * @method string[] getOptions()
 *
 * @method Location setPostalcode(string $postcode)
 * @method Location setCoordinates(Coordinates $coordinates)
 * @method Location setCity(string $city)
 * @method Location setStreet(string $street)
 * @method Location setHouseNr(string $houseNr)
 * @method Location setHouseNrExt(string $houseNrExt)
 * @method Location setAllowSundaySorting(string $sundaySorting)
 * @method Location setDeliveryDate(string $deliveryDate)
 * @method Location setDeliveryOptions(string[] $deliveryOptions)
 * @method Location setOptions(string[] $options)
 */
class Location extends AbstractEntity
{
    /** @var string[] $defaultProperties */
    public static $defaultProperties = [
        'City',
        'Coordinates',
        'HouseNr',
        'HouseNrExt',
        'PostalCode',
        'Street',
        'AllowSundaySorting',
        'DeliveryDate',
        'DeliveryOptions',
        'OpeningTime',
        'Options',
    ];
    // @codingStandardsIgnoreStart
    /** @var string $City */
    protected $City = null;
    /** @var Coordinates $Coordinates */
    protected $Coordinates = null;
    /** @var string $HouseNr */
    protected $HouseNr = null;
    /** @var string $HouseNrExt */
    protected $HouseNrExt = null;
    /** @var string $Postalcode */
    protected $Postalcode = null;
    /** @var string $Street */
    protected $Street = null;
    /** @var string $AllowSundaySorting */
    protected $AllowSundaySorting = null;
    /** @var string $DeliveryDate */
    protected $DeliveryDate = null;
    /** @var string[] $DeliveryOptions */
    protected $DeliveryOptions = null;
    /** @var string $OpeningTime */
    protected $OpeningTime = null;
    /** @var string[] $Options */
    protected $Options = null;
    // @codingStandardsIgnoreEnd

    /**
     * @param string      $zipcode
     * @param string      $allowSundaySorting
     * @param string      $deliveryDate
     * @param array       $deliveryOptions
     * @param array       $options
     * @param Coordinates $coordinates
     * @param string      $city
     * @param string      $street
     * @param string      $houseNr
     * @param string      $houseNrExt
     */
    public function __construct(
        $zipcode,
        $allowSundaySorting,
        $deliveryDate = null,
        array $deliveryOptions = ['PG'],
        array $options = ['Daytime'],
        Coordinates $coordinates = null,
        $city = null,
        $street = null,
        $houseNr = null,
        $houseNrExt = null
    ) {
        parent::__construct();

        $this->setAllowSundaySorting($allowSundaySorting);
        $this->setDeliveryDate($deliveryDate ?: (new \DateTime('next monday'))->format('d-m-Y'));
        $this->setDeliveryOptions($deliveryOptions);
        $this->setOptions($options);
        $this->setPostalcode($zipcode);
        $this->setCoordinates($coordinates);
        $this->setCity($city);
        $this->setStreet($street);
        $this->setHouseNr($houseNr);
        $this->setHouseNrExt($houseNrExt);
    }
}
