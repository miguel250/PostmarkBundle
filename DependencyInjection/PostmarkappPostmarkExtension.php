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

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Extension for postmark bundle
 *
 * @author Miguel Perez  <miguel@mlpz.com>
 */
class PostmarkappPostmarkExtension extends Extension
{
    protected $resources = array(
        'postmark' => 'postmark.xml'
    );

    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $this->loadDefaults($container);

        if (isset($config['alias'])) {
            $container->setAlias($config['alias'], 'postmarkapp_postmark');
        }

      foreach (array('apikey', 'from_address', 'from_name') as $attribute) {
            if (isset($config[$attribute])) {
                $container->setParameter('postmarkapp_postmark.'.$attribute, $config[$attribute]);
            }
        }
    }

    /**
     * @codeCoverageIgnore
     */
    protected function loadDefaults($container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(array(__DIR__.'/../Resources/config', __DIR__.'/Resources/config')));

        foreach ($this->resources as $resource) {
            $loader->load($resource);
        }
    }
}
