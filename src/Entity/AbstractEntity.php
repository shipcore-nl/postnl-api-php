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

use ThirtyBees\PostNL\Exception\InvalidArgumentException;
use ThirtyBees\PostNL\Util\UUID;

/**
 * Class Entity
 *
 * @package ThirtyBees\PostNL\Entity
 *
 * @method string getid()
 *
 * @method static setid(string $id)
 */
abstract class AbstractEntity implements \JsonSerializable
{
    /** @var array $defaultProperties */
    public static $defaultProperties = [];
    /** @var array $other */
    protected $other = [];
    /** @var string $id */
    public $id;

    /**
     * AbstractEntity constructor.
     */
    public function __construct()
    {
        $this->id = UUID::generate();
    }

    /**
     * Create an instance of this class without touching the constructor
     *
     * @param array $properties
     *
     * @return static|null|object
     */
    public static function create(array $properties = [])
    {
        if (get_called_class() === __CLASS__) {
            return null;
        }

        $reflectionClass = new \ReflectionClass(get_called_class());

        $instance = $reflectionClass->newInstanceWithoutConstructor();

        foreach ($properties as $name => $value) {
            $instance->{'set'.$name}($value);
        }
        $instance->id = UUID::generate();

        return $instance;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return object|null
     *
     * @throws InvalidArgumentException
     */
    public function __call($name, $value)
    {
        $methodName = substr($name, 0, 3);
        $propertyName = substr($name, 3, strlen($name));

        if ($methodName === 'get') {
            if (property_exists($this, $propertyName)) {
                return $this->{$propertyName};
            } elseif (array_key_exists($propertyName, $this->other)) {
                return $this->other[$propertyName];
            } else {
                return null;
            }
        } elseif ($methodName === 'set') {
            if (!is_array($value) || count($value) < 1) {
                throw new InvalidArgumentException('Value is missing');
            }

            if (property_exists($this, $propertyName)) {
                $this->{$propertyName} = $value[0];
            } elseif (array_key_exists($propertyName, $this->other)) {
                $this->other[$propertyName] = $value[0];
            }

            return $this;
        }

        throw new InvalidArgumentException('Not a valid `get` or `set` method');
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $json = [];
        foreach (static::$defaultProperties as $property) {
            if (!is_null($this->{$property})) {
                $json[$property] = $this->{$property};
            }
        }
        foreach (array_keys($this->other) as $property) {
            if (!is_null($this->other[$property])) {
                $json[$property] = $this->other[$property];
            }
        }

        return $json;
    }
}
