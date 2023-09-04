<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests\NavsTabs;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;
use StrappinPhp\Engine\NavsTabs\Nav;
use StrappinPhp\Engine\NavsTabs\NavItem;

use const null;

/**
 * @phpstan-import-type PropsArray from AbstractComponent
 * @phpstan-import-type AttributesArray from HtmlHelper
 */
class NavTest extends AbstractTestCase
{
    public function testIsAComponent(): void
    {
        $this->assertTrue($this->getTestedClass()->isSubclassOf(AbstractComponent::class));
    }

    /** @return array<mixed[]> */
    public function providesNavPropsAndAttrs(): array
    {
        $htmlHelper = new HtmlHelper();

        return [
            [
                [
                    'subtype' => null,
                ],
                [
                    'id' => null,
                    'class' => 'nav',
                    'role' => null,
                ],
                new Nav($htmlHelper),
            ],
            [
                [
                    'subtype' => Nav::SUBTYPE_TABS,
                ],
                [
                    'id' => null,
                    'class' => 'nav',
                    'role' => null,
                ],
                new Nav($htmlHelper, Nav::SUBTYPE_TABS),
            ],
        ];
    }

    /**
     * @dataProvider providesNavPropsAndAttrs
     * @phpstan-param PropsArray $expectedProps
     * @phpstan-param AttributesArray $expectedAttrs
     */
    public function testIsInstantiable(
        array $expectedProps,
        array $expectedAttrs,
        Nav $nav
    ): void {
        $this->assertSame($expectedProps, $nav->props());
        $this->assertSame($expectedAttrs, $nav->attrs());
    }

    /** @return array<mixed[]> */
    public function providesComponentHtml(): array
    {
        $htmlHelper = new HtmlHelper();

        return [
            [
                <<<END
                <ul class="nav">
                </ul>
                END,
                new Nav($htmlHelper),
            ],
            [
                <<<END
                <ul class="nav nav-tabs" role="tablist">
                </ul>
                END,
                new Nav($htmlHelper, Nav::SUBTYPE_TABS),
            ],
            [
                <<<END
                <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                <button id="foo" class="nav-link" type="button" role="tab" aria-selected="false" data-bs-toggle="tab" data-bs-target="#tab-pane-1" aria-controls="tab-pane-1">Tab Pane 1</button>
                </li>
                <li class="nav-item">
                <a id="bar" class="nav-link" href="https://example.com/">External Link</a>
                </li>
                </ul>
                END,
                (new Nav($htmlHelper, Nav::SUBTYPE_TABS))
                    ->addChild(new NavItem($htmlHelper, 'foo', 'bs:toggle?object=tab&target=tab-pane-1', 'Tab Pane 1'))
                    ->addChild(new NavItem($htmlHelper, 'bar', 'https://example.com/', 'External Link')),
            ],
        ];
    }

    /** @dataProvider providesComponentHtml */
    public function testTostringReturnsHtml(string $expected, Nav $component): void
    {
        $this->assertSame($expected, "{$component}");
    }
}
