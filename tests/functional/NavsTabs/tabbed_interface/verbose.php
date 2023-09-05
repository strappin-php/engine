<?php

declare(strict_types=1);

use StrappinPhp\Engine\HtmlHelper;
use StrappinPhp\Engine\NavsTabs\Nav;
use StrappinPhp\Engine\NavsTabs\NavItem;
use StrappinPhp\Engine\NavsTabs\TabContent;
use StrappinPhp\Engine\NavsTabs\TabPane;

$htmlHelper = new HtmlHelper();

$nav = (new Nav($htmlHelper, Nav::SUBTYPE_TABS))
    ->addChild(new NavItem(
        $htmlHelper,
        'tab-verbose-1',
        'bs:toggle?object=tab&target=tab-pane-verbose-1',
        'Tab-Pane 1 Label',
        $active = true
    ))
    ->addChild(new NavItem(
        $htmlHelper,
        'tab-verbose-2',
        'bs:toggle?object=tab&target=tab-pane-verbose-2',
        'Tab-Pane 2 Label'
    ))
    ->addChild(new NavItem(
        $htmlHelper,
        'external-link',
        'https://example.com/',
        'External Link'
    ))
;

$tabContent = (new TabContent($htmlHelper))
    ->addChild(new TabPane(
        $htmlHelper,
        'tab-pane-verbose-1',
        'tab-verbose-1',
        'Tab-pane 1 content.',
        $active = true
    ))
    ->addChild(new TabPane(
        $htmlHelper,
        'tab-pane-verbose-2',
        'tab-verbose-2',
        'Tab-pane 2 content.'
    ))
;

// phpcs:ignore
echo "{$nav}{$tabContent}";
