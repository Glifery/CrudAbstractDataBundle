<?php

namespace Glifery\CrudAbstractDataBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class CrudTagCompilerPass implements CompilerPassInterface
{
    const POOL_SERVICE_NAME = 'glifery_crud_abstract_data.service.crud_pool';
    const POOL_METHOD_REGISTER_CRUD = 'registerCrud';
    const CRUD_TAG_NAME = 'glifery_crud_abstract_data.crud';
    const CRUD_TAG_ATTRIBUTE_OBJECT_MANAGER = 'object_manager';
    const CRUD_TAG_ATTRIBUTE_LABEL = 'label';
    const CRUD_METHOD_INIT = 'initAsCrud';
    const CRUD_METHOD_LABEL = 'setLabel';
    const CRUD_METHOD_REGISTER_OM = 'registerObjectManager';

    public function process(ContainerBuilder $container)
    {
        $crudPoolService = $container->findDefinition(self::POOL_SERVICE_NAME);
        $crudServices = $container->findTaggedServiceIds(self::CRUD_TAG_NAME);

        foreach ($crudServices as $id => $tags) {
            $crudDefinition = $container->getDefinition($id);

            $crudPoolService->addMethodCall(self::POOL_METHOD_REGISTER_CRUD, array($id, new Reference($id)));

            foreach ($tags as $attributes) {
                $this->processTagAttributes($crudDefinition, $container, $attributes);
            }

            $this->callInitMethod($crudDefinition, $id, $crudPoolService);
        }
    }

    /**
     * @param Definition $definition
     * @param ContainerBuilder $container
     * @param array $attributes
     */
    private function processTagAttributes(Definition $definition, ContainerBuilder $container, array $attributes)
    {
        if (isset($attributes[self::CRUD_TAG_ATTRIBUTE_OBJECT_MANAGER])) {
            $objectManagerDefinition = $container->findDefinition(
                $attributes[self::CRUD_TAG_ATTRIBUTE_OBJECT_MANAGER]
            );

            $definition->addMethodCall(
                self::CRUD_METHOD_REGISTER_OM,
                array($objectManagerDefinition)
            );
        }

        if (isset($attributes[self::CRUD_TAG_ATTRIBUTE_LABEL])) {
            $definition->addMethodCall(
                self::CRUD_METHOD_LABEL,
                array($attributes[self::CRUD_TAG_ATTRIBUTE_LABEL])
            );
        }
    }

    /**
     * @param Definition $definition
     */
    private function callInitMethod(Definition $definition, $crudName, Definition $crudPool)
    {
        $methodCalls = $definition->getMethodCalls();
        array_unshift($methodCalls, array(self::CRUD_METHOD_INIT, array($crudName, $crudPool)));
        $definition->setMethodCalls($methodCalls);
    }
}