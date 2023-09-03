<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Tests\AbstractComponentTest;

/**
 * N.B. This is a deliberately naive example for the purposes of testing, only
 */
class TabsNav extends Nav
{
    protected static array $defaultAttrs = [
        'class' => '{{ parent }} nav-tabs',
    ];
}
