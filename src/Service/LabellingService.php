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

namespace ThirtyBees\PostNL\Service;

use ThirtyBees\PostNL\PostNL;
use ThirtyBees\PostNL\Request\LabelRequest;

/**
 * Class LabellingService
 *
 * @package ThirtyBees\PostNL\Service
 */
class LabellingService extends AbstractService
{
    const VERSION = '2.1';

    const LIVE_ENDPOINT = 'https://api.postnl.nl/shipment/v2_1/label';
    const SANDBOX_ENDPOINT = 'https://api-sandbox.postnl.nl/shipment/v2_1/label';

    /**
     * Generate a single barcode
     *
     * @param LabelRequest $request
     *
     * @return string Barcode
     */
    public static function generateLabel(LabelRequest $request, $confirm = true)
    {
        $client = PostNL::getHttpClient();
        $apiKey = PostNL::getApiKey();

        $result = $client->request(
            'POST',
            PostNL::getSandbox() ? static::SANDBOX_ENDPOINT : static::LIVE_ENDPOINT,
            [
                "apikey: $apiKey",
            ],
            [
                'confirm' => $confirm,
            ],
            json_encode($request)
        );

        // FIXME
        return $result;
    }
}
