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
 * Class Dimension
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method string getHeight()
 * @method string getLength()
 * @method string getVolume()
 * @method string getWeight()
 * @method string getWidth()
 *
 * @method Dimension setHeight(string $height)
 * @method Dimension setLength(string $length)
 * @method Dimension setVolume(string $volume)
 * @method Dimension setWeight(string $weight)
 * @method Dimension setWidth(string $width)
 */
class Dimension extends AbstractEntity
{
    /** @var string[] $defaultProperties */
    public static $defaultProperties = [
        'Height',
        'Length',
        'Volume',
        'Weight',
        'Width',
    ];
    // @codingStandardsIgnoreStart
    /** @var string $Height */
    protected $Height = null;
    /** @var string $Length */
    protected $Length = null;
    /** @var string $Volume */
    protected $Volume = null;
    /** @var string $Weight */
    protected $Weight = null;
    /** @var string $Width */
    protected $Width = null;
    // @codingStandardsIgnoreEnd

    /**
     * @param string $weight
     * @param string $height
     * @param string $length
     * @param string $volume
     * @param string $width
     */
    public function __construct($weight, $height = null, $length = null, $volume = null, $width = null)
    {
        parent::__construct();

        $this->setWeight($weight);

        // Optional parameters.
        $this->setHeight($height);
        $this->setLength($length);
        $this->setVolume($volume);
        $this->setWidth($width);
    }
}
