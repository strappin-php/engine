<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\HtmlHelper;

use function array_fill;

use const null;

/**
 * @phpstan-import-type AttributesArray from HtmlHelper
 */
class HtmlHelperTest extends AbstractTestCase
{
    /** @return array<mixed[]> */
    public function providesAttributesHtml(): array
    {
        return [
            [
                '',
                [],
            ],
            [
                'foo="bar" baz="qux"',
                ['foo' => 'bar', 'baz' => 'qux'],
            ],
            [  // #2
                'specialchars="&amp;&quot;&apos;&lt;&gt;"',
                ['specialchars' => '&"\'<>'],
            ],
            [
                '',
                ['foo' => null],
            ],
        ];
    }

    /**
     * @dataProvider providesAttributesHtml
     * @phpstan-param AttributesArray $attributes
     */
    public function testCreateattributeshtmlCreatesHtml(
        string $expectedHtml,
        array $attributes
    ): void {
        $htmlHelper = new HtmlHelper();

        $this->assertSame($expectedHtml, $htmlHelper->createAttributesHtml($attributes));
    }

    /** @return array<mixed[]> */
    public function providesManyIterations(): array
    {
        return array_fill(0, 100, []);
    }

    /** @dataProvider providesManyIterations */
    public function testCreateuniquenameCreatesAnUniqueName(): void
    {
        $htmlHelper = new HtmlHelper();

        $this->assertMatchesRegularExpression('~^u[0-9a-f]+$~', $htmlHelper->createUniqueName());
    }

    /** @return array<mixed[]> */
    public function providesElements(): array
    {
        return [
            [
                '<div></div>',
                ['div'],
            ],
            [
                '<div>foo</div>',
                ['div', [], 'foo'],
            ],
            [
                '<div class="bar" id="baz">foo</div>',
                ['div', ['class' => 'bar', 'id' => 'baz'], 'foo'],
            ],
            [
                '<br>',
                ['br', [], null],
            ],
            [
                '<br class="bar" id="baz">',
                ['br', ['class' => 'bar', 'id' => 'baz'], null],
            ],
        ];
    }

    /**
     * @dataProvider providesElements
     * @phpstan-param array{string,AttributesArray,string|null} $createElementArgs
     */
    public function testCreateelementCreatesAnElement(
        string $expectedHtml,
        array $createElementArgs
    ): void {
        $htmlHelper = new HtmlHelper();

        $this->assertSame($expectedHtml, $htmlHelper->createElement(...$createElementArgs));
    }
}
