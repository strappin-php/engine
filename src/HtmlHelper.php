<?php

declare(strict_types=1);

namespace StrappinPhp\Engine;

use function htmlspecialchars;
use function implode;
use function strlen;
use function uniqid;

use const ENT_HTML5;
use const ENT_QUOTES;
use const false;
use const null;

/**
 * @phpstan-type AttributeValue string|null
 * @phpstan-type AttributesArray array<string,AttributeValue>
 */
class HtmlHelper
{
    private function escape(string $value, string $context = null): string
    {
        $flags = 'html_attr' === $context
            ? ENT_HTML5 | ENT_QUOTES
            : ENT_HTML5
        ;

        return htmlspecialchars($value, $flags, '', false);
    }

    /**
     * @phpstan-param AttributesArray $attributes
     */
    public function createAttributesHtml(array $attributes): string
    {
        if (!$attributes) {
            return '';
        }

        $pairs = [];

        foreach ($attributes as $name => $value) {
            if (null === $value) {
                continue;
            }

            $valueEscaped = $this->escape($value, 'html_attr');
            $pairs[] = "{$name}=\"{$valueEscaped}\"";
        }

        return implode(' ', $pairs);
    }

    public function createUniqueName(): string
    {
        return uniqid('u');
    }

    /**
     * @phpstan-param AttributesArray $attributes
     */
    public function createElement(
        string $tagName,
        array $attributes = [],
        ?string $content = ''
    ): string {
        $attrsHtml = $this->createAttributesHtml($attributes);

        if (strlen($attrsHtml)) {
            $attrsHtml = " {$attrsHtml}";
        }

        if (null === $content) {
            return "<{$tagName}{$attrsHtml}>";
        }

        return "<{$tagName}{$attrsHtml}>{$content}</{$tagName}>";
    }
}
