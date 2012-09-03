<?php

/*
 * This file is part of the MZ\PostmarkBundle
 *
 * (c) Miguel Perez <miguel@miguelpz.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MZ\PostmarkBundle\Tests\DependencyInjection;

use MZ\PostmarkBundle\DependencyInjection\Configuration;

/**
 * Test Configuration
 *
 * @author Miguel Perez <miguel@mlpz.mp>
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test get config tree
     *
     * @covers  MZ\PostmarkBundle\DependencyInjection\Configuration::getConfigTreeBuilder
     */
    public function testThatCanGetConfigTreeBuilder()
    {
        $configuration = new Configuration();
        $this->assertInstanceOf('Symfony\Component\Config\Definition\Builder\TreeBuilder', $configuration->getConfigTreeBuilder());
    }
}
