<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests\NavsTabs;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;
use StrappinPhp\Engine\NavsTabs\TabContent;
use StrappinPhp\Engine\NavsTabs\TabPane;

use const true;

class TabContentTest extends AbstractTestCase
{
    public function testIsAComponent(): void
    {
        $this->assertTrue($this->getTestedClass()->isSubclassOf(AbstractComponent::class));
    }

    /** @return array<mixed[]> */
    public function providesComponentHtml(): array
    {
        $htmlHelper = new HtmlHelper();

        return [
            [
                <<<END
                <div class="tab-content">
                </div>
                END,
                new TabContent($htmlHelper),
            ],
            [
                <<<END
                <div class="tab-content">
                <div id="tab-pane-1" class="tab-pane show active" aria-labelledby="tab-1" role="tabpanel" tabindex="0">
                Tab 1 content.
                </div>
                <div id="tab-pane-2" class="tab-pane" aria-labelledby="tab-2" role="tabpanel" tabindex="0">
                Tab 2 content.
                </div>
                </div>
                END,
                (new TabContent($htmlHelper))
                    ->addChild(new TabPane($htmlHelper, 'tab-pane-1', 'tab-1', 'Tab 1 content.', true))
                    ->addChild(new TabPane($htmlHelper, 'tab-pane-2', 'tab-2', 'Tab 2 content.')),
            ],
        ];
    }

    /** @dataProvider providesComponentHtml */
    public function testTostringReturnsHtml(string $expected, TabContent $component): void
    {
        $this->assertSame($expected, "{$component}");
    }
}
