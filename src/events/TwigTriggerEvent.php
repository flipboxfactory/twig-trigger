<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/twig-trigger/blob/master/LICENSE.md
 * @link       https://github.com/flipboxfactory/twig-trigger
 */

namespace flipbox\twig\events;

use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TwigTriggerEvent extends Event
{
    // Properties
    // =========================================================================

    public $params = [];

    public $value;
}
