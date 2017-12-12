<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/twig-trigger/blob/master/LICENSE.md
 * @link       https://github.com/flipboxfactory/twig-trigger
 */

namespace flipbox\twig\traits;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait PrefixTrait
{
    protected $prefix;

    /**
     * @return string
     */
    protected function getPrefixTrigger()
    {
        $name = 'trigger';
        if ($this->prefix) {
            $name = strtolower($this->prefix) . $name;
        }

        return $name;
    }
}
