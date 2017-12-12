<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/twig-trigger/blob/master/LICENSE.md
 * @link       https://github.com/flipboxfactory/twig-trigger
 */

namespace flipbox\twig;

use flipbox\twig\tokenparsers\TriggerTokenParser;
use Twig_Extension;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TriggerExtension extends Twig_Extension
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Trigger';
    }

    /**
     * @inheritdoc
     */
    public function getTokenParsers(): array
    {
        return [
            new TriggerTokenParser(),
        ];
    }
}
