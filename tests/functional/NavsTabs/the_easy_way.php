<?php

declare(strict_types=1);

use StrappinPhp\Engine\Factory;

// phpcs:ignore
echo Factory::create()->createTabbedInterface([
    'panels' => [
        [
            'action' => 'bs:toggle?object=tab&target=easy-way-tab-pane-1',
            'label' => 'Tab-Pane 1 Label',
            'content' => 'Tab-pane 1 content.',
        ],
        [
            'action' => 'bs:toggle?object=tab&target=easy-way-tab-pane-2',
            'label' => 'Tab-Pane 2 Label',
            'content' => 'Tab-pane 2 content.',
        ],
        [
            'action' => 'https://example.com/',
            'label' => 'External Link',
        ],
    ],
]);
