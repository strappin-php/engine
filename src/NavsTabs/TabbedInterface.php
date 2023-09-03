<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\NavsTabs;

use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;

/**
 * A container for a tabs-nav and its associated tab content.  This class is for convenience and does not map to an
 * existing Bootstrap component.
 *
 * @method self addChild(AbstractComponent $child)
 */
class TabbedInterface extends AbstractComponent
{
    protected static array $defaultAttrs = [
        'class' => 'x-tabbed-interface',
    ];

    // For easy access
    private Nav $nav;

    // For easy access
    private TabContent $tabContent;

    public function __construct(
        HtmlHelper $htmlHelper,
        Nav $nav,
        TabContent $tabContent
    ) {
        parent::__construct($htmlHelper);

        $this
            ->setNav($nav)
            ->addChild($this->getNav())
            ->setTabContent($tabContent)
            ->addChild($this->getTabContent())
        ;
    }

    private function setNav(Nav $nav): self
    {
        $this->nav = $nav;

        return $this;
    }

    public function getNav(): Nav
    {
        return $this->nav;
    }

    private function setTabContent(TabContent $tabContent): self
    {
        $this->tabContent = $tabContent;

        return $this;
    }

    public function getTabContent(): TabContent
    {
        return $this->tabContent;
    }
}
