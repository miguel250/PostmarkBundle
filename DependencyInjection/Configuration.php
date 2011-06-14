<?php

/*
 * This file is part of the Postmarkapp\PostmarkBundle
 *
 * (c) Miguel Perez <miguel@mlpz.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Postmarkapp\PostmarkBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Dependency injection configuration
 *
 * @author Miguel Perez  <miguel@mlpz.com>
 */
class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('postmarkapp_postmark');

        $rootNode
                ->children()
                ->scalarNode('apikey')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('from_address')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('from_name')->defaultNull()->end()
                ->end();

        return $treeBuilder;
    }

}