<?php

namespace Thruster\Bundle\DataModifierBundle;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use function Funct\arrayKeyNotExists;
use function Funct\Collection\get;

/**
 * Class ThrusterDataModifierBundle
 *
 * @package Thruster\Bundle\DataModifierBundle
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class ThrusterDataModifierBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new class implements CompilerPassInterface
            {
                /**
                 * @inheritDoc
                 */
                public function process(ContainerBuilder $container)
                {
                    $groupsId         = 'thruster_data_modifiers';
                    $groupsDefinition = new Definition('Thruster\Component\DataModifier\DataModifierGroups');

                    $container->setDefinition($groupsId, $groupsDefinition);

                    foreach ($container->findTaggedServiceIds('thruster_data_modifier') as $id => $tags) {
                        foreach ($tags as $tag) {
                            if (arrayKeyNotExists('group', $tag)) {
                                throw new \LogicException(
                                    sprintf(
                                        'DataModifier tag requires attribute "group" in definition "%s"',
                                        $id
                                    )
                                );
                            }

                            $groupName = $tag['group'];
                            $priority = get($tag, 'priority', 0);

                            $groupId = 'thruster_data_modifier.group.' . $groupName;
                            if ($container->hasDefinition($groupId)) {
                                $groupDefinition = $container->getDefinition($groupId);
                            } else {
                                $groupDefinition = new Definition('Thruster\Component\DataModifier\DataModifierGroup');
                                $container->setDefinition($groupId, $groupDefinition);

                                $groupsDefinition->addMethodCall('addGroup', [$groupName, new Reference($groupId)]);
                            }

                            $groupDefinition->addMethodCall('addModifier', [new Reference($id), $priority]);
                        }
                    }
                }
            }
        );
    }
}
