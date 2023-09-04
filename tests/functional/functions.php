<?php

declare(strict_types=1);

use DanBettles\Marigold\FileInfo;
use StrappinPhp\Engine\Factory;
use StrappinPhp\Engine\HtmlHelper;

/**
 * @param string[] $sourceFilePathnames
 */
function execTests(array $sourceFilePathnames): string
{
    $sections = [];

    foreach ($sourceFilePathnames as $sourceFilePathname) {
        $fileInfo = new FileInfo($sourceFilePathname);
        $title = ucwords(str_replace('_', ' ', $fileInfo->getBasenameMinusExtension()));

        /** @var string */
        $sourceFileContents = file_get_contents($sourceFilePathname);
        $sourceCodeTrimmed = trim($sourceFileContents);
        $sourceCodeHighlighted = preg_replace('~\s*/>~', '>', highlight_string($sourceCodeTrimmed, true));

        ob_start();
        require $sourceFilePathname;
        $testOutput = ob_get_clean();

        $sections[] = [
            'headerText' => $title,
            'bodyContent' => <<<END
            <div class="func-test__body">
                <pre>{$sourceCodeHighlighted}</pre>
                <div>{$testOutput}</div>
            </div>
            END,
        ];
    }

    $accordion = (new Factory(new HtmlHelper()))->createCompleteAccordion([
        'sections' => $sections,
    ]);

    $accordion->attr('class', $accordion->attr('class') . ' func-test-group');

    return "{$accordion}";
}
