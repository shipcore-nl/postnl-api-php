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
 * Class Area
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method Coordinates getCoordinatesNorthWest()
 * @method Coordinates getCoordinatesSouthEast()
 *
 * @method Area setCoordinatesNorthWest(Coordinates $NW)
 * @method Area setCoordinatesSouthEast(Coordinates $SE)
 */
class Area extends AbstractEntity
{
    /** @var string[] $defaultProperties */
    public static $defaultProperties = [
        'Barcode' => [
            'CoordinatesNorthWest' => BarcodeService::DOMAIN_NAMESPACE,
            'CoordinatesSouthEast' => BarcodeService::DOMAIN_NAMESPACE,
        ],
        'Confirming' => [
            'CoordinatesNorthWest' => ConfirmingService::DOMAIN_NAMESPACE,
            'CoordinatesSouthEast' => ConfirmingService::DOMAIN_NAMESPACE,
        ],
        'Labelling' => [
            'CoordinatesNorthWest' => LabellingService::DOMAIN_NAMESPACE,
            'CoordinatesSouthEast' => LabellingService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var Coordinates $CoordinatesNorthWest */
    protected $CoordinatesNorthWest = null;
    /** @var Coordinates $CoordinatesSouthEast */
    protected $CoordinatesSouthEast = null;
    // @codingStandardsIgnoreEnd

    /**
     * @param Coordinates $NW
     * @param Coordinates $SE
     */
    public function __construct($NW, $SE)
    {
        parent::__construct();

        $this->setCoordinatesNorthWest($NW);
        $this->setCoordinatesSouthEast($SE);
    }
}
