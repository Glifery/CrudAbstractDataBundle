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
    const CRUD_METHOD_REGISTER_OBJECT_MANAGER = 'registerObjectManager';
    const CRUD_METHOD_REGISTER_TEMPLATE_SET = 'setTemplateSet';
    const TEMPLATE_SET_DEFAULT_NAME = 'glifery_crud_abstract_data.service.crud_template_set';

    public function process(ContainerBuilder $container)
    {
        $crudPoolDefinition = $container->findDefinition(self::POOL_SERVICE_NAME);
        $crudServices = $container->findTaggedServiceIds(self::CRUD_TAG_NAME);

        foreach ($crudServices as $crudName => $tags) {
            $crudDefinition = $container->getDefinition($crudName);

            $this->registerCrudInPool($crudName, $crudPoolDefinition);
            $this->registerDefaultTemplateSet($crudDefinition, $container);

            foreach ($tags as $attributes) {
                $this->processTagAttributes($crudDefinition, $container, $attributes);
            }

            $this->callCrudInitMethod($crudDefinition, $crudName, $crudPoolDefinition);
        }
    }

    /**
     * @param string $crudName
     * @param Definition $crudPoolDefinition
     */
    private function registerCrudInPool($crudName, Definition $crudPoolDefinition)
    {
        $crudPoolDefinition->addMethodCall(self::POOL_METHOD_REGISTER_CRUD, array($crudName, new Reference($crudName)));
    }

    private function registerDefaultTemplateSet(Definition $crudDefinition, ContainerBuilder $container)
    {
        if ($templateSetDefinition = $container->findDefinition(self::TEMPLATE_SET_DEFAULT_NAME)) {
            $this->callMethodFirst($crudDefinition, self::CRUD_METHOD_REGISTER_TEMPLATE_SET, array($templateSetDefinition));
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
                self::CRUD_METHOD_REGISTER_OBJECT_MANAGER,
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
     * @param Definition $crudDefinition
     */
    private function callCrudInitMethod(Definition $crudDefinition, $crudName, Definition $crudPoolDefinition)
    {
        $this->callMethodFirst($crudDefinition, self::CRUD_METHOD_INIT, array($crudName, $crudPoolDefinition));
    }

    /**
     * @param Definition $definition
     * @param string $methodName
     * @param array $arguments
     */
    private function callMethodFirst(Definition $definition, $methodName, array $arguments)
    {
        $newMethodCall = array($methodName, $arguments);

        $currentMethodCalls = $definition->getMethodCalls();
        array_unshift($currentMethodCalls, $newMethodCall);
        $definition->setMethodCalls($currentMethodCalls);
    }
}