<?php

namespace SixtyNine\DevTools\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder as BaseContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\YamlFileLoader,
    Symfony\Component\Config\FileLocator,
    SixtyNine\DevTools\DependencyInjection\Compiler\ExpandNestedParamsPass;

class ContainerBuilder extends BaseContainerBuilder
{
    public function __construct()
    {
        parent::__construct();

        $this->addCompilerPass(new ExpandNestedParamsPass());
    }

    public static function build($fileName, $initialParams = array())
    {
        $container = new ContainerBuilder();

        foreach ($initialParams as $key => $value) {
            $container->setParameter($key, $value);
        }

        $loader = new YamlFileLoader($container, new FileLocator());
        $loader->load($fileName);

        $container->compile();

        return $container;
    }
}
