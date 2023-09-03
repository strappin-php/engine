<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\NavsTabs;

use StrappinPhp\Engine\AbstractComponent;

/**
 * The wrapper for tab panes
 */
class TabContent extends AbstractComponent
{
    protected static array $defaultAttrs = [
        'class' => 'tab-content',
    ];
}
