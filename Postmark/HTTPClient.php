<?php

/*
 * This file is part of the MZ\PostMarkBundle
 *
 * (c) Miguel Perez <miguel@miguelpz.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MZ\PostmarkBundle\Postmark;

use  Buzz\Browser,
     Buzz\Client\Curl;

class HTTPClient
{
    protected $httpHeaders;
    protected $URL;
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->httpHeaders['Accept'] = 'application/json';
        $this->httpHeaders['Content-Type'] = 'application/json';
        $this->httpHeaders['X-Postmark-Server-Token'] =  $this->apiKey;
    }

    public function setApiKey($key)
    {
        $this->apiKey = $key;
    }

    protected function setHTTPHeader($name, $value)
    {
        $this->httpHeaders[$name] = $value;
    }

    protected function sendRequest($data)
    {
        $curl = new Curl();
        $browser = new Browser($curl);
        $response = $browser->post($this->URL, $this->httpHeaders, $data);

        return $response->getContent();
    }
}
