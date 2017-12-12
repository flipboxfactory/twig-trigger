<?php
/**
 * @link      https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   https://craftcms.github.io/license/
 */

namespace flipbox\twig;

use flipbox\twig\tokenparsers\TriggerTokenParser;
use flipbox\twig\traits\PrefixTrait;
use Twig_Extension;

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
