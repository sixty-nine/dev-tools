<?php

namespace SixtyNine\DevTools\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder;

class ExpandNestedParamsPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->getParameterBag()->all() as $name => $param) {
            $this->resolve($container, $param, $name);
        }
    }

    protected function resolve(ContainerBuilder $container, $param, $curKey)
    {
        if (!is_array($param)) {
            $container->setParameter($curKey, $param);
            return;
        }

        foreach($param as $key => $value) {
            $nextKey = $curKey == '' ? $key : $curKey . '.' . $key;
            $this->resolve($container, $value, $nextKey);
        }
    }

}
