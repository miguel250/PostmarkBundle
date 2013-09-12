<?php

/*
 * This file is part of the MZ\PostmarkBundle
 *
 * (c) Miguel Perez <miguel@mlpz.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MZ\PostmarkBundle\Tests\Postmark;

use MZ\PostmarkBundle\Postmark\Message;
use MZ\PostmarkBundle\Postmark\HTTPClient;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Test message
 *
 * @author Miguel Perez <miguel@mlpz.mp>
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test send request
     *
     * @covers  MZ\PostmarkBundle\Postmark\Message::Send
     */
    public function testSendMessage()
    {
        $client = new HTTPClient('POSTMARK_API_TEST');
        $message = new Message($client, 'test@test.com', 'test name');
        $message->addTo('test1@test.com', 'Test Test');
        $message->setSubject('subject');
        $message->setHTMLMessage('<b>email body</b>');
        $message->addAttachment(new File(__FILE__), 'attachment.php', 'text/plain'); // Attachment with custom filename and mimetype
        $response = json_decode($message->send(), true);

        $this->assertEquals('"Test Test" <test1@test.com>', $response['To']);
        $this->assertEquals(0, $response['ErrorCode']);
        $this->assertEquals('Test job accepted', $response['Message']);
    }

    /**
     * Test multiple send requests
     *
     * @covers  MZ\PostmarkBundle\Postmark\Message::Send
     */
    public function testSendMultipleMessages()
    {
        $client = new HTTPClient('POSTMARK_API_TEST');
        $message = new Message($client, 'test@test.com', 'multiple test one');
        $message->addTo('test2@test.com', 'Test Test');
        $message->setSubject('subject');
        $message->setHTMLMessage('<b>email body</b>');
        $message->addAttachment(new File(__FILE__), 'attachment.php'); // Attachment with custom filename
        $response = json_decode($message->send(), true);

        $this->assertEquals('"Test Test" <test2@test.com>', $response['To']);
        $this->assertEquals(0, $response['ErrorCode']);
        $this->assertEquals('Test job accepted', $response['Message']);

        // Send a second message
        $message->addTo('test3@test.com', 'Test Test');
        $message->setSubject('subject second e-mail');
        $message->setHTMLMessage('<b>second email body</b>');
        $message->addAttachment(new File(__FILE__), 'attachment.php'); // Attachment without custom filename or mimetype
        $response = json_decode($message->send(), true);

        $this->assertEquals('"Test Test" <test3@test.com>', $response['To']);
        $this->assertEquals(0, $response['ErrorCode']);
        $this->assertEquals('Test job accepted', $response['Message']);

        // Send a third message, without attachment
        $message->addTo('test4@test.com', 'Test Test');
        $message->setSubject('subject second e-mail');
        $message->setHTMLMessage('<b>second email body</b>');
        $response = json_decode($message->send(), true);

        $this->assertEquals('"Test Test" <test4@test.com>', $response['To']);
        $this->assertEquals(0, $response['ErrorCode']);
        $this->assertEquals('Test job accepted', $response['Message']);
    }

    /**
     * Test multiple messages via batch
     *
     * @covers  MZ\PostmarkBundle\Postmark\Message::Send
     */
    public function testSendMultipleMessagesViaBatch()
    {
        $client = new HTTPClient('POSTMARK_API_TEST');
        $message = new Message($client, 'test@test.com', 'multiple test one');
        $message->addTo('test2@test.com', 'Test Test');
        $message->setSubject('subject');
        $message->setHTMLMessage('<b>email body</b>');
        $message->addAttachment(new File(__FILE__), 'attachment.php'); // Attachment with custom filename
        $message->queue();

        // Send a second message
        $message->addTo('test3@test.com', 'Test Test');
        $message->setSubject('subject second e-mail');
        $message->setHTMLMessage('<b>second email body</b>');
        $message->addAttachment(new File(__FILE__), 'attachment.php'); // Attachment without custom filename or mimetype
        $message->queue();

        // Send a third message, without attachment
        $message->addTo('test4@test.com', 'Test Test');
        $message->setSubject('subject second e-mail');
        $message->setHTMLMessage('<b>second email body</b>');

        // Send the queue
        $responses = $message->send();
        $this->assertEquals(1, count($responses));
        $response = json_decode($responses[0], true);

        $this->assertEquals('"Test Test" <test2@test.com>', $response[0]['To']);
        $this->assertEquals(0, $response[0]['ErrorCode']);
        $this->assertEquals('Test job accepted', $response[0]['Message']);

        $this->assertEquals('"Test Test" <test3@test.com>', $response[1]['To']);
        $this->assertEquals(0, $response[1]['ErrorCode']);
        $this->assertEquals('Test job accepted', $response[1]['Message']);

        $this->assertEquals('"Test Test" <test4@test.com>', $response[2]['To']);
        $this->assertEquals(0, $response[2]['ErrorCode']);
        $this->assertEquals('Test job accepted', $response[2]['Message']);
    }

    /**
     * Test batch greater than 500
     *
     * @covers  MZ\PostmarkBundle\Postmark\Message::Send
     */
    public function testIssue20()
    {
        $batchNumber = 501;
        $count = 0;

        $client = new HTTPClient('POSTMARK_API_TEST');
        $message = new Message($client, 'test@test.com', 'test batch greater than 500');

        while ($batchNumber >= $count) {
            $message->addTo('test3@test.com', 'Test Test');
            $message->setSubject('subject second e-mail');
            $message->setHTMLMessage('<b>second email body</b>');
            $message->queue();
            $count++;
        }

        $responses = $message->send();
        $this->assertEquals(2, count($responses));
    }
}
