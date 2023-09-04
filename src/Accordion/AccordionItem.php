<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Accordion;

use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;

use const false;
use const null;

class AccordionItem extends AbstractComponent
{
    protected static array $defaultProps = [
        'parentId' => null,
        'headerText' => null,
        'bodyContent' => null,
        'show' => false,
        'collapseId' => null,
    ];

    protected static array $defaultAttrs = [
        'class' => 'accordion-item',
    ];

    public function __construct(
        HtmlHelper $htmlHelper,
        string $parentId,
        string $headerText,
        string $bodyContent,
        bool $show = false,
        string $collapseId = null
    ) {
        parent::__construct($htmlHelper);

        $this
            ->prop('parentId', $parentId)
            ->prop('headerText', $headerText)
            ->prop('bodyContent', $bodyContent)
            ->prop('show', $show)
            ->prop('collapseId', ($collapseId ?: $this->getHtmlHelper()->createUniqueName()))
        ;
    }

    public function serialize(): string
    {
        $htmlHelper = $this->getHtmlHelper();

        /** @var string */
        $collapseId = $this->prop('collapseId');
        $show = (bool) $this->prop('show');

        /** @var string */
        $headerText = $this->prop('headerText');

        $headerInnerHtml = $htmlHelper->createElement('button', [
            'class' => 'accordion-button',
            'type' => 'button',
            'data-bs-toggle' => 'collapse',
            'data-bs-target' => "#{$collapseId}",
            'aria-expanded' => ($show ? 'true' : null),
            'aria-controls' => $collapseId,
        ], $headerText);

        $headerOuterHtml = $htmlHelper->createElement('h2', [
            'class' => 'accordion-header',
        ], "\n{$headerInnerHtml}\n");

        /** @var string */
        $bodyContent = $this->prop('bodyContent');

        $bodyHtml = $htmlHelper->createElement('div', [
            'class' => 'accordion-body',
        ], $bodyContent);

        $collapseHtml = $htmlHelper->createElement('div', [
            'id' => $collapseId,
            'class' => 'accordion-collapse collapse' . ($show ? ' show' : ''),
            'data-bs-parent' => ('#' . $this->prop('parentId')),
        ], "\n{$bodyHtml}\n");

        return $this->getHtmlHelper()->createElement(
            static::$rootTagName,
            $this->attrs(),
            "\n{$headerOuterHtml}\n{$collapseHtml}\n"
        );
    }
}
