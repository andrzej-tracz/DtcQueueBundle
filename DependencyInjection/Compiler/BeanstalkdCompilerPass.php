<?php

namespace Dtc\QueueBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class BeanstalkdCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('dtc_queue.beanstalkd.host')) {
            $definition = new Definition(
                'Pheanstalk\\Pheanstalk',
                [$container->getParameter('dtc_queue.beanstalkd.host')]
            );
            $container->setDefinition('dtc_queue.beanstalkd', $definition);
            $definition = $container->getDefinition('dtc_queue.job_manager.beanstalkd');
            $definition->addMethodCall('setBeanstalkd', [new Reference('dtc_queue.beanstalkd')]);
            if ($container->hasParameter('dtc_queue.beanstalkd.tube')) {
                $definition->addMethodCall('setTube', [$container->getParameter('dtc_queue.beanstalkd.tube')]);
            }
        }
    }
}
