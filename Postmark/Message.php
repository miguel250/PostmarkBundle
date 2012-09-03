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

use MZ\PostmarkBundle\Postmark\HTTPClient;

class Message extends HTTPClient
{
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
        parent::__construct($apiKey);

        if (!empty($fromName)) {
            $from = "{$fromName} <{$from}>";
        }
        $this->from = $from;
    }

    public function addTo($email, $name = null)
    {
        if (!empty($name)) {
            $email = "{$name} <{$email}>";
        }
        $this->to[] = $email;
    }

    public function addCC($email, $name = null)
    {
        if (!empty($name)) {
            $email = "{$name} <{$email}>";
        }
        $this->cc[] = $email;
    }

    public function addBCC($email, $name = null)
    {
        if (!empty($name)) {
            $email = "{$name} <{$email}>";
        }
        $this->bcc[] = $email;
    }

    public function setReplyTo($email, $name = null)
    {
        if (!empty($name)) {
            $email = "{$name} <{$email}>";
        }
        $this->replyTo = $email;
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

        if (!empty($this->htmlMessage)) {
            $data['HtmlBody'] = $this->htmlMessage;
            unset($this->htmlMessage);
        }

        if (!empty($this->textMessage)) {
            $data['TextBody'] = $this->textMessage;
            unset($this->textMessage);
        }

        if (!empty($this->from)) {
            $data['From'] = $this->from;
            unset($this->from);
        }

        if (!empty($this->to)) {
            $data['To'] = implode(',', $this->to);
            unset($this->to);
        }

        if (!empty($this->cc)) {
            $data['Cc'] = implode(',', $this->cc);
            unset($this->cc);
        }

        if (!empty($this->bcc)) {
            $data['Bcc'] = implode(',', $this->bcc);
            unset($this->bcc);
        }

        if (!empty($this->subject)) {
            $data['Subject'] = $this->subject;
            unset($this->subject);
        }

        if (!empty($this->tag)) {
            $data['Tag'] = $this->tag;
            unset($this->tag);
        }

        if (!empty($this->replyTo)) {
            $data['ReplyTo'] = $this->replyTo;
            unset($this->replyTo);
        }

        if (!empty($this->headers)) {
            $data['Headers'] = $this->headers;
            unset($this->headers);
        }

        $payload = json_encode($data);

        return $this->sendRequest($payload);
    }
}
