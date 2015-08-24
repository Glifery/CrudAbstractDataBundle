<?php

namespace Glifery\CrudAbstractDataBundle;

use Glifery\CrudAbstractDataBundle\DependencyInjection\Compiler\CrudTagCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GliferyCrudAbstractDataBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CrudTagCompilerPass());
    }
}
