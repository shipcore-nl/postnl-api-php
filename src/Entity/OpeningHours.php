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
 * Class OpeningHours
 *
 * @package MijnPostNLExportModule\Postnl\ComplexTypes
 *
 * @method string getMonday()
 * @method string getTuesday()
 * @method string getWednesday()
 * @method string getThursday()
 * @method string getFriday()
 * @method string getSaturday()
 * @method string getSunday()
 *
 * @method OpeningHours setMonday(string $monday)
 * @method OpeningHours setTuesday(string $tuesday)
 * @method OpeningHours setWednesday(string $wednesday)
 * @method OpeningHours setThursday(string $thursday)
 * @method OpeningHours setFriday(string $friday)
 * @method OpeningHours setSaturday(string $saturday)
 * @method OpeningHours setSunday(string $sunday)
 */
class OpeningHours extends AbstractEntity
{
    /** @var string[] $defaultProperties */
    public static $defaultProperties = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];
    // @codingStandardsIgnoreStart
    /** @var string $Monday */
    protected $Monday = null;
    /** @var string $Tuesday */
    protected $Tuesday = null;
    /** @var string $Wednesday */
    protected $Wednesday = null;
    /** @var string $Thursday */
    protected $Thursday = null;
    /** @var string $Friday */
    protected $Friday = null;
    /** @var string $Saturday */
    protected $Saturday = null;
    /** @var string $Sunday */
    protected $Sunday = null;
    // @codingStandardsIgnoreEnd
}
