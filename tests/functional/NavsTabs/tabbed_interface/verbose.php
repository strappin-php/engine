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
        'verbose-tab-1',
        'bs:toggle?object=tab&target=verbose-tab-pane-1',
        'Tab-Pane 1 Label',
        true
    ))
    ->addChild(new NavItem(
        $htmlHelper,
        'verbose-tab-2',
        'bs:toggle?object=tab&target=verbose-tab-pane-2',
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
        'verbose-tab-pane-1',
        'verbose-tab-1',
        'Tab-pane 1 content.',
        true
    ))
    ->addChild(new TabPane(
        $htmlHelper,
        'verbose-tab-pane-2',
        'verbose-tab-2',
        'Tab-pane 2 content.'
    ))
;

// phpcs:ignore
echo "{$nav}{$tabContent}";
