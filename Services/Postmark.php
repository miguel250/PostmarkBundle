<?php

/*
 * This file is part of the Postmarkapp\PostmarkBundle
 *
 * (c) Miguel Perez <miguel@mlpz.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Postmarkapp\PostmarkBundle\Services;

use Postmark\Postmark as PostmarkClass;

/**
 * Service for postmark api
 *
 * @author Miguel Perez  <miguel@mlpz.com>
 */
class Postmark extends PostmarkClass
{

    public function __construct($apikey, $from_address, $from_name)
    {
        $this->_apiKey = $apikey;
        $this->from($from_address, $from_name);
        $this->messageHtml(null)->messagePlain(null);
    }

}