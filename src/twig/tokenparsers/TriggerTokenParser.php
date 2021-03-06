<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://github.com/flipboxfactory/twig-trigger/blob/master/LICENSE.md
 * @link       https://github.com/flipboxfactory/twig-trigger
 */

namespace flipbox\twig\tokenparsers;

use flipbox\twig\nodes\TriggerNode;
use Twig_Error_Syntax;
use Twig_Node_Text;
use Twig_Token;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TriggerTokenParser extends \Twig_TokenParser
{
    /**
     * @inheritdoc
     */
    public function getTag()
    {
        return 'trigger';
    }

    /**
     * @inheritdoc
     */
    public function parse(Twig_Token $token)
    {
        $line = $token->getLine();
        $parser = $this->parser;

        $nodes = [
            'event' => $this->parser->getExpressionParser()->parseExpression()
        ];

        $variables = [
            'capture' => true
        ];

        // Look for value as an attribute
        $variables['capture'] = $this->parseValueAttribute($nodes);

        // Params 'with'
        $variables['params'] = $this->parseWith();

        // Close out opening tag
        $parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

        // Is there a closing tag?
        if ($variables['capture']) {
            $this->parseValueBetweenTags($nodes);
        }

        return new TriggerNode($nodes, $variables, $line, $this->getTag());
    }

    /**
     * @param array $nodes
     * @return bool
     * @throws Twig_Error_Syntax
     */
    protected function parseValueAttribute(array &$nodes): bool
    {
        $parser = $this->parser;
        $stream = $parser->getStream();
        $expressionParser = $this->parser->getExpressionParser();

        // Look for value as a param
        if ($stream->nextIf(Twig_Token::NAME_TYPE, 'value')) {
            $stream->expect(Twig_Token::OPERATOR_TYPE, '=');

            $nodes['value'] = $expressionParser->parseExpression();
            return false;
        }

        return true;
    }

    /**
     * @param array $nodes
     * @throws Twig_Error_Syntax
     */
    protected function parseValueBetweenTags(array &$nodes)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();

        $nodes['value'] = $parser->subparse(array($this, 'decideBlockEnd'), true);
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
    }

    /**
     * @return null|Twig_Node_Text
     * @throws Twig_Error_Syntax
     */
    protected function parseWith()
    {
        $stream = $this->parser->getStream();
        $expressionParser = $this->parser->getExpressionParser();

        // Is there an options param?
        if ($stream->test(Twig_Token::NAME_TYPE, 'with')) {
            $stream->next();
            return $expressionParser->parseExpression();
        }

        return null;
    }

    /**
     * @param Twig_Token $token
     *
     * @return bool
     */
    public function decideBlockEnd(Twig_Token $token): bool
    {
        return $token->test('end' . strtolower($this->getTag()));
    }
}
