<?php

namespace flipbox\twig\events;

use yii\base\Event;

class TwigTriggerEvent extends Event
{
    // Properties
    // =========================================================================

    public $params = [];

    public $value;
}
