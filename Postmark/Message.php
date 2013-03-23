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

use MZ\PostmarkBundle\Postmark\HTTPClient,
    Symfony\Component\HttpFoundation\File\File;

/**
 * Send emails using postmark api
 *
 * @author Miguel Perez <miguel@miguelpz.com>
 */
class Message
{
    /**
     * @var \MZ\PostmarkBundle\Postmark\HTTPClient
     */
    protected $client;

    /**
     * Contains all the messages that are going to be send.
     *
     * @var array
     */
    protected $queue = array();

    /**
     * From email
     *
     * @var string
     */
    protected $from;

    /**
     * To emails
     *
     * @var array
     */
    protected $to = array();

    /**
     * cc emails
     *
     * @var array
     */
    protected $cc = array();

    /**
     * bcc emails
     *
     * @var array
     */
    protected $bcc = array();

    /**
     * Mail headers
     *
     * @var array
     */
    protected $headers = array();

    /**
     * Message subject
     *
     * @var string
     */
    protected $subject;

    /**
     * Message tag
     *
     * @var string
     */
    protected $tag;

    /**
     * Message attachments
     *
     * @var array
     */
    protected $attachments = array();

    /**
     * Reply to email
     *
     * @var string
     */
    protected $replyTo;

    /**
     * Message body html
     *
     * @var string
     */
    protected $htmlMessage;

    /**
     * Message body text
     *
     * @var string
     */
    protected $textMessage;

    /**
     * Constructor
     *
     * @param HTTPClient $client
     * @param string     $from_email
     * @param string     $from_name
     */
    public function __construct(HTTPClient $client, $from_email, $from_name = null)
    {
        $this->client = $client;
        $this->setFrom($from_email, $from_name);
    }

    /**
     * Set from email and name
     *
     * @param string $email
     * @param string $name  null
     */
    public function setFrom($email, $name = null)
    {
        if (!empty($name)) {
            $email = "{$name} <{$email}>";
        }

        $this->from = $email;
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
     * Add attachment
     *
     * @param File $file
     * @param string $filename  null
     * @param string $mimeType  nill
     */
    public function addAttachment(File $file, $filename = null, $mimeType = null)
    {
        if (empty($filename)) {
            $filename = $file->getFilename();
        }

        if (empty($mimeType)) {
            $mimeType = $file->getMimeType();
        }

    	$this->attachments[] = array(
            'Name' => $filename,
            'Content' => base64_encode(file_get_contents($file->getRealPath())),
            'ContentType' => $mimeType
        );
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
     * Queue the message to send it later via the batch method
     */
    public function queue()
    {
        $data = array();

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

        if (!empty($this->attachments)) {
            $data['Attachments'] = $this->attachments;
            unset($this->attachments);
        }

        if (!empty($this->replyTo)) {
            $data['ReplyTo'] = $this->replyTo;
            unset($this->replyTo);
        }

        if (!empty($this->headers)) {
            $data['Headers'] = $this->headers;
            unset($this->headers);
        }

        if (!empty($data)) {
            $this->queue[] = $data;

            return true;
        }

        return false;
    }

    /**
     * Make request to postmark api
     *
     * @return string
     */
    public function send()
    {
        $this->queue();

        if (count($this->queue) === 1) {
            $payload = json_encode($this->queue[0]);
            $path = 'email';
        } else {
            $payload = json_encode($this->queue);
            $path = 'email/batch';
        }

        $this->queue = array();

        return $this->client->sendRequest($path, $payload);
    }
}
