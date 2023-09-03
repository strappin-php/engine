<?php

declare(strict_types=1);

use StrappinPhp\Engine\HtmlHelper;
use StrappinPhp\Engine\NavsTabs\Nav;
use StrappinPhp\Engine\NavsTabs\NavItem;
use StrappinPhp\Engine\NavsTabs\TabContent;
use StrappinPhp\Engine\NavsTabs\TabPane;

$htmlHelper = new HtmlHelper();

// phpcs:ignore
echo (new Nav($htmlHelper, Nav::SUBTYPE_TABS))
    ->addChild(new NavItem(
        $htmlHelper,
        'longer-way-tab-1',
        'bs:toggle?object=tab&target=longer-way-tab-pane-1',
        'Tab-Pane 1 Label',
        true
    ))
    ->addChild(new NavItem(
        $htmlHelper,
        'longer-way-tab-2',
        'bs:toggle?object=tab&target=longer-way-tab-pane-2',
        'Tab-Pane 2 Label'
    ))
    ->addChild(new NavItem(
        $htmlHelper,
        'external-link',
        'https://example.com/',
        'External Link'
    ))
;

// phpcs:ignore
echo (new TabContent($htmlHelper))
    ->addChild(new TabPane(
        $htmlHelper,
        'longer-way-tab-pane-1',
        'longer-way-tab-1',
        'Tab-pane 1 content.',
        true
    ))
    ->addChild(new TabPane(
        $htmlHelper,
        'longer-way-tab-pane-2',
        'longer-way-tab-2',
        'Tab-pane 2 content.'
    ))
;
