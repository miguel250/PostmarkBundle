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
        $message = new Message('POSTMARK_API_TEST', 'test@test.com');
        $message->addTo('test2@test.com', 'Test Test');
        $message->setSubject('subject');
        $message->setHTMLMessage('<b>email body</b>');
        $response = json_decode($message->send(), true);

        $this->assertEquals($response['To'], 'Test Test <test2@test.com>');
        $this->assertEquals($response['ErrorCode'], 0);
        $this->assertEquals($response['Message'], 'Test job accepted');
    }
}
