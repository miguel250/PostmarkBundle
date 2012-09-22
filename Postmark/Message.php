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

/**
 * Send emails using postmark api
 *
 * @author Miguel Perez <miguel@miguelpz.com>
 */
class Message extends HTTPClient
{
    /**
     * From email
     *
     * @var string
     */
    private $from;

    /**
     * To emails
     *
     * @var array
     */
    private $to = array();

    /**
     * cc emails
     *
     * @var array
     */
    private $cc = array();

    /**
     * bcc emails
     *
     * @var array
     */
    private $bcc = array();

    /**
     * Mail headers
     *
     * @var array
     */
    private $headers = array();

    /**
     * Message subject
     *
     * @var string
     */
    private $subject;

    /**
     * Message tag
     *
     * @var string
     */
    private $tag;

    /**
     * Reply to email
     *
     * @var string
     */
    private $replyTo;

    /**
     * Message body html
     *
     * @var string
     */
    private $htmlMessage;

    /**
     * Message body text
     *
     * @var string
     */
    private $textMessage;

    /**
     * Constructor
     *
     * @param string $apiKey
     * @param string $from
     * @param string $fromName
     */
    public function __construct($apiKey, $from)
    {
        parent::__construct($apiKey);

        $this->from = $from;
    }

    /**
     * Add email and name to TO: field
     *
     * @param string $email
     * @param string $name  null
     */
    public function addTo($email, $name = null)
    {
        if (!empty($name)) {
            $email = "{$name} <{$email}>";
        }
        $this->to[] = $email;
    }

    /**
     * Add cc emails to CC: field
     *
     * @param string $email
     * @param string $name  null
     */
    public function addCC($email, $name = null)
    {
        if (!empty($name)) {
            $email = "{$name} <{$email}>";
        }
        $this->cc[] = $email;
    }

    /**
     * Add bcc emails to BCC: field
     *
     * @param string $email
     * @param string $email null
     */
    public function addBCC($email, $name = null)
    {
        if (!empty($name)) {
            $email = "{$name} <{$email}>";
        }
        $this->bcc[] = $email;
    }

    /**
     * Set ReplyTo email
     *
     * @param string $email
     * @param string $name  null
     */
    public function setReplyTo($email, $name = null)
    {
        if (!empty($name)) {
            $email = "{$name} <{$email}>";
        }
        $this->replyTo = $email;
    }

    /**
     * Set email tag
     *
     * @param string $name
     */
    public function setTag($name)
    {
        $this->tag = $name;
    }

    /**
     * Set message subject
     *
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * HTML message body
     *
     * @param string $htmlMessage
     */
    public function setHtmlMessage($htmlMessage)
    {
        $this->htmlMessage = $htmlMessage;
    }

    /**
     * Text message body
     *
     * @param string $textMessage
     */
    public function setTextMessage($textMessage)
    {
        $this->textMessage = $textMessage;
    }

    /**
     * Set email header
     *
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value)
    {
        $this->headers[] = array(
            'Name'=> $name,
            'Value' => $value
        );
    }

    /**
     * Make request to postmark api
     *
     * @return string
     */
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
