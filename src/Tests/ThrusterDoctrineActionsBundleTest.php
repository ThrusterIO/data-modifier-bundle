<?php

namespace Thruster\Bundle\DataModifierBundle\Tests;

use Thruster\Bundle\DataModifierBundle\ThrusterDataModifierBundle;

/**
 * Class ThrusterDoctrineActionsBundleTest
 *
 * @package Thruster\Bundle\DataModifierBundle\Tests
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class ThrusterDoctrineActionsBundleTest extends \PHPUnit_Framework_TestCase
{

    public function testAddCompilerPass()
    {
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerBuilder');

        $container->expects($this->once())
            ->method('addCompilerPass')
            ->will(
                $this->returnCallback(
                    function ($compilerPass) use ($container) {
                        $compilerPass->process($container);
                    }
                )
            );

        $container->expects($this->at(0))
            ->method('setDefinition')
            ->will(
                $this->returnCallback(
                    function($id, $definition) {
                        $this->assertSame('thruster_data_modifiers', $id);
                        $this->assertInstanceOf('\Symfony\Component\DependencyInjection\Definition', $definition);

                        $this->assertSame(
                            'Thruster\Component\DataModifier\DataModifierGroups',
                            $definition->getClass()
                        );
                    }
                )
            );

        $container->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('thruster_data_modifier')
            ->willReturn([
                'demo_modifier' => [
                    ['group' => 'demo']
                ],
                'second_demo_modifier' => [
                    ['group' => 'demo']
                ]
            ]);

        $container->expects($this->at(1))
            ->method('setDefinition')
            ->will(
                $this->returnCallback(
                    function($id, $definition) {
                        $this->assertSame('thruster_data_modifier.group.demo', $id);
                        $this->assertInstanceOf('\Symfony\Component\DependencyInjection\Definition', $definition);

                        $this->assertSame(
                            'Thruster\Component\DataModifier\DataModifierGroup',
                            $definition->getClass()
                        );
                    }
                )
            );

        $bundle = new ThrusterDataModifierBundle();
        $bundle->build($container);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage DataModifier tag requires attribute "group" in definition "demo_modifier"
     */
    public function testAddCompilerPassWithoutGroupAttribute()
    {
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerBuilder');

        $container->expects($this->once())
            ->method('addCompilerPass')
            ->will(
                $this->returnCallback(
                    function ($compilerPass) use ($container) {
                        $compilerPass->process($container);
                    }
                )
            );

        $container->expects($this->at(0))
            ->method('setDefinition')
            ->will(
                $this->returnCallback(
                    function($id, $definition) {
                        $this->assertSame('thruster_data_modifiers', $id);
                        $this->assertInstanceOf('\Symfony\Component\DependencyInjection\Definition', $definition);

                        $this->assertSame(
                            'Thruster\Component\DataModifier\DataModifierGroups',
                            $definition->getClass()
                        );
                    }
                )
            );

        $container->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with('thruster_data_modifier')
            ->willReturn([
                'demo_modifier' => [[]]
            ]);

        $bundle = new ThrusterDataModifierBundle();
        $bundle->build($container);
    }
}
