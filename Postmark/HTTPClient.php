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

/**
 * HTTP client use to send requests to postmark api
 *
 * @author Miguel Perez <miguel@miguelpz.com>
 */
class HTTPClient
{
    /**
     * cURL headers
     *
     * @var array
     */
    protected $httpHeaders;

    /**
     * Postmark api key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Constructor
     *
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->setApiKey($apiKey);
        $this->setHTTPHeader('Accept', 'application/json');
        $this->setHTTPHeader('Content-Type', 'application/json');
    }

    /**
     * Set Postmark api key
     *
     * @param string $key
     */
    public function setApiKey($key)
    {
        $this->apiKey = $key;
        $this->setHTTPHeader('X-Postmark-Server-Token', $this->apiKey);
    }

    /**
     * Set cURL headers
     *
     * @param string $name
     * @param string $value
     */
    public function setHTTPHeader($name, $value)
    {
        $this->httpHeaders[$name] = $value;
    }

    /**
     * Make request to postmark api
     *
     * @param string URL to post to
     * @param mixed $data
     */
    public function sendRequest($url, $data)
    {
        $curl = new Curl();
        $browser = new Browser($curl);
        $response = $browser->post($url, $this->httpHeaders, $data);

        return $response->getContent();
    }
}
