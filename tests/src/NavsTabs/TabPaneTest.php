<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests\NavsTabs;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;
use StrappinPhp\Engine\NavsTabs\TabPane;

use const false;
use const true;

/**
 * @phpstan-import-type PropsArray from AbstractComponent
 * @phpstan-import-type AttributesArray from HtmlHelper
 */
class TabPaneTest extends AbstractTestCase
{
    public function testIsAComponent(): void
    {
        $this->assertTrue($this->getTestedClass()->isSubclassOf(AbstractComponent::class));
    }

    /** @return array<mixed[]> */
    public function providesTabPanePropsAndAttrs(): array
    {
        $htmlHelper = new HtmlHelper();

        return [
            [
                [
                    'content' => '<p>Lorem ipsum dolor</p>',
                    'active' => false,
                ],
                [
                    'id' => 'tab-pane-1',
                    'class' => 'tab-pane',
                    'aria-labelledby' => 'tab-1',
                    'role' => 'tabpanel',
                    'tabindex' => '0',
                ],
                new TabPane($htmlHelper, 'tab-pane-1', 'tab-1', '<p>Lorem ipsum dolor</p>'),
            ],
            [
                [
                    'content' => '<p>Lorem ipsum dolor</p>',
                    'active' => true,
                ],
                [
                    'id' => 'tab-pane-1',
                    'class' => 'tab-pane',
                    'aria-labelledby' => 'tab-1',
                    'role' => 'tabpanel',
                    'tabindex' => '0',
                ],
                new TabPane($htmlHelper, 'tab-pane-1', 'tab-1', '<p>Lorem ipsum dolor</p>', true),
            ],
        ];
    }

    /**
     * @dataProvider providesTabPanePropsAndAttrs
     * @phpstan-param PropsArray $expectedProps
     * @phpstan-param AttributesArray $expectedAttrs
     */
    public function testIsInstantiable(
        array $expectedProps,
        array $expectedAttrs,
        TabPane $tabPane
    ): void {
        $this->assertSame($expectedProps, $tabPane->props());
        $this->assertSame($expectedAttrs, $tabPane->attrs());
    }

    /** @return array<mixed[]> */
    public function providesComponentHtml(): array
    {
        $htmlHelper = new HtmlHelper();

        return [
            [
                <<<END
                <div id="tab-pane-1" class="tab-pane" aria-labelledby="tab-1" role="tabpanel" tabindex="0">
                <p>Lorem ipsum dolor</p>
                </div>
                END,
                new TabPane($htmlHelper, 'tab-pane-1', 'tab-1', '<p>Lorem ipsum dolor</p>'),
            ],
            [
                <<<END
                <div id="tab-pane-2" class="tab-pane show active" aria-labelledby="tab-2" role="tabpanel" tabindex="0">
                <p>Lorem ipsum dolor</p>
                </div>
                END,
                new TabPane($htmlHelper, 'tab-pane-2', 'tab-2', '<p>Lorem ipsum dolor</p>', true),
            ],
            [  // #2
                <<<END
                <div id="tab-pane-3" class="tab-pane" aria-labelledby="tab-3" role="tabpanel" tabindex="0">
                <p>Lorem ipsum dolor</p>
                </div>
                END,
                (new TabPane($htmlHelper, 'tab-pane-3', 'tab-3', '<p>Lorem ipsum dolor</p>')),
            ],
        ];
    }

    /** @dataProvider providesComponentHtml */
    public function testTostringReturnsHtml(string $expected, TabPane $component): void
    {
        $this->assertSame($expected, "{$component}");
    }
}
