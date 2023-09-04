<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests\Accordion;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\Accordion\AccordionItem;
use StrappinPhp\Engine\HtmlHelper;

use const false;
use const null;
use const true;

class AccordionItemTest extends AbstractTestCase
{
    public function testIsAComponent(): void
    {
        $this->assertTrue($this->getTestedClass()->isSubclassOf(AbstractComponent::class));
    }

    public function testIsInstantiable(): void
    {
        $htmlHelper = new HtmlHelper();

        // Maximal:

        $accordionItem = new AccordionItem(
            $htmlHelper,
            'accordion-1',
            'Accordion Item #1',
            'Lorem ipsum dolor.',
            true,
            'accordion-collapse-1'
        );

        $this->assertSame([
            'parentId' => 'accordion-1',
            'headerText' => 'Accordion Item #1',
            'bodyContent' => 'Lorem ipsum dolor.',
            'show' => true,
            'collapseId' => 'accordion-collapse-1',
        ], $accordionItem->props());

        $this->assertSame([
            'id' => null,
            'class' => 'accordion-item',
        ], $accordionItem->attrs());

        // Minimal:

        $accordionItemWithDefaultValues = new AccordionItem(
            $htmlHelper,
            'accordion-2',
            'Accordion Item #1',
            'Lorem ipsum dolor.'
        );

        /** @var string */
        $collapseId = $accordionItemWithDefaultValues->prop('collapseId');

        $this->assertMatchesRegularExpression('~^u[0-9a-f]+$~', $collapseId);

        $accordionItemWithDefaultValues->prop('collapseId', 'whatever');

        $this->assertSame([
            'parentId' => 'accordion-2',
            'headerText' => 'Accordion Item #1',
            'bodyContent' => 'Lorem ipsum dolor.',
            'show' => false,
            'collapseId' => 'whatever',
        ], $accordionItemWithDefaultValues->props());

        $this->assertSame([
            'id' => null,
            'class' => 'accordion-item',
        ], $accordionItemWithDefaultValues->attrs());
    }

    /** @return array<mixed[]> */
    public function providesHtml(): array
    {
        $htmlHelper = new HtmlHelper();

        return [
            [
                <<<END
                <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Accordion Item #1</button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">Accordion item #1 body.</div>
                </div>
                </div>
                END,
                new AccordionItem(
                    $htmlHelper,
                    'accordionExample',
                    'Accordion Item #1',
                    'Accordion item #1 body.',
                    true,
                    'collapseOne'  // For the sake of testing
                ),
            ],
        ];
    }

    /** @dataProvider providesHtml */
    public function testTostringReturnsHtml(string $expectedHtml, AccordionItem $component): void
    {
        $this->assertSame($expectedHtml, "{$component}");
    }
}
