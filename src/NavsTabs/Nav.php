<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\NavsTabs;

use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;

use const null;

class Nav extends AbstractComponent
{
    /** @var int */
    public const SUBTYPE_TABS = 1;

    protected static array $defaultProps = [
        'subtype' => null,
    ];

    protected static array $defaultAttrs = [
        'class' => 'nav',
        'role' => null,
    ];

    public function __construct(
        HtmlHelper $htmlHelper,
        int $subtype = null
    ) {
        parent::__construct($htmlHelper);

        $this->prop('subtype', $subtype);
    }

    public function serialize(): string
    {
        $attrs = $this->attrs();

        switch ($this->prop('subtype')) {
            case self::SUBTYPE_TABS:
                $attrs['class'] = "{$attrs['class']} nav-tabs";
                $attrs['role'] = 'tablist';

                break;
        }

        $childrenHtml = "\n" . ($this->hasChildren() ? $this->serializeChildren() . "\n" : '');

        return $this->getHtmlHelper()->createElement('ul', $attrs, $childrenHtml);
    }
}
