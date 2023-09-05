<?php

declare(strict_types=1);

use StrappinPhp\Engine\Accordion\Accordion;
use StrappinPhp\Engine\Accordion\AccordionItem;
use StrappinPhp\Engine\HtmlHelper;

$htmlHelper = new HtmlHelper();
$parentAccordionId = $htmlHelper->createUniqueName();

$accordion = (new Accordion($htmlHelper, $parentAccordionId))
    ->addChild(
        new AccordionItem(
            $htmlHelper,
            $parentAccordionId,
            'Section 1 Heading',
            'Section 1 content',
            $show = true
        )
    )
    ->addChild(
        new AccordionItem(
            $htmlHelper,
            $parentAccordionId,
            'Section 2 Heading',
            'Section 2 content'
        )
    )
;

// phpcs:ignore
echo $accordion;
