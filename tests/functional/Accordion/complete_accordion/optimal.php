<?php

declare(strict_types=1);

use StrappinPhp\Engine\Factory;

$completeAccordion = Factory::create()->createCompleteAccordion([
    'sections' => [
        [
            'headerText' => 'Section 1 Heading',
            'bodyContent' => 'Section 1 content',
        ],
        [
            'headerText' => 'Section 2 Heading',
            'bodyContent' => 'Section 2 content',
        ],
    ],
]);

// phpcs:ignore
echo $completeAccordion;
