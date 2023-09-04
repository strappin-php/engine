<?php

declare(strict_types=1);

namespace StrappinPhp\Engine;

use StrappinPhp\Engine\Accordion\Accordion;
use StrappinPhp\Engine\Accordion\AccordionItem;
use StrappinPhp\Engine\NavsTabs\Nav;
use StrappinPhp\Engine\NavsTabs\NavItem;
use StrappinPhp\Engine\NavsTabs\TabbedInterface;
use StrappinPhp\Engine\NavsTabs\TabContent;
use StrappinPhp\Engine\NavsTabs\TabPane;

use const false;
use const true;

/**
 * @phpstan-type PanelConfig array{tabId?:string,action:string,label:string,content?:string}
 * @phpstan-type TabbedInterfaceConfig array{panels:PanelConfig[]}
 * @phpstan-type AccordionSectionConfig array{collapseId?:string,headerText:string,bodyContent:string}
 * @phpstan-type CompleteAccordionConfig array{id?:string,sections:AccordionSectionConfig[]}
 */
class Factory
{
    private HtmlHelper $htmlHelper;

    public function __construct(HtmlHelper $htmlHelper)
    {
        $this->setHtmlHelper($htmlHelper);
    }

    /**
     * @phpstan-param TabbedInterfaceConfig $config
     */
    public function createTabbedInterface(array $config): TabbedInterface
    {
        $htmlHelper = $this->getHtmlHelper();

        $nav = new Nav($htmlHelper, Nav::SUBTYPE_TABS);
        $tabContent = new TabContent($htmlHelper);

        $active = true;

        foreach ($config['panels'] as $panel) {
            $tabId = $panel['tabId']
                ?? $htmlHelper->createUniqueName()
            ;

            $action = $panel['action'];

            $navItem = new NavItem(
                $htmlHelper,
                $tabId,
                $action,
                $panel['label'],
                $active
            );

            $nav->addChild($navItem);

            if ($navItem->hasHyperlink()) {
                // (A tab-pane is pointless in this case)
                continue;
            }

            $bootstrapUrl = BootstrapUrl::fromString($action);

            /** @var string */
            $paneId = $bootstrapUrl
                ? $bootstrapUrl->arg('target')
                : $htmlHelper->createUniqueName()
            ;

            $content = $panel['content'] ?? '';

            $tabPane = new TabPane(
                $htmlHelper,
                $paneId,
                $tabId,
                $content,
                $active
            );

            $tabContent->addChild($tabPane);

            $active = false;
        }

        return new TabbedInterface($htmlHelper, $nav, $tabContent);
    }

    /**
     * @phpstan-param CompleteAccordionConfig $config
     */
    public function createCompleteAccordion(array $config): Accordion
    {
        $htmlHelper = new HtmlHelper();

        $parentId = $config['id']
            ?? $htmlHelper->createUniqueName()
        ;

        $accordion = new Accordion($htmlHelper, $parentId);

        $show = true;

        foreach ($config['sections'] as $section) {
            $collapseId = $section['collapseId']
                ?? $htmlHelper->createUniqueName()
            ;

            $accordion->addChild(new AccordionItem(
                $htmlHelper,
                $parentId,
                $section['headerText'],
                $section['bodyContent'],
                $show,
                $collapseId
            ));

            $show = false;
        }

        return $accordion;
    }

    private function setHtmlHelper(HtmlHelper $htmlHelper): self
    {
        $this->htmlHelper = $htmlHelper;

        return $this;
    }

    public function getHtmlHelper(): HtmlHelper
    {
        return $this->htmlHelper;
    }

    public static function create(): self
    {
        return new self(new HtmlHelper());
    }
}
