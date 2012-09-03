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

class Main
{
    private $httpHeaders;
    private $URL;
    private $apiKey;
    private $from;
    private $to = array();
    private $cc = array();
    private $bcc = array();
    private $headers = array();
    private $subject;
    private $tag;
    private $replyTo;
    private $Message;
    private $textMessage;
    
    public function __construct($apiKey, $from, $fromName = null)
    {
        $this->apiKey = $apiKey;
        $this->from = trim("{$fromName} {$from}");
        $this->httpHeaders['Accept'] = 'application/json';
        $this->httpHeaders['Content-Type'] = 'application/json';
        $this->httpHeaders['X-Postmark-Server-Token'] =  $this->apiKey;
    }

    public function setApiKey($key)
    {
        $this->apiKey = $key;
    }

    public function setHTTPHeader($name, $value)
    {
        $this->httpHeaders[$name] = $value;
    }

    public function addTo($email, $name = null)
    {
        $this->to[] = trim("{$name} {$email}");
    }

    public function addCC($email, $name = null)
    {
        $this->cc[] =  trim("{$name} {$email}");
    }

    public function addBCC($email, $name = null)
    {
         $this->bcc[] =  trim("{$name} {$email}");
    }

    public function setReplyTo($email, $name = null)
    {
        $this->replyTo = trim("{$name} {$email}");
    }

    public function setTag($name)
    {
        $this->tag = $name;
    }
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setHtmlMessage($message)
    {
        $this->htmlMessage = $message;
    }

    public function setTextMessage($message)
    {
        $this->textMessage = $message;
    }

    public function setHeaders($name, $value)
    {
        $this->headers[] = array(
            'Name'=> $name,
            'Value' => $value
            );
    }

    public function Send()
    {
        $data = array();
        $this->URL = 'https://api.postmarkapp.com/email';

        if(!empty($this->htmlMessage)) {
            $data['HtmlBody'] = $this->htmlMessage;
            unset($this->htmlMessage);
        }

        if(!empty($this->textMessage)){
            $data['TextBody'] = $this->textMessage;
            unset($this->textMessage);
        }

        if(!empty($this->from)){
            $data['From'] = $this->from;
            unset($this->from);
        }

        if(!empty($this->to)){
            $data['To'] = implode(',', $this->to);
            unset($this->to);
        }

        if(!empty($this->cc)){
            $data['Cc'] = implode(',', $this->cc);
            unset($this->cc);
        }

        if(!empty($this->bcc)){
            $data['Bcc'] = implode(',', $this->bcc);
            unset($this->bcc);
        }

        if(!empty($this->subject)){
            $data['Subject'] = $this->subject;
            unset($this->subject);
        }

        if(!empty($this->tag)){
            $data['Tag'] = $this->tag;
            unset($this->tag);
        }

        if(!empty($this->replyTo)){
            $data['ReplyTo'] = $this->replyTo;
            unset($this->replyTo);
        }

        if(!empty($this->headers)){
            $data['Headers'] = $this->headers;
            unset($this->headers);
        }

        $payload = json_encode($data);

        return $this->sendRequest($payload);
    }

    protected function sendRequest($data)
    {
        $curl = new Curl();
        $browser = new Browser($curl);
        $response = $browser->post($this->URL, $this->httpHeaders, $data);
        return $response->getContent();
    }
}