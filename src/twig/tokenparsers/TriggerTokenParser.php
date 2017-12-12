<?php

namespace flipbox\twig\tokenparsers;

use flipbox\twig\nodes\TriggerNode;
use flipbox\twig\traits\PrefixTrait;
use Twig_Error_Syntax;
use Twig_Node_Text;
use Twig_Token;

class TriggerTokenParser extends \Twig_TokenParser
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
    public function getTag()
    {
        return $this->getPrefixTrigger();
    }

    /**
     * @inheritdoc
     */
    public function parse(Twig_Token $token)
    {
        $line = $token->getLine();
        $parser = $this->parser;
        $stream = $parser->getStream();
        $expressionParser = $this->parser->getExpressionParser();
        $nodes = [
            'event' => $expressionParser->parseExpression()
        ];

        $variables = [
            'prefix' => $this->prefix,
            'capture' => true
        ];

        // Look for value as a param
        if ($stream->nextIf(Twig_Token::NAME_TYPE, 'value')) {
            $stream->expect(Twig_Token::OPERATOR_TYPE, '=');
            $variables['capture'] = false;
            $nodes['value'] = $expressionParser->parseExpression();
        }

        // Params 'with'
        $variables['params'] = $this->parseWith();

        // Close out opening tag
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        // Is there a closing tag?
        if ($variables['capture']) {
            $nodes['value'] = $parser->subparse(array($this, 'decideBlockEnd'), true);
            $stream->expect(Twig_Token::BLOCK_END_TYPE);
        }

        return new TriggerNode($nodes, $variables, $line, $this->getTag());
    }

    /**
     * @return null|Twig_Node_Text
     * @throws Twig_Error_Syntax
     */
    protected function parseWith()
    {
        $parser = $this->parser;
        $stream = $parser->getStream();
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
