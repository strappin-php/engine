<?php

declare(strict_types=1);

namespace StrappinPhp\Engine\Accordion;

use StrappinPhp\Engine\AbstractComponent;
use StrappinPhp\Engine\HtmlHelper;

class Accordion extends AbstractComponent
{
    protected static array $defaultAttrs = [
        'class' => 'accordion',
    ];

    public function __construct(
        HtmlHelper $htmlHelper,
        string $id
    ) {
        parent::__construct($htmlHelper);

        $this->attr('id', $id);
    }
}
