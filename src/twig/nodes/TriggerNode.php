<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/twig-trigger/blob/master/LICENSE.md
 * @link       https://github.com/flipboxfactory/twig-trigger
 */

namespace flipbox\twig\nodes;

use flipbox\twig\events\TwigTriggerEvent;
use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression_Constant;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TriggerNode extends Twig_Node
{
    /**
     * @inheritdoc
     */
    public function compile(Twig_Compiler $compiler)
    {
        $this->compileValue($compiler);

        $compiler
            ->addDebugInfo($this)
            ->write("\$event = ")
            ->raw("new " . TwigTriggerEvent::class . "([")
            ->raw("'params' => ");

        $this->compileParams($compiler);

        $compiler
            ->write(", 'value' => \$value")
            ->raw("]);\n\n")
            ->write('Craft::$app->getView()->trigger(');

        $this->eventName($compiler);

        $compiler->raw(", \$event);\n\n");

        $compiler->write("echo \$event->value;");
    }

    /**
     * @param Twig_Compiler $compiler
     */
    protected function compileValue(Twig_Compiler $compiler)
    {
        $value = $this->getNode('value');
        if ($this->getAttribute('capture')) {
            $compiler
                ->write("ob_start();\n")
                ->subcompile($value)
                ->raw("\$value = ob_get_clean();\n");
        } else {
            $compiler
                ->raw("\$value = ")
                ->subcompile($value)
                ->raw(";\n");
        }
    }

    /**
     * @param Twig_Compiler $compiler
     */
    protected function compileParams(Twig_Compiler $compiler)
    {
        if ($this->hasAttribute('params') && $params = $this->getAttribute('params')) {
            $compiler->subcompile($params, false);
        } else {
            $compiler->raw('[]');
        }
    }

    /**
     * @param Twig_Compiler $compiler
     */
    protected function eventName(Twig_Compiler $compiler)
    {
        $node = $this->getNode('event');
        if ($node && $node instanceof Twig_Node_Expression_Constant) {
            $prefix = '';
            if ($this->hasAttribute('prefix')) {
                $prefix = $this->getAttribute('prefix').'.';
            }
            $compiler->raw("'".$prefix . $node->getAttribute('value')."'");
            return;
        }

        $compiler->subcompile($this->getNode('event'), false);
        return;
    }
}
