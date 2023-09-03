<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\NavsTabs;

use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;

use const false;
use const null;

class TabPane extends AbstractComponent
{
    protected static array $defaultProps = [
        'content' => null,
        'active' => false,
    ];

    protected static array $defaultAttrs = [
        'class' => 'tab-pane',
        'aria-labelledby' => null,
        'role' => 'tabpanel',
        'tabindex' => '0',
    ];

    public function __construct(
        HtmlHelper $htmlHelper,
        string $id,
        string $labelledById,
        string $content,
        bool $active = false
    ) {
        parent::__construct($htmlHelper);

        $this
            ->attr('id', $id)
            ->attr('aria-labelledby', $labelledById)
            ->prop('content', $content)
            ->prop('active', $active)
        ;
    }

    public function serialize(): string
    {
        $attrs = $this->attrs();

        if ($this->prop('active')) {
            $attrs['class'] .= ' show active';
        }

        /** @var string */
        $content = $this->prop('content');

        return $this->getHtmlHelper()->createElement('div', $attrs, "\n{$content}\n");
    }
}
