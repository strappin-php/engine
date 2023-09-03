<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests\NavsTabs;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;
use StrappinPhp\Engine\NavsTabs\NavItem;

use const false;
use const true;

class NavItemTest extends AbstractTestCase
{
    public function testIsAComponent(): void
    {
        $this->assertTrue($this->getTestedClass()->isSubclassOf(AbstractComponent::class));
    }

    public function testIsInstantiable(): void
    {
        $htmlHelper = new HtmlHelper();

        $inactiveNavItem = new NavItem($htmlHelper, 'bar', 'https://example.com/', 'External Link');

        $this->assertSame([
            'action' => 'https://example.com/',
            'textLabel' => 'External Link',
            'active' => false,
        ], $inactiveNavItem->props());

        $this->assertSame([
            'id' => 'bar',
            'class' => 'nav-link',
        ], $inactiveNavItem->attrs());

        $activeNavItem = new NavItem($htmlHelper, 'baz', 'https://example.com/', 'External Link', true);

        $this->assertSame([
            'action' => 'https://example.com/',
            'textLabel' => 'External Link',
            'active' => true,
        ], $activeNavItem->props());

        $this->assertSame([
            'id' => 'baz',
            'class' => 'nav-link',
        ], $activeNavItem->attrs());
    }

    /** @return array<mixed[]> */
    public function providesHtml(): array
    {
        $htmlHelper = new HtmlHelper();

        return [
            [
                <<<END
                <li class="nav-item">
                <a id="foo" class="nav-link" href="https://example.com/">External Link</a>
                </li>
                END,
                new NavItem($htmlHelper, 'foo', 'https://example.com/', 'External Link'),
            ],
            [
                <<<END
                <li class="nav-item">
                <a id="foo" class="nav-link active" href="https://example.com/" aria-current="page">External Link</a>
                </li>
                END,
                new NavItem($htmlHelper, 'foo', 'https://example.com/', 'External Link', true),
            ],
            [  // #2
                <<<END
                <li class="nav-item" role="presentation">
                <button id="bar" class="nav-link" type="button" role="tab" aria-selected="false" data-bs-toggle="tab" data-bs-target="#tab-pane-1" aria-controls="tab-pane-1">Tab 1</button>
                </li>
                END,
                new NavItem($htmlHelper, 'bar', 'bs:toggle?object=tab&target=tab-pane-1', 'Tab 1'),
            ],
            [
                <<<END
                <li class="nav-item">
                <a id="baz" class="nav-link active" href="https://example.com/" aria-current="page">External Link</a>
                </li>
                END,
                (new NavItem($htmlHelper, 'baz', 'https://example.com/', 'External Link'))
                    ->prop('active', true),
            ],
            [
                <<<END
                <li class="nav-item" role="presentation">
                <button id="qux" class="nav-link active" type="button" role="tab" aria-selected="true" data-bs-toggle="tab" data-bs-target="#tab-pane-1" aria-controls="tab-pane-1">Tab 1</button>
                </li>
                END,
                (new NavItem($htmlHelper, 'qux', 'bs:toggle?object=tab&target=tab-pane-1', 'Tab 1'))
                    ->prop('active', true),
            ],
        ];
    }

    /** @dataProvider providesHtml */
    public function testTostringReturnsHtml(string $expectedHtml, NavItem $component): void
    {
        $this->assertSame($expectedHtml, "{$component}");
    }

    /** @return array<mixed[]> */
    public function providesNavItems(): array
    {
        $htmlHelper = new HtmlHelper();

        return [
            [
                'invokesBootstrap' => true,
                'hasHyperlink' => false,
                new NavItem($htmlHelper, 'foo', 'bs:toggle?object=tab&target=tab-pane-1', 'Tab 1'),
            ],
            [
                'invokesBootstrap' => false,
                'hasHyperlink' => true,
                new NavItem($htmlHelper, 'bar', 'https://example.com/', 'External Link', true),
            ],
        ];
    }

    /** @dataProvider providesNavItems */
    public function testInvokesbootstrapReturnsTrueIfTheNavItemWillInvokeBootstrap(
        bool $expected,
        bool $ignore,
        NavItem $navItem
    ): void {
        $this->assertSame($expected, $navItem->invokesBootstrap());
    }

    /** @dataProvider providesNavItems */
    public function testHashyperlinkReturnsTrueIfTheNavItemHasAHyperlink(
        bool $ignore,
        bool $expected,
        NavItem $navItem
    ): void {
        $this->assertSame($expected, $navItem->hasHyperlink());
    }
}
