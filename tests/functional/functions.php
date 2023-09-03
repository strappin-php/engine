<?php

declare(strict_types=1);

function execTest(string $sourceFilePathname): string
{
    ob_start();
    require $sourceFilePathname;
    $testOutput = ob_get_clean();

    /** @var string */
    $sourceFileContents = file_get_contents($sourceFilePathname);
    $sourceCodeTrimmed = trim($sourceFileContents);
    $sourceCodeHighlighted = preg_replace('~\s*/>~', '>', highlight_string($sourceCodeTrimmed, true));

    return <<<END
    <div class="func-test__body">
        <pre>{$sourceCodeHighlighted}</pre>
        <div>{$testOutput}</div>
    </div>
    END;
}
