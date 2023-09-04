<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests;

use DanBettles\Marigold\AbstractTestCase;
use StrappinPhp\Engine\Accordion\Accordion;
use StrappinPhp\Engine\Factory;
use StrappinPhp\Engine\HtmlHelper;
use StrappinPhp\Engine\NavsTabs\TabbedInterface;

class FactoryTest extends AbstractTestCase
{
    public function testIsInstantiable(): void
    {
        $htmlHelper = new HtmlHelper();
        $factory = new Factory($htmlHelper);

        $this->assertSame($htmlHelper, $factory->getHtmlHelper());
    }

    public function testCreatetabbedinterface(): void
    {
        $tabbedInterface = (new Factory(new HtmlHelper()))->createTabbedInterface([
            'panels' => [
                [
                    'tabId' => 'tab-1',  // Optional.  Added only for testing.
                    'action' => 'bs:toggle?object=tab&target=tab-pane-1',
                    'label' => 'Tab 1',
                    'content' => 'Tab-pane 1 content.',
                    // 'active' => true,
                ],
                [
                    'tabId' => 'tab-2',  // Optional.  Added only for testing.
                    'action' => 'bs:toggle?object=tab&target=tab-pane-2',
                    'label' => 'Tab 2',
                    'content' => 'Tab-pane 2 content.',
                    // 'active' => false,
                ],
                [
                    'tabId' => 'tab-3',  // Optional.  Added only for testing.
                    'action' => 'https://example.com/',
                    'label' => 'External Link',
                    // 'content' => '',  // Simply ignored in this case
                    // 'active' => false,
                ],
            ],
        ]);

        $this->assertInstanceOf(TabbedInterface::class, $tabbedInterface);

        $this->assertSame(<<<END
        <div class="x-tabbed-interface">
        <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
        <button id="tab-1" class="nav-link active" type="button" role="tab" aria-selected="true" data-bs-toggle="tab" data-bs-target="#tab-pane-1" aria-controls="tab-pane-1">Tab 1</button>
        </li>
        <li class="nav-item" role="presentation">
        <button id="tab-2" class="nav-link" type="button" role="tab" aria-selected="false" data-bs-toggle="tab" data-bs-target="#tab-pane-2" aria-controls="tab-pane-2">Tab 2</button>
        </li>
        <li class="nav-item">
        <a id="tab-3" class="nav-link" href="https://example.com/">External Link</a>
        </li>
        </ul>
        <div class="tab-content">
        <div id="tab-pane-1" class="tab-pane show active" aria-labelledby="tab-1" role="tabpanel" tabindex="0">
        Tab-pane 1 content.
        </div>
        <div id="tab-pane-2" class="tab-pane" aria-labelledby="tab-2" role="tabpanel" tabindex="0">
        Tab-pane 2 content.
        </div>
        </div>
        </div>
        END, "{$tabbedInterface}");
    }

    public function testCreateCreatesAFullyConfiguredFactory(): void
    {
        $factory = Factory::create();

        $this->assertInstanceOf(Factory::class, $factory);
        $this->assertInstanceOf(HtmlHelper::class, $factory->getHtmlHelper());
    }

    public function testCreatecompleteaccordionCreatesACompleteAccordion(): void
    {
        $accordion = (new Factory(new HtmlHelper()))->createCompleteAccordion([
            'sections' => [
                [
                    'headerText' => 'Section 1 Heading',
                    'bodyContent' => 'Section 1 content',
                    'collapseId' => 'collapse-1',  // Optional.  Added only for testing.
                ],
                [
                    'headerText' => 'Section 2 Heading',
                    'bodyContent' => 'Section 2 content',
                    'collapseId' => 'collapse-2',  // Optional.  Added only for testing.
                ],
            ],
            'id' => 'accordion-1',  // Optional.  Added only for testing.
        ]);

        $this->assertInstanceOf(Accordion::class, $accordion);

        $this->assertSame(<<<END
        <div id="accordion-1" class="accordion">
        <div class="accordion-item">
        <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true" aria-controls="collapse-1">Section 1 Heading</button>
        </h2>
        <div id="collapse-1" class="accordion-collapse collapse show" data-bs-parent="#accordion-1">
        <div class="accordion-body">Section 1 content</div>
        </div>
        </div>
        <div class="accordion-item">
        <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-controls="collapse-2">Section 2 Heading</button>
        </h2>
        <div id="collapse-2" class="accordion-collapse collapse" data-bs-parent="#accordion-1">
        <div class="accordion-body">Section 2 content</div>
        </div>
        </div>
        </div>
        END, "{$accordion}");
    }
}
