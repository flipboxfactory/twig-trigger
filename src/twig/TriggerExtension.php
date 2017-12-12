<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/twig-trigger/blob/master/LICENSE.md
 * @link       https://github.com/flipboxfactory/twig-trigger
 */

namespace flipbox\twig;

use flipbox\twig\tokenparsers\TriggerTokenParser;
use flipbox\twig\traits\PrefixTrait;
use Twig_Extension;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TriggerExtension extends Twig_Extension
{
    use PrefixTrait;

    /**
     * @param string|null $prefix
     */
    public function __construct(string $prefix = null)
    {
        $this->prefix = $prefix;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->getPrefixTrigger();
    }

    /**
     * @inheritdoc
     */
    public function getTokenParsers(): array
    {
        return [
            new TriggerTokenParser($this->prefix),
        ];
    }
}
