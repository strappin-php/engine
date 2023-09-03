<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests\NavsTabs;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;
use StrappinPhp\Engine\NavsTabs\Nav;
use StrappinPhp\Engine\NavsTabs\TabbedInterface;
use StrappinPhp\Engine\NavsTabs\TabContent;

class TabbedInterfaceTest extends AbstractTestCase
{
    public function testIsAComponent(): void
    {
        $this->assertTrue($this->getTestedClass()->isSubclassOf(AbstractComponent::class));
    }

    public function testIsInstantiable(): void
    {
        $htmlHelper = new HtmlHelper();

        $nav = new Nav($htmlHelper);
        $tabContent = new TabContent($htmlHelper);
        $tabbedInterface = new TabbedInterface($htmlHelper, $nav, $tabContent);

        $this->assertSame($nav, $tabbedInterface->getNav());
        $this->assertSame($tabContent, $tabbedInterface->getTabContent());

        $this->assertSame([
            $tabbedInterface->getNav(),
            $tabbedInterface->getTabContent(),
        ], $tabbedInterface->getChildren());
    }

    public function testTostringReturnsHtml(): void
    {
        $htmlHelper = new HtmlHelper();

        $nav = new Nav($htmlHelper);
        $tabContent = new TabContent($htmlHelper);
        $tabbedInterface = new TabbedInterface($htmlHelper, $nav, $tabContent);

        $this->assertSame(<<<END
        <div class="x-tabbed-interface">
        <ul class="nav">
        </ul>
        <div class="tab-content">
        </div>
        </div>
        END, "{$tabbedInterface}");
    }
}
