<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests\Accordion;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\Accordion\Accordion;
use StrappinPhp\Engine\HtmlHelper;

class AccordionTest extends AbstractTestCase
{
    public function testIsAComponent(): void
    {
        $this->assertTrue($this->getTestedClass()->isSubclassOf(AbstractComponent::class));
    }

    public function testIsInstantiable(): void
    {
        $htmlHelper = new HtmlHelper();
        $accordion = new Accordion($htmlHelper, 'accordion-1');

        $this->assertSame([], $accordion->props());

        $this->assertSame([
            'id' => 'accordion-1',
            'class' => 'accordion',
        ], $accordion->attrs());
    }

    public function testTostringReturnsHtml(): void
    {
        $htmlHelper = new HtmlHelper();
        $component = new Accordion($htmlHelper, 'accordion-1');

        $this->assertSame(<<<END
        <div id="accordion-1" class="accordion">
        </div>
        END, "{$component}");
    }
}
