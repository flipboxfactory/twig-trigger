<?php

namespace flipbox\twig\traits;

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
