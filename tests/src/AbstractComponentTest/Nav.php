<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests\AbstractComponentTest;

use StrappinPhp\Engine\AbstractComponent;

/**
 * N.B. This is a deliberately naive example for the purposes of testing, only
 */
class Nav extends AbstractComponent
{
    protected static array $defaultAttrs = [
        'class' => 'nav',
    ];
}
