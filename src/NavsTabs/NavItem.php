<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\NavsTabs;

use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\BootstrapUrl;
use StrappinPhp\Engine\HtmlHelper;

use function array_replace;
use function strpos;

use const false;
use const null;

class NavItem extends AbstractComponent
{
    protected static array $defaultProps = [
        'action' => null,
        'textLabel' => null,
        'active' => false,
    ];

    protected static array $defaultAttrs = [
        'class' => 'nav-link',
    ];

    public function __construct(
        HtmlHelper $htmlHelper,
        string $id,
        string $action,
        string $textLabel,
        bool $active = false
    ) {
        parent::__construct($htmlHelper);

        $this
            ->attr('id', $id)
            ->prop('action', $action)
            ->prop('textLabel', $textLabel)
            ->prop('active', $active)
        ;
    }

    public function invokesBootstrap(): bool
    {
        /** @var string */
        $action = $this->prop('action');

        return BootstrapUrl::validateUrl($action);
    }

    public function hasHyperlink(): bool
    {
        /** @var string */
        $action = $this->prop('action');

        return false !== strpos($action, '://');
    }

    public function serialize(): string
    {
        /** @var string */
        $action = $this->prop('action');
        $bootstrapUrl = BootstrapUrl::fromString($action);

        /** @var string */
        $textLabel = $this->prop('textLabel');

        $defaultAttrs = $this->attrs();

        if ($this->prop('active')) {
            $defaultAttrs['class'] .= ' active';
        }

        $htmlHelper = $this->getHtmlHelper();

        if ($bootstrapUrl) {
            // We can guarantee this will be a string because we used `BootstrapUrl::fromString()`, above
            $actionName = $bootstrapUrl->action();

            $button = $htmlHelper->createElement('button', array_replace($defaultAttrs, [
                'type' => 'button',
                'role' => 'tab',
                'aria-selected' => ($this->prop('active') ? 'true' : 'false'),
                "data-bs-{$actionName}" => $bootstrapUrl->arg('object'),
                'data-bs-target' => "#{$bootstrapUrl->arg('target')}",
                'aria-controls' => $bootstrapUrl->arg('target'),
            ]), $textLabel);

            return $htmlHelper->createElement('li', [
                'class' => 'nav-item',
                'role' => 'presentation',
            ], "\n{$button}\n");
        }

        $anchor = $htmlHelper->createElement('a', array_replace($defaultAttrs, [
            'href' => $action,
            'aria-current' => $this->prop('active') ? 'page' : null,
        ]), $textLabel);

        return $htmlHelper->createElement('li', [
            'class' => 'nav-item',
        ], "\n{$anchor}\n");
    }
}
