<?php

namespace Thruster\Bundle\DataModifierBundle;

use Thruster\Component\DataModifier\DataModifierGroup;

/**
 * Trait DataModifierAwareTrait
 *
 * @package Thruster\Bundle\DataModifierBundle
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
trait DataModifierAwareTrait
{
    public function getDataModifierGroup(string $groupName) : DataModifierGroup
    {
        if (property_exists($this, 'container')) {
            return $this->container->get('thruster_data_modifiers')->getGroup($groupName);
        } elseif (method_exists($this, 'getContainer')) {
            return $this->getContainer()->get('thruster_data_modifiers')->getGroup($groupName);
        }

        throw new \LogicException(
            'DataModifierAwareTrait require Symfony Container accessible via property' .
            '$container or ->getContainer() method'
        );
    }
}
