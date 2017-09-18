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

namespace ThirtyBees\PostNL\Util;

use Sabre\Xml\Service;
use Sabre\Xml\XmlSerializable;

/**
 * Class PostNLXmlService
 *
 * @package ThirtyBees\PostNL\Util
 */
class PostNLXmlService extends Service
{
    /**
     * Current PostNL webservice the XML is being generated for
     *
     * @var string $service
     */
    protected $service;

    /**
     * Set the PostNL webservice used for this XmlService
     *
     * @param string $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * Get the PostNL webservice used for this XmlService
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }


    /**
     * Generates an XML document in one go.
     *
     * The $rootElement must be specified in clark notation.
     * The value must be a string, an array or an object implementing
     * XmlSerializable. Basically, anything that's supported by the Writer
     * object.
     *
     * $contextUri can be used to specify a sort of 'root' of the PHP application,
     * in case the xml document is used as a http response.
     *
     * This allows an implementor to easily create URI's relative to the root
     * of the domain.
     *
     * NOTE: this function has been overloaded to attach the current PostNL
     * webservice before writing
     *
     * @param string                       $rootElementName
     * @param string|array|XmlSerializable $value
     * @param string|null                  $contextUri
     *
     * @return string
     */
    public function write($rootElementName, $value, $contextUri = null)
    {
        $w = $this->getWriter();
        $w->service = $this->service;
        $w->openMemory();
        $w->contextUri = $contextUri;
        $w->setIndent(true);
        $w->startDocument();
        $w->writeElement($rootElementName, $value);

        return $w->outputMemory();
    }
}
