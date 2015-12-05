<?php

namespace Thruster\Bundle\DataModifierBundle\Tests;

use Thruster\Bundle\DataModifierBundle\DataModifierAwareTrait;

/**
 * Class DataModifierAwareTraitTest
 *
 * @package Thruster\Bundle\DataModifierBundle\Tests
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class DataModifierAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testTraitWithProperty()
    {
        $class = new class {
            use DataModifierAwareTrait;

            public $container;
        };

        $group = $this->getMock('\Thruster\Component\DataModifier\DataModifierGroup');
        $groups = $this->getMock('\Thruster\Component\DataModifier\DataModifierGroups');
        $groups->expects($this->once())
            ->method('getGroup')
            ->with('foo_bar')
            ->willReturn($group);

        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->once())
            ->method('get')
            ->with('thruster_data_modifiers')
            ->willReturn($groups);

        $class->container = $container;

        $this->assertEquals($group, $class->getDataModifierGroup('foo_bar'));
    }

    public function testTraitWithMethod()
    {
        $class = new class {
            use DataModifierAwareTrait;

            public $containeris;

            public function getContainer()
            {
                return $this->containeris;
            }
        };

        $group = $this->getMock('\Thruster\Component\DataModifier\DataModifierGroup');
        $groups = $this->getMock('\Thruster\Component\DataModifier\DataModifierGroups');
        $groups->expects($this->once())
            ->method('getGroup')
            ->with('foo_bar')
            ->willReturn($group);

        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->once())
            ->method('get')
            ->with('thruster_data_modifiers')
            ->willReturn($groups);

        $class->containeris = $container;

        $this->assertEquals($group, $class->getDataModifierGroup('foo_bar'));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage DataModifierAwareTrait require Symfony Container accessible via property$container or ->getContainer() method
     */
    public function testTraitWithException()
    {
        $class = new class {
            use DataModifierAwareTrait;
        };

        $class->getDataModifierGroup('foo_bar');
    }
}
