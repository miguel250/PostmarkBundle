<?php

/*
 * This file is part of the MZ\PostmarkBundle
 *
 * (c) Miguel Perez <miguel@mlpz.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MZ\PostmarkBundle\Tests\DependencyInjection;

use MZ\PostmarkBundle\DependencyInjection\MZPostmarkExtension;

/**
 * Test MZPostmarkBundleExtension
 *
 * @author Miguel Perez <miguel@mlpz.mp>
 */
class MZPostmarkExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test load failed
     *
     * @covers MZ\PostmarkBundle\DependencyInjection\MZPostmarkExtension::load
     */
    public function testLoadFailed()
    {
        $container = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ContainerBuilder')
                ->disableOriginalConstructor()
                ->getMock();

        $extension = $this->getMockBuilder('MZ\PostmarkBundle\DependencyInjection\MZPostmarkExtension')
                ->getMock();

        $extension->load(array(array()), $container);
    }

    /**
     * Test setParameters
     *
     * @covers MZ\PostmarkBundle\DependencyInjection\MZPostmarkExtension::load
     */
    public function testLoadSetParameters()
    {
        $container = $this->getMockBuilder('Symfony\\Component\\DependencyInjection\\ContainerBuilder')
                ->disableOriginalConstructor()
                ->getMock();

        $parameterBag = $this->getMockBuilder('Symfony\Component\DependencyInjection\ParameterBag\\ParameterBag')
                ->disableOriginalConstructor()
                ->getMock();

        $parameterBag->expects($this->any())
                ->method('add');

        $container->expects($this->any())
                ->method('getParameterBag')
                ->will($this->returnValue($parameterBag));

        $extension = new MZPostmarkExtension();
        $configs = array(
			array('api_key' => 'foo'),
			array('from_email' => 'foo'),
			array('from_name' => 'foo')
		);
        $extension->load($configs, $container);
    }
}
